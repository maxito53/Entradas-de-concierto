<?php 

class LoginView extends View {
    
    public function show($params=null) {
        
        $errorMessage = isset($params['error']) ? $params['error'] : '';
        $successMessage = isset($params['success']) ? $params['success'] : '';
        
        echo "<!DOCTYPE html><html lang=\"es\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>Iniciar Sesión - Ticketmaxter</title>
                <link rel=\"stylesheet\" href=\"../css/login.css\" type=\"text/css\">
            </head>";
        echo "<body>
                
                <div class=\"login-container\">
                    <div class=\"logo\">
                        <img src=\"media/logoAzul.svg\" alt=\"Tiketmaxter\" style=\"height: 1em; width: auto;\" />
                    </div>
                    <p class=\"subtitle\">Inicia sesión para comprar tus entradas</p>";
        
        if (!empty($errorMessage)) {
            echo "<div class=\"error-message\">" . htmlspecialchars($errorMessage) . "</div>";
        }
        
        if (!empty($successMessage)) {
            echo "<div class=\"success-message\">" . htmlspecialchars($successMessage) . "</div>";
        }
                    
        echo        "<form id=\"loginForm\" method=\"POST\" action=\"index.php?Login/login\">
                        <div class=\"form-group\">
                            <label for=\"email\">Email</label>
                            <div class=\"input-icon email-icon\">
                                <input 
                                    type=\"email\" 
                                    id=\"email\" 
                                    name=\"email\" 
                                    required 
                                    placeholder=\"tu@email.com\"
                                    value=\"" . (isset($params['email']) ? htmlspecialchars($params['email']) : '') . "\"
                                >
                            </div>
                        </div>
                        
                        <div class=\"form-group\">
                            <label for=\"password\">Contraseña</label>
                            <div class=\"input-icon password-icon\">
                                <input 
                                    type=\"password\" 
                                    id=\"password\" 
                                    name=\"password\" 
                                    required 
                                    placeholder=\"Tu contraseña\"
                                >
                            </div>
                        </div>
                        
                        <button type=\"submit\" class=\"login-btn\">Iniciar Sesión</button>
                    </form>
                </div>
            </body>
            </html>";
    }
    
}