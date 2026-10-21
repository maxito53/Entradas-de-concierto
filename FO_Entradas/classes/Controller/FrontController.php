<?php

//namespace classes\controller;
//use Exception;

/**
 * FrontController con Singelton y XML
 * @author max
 */
class FrontController extends Controller {
    
    private const DEFAULT_CONTROLLER = "Home";
    private const DEFAULT_ACTION = "show";
    private const CONFIG_FILE = "../Core/routes.xml";
    
    private static $instance = null;
    private $controller;
    private $action;
    private $params;
    private $config;
    
    /**
     * constructor privado para implementar Singleton
     */
    private function __construct() {
        $this->loadConfiguration();
    }
    
    /**
     * prevenir clonación
     */
    private function __clone() {}
    
    /**
     * prevenir deserialización
     */
    public function __wakeup() {
        throw new Exception("No se puede deserializar singleton");
    }
    
    /**
     * método para obtener la instancia única de los singelton
     * @return FrontController instancia del FrontController
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * cargar configuración desde XML
     */
    private function loadConfiguration() {
        $configPath = __ROOT__ . self::CONFIG_FILE;
        
        // si no existe el archivo de configuración, crear uno por defecto
        if (!file_exists($configPath)) {
            $this->createDefaultConfiguration($configPath);
        }
        
        try {
            $this->config = simplexml_load_file($configPath);
            if ($this->config === false) {
                throw new Exception("Error al cargar el archivo de configuración XML");
            }
        } catch (Exception $e) {
            throw new Exception("Error al cargar el archivo de configuración XML: $e");

        }
    }
    
    /**
     * crear archivo de configuración XML por defecto
     * @param $configPath
     */
    private function createDefaultConfiguration($configPath) {

        $configDir = dirname($configPath);
        if (!is_dir($configDir)) {
            mkdir($configDir, 0755, true);
        }
        
        $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>
                            <configuration>
                                <routes>
                                    <default>
                                        <controller>' . self::DEFAULT_CONTROLLER . '</controller>
                                        <action>' . self::DEFAULT_ACTION . '</action>
                                    </default>
                                    <supported_methods>
                                        <method>GET</method>
                                        <method>POST</method>
                                        <method>PUT</method>
                                        <method>DELETE</method>
                                    </supported_methods>
                                </routes>
                                <sanitization>
                                    <filters>
                                        <filter type="url">FILTER_SANITIZE_URL</filter>
                                        <filter type="int">FILTER_SANITIZE_NUMBER_INT</filter>
                                        <filter type="float">FILTER_SANITIZE_NUMBER_FLOAT</filter>
                                        <filter type="email">FILTER_SANITIZE_EMAIL</filter>
                                        <filter type="string">FILTER_SANITIZE_FULL_SPECIAL_CHARS</filter>
                                    </filters>
                                </sanitization>
                            </configuration>';
        
        file_put_contents($configPath, $xmlContent);
    }
    
    // 
    /**
     * método público para dispatch, para usar el FrontController como de normal
     */
    public static function dispatch() {
        $instance = self::getInstance();
        $instance->handleRequest();
    }
    
    /**
     * manejar la petición HTTP
     */
    private function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        
        if (!$this->isMethodSupported($method)) {
            throw new Exception("Método no soportado: $method");
        }
        
