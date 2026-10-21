<?php

class PrecioModel {
    
    private $apiUrl;
    
    public function __construct() {
        $this->apiUrl = 'http://localhost/Microservicios/src/index.php?entity=precio';
    }
    
    public function getPrecioByZonaYSesion($zonaId, $sesionId) {
        $loginModel = new LoginModel();
        $token = $loginModel->getAuthToken();
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl . "&action=getPrecioBySesionAndZona&sesionId=" . $sesionId . "&zonaId=" . $zonaId,
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
        
        $precioData = is_array($decodedResponse) && isset($decodedResponse[0]) ? $decodedResponse[0] : $decodedResponse;
        
        return $this->convertToPrecioObject($precioData);
    }
    
    public function getPreciosBySesion($sesionId) {
        $loginModel = new LoginModel();
        $token = $loginModel->getAuthToken();
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl . "&action=getPreciosBySesion&sesionId=" . $sesionId,
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
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }
        
        return $this->convertToPrecioObjects($decodedResponse);
    }
    
    public function getPrecioById($id) {
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
        
        $precioData = is_array($decodedResponse) && isset($decodedResponse[0]) ? $decodedResponse[0] : $decodedResponse;
        
        // usar convertToPrecioObject (singular) en lugar de convertToPrecioObjects (plural)
        return $this->convertToPrecioObject($precioData);
    }
    
    private function convertToPrecioObject($precioData) {
        //echo '<pre>';
        //var_dump($precioData);
        //echo '</pre>';
        //die;
        return new Precio(
            
            $precioData['id'],
            $precioData['sesion'],
            $precioData['zona'],
            $precioData['importe'],
            isset($precioData['tipo']) ? $precioData['tipo'] : 'GENERAL'
        );
    }
    
    private function convertToPrecioObjects($jsonData) {
        $precios = [];
        
        foreach ($jsonData as $precioData) {
            $precios[] = $this->convertToPrecioObject($precioData);
        }
        
        return $precios;
    }
}