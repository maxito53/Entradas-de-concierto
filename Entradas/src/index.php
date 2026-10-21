<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
$entityManager = require_once __DIR__ . '/bootstrap.php';

// inserts a db, se ejecutan si no están los datos ya cargados, sino ejecuta pero no hace nada
try {
    
    // ADMIN
    $artistaModel = new AdminModel($entityManager);
    $artistaModel->insertarAdmin();
    
    // ARTISTAS
    $artistaModel = new ArtistaModel($entityManager);
    $artistaModel->insertarArtistas();
    
    // LUGARES
    $lugarModel = new LugarModel($entityManager);
    $lugaresObj = $lugarModel->insertarLugares();
    
    // flush para tener los IDs de artistas y lugares antes de crear conciertos y sesiones
    $entityManager->flush();
    
    // CONCIERTOS
    $conciertoModel = new ConciertoModel($entityManager);
    $conciertosObj = $conciertoModel->insertarConciertos();
    
    // ZONAS
    $zonaModel = new ZonaModel($entityManager);
    $zonasObj = $zonaModel->insertarZonas();
    
    // flush para guardar conciertos y zonas antes de crear sesiones
    $entityManager->flush();
    
    // SESIONES
    $sesionModel = new SesionModel($entityManager);
    $sesionesObj = $sesionModel->insertarSesiones($conciertosObj);
    
    // flush para guardar sesiones antes de crear precios
    $entityManager->flush();
    
    // PRECIOS
    $precioModel = new PrecioModel($entityManager);
    $preciosObj = $precioModel->insertarPrecios($sesionesObj, $zonasObj);
    
    // flush para guardar precios antes de crear clientes
    $entityManager->flush();
    
    // CLIENTES
    $clienteModel = new ClienteModel($entityManager);
    $clientesObj = $clienteModel->insertarClientes();
    
    // flush para guardar clientes antes de crear pagos
    $entityManager->flush();
    
    // PAGOS
    $pagoModel = new PagoModel($entityManager);
    $pagosObj = $pagoModel->insertarPagos($clientesObj);
    
    // flush para guardar pagos antes de crear asientos
    $entityManager->flush();
    
    // ASIENTOS
    $asientoModel = new AsientoModel($entityManager);
    $asientosObj = $asientoModel->insertarAsientos($lugaresObj, $zonasObj);
    
    // flush para guardar asientos antes de crear entradas
    $entityManager->flush();
    
    // ENTRADAS
    $entradaModel = new EntradaModel($entityManager);
    $entradaModel->insertarEntradas($pagosObj, $sesionesObj, $zonasObj, $preciosObj, $asientosObj);
    
    // guardar todo al final
    $entityManager->flush();
    
} catch (Exception $e) {
    //throw new Exception($e->getMessage());
    http_response_code(400);
    echo 'Crea la base de datos a partir de las entidades para poder cargar los datos en ella';
    exit;
}

// solo permitir peticiones GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Method Not Allowed
    //echo "Método no permitido. Esta API sólo acepta peticiones GET.";
    exit;
}

// peticiones GET
if (isset($_GET['ref'])) {
    
    $entradaModel = new EntradaModel($entityManager);
    $entradaController = new EntradaController($entradaModel);
    
    $entradaController->generarPDF($_GET['ref']);
    
} elseif (isset($_GET['data'])) {
    
    $sesionModel = new SesionModel($entityManager);
    $eventoController = new EventoController($sesionModel);
    
    // validar formato de la fecha que tiene YYYY-MM-DD
    $fecha = $_GET['data'];
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        http_response_code(400);
        //echo "Formato de fecha incorrecto. Utilice el formato YYYY-MM-DD.";
        exit;
    }
    
    $eventoController->generarXML($fecha);
    
} else {
    http_response_code(400);
    //echo "Petición incorrecta. Debe proporcionar el parámetro 'ref' para obtener una entrada en PDF o 'data' para obtener eventos en XML";
    exit;
}