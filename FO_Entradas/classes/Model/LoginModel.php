<?php

class LoginModel {
    
    private $apiUrl;
    
    public function __construct() {
        $this->apiUrl = 'http://localhost/Microservicios/src/index.php?entity=auth';
    }
    
    /**
     * autentica un usuario mediante la API
     */
    public function authenticate($email, $password) {
        
        $data = json_encode([
            'email' => $email,
            'password' => $password
        ]);
        
        // configurar cURL
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true
        ]);
        
        // ejecutar la petición
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        // verificar errores de cURL
        if ($error) {
            error_log("Error cURL en LoginModel: " . $error);
            return false;
        }
        
        // verificar código de respuesta HTTP
        if ($httpCode !== 200) {
            error_log("Error HTTP en LoginModel: Código " . $httpCode);
            return false;
        }
        
        // decodificar la respuesta JSON
        $decodedResponse = json_decode($response, true);
        
        // verificar si la decodificación fue exitosa
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Error JSON en LoginModel: " . json_last_error_msg());
            return false;
        }
        
        // verificar si la respuesta contiene token y user
        if (!isset($decodedResponse['token']) || !isset($decodedResponse['user'])) {
            return false;
        }
        
        return $decodedResponse;
    }
    
    /**
     * Guarda la sesión del usuario
     */
    public function saveSession($userData) {
        
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $_SESSION['user_logged'] = true;
            $_SESSION['user_id'] = $userData['user']['id'];
            $_SESSION['user_email'] = $userData['user']['email'];
            $_SESSION['auth_token'] = $userData['token'];
            $_SESSION['login_time'] = time();
            
            return true;
        } catch (Exception $e) {
            error_log("Error al guardar sesión: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * verifica si el usuario está logueado
     * @return bool
     */
    public function isLoggedIn() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['user_logged']) && $_SESSION['user_logged'] === true;
    }
    
    /**
     * obtiene el token de autenticación de la sesión
     * @return string|null
     */
    public function getAuthToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return $_SESSION['auth_token'] ?? null;
    }
    
    /**
     * obtiene los datos del usuario logueado
     * @return array|null
     */
    public function getLoggedUser() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'email' => $_SESSION['user_email']
        ];
    }
    
    /**
     * cierra la sesión del usuario
     * @return bool
     */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        unset($_SESSION['user_logged']);
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['auth_token']);
        unset($_SESSION['login_time']);
        
        if (empty($_SESSION)) {
            session_destroy();
        }
        
        return true;
    }
}