<?php

class SesionModel {
    
    private $apiUrl;
    
    public function __construct() {
        $this->apiUrl = 'http://localhost/Microservicios/src/index.php?entity=sesion';
    }
    
    public function getSesionById($id) {
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
        
        $sesionData = is_array($decodedResponse) && isset($decodedResponse[0]) ? $decodedResponse[0] : $decodedResponse;
        
        return $this->convertToSesionObject($sesionData);
    }
    
    public function getAllSesiones() {
        $loginModel = new LoginModel();
        $token = $loginModel->getAuthToken();
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl,
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
        
        return $this->convertToSesionObjects($decodedResponse);
    }
    

    private function convertToSesionObject($sesionData) {

        $artista = new Artista(
            $sesionData['concierto']['artista']['id'],
            $sesionData['concierto']['artista']['nombre'],
            $sesionData['concierto']['artista']['pais'],
            $sesionData['concierto']['artista']['genero']
        );
        
        $concierto = new Concierto(
            $sesionData['concierto']['id'],
            $sesionData['concierto']['nombre'],
            $sesionData['concierto']['descripcion'],
            $sesionData['concierto']['imagen'],
            $artista
        );
        
        $lugar = new Lugar(
            $sesionData['lugar']['id'],
            $sesionData['lugar']['nombre'],
            $sesionData['lugar']['direccion'],
            $sesionData['lugar']['ciudad'],
            $sesionData['lugar']['capacidad']
        );
        
        return new Sesion(
            $sesionData['id'],
            $concierto,
            $lugar,
            $sesionData['fecha'],
            $sesionData['hora'],
            $sesionData['estado']
        );
    }
    
    private function convertToSesionObjects($jsonData) {
        $sesiones = [];
        
        foreach ($jsonData as $sesionData) {
            $sesiones[] = $this->convertToSesionObject($sesionData);
        }
        
        return $sesiones;
    }
}