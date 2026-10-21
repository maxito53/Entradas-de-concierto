<?php

class EntradaFormController extends Controller {
    
    public function __construct() {}
    
    /**
     * muestra el formulario inicial para comprar una entrada
     */
    public function show($params = null) {
        $loginModel = new LoginModel();
        
        if (!$loginModel->isLoggedIn()) {
            $loginView = new LoginView();
            $loginView->show();
            return;
        }
        
        // iD de sesión de los parámetros
        $sesionId = isset($params['0']) ? $params['0'] : null;
        
        if (!$sesionId) {
            // si no hay ID vuelve a home
            header('Location: index.php?Home/show');
            exit;
        }
        
        $sesionModel = new SesionModel();
        $zonaModel = new ZonaModel();
        $precioModel = new PrecioModel();
        
        $sesion = $sesionModel->getSesionById($sesionId);
        $zonas = $zonaModel->getAllZonas();
        $precios = $precioModel->getPreciosBySesion($sesionId);

        // zonas que tienen precios definidos para esta sesión
        $zonasConPrecio = [];
        foreach ($zonas as $zona) {
            
            foreach ($precios as $precio) {
                if ($precio->__get('zona')['id'] == $zona->__get('id')) {
                    $zonasConPrecio[] = $zona;
                    break;
                }
            }
        }
        
        $data = [
            'sesion' => $sesion,
            'zonas' => $zonasConPrecio,
            'asientos' => [],
            'precios' => $precios,
            'zona_seleccionada' => null,
            'asiento_seleccionado' => null,
            'precio_seleccionado' => null,
            'datos_cliente' => [],
            'datos_pago' => [],
            'error' => isset($params['error']) ? $params['error'] : '',
            'success' => isset($params['success']) ? $params['success'] : ''
        ];
        
        $view = new EntradaFormView();
        $view->show($data);
    }
    
    /**
     * procesa la selección de zona y muestra asientos disponibles
     */
    public function seleccionarZona($params = null) {
        $loginModel = new LoginModel();
        
        if (!$loginModel->isLoggedIn()) {
            $loginView = new LoginView();
            $loginView->show();
            return;
        }
        
        $sesionId = $params['sesion_id'] ?? null;
        $zonaId = $params['zona_id'] ?? null;
        
        if (!$sesionId || !$zonaId) {
            $vHome = new EntradaFormView();
            $vHome->show(['error' => 'Debes seleccionar una zona válida']);
        }
        
        $sesionModel = new SesionModel();
        $zonaModel = new ZonaModel();
        $asientoModel = new AsientoModel();
        $precioModel = new PrecioModel();
        
        $sesion = $sesionModel->getSesionById($sesionId);
        $zonaSeleccionada = $zonaModel->getZonaById($zonaId);
        $precioSeleccionado = $precioModel->getPrecioByZonaYSesion($zonaId, $sesionId);
        
        // verificar que existe precio para esta zona en esta sesión
        if (!$precioSeleccionado) {
            $vHome = new EntradaFormView();
            $vHome->show(['error' => 'La zona seleccionada no tiene precios disponibles para esta sesión']);
        }
        
        $zonas = $zonaModel->getZonasByLugar($sesion->__get('lugar')->__get('id'));
        $asientos = $asientoModel->getAsientosByZona($zonaId);
        $precios = $precioModel->getPreciosBySesion($sesionId);
        
        // filtrar zonas con precio para mostrar solo las disponibles
        $zonasConPrecio = [];
        foreach ($zonas as $zona) {
            foreach ($precios as $precio) {
                if ($precio->__get('zona')['id'] == $zona->__get('id')) {
                    $zonasConPrecio[] = $zona;
                    break;
                }
            }
        }
        
        $datosCliente = $this->extraerDatosCliente($_POST);
        $datosPago = $this->extraerDatosPago($_POST);
        
        $data = [
            'sesion' => $sesion,
            'zonas' => $zonasConPrecio,
            'asientos' => $asientos,
            'precios' => $precios,
            'zona_seleccionada' => $zonaSeleccionada,
            'asiento_seleccionado' => null,
            'precio_seleccionado' => $precioSeleccionado,
            'datos_cliente' => $datosCliente,
            'datos_pago' => $datosPago,
            'error' => '',
            'success' => ''
        ];
        
        $view = new EntradaFormView();
        $view->show($data);
    }
    
