<?php 

class ErrorView extends View {
    
    public function show($e) {
        
        echo "
            <!DOCTYPE html><html lang=\"es\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>Página no encontrada</title>
                <link rel=\"stylesheet\" href=\"../css/error404.css\" type=\"text/css\">
            </head>";
        
        echo "<body>
            <div class=\"container\">
                <div class=\"error-code\">404</div>
                    <h1 class=\"error-title\">Página no encontrada</h1>
                    <p class=\"error-message\">
                        {$e->getMessage()}
                    </p>
                    <div class=\"btn-container\">
                    <a href=\"\" class=\"btn btn-primary\">Volver al inicio</a>
                    <button class=\"btn btn-secondary\" onclick=\"history.back()\">Página anterior</button>
                </div>
            </div>
        </body>
        </html>";
        
    }
}