        switch ($method) {
            case "GET":
                $this->handleGet();
                break;
                
            case "POST":
                $this->handlePost();
                break;
                
            case "PUT":
                $this->handlePut();
                break;
                
            case "DELETE":
                $this->handleDelete();
                break;
                
            case "HEAD":
            default:
                throw new Exception("Método no soportado");
                break;
        }
    }
    
    /**
     * verificar si un método HTTP está soportado según la configuración XML
     * @param string $method
     * @return boolean
     */
    private function isMethodSupported($method) {
        foreach ($this->config->routes->supported_methods->method as $supportedMethod) {
            if ((string)$supportedMethod === $method) {
                return true;
            }
        }
        return false;
    }
    
    /**
     *  manejar peticiones GET
     */
    private function handleGet() {
        $params = [];
        if (count($_GET) == 0) {
            $controller_name = (string)$this->config->routes->default->controller;
            $action = (string)$this->config->routes->default->action;
            $params = null;
        } else {
            $url = array_keys($_GET)[0];
            $url = trim($url, "/");
            $url = $this->sanitize($url, "url");
            $url = explode("/", $url);
            
            if (isset($url[0])) {
                $controller_name = ucwords($url[0]);
                if (isset($url[1])) {
                    $action = $url[1];
                }
                if (count($url) > 2) {
                    for ($i = 2; $i < count($url); $i++) {
                        $params[] = strtolower($url[$i]);
                    }
                }
            }
        }
        
        $this->init($controller_name, $action, $params);
        $this->executeAction();
    }
    
    /**
     * manejar peticiones POST
     */
    private function handlePost() {
        $params = "";
        $url = array_keys($_GET)[0];
        $url = trim($url, "/");
        $url = $this->sanitize($url, "url");
        $url = explode("/", $url);
        
        if (isset($url[0])) {
            $controller_name = ucwords($url[0]);
            if (isset($url[1])) {
                $action = $url[1];
            }
            $params = $_POST;
        }
        
        $this->init($controller_name, $action, $params);
        $this->executeAction();
    }
    
    /**
     * manejar peticiones PUT
     */
    private function handlePut() {
        $params = "";
        $url = array_keys($_GET)[0];
        $url = trim($url, "/");
        $url = $this->sanitize($url, "url");
        $url = explode("/", $url);
        
        if (isset($url[0])) {
            $controller_name = ucwords($url[0]);
            if (isset($url[1])) {
                $action = $url[1];
            }
            $input = file_get_contents('php://input');
            parse_str($input, $params);
        }
        
        $this->init($controller_name, $action, $params);
        $this->executeAction();
    }
    
    /**
     * manejar peticiones DELETE
     */
    private function handleDelete() {
        $params = [];
        $url = array_keys($_GET)[0];
        $url = trim($url, "/");
        $url = $this->sanitize($url, "url");
        $url = explode("/", $url);
        
        if (isset($url[0])) {
            $controller_name = ucwords($url[0]);
            if (isset($url[1])) {
                $action = $url[1];
            }
            if (count($url) > 2) {
                for ($i = 2; $i < count($url); $i++) {
                    $params[] = strtolower($url[$i]);
                }
            }
            $input = file_get_contents('php://input');
            if (!empty($input)) {
                parse_str($input, $bodyParams);
                $params = array_merge($params, $bodyParams);
            }
        }
        
        $this->init($controller_name, $action, $params);
        $this->executeAction();
    }
    
    /**
     * obtener configuración de filtro desde XML
     * @param string $type
     * @return string|NULL
     */
    private function getFilterFromConfig($type) {
        foreach ($this->config->sanitization->filters->filter as $filter) {
            if ((string)$filter['type'] === $type) {
                return (string)$filter;
            }
        }
        return null;
    }
    
    /**
     * inicializar controlador y acción
     * @param string $controller_name nombre
     * @param string $action acción
     * @param string $params parámetros
     */
    private function init($controller_name, $action, $params) {
        if (file_exists(__ROOT__ . "classes/Controller/{$controller_name}Controller.php")) {
            $classe = $controller_name . "Controller";
            
            if (class_exists($classe)) {
                $this->controller = new $classe();
                
                if (method_exists($this->controller, $action)) {
                    $this->action = $action;
                    $this->params = $params;
                } else {
                    throw new Exception("No existe la acción definida de $controller_name");
                }
            } else {
                throw new Exception("La clase $classe no existe");
            }
        } else {
            throw new Exception("No existe la definición de $controller_name");
        }
    }
    
    /**
     * ejecutar la acción del controlador
     */
    private function executeAction() {
        if ($this->controller && $this->action) {
            $action = $this->action;
            
            if (method_exists($this->controller, $action)) {
                $this->controller->$action($this->params);
            } else {
                throw new Exception("El método '$action' no existe en el controlador");
            }
        } else {
            throw new Exception("El controlador o la acción no están inicializados");
        }
    }
    
    // método para obtener la configuración (útil para debugging)
    public function getConfiguration() {
        return $this->config;
    }
    
    // método para recargar la configuración
    public function reloadConfiguration() {
        $this->loadConfiguration();
    }
}