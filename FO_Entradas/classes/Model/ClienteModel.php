<?php

class ClienteModel {
    
    private $apiUrl;
    
    public function __construct() {
        $this->apiUrl = 'http://localhost/Microservicios/src/index.php?entity=cliente';
    }
    
    /**
     * crea un nuevo cliente
     * @param array $clienteData array con los datos del cliente
     * @return bool|int ID del cliente creado o false si falla
     */
    public function crearCliente($clienteData) {
        $loginModel = new LoginModel();
        $token = $loginModel->getAuthToken();
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($clienteData),
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
     * obtiene un cliente específico por ID
     * @param int $id ID del cliente
     * @return Cliente|false objeto Cliente o false si falla
     */
    public function getClienteById($id) {
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
        
        $clienteData = is_array($decodedResponse) && isset($decodedResponse[0]) ? $decodedResponse[0] : $decodedResponse;
        
        return $this->convertToClienteObject($clienteData);
    }
    
    /**
     * obtiene un cliente por DNI
     * @param string $dni DNI del cliente
     * @return Cliente|false Objeto cliente o false si no existe o falla
     */
    public function getClienteByDNI($dni) {
        $loginModel = new LoginModel();
        $token = $loginModel->getAuthToken();
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl . "&action=getClienteByDni&dni=" . urlencode($dni),
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
        
        // di la API devuelve un array, tomar el primer elemento
        $clienteData = is_array($decodedResponse) && isset($decodedResponse[0]) ? $decodedResponse[0] : $decodedResponse;
        
        return $this->convertToClienteObject($clienteData);
    }
    
    /**
     * obtiene todos los vlientes
     * @return array|false Aarray de objetos Cliente o false si falla
     */
    public function getAllClientes() {
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
        
        return $this->convertToClienteObjects($decodedResponse);
    }
    
    /**
     * actualiza los datos de un cliente
     * @param int $id ID del cliente
     * @param array $clienteData array con los nuevos datos del cliente
     * @return bool true si se actualizó correctamente, false en caso contrario
     */
    public function actualizarCliente($id, $clienteData) {
        $loginModel = new LoginModel();
        $token = $loginModel->getAuthToken();
                
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl . "&id=" . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($clienteData),
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
     * elimina un cliente
     * @param int $id ID del cliente
     * @return bool true si se eliminó coreectamete, false en caso contrario
     */
    public function eliminarCliente($id) {
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
     * convierte un array de datos JSON a un objeto Cliente
     * @param array $clienteData array de datos de cliente
     * @return Cliente Objeto Cliente
     */
    private function convertToClienteObject($clienteData) {
        return new Cliente(
            $clienteData['id'],
            $clienteData['dni'],
            $clienteData['nombre'],
            $clienteData['apellidos'],
            $clienteData['email'],
            isset($clienteData['telefono']) ? $clienteData['telefono'] : ''
        );
    }
    
    /**
     * convierte array de datos JSON a array de objetos Cliente
     * @param array $jsonData array de datos JSON
     * @return array array de objetos Cliente
     */
    private function convertToClienteObjects($jsonData) {
        $clientes = [];
        
        foreach ($jsonData as $clienteData) {
            $clientes[] = $this->convertToClienteObject($clienteData);
        }
        
        return $clientes;
    }
}