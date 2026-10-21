<?php

class AsientoModel {
    
    private $apiUrl;
    
    public function __construct() {
        $this->apiUrl = 'http://localhost/Microservicios/src/index.php?entity=asiento';
    }
    
    /**
     * obtiene todos los asientos de una zona específica
     * @param int $zonaId ID de la zona
     * @return array|false array de objetos Asiento o false si falla
     */
    public function getAsientosByZona($zonaId) {
        $loginModel = new LoginModel();
        $token = $loginModel->getAuthToken();
        
        // configurar cURL
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl . "&action=getAsientosByZona&zona_id=" . $zonaId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Authorization: Bearer $token"
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true
        ]);
        
        // ejecutar la petición
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        // verificar errores
        if ($error || $httpCode !== 200) {
            return false;
        }
        
        // decodificar la respuesta JSON
        $decodedResponse = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }
        
        // convertir a objetos Asiento
        return $this->convertToAsientoObjects($decodedResponse);
    }
    
    /**
     * obtiene asientos disponibles de una zona específica
     * @param int $zonaId ID de la zona
     * @return array|false array de objetos Asiento disponibles o false si falla
     */
    public function getAsientosDisponiblesByZona($zonaId) {
        $asientos = $this->getAsientosByZona($zonaId);
        
        if ($asientos === false) {
            return false;
        }
        
        $asientosDisponibles = [];
        foreach ($asientos as $asiento) {
            if ($asiento->__get('estado') === 'DISPONIBLE') {
                $asientosDisponibles[] = $asiento;
            }
        }
        
        return $asientosDisponibles;
    }
    
    /**
     * obtiene un asiento específico por ID
     * @param int $id ID del asiento
     * @return Asiento|false objeto Asiento o false si falla
     */
    public function getAsientoById($id) {
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
        
        // si la API devuelve un array, tomar el primer elemento
        $asientoData = is_array($decodedResponse) && isset($decodedResponse[0]) ? $decodedResponse[0] : $decodedResponse;
        
        return $this->convertToAsientoObject($asientoData);
    }
    
    /**
     * actualiza el estado de un asiento
     * @param int $id ID del asiento
     * @param string $estado nuevo estado del asiento (DISPONIBLE, OCUPADO, RESERVADO)
     * @return bool true si se actualizó correctamente, false en caso contrario
     */
    public function actualizarEstadoAsiento($id, $estado) {
        $loginModel = new LoginModel();
        $token = $loginModel->getAuthToken();
        
        $data = [
            'estado' => $estado
        ];
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl  . "&id=" . $id,
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
     * convierte un array de datos JSON a un objeto Asiento
     * @param array $asientoData array de datos de asiento
     * @return Asiento objeto Asiento
     */
    private function convertToAsientoObject($asientoData) {
        return new Asiento(
            $asientoData['id'],
            $asientoData['lugar'],
            $asientoData['zona'],
            $asientoData['fila'],
            $asientoData['numero'],
            $asientoData['estado']
        );
    }
    
    /**
     * convierte array de datos JSON a array de objetos Asiento
     * @param array $jsonData array de datos JSON
     * @return array array de objetos Asiento
     */
    private function convertToAsientoObjects($jsonData) {
        $asientos = [];
        
        foreach ($jsonData as $asientoData) {
            $asientos[] = $this->convertToAsientoObject($asientoData);
        }
        
        return $asientos;
    }
}