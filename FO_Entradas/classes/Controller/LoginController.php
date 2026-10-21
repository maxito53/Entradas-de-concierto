<?php

class LoginController extends Controller {
    
    public function __construct() {}
    
    public function show($params = null) {
        $loginModel = new LoginModel();
        
        if (!$loginModel->isLoggedIn()) {
            $vHome = new LoginView();
            $vHome->show();
        } else {
            $vHome = new HomeView();
            $vHome->show();
        } 
    }
    
    public function login($params) {
        
        $loginModel = new LoginModel();
        if ($loginModel->isLoggedIn()) {
            $vHome = new LoginView();
            $vHome->show();
            return;
        }
        
        $email = "";
        $password = "";
        $errores = array();
        
        //var_dump($params);
        //die();
        
        // Validar email
        if (empty($params['email'])) {
            $errores["email"] = "&#9888; El email es obligatorio";
        } elseif (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
            $errores["email"] = "&#9888; El email no es válido";
        } else {
            $email = ($this->validateItem($params["email"], "alfanum")) ? $this->sanitize($params["email"]) : "";
            if ($email == "") {
                $errores["email"] = "&#9888; El email no pasó las validaciones";
            }
        }
        
        // Validar password
        if (empty($params['password'])) {
            $errores["password"] = "&#9888; La contraseña es obligatoria";
        } elseif (strlen($params['password']) < 8 ||
            !preg_match('/[A-Z]/', $params['password']) ||
            !preg_match('/[a-z]/', $params['password']) ||
            !preg_match('/[0-9]/', $params['password']) ||
            !preg_match('/[\W_]/', $params['password'])) {
                $errores["password"] = "&#9888; La contraseña no cumple los requisitos";
        } else {
            $password = $this->sanitize($params['password']);
            if ($password == "") {
                $errores["password"] = "&#9888; La contraseña no pasó las validaciones";
            }
        }
        
        // si no hay errores de validación, intentar autenticar
        if (!isset($errores) || empty($errores)) {
            
            $loginModel = new LoginModel();
            
            try {
                // intentar autenticar con la API
                $authResult = $loginModel->authenticate($email, $password);
                
                if ($authResult === false) {
                    $errores['bbdd'] = "Email o contraseña incorrectos";
                } else {
                    // guardar la sesión
                    if ($loginModel->saveSession($authResult)) {
                        header('Location: index.php');
                        exit();
                    } else {
                        $errores['bbdd'] = "Error al iniciar sesión, intenta de nuevo";
                    }
                }
                
            } catch (Exception $e) {
                error_log("Error en LoginController: " . $e->getMessage());
                $errores['bbdd'] = "Error de conexión, intenta más tarde";
            }
        }
        
        // si hay errores, mostrar el formulario con los errores
        if (isset($errores) && !empty($errores)) {
            $vLogin = new LoginView();
            $vLogin->show(['error' => implode('<br>', $errores)]);
        }
    }
    
    /**
     * método para hacer logoutt
     */
    public function logout($params = null) {
        $loginModel = new LoginModel();
        $loginModel->logout();
        
        // Redireccionar al login o página principal
        header('Location: index.php?Login/show');
        exit();
    }
    
    /**
     * método para verificar si el usuario está logueado
     */
    public function checkAuth() {
        $loginModel = new LoginModel();
        return $loginModel->isLoggedIn();
    }
}

?>