    /**
     * procesar la selección de asiento
     */
    public function seleccionarAsiento($params = null) {
        $loginModel = new LoginModel();
        
        if (!$loginModel->isLoggedIn()) {
            $loginView = new LoginView();
            $loginView->show();
            return;
        }
        
        $sesionId = $params['sesion_id'] ?? null;
        $zonaId = $params['zona_id'] ?? null;
        $asientoId = $params['asiento_id'] ?? null;
        $precioId = $params['precio_id'] ?? null;
        
        if (!$sesionId || !$zonaId || !$asientoId || !$precioId) {
            $vHome = new EntradaFormView();
            $vHome->show(['error' => 'Debes seleccionar un asiento válido']);
        }
        
        // verifica que el aiento este disponible
        $asientoModel = new AsientoModel();
        $asiento = $asientoModel->getAsientoById($asientoId);
        
        if (!$asiento || $asiento->__get('estado') != 'DISPONIBLE') {
            $vHome = new EntradaFormView();
            $vHome->show(['error' => 'El asiento seleccionado no está disponible']);
        }
        
        $sesionModel = new SesionModel();
        $zonaModel = new ZonaModel();
        $precioModel = new PrecioModel();
        
        $sesion = $sesionModel->getSesionById($sesionId);
        $zonas = $zonaModel->getZonasByLugar($sesion->__get('lugar')->__get('id'));
        $zonaSeleccionada = $zonaModel->getZonaById($zonaId);
        $asientos = $asientoModel->getAsientosByZona($zonaId);
        $asientoSeleccionado = $asiento;
        $precioSeleccionado = $precioModel->getPrecioById($precioId);
        $precios = $precioModel->getPreciosBySesion($sesionId);
        
        // filtrar zonas con precio
        $zonasConPrecio = [];
        foreach ($zonas as $zona) {
            foreach ($precios as $precio) {
                if ($precio->__get('zona')['id'] == $zona->__get('id')) {
                    $zonasConPrecio[] = $zona;
                    break;
                }
            }
        }
        
        $datosCliente = $this->extraerDatosCliente($_POST);
        $datosPago = $this->extraerDatosPago($_POST);
        
        $data = [
            'sesion' => $sesion,
            'zonas' => $zonasConPrecio,
            'asientos' => $asientos,
            'precios' => $precios,
            'zona_seleccionada' => $zonaSeleccionada,
            'asiento_seleccionado' => $asientoSeleccionado,
            'precio_seleccionado' => $precioSeleccionado,
            'datos_cliente' => $datosCliente,
            'datos_pago' => $datosPago,
            'error' => '',
            'success' => ''
        ];
        
        $view = new EntradaFormView();
        $view->show($data);
    }
    
    /**
     * guarda los datos del cliente
     */
    public function guardarCliente($params = null) {
        $loginModel = new LoginModel();
        
        if (!$loginModel->isLoggedIn()) {
            $loginView = new LoginView();
            $loginView->show();
            return;
        }
        
        $sesionId = $params['sesion_id'] ?? null;
        $zonaId = $params['zona_id'] ?? null;
        $asientoId = $params['asiento_id'] ?? null; // puede ser null si no hay asientos
        $precioId = $params['precio_id'] ?? null;
        
        // valida datos del cliente
        $datosCliente = [
            'dni' => trim($_POST['cliente_dni'] ?? ''),
            'nombre' => trim($_POST['cliente_nombre'] ?? ''),
            'apellidos' => trim($_POST['cliente_apellidos'] ?? ''),
            'email' => trim($_POST['cliente_email'] ?? ''),
            'telefono' => trim($_POST['cliente_telefono'] ?? '')
        ];
        
        // validaciones
        if (empty($datosCliente['dni']) || empty($datosCliente['nombre']) ||
            empty($datosCliente['apellidos']) || empty($datosCliente['email'])) {
                $vHome = new EntradaFormView();
                $vHome->show(['error' => 'Todos los campos marcados con * son obligatorios']);
            }
            
            if (!filter_var($datosCliente['email'], FILTER_VALIDATE_EMAIL)) {
                $vHome = new EntradaFormView();
                $vHome->show(['error' => 'Todos los campos marcados con * son obligatorios']);
            }
            
            $sesionModel = new SesionModel();
            $zonaModel = new ZonaModel();
            $asientoModel = new AsientoModel();
            $precioModel = new PrecioModel();
            
            $sesion = $sesionModel->getSesionById($sesionId);
            $zonas = $zonaModel->getZonasByLugar($sesion->__get('lugar')->__get('id'));
            $zonaSeleccionada = $zonaModel->getZonaById($zonaId);
            $asientos = $asientoModel->getAsientosByZona($zonaId);
            $asientoSeleccionado = $asientoId ? $asientoModel->getAsientoById($asientoId) : null; // solo obtiene asiento si existe ID
            $precioSeleccionado = $precioModel->getPrecioById($precioId);
            $precios = $precioModel->getPreciosBySesion($sesionId);
            
            // filtrar zonas con precio
            $zonasConPrecio = [];
            foreach ($zonas as $zona) {
                foreach ($precios as $precio) {
                    if ($precio->__get('zona')['id'] == $zona->__get('id')) {
                        $zonasConPrecio[] = $zona;
                        break;
                    }
                }
            }
            
            $datosPago = $this->extraerDatosPago($_POST);
            
            $data = [
                'sesion' => $sesion,
                'zonas' => $zonasConPrecio,
                'asientos' => $asientos,
                'precios' => $precios,
                'zona_seleccionada' => $zonaSeleccionada,
                'asiento_seleccionado' => $asientoSeleccionado,
                'precio_seleccionado' => $precioSeleccionado,
                'datos_cliente' => $datosCliente,
                'datos_pago' => $datosPago,
                'error' => '',
                'success' => ''
            ];
            
            $view = new EntradaFormView();
            $view->show($data);
    }
    
