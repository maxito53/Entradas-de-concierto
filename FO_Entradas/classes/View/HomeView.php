<?php 

class HomeView extends View {
    
    public function show($sesiones=null,  $precios=[]) {
        
        echo "<!DOCTYPE html><html lang=\"es\">
        <head>
        <meta charset=\"UTF-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <title>Ticketmaxter - Venta de Entradas</title>
        <link rel=\"stylesheet\" href=\"../css/home.css\" type=\"text/css\">
        </head>";
        echo "<body>
            <header>
                <div class=\"container\">
                    <div class=\"header-content\">
                        <div class=\logo\">
                            <img src=\"media/logo.svg\" alt=\"Tiketmaxter\" style=\"height: 2em; width: auto;\" />
                        </div>
                        <div class=\"search-bar\">
                            <form method=\"post\" action=\"index.php?Login/logout\">
                                <button type=\"submit\" class=\"search-btn\">Cerrar sesión</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
        
            <section class=\"hero\">
                <div class=\"container\">
                    <h1>Los mejores conciertos</h1>
                    <p>Encuentra las entradas para tus artistas favoritos</p>
                </div>
            </section>
        
            <section class=\"concerts-section\">
                <div class=\"container\">
                    <h2 class=\"section-title\">Próximos Conciertos</h2>
                    
                    <div class=\"concerts-grid\">";
        if (is_array($sesiones) && !empty($sesiones)) {
            foreach ($sesiones as $sesion) {
                // obtener el precio más bajo para esta sesión
                $sesionId = $sesion->__get('id');
                $precioTexto = "Precio no disponible";
                
                if (isset($precios[$sesionId]) && $precios[$sesionId] !== null) {
                    $precioTexto = "Desde " . number_format($precios[$sesionId], 0) . "€";
                }
                echo "<div class=\"concert-card\">
                        <img src=\"{$sesion->__get('concierto')->__get('imagen')}\" alt=\"Concierto\" class=\"concert-image\">
                        <div class=\"concert-info\">
                            <div class=\"artist-name\">{$sesion->__get('concierto')->__get('artista')->__get('nombre')}</div>
                            <div class=\"concert-date\">📅 {$this->formatearFechaAbreviada($sesion->__get('fecha'))} - {$this->formatearHoraCorta($sesion->__get('hora'))}h</div>
                            <div class=\"concert-venue\">📍 {$sesion->__get('lugar')->__get('nombre')}, {$sesion->__get('lugar')->__get('ciudad')}</div>
                            <div class=\"price-section\">
                                <div class=\"price\">{$precioTexto}</div>
                                <a href=\"index.php?EntradaForm/show/{$sesion->__get('id')}\" class=\"buy-btn\">Comprar</a>
                            </div>
                        </div>
                    </div>";
            }
        } else {
            echo "<p style=\"text-align: center;\">No hay sesiones disponibles.</p>";
        }
        
    echo            "</div>
                </div>
            </section></body>
        </html>";
        
    }
    
}