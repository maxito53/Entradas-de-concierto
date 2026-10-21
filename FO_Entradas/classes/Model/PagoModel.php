<?php

class PagoModel {
    
    private $apiUrl;
    
    public function __construct() {
        $this->apiUrl = 'http://localhost/Microservicios/src/index.php?entity=pago';
    }
    
    public function crearPago($pagoData) {
        $loginModel = new LoginModel();
        $token = $loginModel->getAuthToken();
        
        if (!isset($pagoData['fecha'])) {
            $pagoData['fecha'] = date('Y-m-d');
        }
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($pagoData),
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
    
    public function getPagoById($id) {
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
        
        $pagoData = is_array($decodedResponse) && isset($decodedResponse[0]) ? $decodedResponse[0] : $decodedResponse;
        
        return $this->convertToPagoObject($pagoData);
    }
    
    public function getPagosByCliente($clienteId) {
        $loginModel = new LoginModel();
        $token = $loginModel->getAuthToken();
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl . "&action=getPagosByCliente&clienteId=" . $clienteId,
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
        
        return $this->convertToPagoObjects($decodedResponse);
    }
    
    public function actualizarEstadoPago($id, $estado) {
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
    
    public function eliminarPago($id) {
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
    
    private function convertToPagoObject($pagoData) {
        return new Pago(
            $pagoData['id'],
            $pagoData['cliente_id'],
            $pagoData['fecha'],
            $pagoData['total'],
            $pagoData['metodo'],
            isset($pagoData['referencia']) ? $pagoData['referencia'] : '',
            $pagoData['estado']
        );
    }
    
    private function convertToPagoObjects($jsonData) {
        $pagos = [];
        
        foreach ($jsonData as $pagoData) {
            $pagos[] = $this->convertToPagoObject($pagoData);
        }
        
        return $pagos;
    }
}