    /**
     * guarda los datos de pago
     */
    public function guardarPago($params = null) {
        $loginModel = new LoginModel();
        
        if (!$loginModel->isLoggedIn()) {
            $loginView = new LoginView();
            $loginView->show();
            return;
        }
        
        $sesionId = $_POST['sesion_id'] ?? null;
        $zonaId = $_POST['zona_id'] ?? null;
        $asientoId = $_POST['asiento_id'] ?? null; // puede ser null si no hay asientos
        $precioId = $_POST['precio_id'] ?? null;
        
        $datosPago = [
            'metodo' => $_POST['metodo_pago'] ?? '',
            'referencia' => trim($_POST['referencia_pago'] ?? '')
        ];
        
        // validaciones
        if (empty($datosPago['metodo'])) {
            $vHome = new EntradaFormView();
            $vHome->show(['error' => 'Debes seleccionar un método de pago']);
        }
        
        // Obtener datos necesarios
        $sesionModel = new SesionModel();
        $zonaModel = new ZonaModel();
        $asientoModel = new AsientoModel();
        $precioModel = new PrecioModel();
        
        $sesion = $sesionModel->getSesionById($sesionId);
        $zonas = $zonaModel->getAllZonas();
        $zonaSeleccionada = $zonaModel->getZonaById($zonaId);
        $asientos = $asientoModel->getAsientosByZona($zonaId);
        $asientoSeleccionado = $asientoId ? $asientoModel->getAsientoById($asientoId) : null;
        $precioSeleccionado = $precioModel->getPrecioById($precioId);
        $precios = $precioModel->getPreciosBySesion($sesionId);
        
        $zonasConPrecio = [];
        foreach ($zonas as $zona) {
            foreach ($precios as $precio) {
                if ($precio->__get('zona')['id'] == $zona->__get('id')) {
                    $zonasConPrecio[] = $zona;
                    break;
                }
            }
        }
        
        $datosCliente = $this->extraerDatosCliente($_POST);
        
        $data = [
            'sesion' => $sesion,
            'zonas' => $zonasConPrecio,
            'asientos' => $asientos,
            'precios' => $precios,
            'zona_seleccionada' => $zonaSeleccionada,
            'asiento_seleccionado' => $asientoSeleccionado,
            'precio_seleccionado' => $precioSeleccionado,
            'datos_cliente' => $datosCliente,
            'datos_pago' => $datosPago,
            'error' => '',
            'success' => ''
        ];
        
        $view = new EntradaFormView();
        $view->show($data);
    }
    
