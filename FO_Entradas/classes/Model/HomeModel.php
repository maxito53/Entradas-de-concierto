<?php

class HomeModel {
    
    private $apiUrl;
    private $preciosApiUrl;
    
    public function __construct() {
        $this->apiUrl = 'http://localhost/Microservicios/src/index.php?entity=sesion';
        $this->preciosApiUrl = 'http://localhost/Microservicios/src/index.php?entity=precio';
    }
    
    public function getSesiones() {
        
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
        
        if ($error) {
            return false;
        }
        
        if ($httpCode !== 200) {
            return false;
        }
        
        $decodedResponse = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }
        
        return $this->convertToDAOObjects($decodedResponse);
    }
    
    private function getPrecioMasBajo($sesionId) {
        $loginModel = new LoginModel();
        $token = $loginModel->getAuthToken();
        
        $url = $this->preciosApiUrl . "&action=getPreciosBySesion&sesionId=" . $sesionId;
        
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
            return null;
        }
        
        $precios = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE || empty($precios)) {
            return null;
        }
        
        $precioMasBajo = null;
        foreach ($precios as $precio) {
            if ($precioMasBajo === null || $precio['importe'] < $precioMasBajo) {
                $precioMasBajo = $precio['importe'];
            }
        }
        
        return $precioMasBajo;
    }
    
    private function convertToDAOObjects($jsonData) {
        $sesiones = [];
        $precios = [];
        
        foreach ($jsonData as $sesionData) {
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
            
            $sesion = new Sesion(
                $sesionData['id'],
                $concierto,
                $lugar,
                $sesionData['fecha'],
                $sesionData['hora'],
                $sesionData['estado']
                );
            
            $precioMasBajo = $this->getPrecioMasBajo($sesion->__get('id'));
            
            $precios[$sesion->__get('id')] = $precioMasBajo;
            
            $sesiones[] = $sesion;
        }
        
        return ['sesiones' => $sesiones, 'precios' => $precios];
    }
}