<?php

class EntradaModel {
    
    private $apiUrl;
    private $baseApiUrl;
    
    public function __construct() {
        $this->apiUrl = 'http://localhost/Microservicios/src/index.php?entity=entrada';
        $this->baseApiUrl = 'http://localhost/Microservicios/src/index.php';
    }
    
    /**
     * crea una nueva entrada
     * @param array $entradaData array con los datos de la entrada
     * @return bool|int ID de la entrada creada o false si falla
     */
    public function crearEntrada($entradaData) {
        $loginModel = new LoginModel();
        $token = $loginModel->getAuthToken();
        
        // agregar fecha de compra si no está presente
        if (!isset($entradaData['fecha_compra'])) {
            $entradaData['fecha_compra'] = date('Y-m-d H:i:s');
        }
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($entradaData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Authorization: Bearer $token"
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error || ($httpCode !== 200 && $httpCode !== 201)) {
            return false;
        }
        
        $decodedResponse = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }
        
        return isset($decodedResponse['id']) ? $decodedResponse['id'] : true;
    }
    
    /**
     * obtiene una entrada específica por ID
     * @param int $id ID de la entrada
     * @return Entrada|false objeto Entrada o false si falla
     */
    public function getEntradaById($id) {
        $loginModel = new LoginModel();
        $token = $loginModel->getAuthToken();
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl . "&id=" . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Authorization: Bearer $token"
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error || $httpCode !== 200) {
            return false;
        }
        
        $decodedResponse = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE || empty($decodedResponse)) {
            return false;
        }
        
        $entradaData = is_array($decodedResponse) && isset($decodedResponse[0]) ? $decodedResponse[0] : $decodedResponse;
        
        return $this->convertToEntradaObject($entradaData);
    }
    
    /**
     * actualiza el estado de una entrada
     * @param int $id ID de la entrada
     * @param string $estado nuevo estado de la entrada
     * @return bool true si se actualizó correctamente, false en caso contrario
     */
    public function actualizarEstadoEntrada($id, $estado) {
        $loginModel = new LoginModel();
        $token = $loginModel->getAuthToken();
        
        $data = [
            'estado' => $estado
        ];
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl . "&id=" . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Authorization: Bearer $token"
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error || ($httpCode !== 200 && $httpCode !== 204)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * elimina una entrada (cancelar compra)
     * @param int $id ID de la entrada
     * @return bool true si se eliminó correctamente, false en caso contrario
     */
    public function eliminarEntrada($id) {
        $loginModel = new LoginModel();
        $token = $loginModel->getAuthToken();
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl . "&id=" . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Authorization: Bearer $token"
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error || ($httpCode !== 200 && $httpCode !== 204)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * realiza una petición GET a una entidad específica
     * @param string $entity nombre de la entidad
     * @param int $id ID del objeto
     * @return array|false datos del objeto o false si falla
     */
    private function fetchEntityById($entity, $id) {
        $loginModel = new LoginModel();
        $token = $loginModel->getAuthToken();
        
        $url = $this->baseApiUrl . "?entity=" . $entity . "&id=" . $id;
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Authorization: Bearer $token"
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error || $httpCode !== 200) {
            return false;
        }
        
        $decodedResponse = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE || empty($decodedResponse)) {
            return false;
        }
        
        return is_array($decodedResponse) && isset($decodedResponse[0]) ? $decodedResponse[0] : $decodedResponse;
    }
    
    /**
     * construye el objeto Pago completo
     * @param array $entradaData datos de la entrada
     * @return array Objeto pago completo
     */
    private function buildPagoObject($entradaData) {
        
        
        $clienteData = $this->fetchEntityById('cliente', $entradaData['pago']['cliente']['id']);
        
        return [
            'id' => $entradaData['pago']['id'],
            'cliente' => $clienteData ? [
                'id' => $clienteData['id'],
                'nombre' => $clienteData['nombre'],
                'email' => $clienteData['email']
            ] : null,
            'fecha' => $entradaData['pago']['fecha'],
            'total' => $entradaData['pago']['total'],
            'metodo' => $entradaData['pago']['metodo'],
            'referencia' => isset($entradaData['pago']['referencia']) ? $entradaData['pago']['referencia'] : '',
            'estado' => $entradaData['pago']['estado']
        ];
    }
    
    /**
     * construye el objeto Sesion completo
     * @param array $entradaData datos de la entrada
     * @return array Objeto Sesion completo
     */
    private function buildSesionObject($entradaData) {
        
        $conciertoData = $this->fetchEntityById('concierto', $entradaData['sesion']['concierto']['id']);
        
        $lugarData = $this->fetchEntityById('lugar', $entradaData['sesion']['lugar']['id']);
        
        $artistaData = null;
        if ($conciertoData && isset($conciertoData['artista']['id'])) {
            $artistaData = $this->fetchEntityById('artista', $conciertoData['artista']['id']);
        }
        
        return [
            'id' => $entradaData['sesion']['id'],
            'concierto' => $conciertoData ? [
                'id' => $conciertoData['id'],
                'nombre' => $conciertoData['nombre'],
                'descripcion' => $conciertoData['descripcion'],
                'imagen' => $conciertoData['imagen'],
                'artista' => $artistaData ? [
                    'id' => $artistaData['id'],
                    'nombre' => $artistaData['nombre'],
                    'pais' => $artistaData['pais'],
                    'genero' => $artistaData['genero']
                ] : null
            ] : null,
            'lugar' => $lugarData ? [
                'id' => $lugarData['id'],
                'nombre' => $lugarData['nombre'],
                'direccion' => $lugarData['direccion'],
                'ciudad' => $lugarData['ciudad'],
                'capacidad' => $lugarData['capacidad']
            ] : null,
            'fecha' => $entradaData['sesion']['fecha'],
            'hora' => $entradaData['sesion']['hora'],
            'estado' => $entradaData['sesion']['estado']
        ];
    }
    
    private function buildZonaObject($entradaData) {
        $zonaData = $this->fetchEntityById('zona', $entradaData['zona']['id']);
        
        return $zonaData ? [
            'id' => $zonaData['id'],
            'nombre' => $zonaData['nombre'],
            'descripcion' => $zonaData['descripcion']
        ] : null;
    }

    
    private function buildAsientoObject($entradaData) {
        $asientoData = $this->fetchEntityById('asiento', $entradaData['asiento']['id']);
        
        return $asientoData ? [
            'id' => $asientoData['id'],
            'fila' => $asientoData['fila'],
            'numero' => $asientoData['numero'],
            'estado' => $asientoData['estado'],
            'zona' => $asientoData['zona']['id'],
            'lugar' => $asientoData['lugar']['id']
        ] : null;
    }
    
    private function buildPrecioObject($entradaData) {
        $precioData = $this->fetchEntityById('precio', $entradaData['precio']['id']);
        
        return $precioData ? [
            'id' => $precioData['id'],
            'importe' => $precioData['importe'],
            'sesion' => $precioData['sesion']['id'],
            'zona' => $precioData['zona']['id']
        ] : null;
    }
    

    private function convertToEntradaObject($entradaData) {

        $pagoObject = $this->buildPagoObject($entradaData);
        $sesionObject = $this->buildSesionObject($entradaData);
        $zonaObject = $this->buildZonaObject($entradaData);
        $asientoObject = $this->buildAsientoObject($entradaData);
        $precioObject = $this->buildPrecioObject($entradaData);
        
        $entradaCompleta = [
            'id' => $entradaData['id'],
            'codigo' => $entradaData['codigo'],
            'pago' => $pagoObject,
            'sesion' => $sesionObject,
            'zona' => $zonaObject,
            'asiento' => $asientoObject,
            'fila' => $asientoObject ? $asientoObject['fila'] : '',
            'asiento_numero' => $asientoObject ? $asientoObject['numero'] : '',
            'precio' => $precioObject,
            'estado' => $entradaData['estado']
        ];
        
        return new Entrada(
            $entradaCompleta['id'],
            $pagoObject ? $pagoObject['cliente']['id'] : null,
            $sesionObject ? $sesionObject['id'] : null,
            $asientoObject ? $asientoObject['id'] : null,
            $precioObject ? $precioObject['id'] : null,
            $pagoObject ? $pagoObject['fecha'] : null,
            $pagoObject ? $pagoObject['metodo'] : null,
            $pagoObject ? $pagoObject['referencia'] : '',
            $entradaCompleta['estado'],
            $entradaCompleta 
            );
    }
    
    private function convertToEntradaObjects($jsonData) {
        $entradas = [];
        
        foreach ($jsonData as $entradaData) {
            $entradas[] = $this->convertToEntradaObject($entradaData);
        }
        
        return $entradas;
    }
    
    public function getEntradaCompletaById($id) {
        $loginModel = new LoginModel();
        $token = $loginModel->getAuthToken();
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl . "&id=" . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Authorization: Bearer $token"
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error || $httpCode !== 200) {
            return false;
        }
        
        $decodedResponse = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE || empty($decodedResponse)) {
            return false;
        }
        
        $entradaData = is_array($decodedResponse) && isset($decodedResponse[0]) ? $decodedResponse[0] : $decodedResponse;
        
        return [
            'id' => $entradaData['id'],
            'codigo' => $entradaData['codigo'],
            'pago' => $this->buildPagoObject($entradaData),
            'sesion' => $this->buildSesionObject($entradaData),
            'zona' => $this->buildZonaObject($entradaData),
            'asiento' => $this->buildAsientoObject($entradaData),
            'fila' => $this->buildAsientoObject($entradaData)['fila'],
            'asiento_numero' => $this->buildAsientoObject($entradaData)['numero'],
            'precio' => $this->buildPrecioObject($entradaData),
            'estado' => $entradaData['estado']
        ];
    }
}