    /**
     * procesa la compra de la entrada
     */
    public function procesar($params = null) {
        $loginModel = new LoginModel();
        
        if (!$loginModel->isLoggedIn()) {
            $loginView = new LoginView();
            $loginView->show();
            return;
        }
        
        $sesionId = $params['sesion_id'] ?? null;
        $zonaId = $params['zona_id'] ?? null;
        $asientoId = $params['asiento_id'] ?? null; // puede ser nullo para zonas sin asientos
        $precioId = $params['precio_id'] ?? null;
        $clienteDNI = $params['cliente_dni'] ?? null;
        $clienteNombre = $params['cliente_nombre'] ?? null;
        $clienteApellidos = $params['cliente_apellidos'] ?? null;
        $clienteEmail = $params['cliente_email'] ?? null;
        $clienteTelefono = $params['cliente_telefono'] ?? '';
        $metodoPago = $params['pago_metodo'] ?? null;
        $referencia = $params['pago_referencia'] ?? '';
        
        if (!$sesionId || !$zonaId || !$precioId || !$metodoPago ||
            !$clienteDNI || !$clienteNombre || !$clienteApellidos || !$clienteEmail) {
                $vHome = new EntradaFormView();
                $vHome->show(['sesion' => $sesionId, 'error' => 'Todos los campos son obligatorios']);
                exit;
            }
            
            try {
                // 1. obtener el precio total desde precio_id
                $precioModel = new PrecioModel();
                $precio = $precioModel->getPrecioById($precioId);
                
                if (!$precio) {
                    throw new Exception('Error: Precio no encontrado.');
                    exit;
                }
                
                $total = $precio->__get('importe');
                
                // 2. verificar si el cliente existe por DNI, si no existe crearlo
                $clienteModel = new ClienteModel();
                $cliente = $clienteModel->getClienteByDNI($clienteDNI);
                
                if (!$cliente) {
                    // Crear nuevo cliente
                    $clienteData = [
                        'dni' => $clienteDNI,
                        'nombre' => $clienteNombre,
                        'apellidos' => $clienteApellidos,
                        'email' => $clienteEmail,
                        'telefono' => $clienteTelefono,
                        'password' => 'password' // ! quitar esta cutrada después
                    ];
                    
                    $clienteId = $clienteModel->crearCliente($clienteData);
                    
                    if (!$clienteId) {
                        throw new Exception('Error al registrar al cliente durante la compra');
                    }
                } else {
                    $clienteId = $cliente->__get('id');
                }
                
                // 3. crear el pago
                $pagoModel = new PagoModel();
                $pagoData = [
                    'cliente_id' => $clienteId,
                    'fecha' => date('Y-m-d'),
                    'total' => $total,
                    'metodo' => $metodoPago,
                    'referencia' => $referencia,
                    'estado' => 'PENDIENTE'
                ];
                
                $pagoId = $pagoModel->crearPago($pagoData);
                
                if (!$pagoId) {
                    throw new Exception('Error al registrar el pago');
                }
                
                // 4. crear la entrada
                $entradaModel = new EntradaModel();
                $entradaData = [
                    'pago_id' => $pagoId,
                    'sesion_id' => $sesionId,
                    'zona_id' => $zonaId,
                    'precio_id' => $precioId,
                    'asiento_id' => $asientoId // ! puede ser null
                ];
                
                $resultado = $entradaModel->crearEntrada($entradaData);
                
                if ($resultado) {
                    // 5. solo actualizar estado del asiento si existe
                    if ($asientoId) {
                        $asientoModel = new AsientoModel();
                        $asientoModel->actualizarEstadoAsiento($asientoId, 'OCUPADO');
                    }
                    
                    // 6. actualizar estado del pago a CONFIRMADO
                    $pagoModel->actualizarEstadoPago($pagoId, 'CONFIRMADO');
                    
                    $entrada = $entradaModel->getEntradaById($resultado);
                    
                    // redirigir a página de confirmación
                    $vHome = new EntradaExitoView();
                    $vHome->show($entrada);
                } else {
                    // si falla la creación de entrada, cancelar el pago
                    $pagoModel->actualizarEstadoPago($pagoId, 'CANCELADO');
                    $vHome = new EntradaFormView();
                    $vHome->show(['error' => 'Error al crear la entrada']);
                }
                
            } catch (Exception $e) {
                // En caso de error, si se creó un pago, marcarlo como cancelado
                if (isset($pagoId)) {
                    $pagoModel->actualizarEstadoPago($pagoId, 'CANCELADO');
                }
                $vHome = new EntradaFormView();
                $vHome->show(['error' => 'Error alcrear la entrada. intenta más tarde.']);
            }
    }
    
    /**
     * extrae los datos del cliente de los parámetros POST
     */
    private function extraerDatosCliente($post) {
        $datos = [];
        
        if (isset($post['cliente_dni'])) $datos['dni'] = $post['cliente_dni'];
        if (isset($post['cliente_nombre'])) $datos['nombre'] = $post['cliente_nombre'];
        if (isset($post['cliente_apellidos'])) $datos['apellidos'] = $post['cliente_apellidos'];
        if (isset($post['cliente_email'])) $datos['email'] = $post['cliente_email'];
        if (isset($post['cliente_telefono'])) $datos['telefono'] = $post['cliente_telefono'];
        
        return $datos;
    }
    
    /**
     * extrae los datos de pago de los parámetros POST
     */
    private function extraerDatosPago($post) {
        $datos = [];
        
        if (isset($post['pago_metodo'])) $datos['metodo'] = $post['pago_metodo'];
        if (isset($post['pago_referencia'])) $datos['referencia'] = $post['pago_referencia'];
        
        return $datos;
    }
}