<?php

class EntradaExitoView extends View {
    
    public function show($entrada) {
        if (!$entrada) {
            // redirigir al inicio si no hay entrada
            header('Location: index.php?Home/show');
            exit;
        }
        
        echo "<!DOCTYPE html><html lang=\"es\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>¡Entrada Confirmada! - Ticketmaxter</title>
                <link rel=\"stylesheet\" href=\"../css/entrada-exito.css\" type=\"text/css\">
            </head>";
        
        echo "<body>
            <header>
                <div class=\"container\">
                    <div class=\"header-content\">
                        <div class=\"logo\">
                            <img src=\"media/logo.svg\" alt=\"Tiketmaxter\" style=\"height: 1em; width: auto;\" />
                        </div>
                        <div class=\"header-actions\">
                            <form method=\"post\" action=\"index.php?Login/logout\" style=\"display: inline;\">
                                <button type=\"submit\" class=\"logout-btn\">Cerrar sesión</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>";
        
        // sección de éxito
        echo "<section class=\"success-hero\">
                <div class=\"container\">
                    <div class=\"success-content\">
                        <div class=\"success-icon\">
                            <div class=\"check-circle\">
                                <div class=\"check-mark\">✓</div>
                            </div>
                        </div>
                        <h1>¡Felicidades!</h1>
                        <h2>Tu entrada se creó correctamente</h2>
                    </div>
                </div>
            </section>";
        
        // sección de acciones simplificada
        echo "<section class=\"actions-section\">
                <div class=\"container\">
                    <div class=\"actions-card\">
                        <h3>¿Qué quieres hacer ahora?</h3>
                        <div class=\"actions-buttons\">
                            <a href=\"index.php?Home/show\" class=\"action-btn home-btn\">
                                <span class=\"btn-icon\">🏠</span>
                                Volver al Inicio
                            </a>
                            <a href=\"http://localhost/Entradas/src/index.php?ref={$entrada->__get('codigo')}\" class=\"action-btn pdf-btn\" target=\"_blank\">
                                <span class=\"btn-icon\">📄</span>
                                Ver Entrada en PDF
                            </a>
                        </div>
                        
                        <div class=\"additional-info\">
                            <div class=\"info-box\">
                                <h4>📱 Importante</h4>
                                <p>Guarda tu código de entrada que aparece en el PDF</p>
                                <p>Recuerda que la contraseña para abrirlo es 2025@Thos</p>
                            </div>
                            
                            <div class=\"info-box\">
                                <h4>⏰ Información del evento</h4>
                                <p>Te recomendamos llegar con 30 minutos de antelación</p>
                                <p>Recuerda traer un documento de identidad</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            </body>
        </html>";
    }
}