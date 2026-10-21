<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// dependencias
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';

// Configuración de cabeceras para API REST
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// si es una solicitud OPTIONS, salir
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// se obtiene el método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// se obtiene la entidad solicitada
$entity = isset($_GET['entity']) ? $_GET['entity'] : null;

if (!$entity) {
    http_response_code(400);
    echo json_encode(['error' => 'Se requiere especificar una entidad']);
    exit;
}

// verificar si es una petición de autenticación, osea si se pasa la "entidad" auth
if ($entity === 'auth') {
    $controller = new AuthController($entityManager);
    
    if ($method === 'POST') {
        // procesar login
        $controller->login();
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido para auth']);
    }
    exit;
}

// para las demás peticiones hay que verificamos token
$tokenService = new TokenService();

// sacar el token del header
$headers = getallheaders();
$authHeader = '';

// buscar la cabecera de autorización en mayúsculas/minúsculas
foreach ($headers as $key => $value) {
    if (strtolower($key) === 'authorization') {
        $authHeader = $value;
        break;
    }
}

// si no se encuentra en getallheaders(), se intenta buscar con $_SERVER
if (empty($authHeader)) {
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
    } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    } elseif (isset($_SERVER['Authorization'])) {
        $authHeader = $_SERVER['Authorization'];
    }
}

error_log("Headers recibidos: " . json_encode(getallheaders())); // hay error_log por muchos sitios de mientras corregia errores
error_log("Auth Header encontrado: " . $authHeader);

$token = '';

// extraer token del header en formato "Bearer {token}"
if (!empty($authHeader) && preg_match('/Bearer\s+(\S+)/i', $authHeader, $matches)) {
    $token = $matches[1];
    error_log("Token extraído del header: " . substr($token, 0, 10) . "...");
}

// verificar token
error_log("Token recibido: " . substr($token, 0, 20) . "...");
if (!$tokenService->verifyToken($token)) {
    error_log("Verificación de token fallida");
    http_response_code(401);
    echo json_encode(['error' => 'Token no válido o caducado']);
    exit;
}
error_log("Token verificado correctamente");

// si el toekn es válido, se sigue
// formatear el nombre del controlador a partir la entidad
$controllerName = ucfirst($entity) . 'Controller';
$controllerFile = __DIR__ . '/Controller/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    http_response_code(404);
    echo json_encode(['error' => 'Entidad no encontrada: ' . $controllerFile]);
    exit;
}

require_once $controllerFile;
$controller = new $controllerName($entityManager);

// verificar si se ha especificado una acción personalizada, osea el parámetro action
if (isset($_GET['action']) && method_exists($controller, $_GET['action'])) {
    $action = $_GET['action'];
    
    $params = [];
    
    // si está definido 'id', es el primer parámetro
    if (isset($_GET['id'])) {
        $params[] = is_numeric($_GET['id']) ? (int)$_GET['id'] : $_GET['id'];
    }
    
    // extraer los otros parámetros según la acción
    foreach ($_GET as $key => $value) {
        if (!in_array($key, ['entity', 'action', 'id'])) {
            $params[] = $value;
        }
    }
    
    // llamar al método de acción con los parámetros extraídos
    call_user_func_array([$controller, $action], $params);
    exit;
}

// si no hay acción personalizada, se hace según el método HTTP (el CRUD básico)
switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $controller->get($_GET['id']);
        } else {
            $controller->getAll();
        }
        break;
    case 'POST':
        $controller->create();
        break;
    case 'PUT':
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Se requiere ID para actualizar']);
            exit;
        }
        $controller->update($_GET['id']);
        break;
    case 'DELETE':
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Se requiere ID para eliminar']);
            exit;
        }
        $controller->delete($_GET['id']);
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        break;
}