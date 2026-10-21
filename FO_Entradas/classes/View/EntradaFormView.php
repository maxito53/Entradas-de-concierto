<?php

class EntradaFormView extends View {
    
    public function show($params = null) {
        
        // extraer datos necesarios de los parámetros
        $sesion = isset($params['sesion']) ? $params['sesion'] : null;
        $zonas = isset($params['zonas']) ? $params['zonas'] : [];
        $asientos = isset($params['asientos']) ? $params['asientos'] : [];
        $precios = isset($params['precios']) ? $params['precios'] : [];
        $zonaSeleccionada = isset($params['zona_seleccionada']) ? $params['zona_seleccionada'] : null;
        $asientoSeleccionado = isset($params['asiento_seleccionado']) ? $params['asiento_seleccionado'] : null;
        $precioSeleccionado = isset($params['precio_seleccionado']) ? $params['precio_seleccionado'] : null;
        $datosCliente = isset($params['datos_cliente']) ? $params['datos_cliente'] : [];
        $datosPago = isset($params['datos_pago']) ? $params['datos_pago'] : [];
        $errorMessage = isset($params['error']) ? $params['error'] : '';
        $successMessage = isset($params['success']) ? $params['success'] : '';
        
        echo "<!DOCTYPE html><html lang=\"es\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>Comprar Entrada - Ticketmaxter</title>
                <link rel=\"stylesheet\" href=\"../css/entrada-form.css\" type=\"text/css\">
            </head>";
        
        echo "<body>
            <header>
                <div class=\"container\">
                    <div class=\"header-content\">
                        <div class=\"logo\">
                            <img src=\"media/logo.svg\" alt=\"Tiketmaxter\" style=\"height: 1em; width: auto;\" />
                        </div>
                        <div class=\"header-actions\">
                            <a href=\"index.php?Home/show\" class=\"back-btn\">Volver al inicio</a>
                            <form method=\"post\" action=\"index.php?Login/logout\" style=\"display: inline;\">
                                <button type=\"submit\" class=\"logout-btn\">Cerrar sesión</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>";
        
        // sección con información del concierto
        if ($sesion) {
            echo "<section class=\"concert-hero\">
                    <div class=\"container\">
                        <div class=\"concert-header\">
                            <img src=\"{$sesion->__get('concierto')->__get('imagen')}\" alt=\"Concierto\" class=\"concert-image\">
                            <div class=\"concert-details\">
                                <h1>{$sesion->__get('concierto')->__get('nombre')}</h1>
                                <div class=\"artist-info\">
                                    <span class=\"artist-name\">{$sesion->__get('concierto')->__get('artista')->__get('nombre')}</span>
                                    <span class=\"genre\">{$sesion->__get('concierto')->__get('artista')->__get('genero')}</span>
                                </div>
                                <div class=\"event-info\">
                                    <div class=\"info-item\">
                                        <span class=\"icon\">📅</span>
                                        <span>{$this->formatearFechaCompleta($sesion->__get('fecha'))}</span>
                                    </div>
                                    <div class=\"info-item\">
                                        <span class=\"icon\">🕐</span>
                                        <span>{$this->formatearHoraCorta($sesion->__get('hora'))}h</span>
                                    </div>
                                    <div class=\"info-item\">
                                        <span class=\"icon\">📍</span>
                                        <span>{$sesion->__get('lugar')->__get('nombre')}, {$sesion->__get('lugar')->__get('ciudad')}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>";
        }
        
        echo "<section class=\"form-section\">
                <div class=\"container\">
                    <div class=\"form-container\">";
        
        // mostrar mensajes de error o éxito
        if (!empty($errorMessage)) {
            echo "<div class=\"error-message\">" . htmlspecialchars($errorMessage) . "</div>";
        }
        
        if (!empty($successMessage)) {
            echo "<div class=\"success-message\">" . htmlspecialchars($successMessage) . "</div>";
        }
        
        echo "<div class=\"form-header\">
                <h2>🎫 Comprar Entrada</h2>
                <p>Completa los datos paso a paso para finalizar tu compra</p>
            </div>";
        
        // PASO 1: selección de zona
        $step1Class = 'form-step active';
        if (!$zonaSeleccionada) {
            echo "<div class=\"$step1Class\">
                    <h3 class=\"step-title\">1. Selecciona tu zona</h3>
                    <form method=\"POST\" action=\"index.php?EntradaForm/seleccionarZona\">
                        <input type=\"hidden\" name=\"sesion_id\" value=\"" . ($sesion ? $sesion->__get('id') : '') . "\">";
            
            foreach ($datosCliente as $key => $value) {
                echo "<input type=\"hidden\" name=\"cliente_$key\" value=\"" . htmlspecialchars($value) . "\">";
            }
            
            foreach ($datosPago as $key => $value) {
                echo "<input type=\"hidden\" name=\"pago_$key\" value=\"" . htmlspecialchars($value) . "\">";
            }
            
            echo "<div class=\"form-group\">
                            <label for=\"zona_id\">Zona del evento *</label>
                            <select id=\"zona_id\" name=\"zona_id\" required>
                                <option value=\"\">Selecciona una zona</option>";
            
            if (is_array($zonas) && !empty($zonas)) {
                foreach ($zonas as $zona) {
                    $precioZona = '';
                    if (is_array($precios)) {
                        foreach ($precios as $precio) {
                            if ($precio->__get('zona')['id'] == $zona->__get('id')) {
                                $precioZona = ' - ' . $precio->__get('importe') . '€';
                                break;
                            }
                        }
                    }
                    echo "<option value=\"{$zona->__get('id')}\">
                            {$zona->__get('nombre')} - {$zona->__get('descripcion')}{$precioZona}
                          </option>";
                }
            }
            
            echo "        </select>
                        </div>
                        <div class=\"form-actions-step\">
                            <button type=\"submit\" class=\"next-btn\">Continuar</button>
                        </div>
                    </form>
                </div>";
        } else {
            echo "<div class=\"form-step\">
                    <h3 class=\"step-title\">✓ 1. Zona seleccionada</h3>
                    <div class=\"price-display\">
                        <span class=\"price-label\">Zona: {$zonaSeleccionada->__get('nombre')}</span>";
            
            if ($precioSeleccionado) {
                echo "<span class=\"price-value\">{$precioSeleccionado->__get('importe')}€</span>";
            } else {
                echo "<span class=\"price-value\">Precio no disponible</span>";
            }
            
            echo "    </div>
                </div>";
        }
        
        // PASO 2: selección de asiento (exceptio y es Pista)
        if ($zonaSeleccionada) {
            $asientosDisponibles = [];
            if (is_array($asientos) && !empty($asientos)) {
                foreach ($asientos as $asiento) {
                    if ($asiento->__get('zona')['id'] == $zonaSeleccionada->__get('id') && $asiento->__get('estado') == 'DISPONIBLE') {
                        $asientosDisponibles[] = $asiento;
                    }
                }
            }
            
            $tieneAsientos = !empty($asientosDisponibles);
            
            if (!$asientoSeleccionado && $tieneAsientos) {
                echo "<div class=\"form-step active\">
                        <h3 class=\"step-title\">2. Selecciona tu asiento</h3>
                        <form method=\"POST\" action=\"index.php?EntradaForm/seleccionarAsiento\">
                            <input type=\"hidden\" name=\"sesion_id\" value=\"" . ($sesion ? $sesion->__get('id') : '') . "\">
                            <input type=\"hidden\" name=\"zona_id\" value=\"{$zonaSeleccionada->__get('id')}\">
                            <input type=\"hidden\" name=\"precio_id\" value=\"{$precioSeleccionado->__get('id')}\">";
                
                foreach ($datosCliente as $key => $value) {
                    echo "<input type=\"hidden\" name=\"cliente_$key\" value=\"" . htmlspecialchars($value) . "\">";
                }
                
                foreach ($datosPago as $key => $value) {
                    echo "<input type=\"hidden\" name=\"pago_$key\" value=\"" . htmlspecialchars($value) . "\">";
                }
                
                echo "<div class=\"form-group\">
                                <label for=\"asiento_id\">Asientos disponibles en {$zonaSeleccionada->__get('nombre')} *</label>
                                <select id=\"asiento_id\" name=\"asiento_id\" required>
                                    <option value=\"\">Selecciona un asiento</option>";
                
                foreach ($asientosDisponibles as $asiento) {
                    echo "<option value=\"{$asiento->__get('id')}\">Fila {$asiento->__get('fila')} - Asiento {$asiento->__get('numero')}</option>";
                }
                
                echo "        </select>
                            </div>
                            <div class=\"form-actions-step\">
                                <button type=\"submit\" class=\"next-btn\">Continuar</button>
                            </div>
                        </form>
                    </div>";
            } elseif (!$tieneAsientos && empty($datosCliente)) {
                echo "<div class=\"form-step\">
                        <h3 class=\"step-title\">✓ 2. Asiento</h3>
                        <div class=\"info-message\">
                            <p><strong>Zona sin asientos numerados</strong><br>
                            Esta zona ({$zonaSeleccionada->__get('nombre')}) no tiene asientos específicos asignados.</p>
                        </div>
                    </div>";
            } elseif ($asientoSeleccionado) {
                echo "<div class=\"form-step\">
                        <h3 class=\"step-title\">✓ 2. Asiento seleccionado</h3>
                        <p><strong>Fila {$asientoSeleccionado->__get('fila')} - Asiento {$asientoSeleccionado->__get('numero')}</strong></p>
                    </div>";
            }
        } else {
            echo "<div class=\"form-step inactive\">
                    <h3 class=\"step-title\">2. Selecciona tu asiento</h3>
                    <p class=\"step-description\">Primero selecciona una zona</p>
                </div>";
        }
        
        // PASO 3: fatos del cliente
        $puedeIngresarDatosCliente = $zonaSeleccionada && (
            $asientoSeleccionado || 
            (!$this->zonaRequiereAsientos($zonaSeleccionada, $asientos)) 
            );
        
        if ($puedeIngresarDatosCliente && empty($datosCliente)) {
            echo "<div class=\"form-step active\">
                    <h3 class=\"step-title\">3. Datos del cliente</h3>
                    <form method=\"POST\" action=\"index.php?EntradaForm/guardarCliente\">
                        <input type=\"hidden\" name=\"sesion_id\" value=\"" . ($sesion ? $sesion->__get('id') : '') . "\">
                        <input type=\"hidden\" name=\"zona_id\" value=\"{$zonaSeleccionada->__get('id')}\">
                        <input type=\"hidden\" name=\"precio_id\" value=\"{$precioSeleccionado->__get('id')}\">";
            
            if ($asientoSeleccionado) {
                echo "<input type=\"hidden\" name=\"asiento_id\" value=\"{$asientoSeleccionado->__get('id')}\">";
            }
            
            foreach ($datosPago as $key => $value) {
                echo "<input type=\"hidden\" name=\"pago_$key\" value=\"" . htmlspecialchars($value) . "\">";
            }
            
            echo "<div class=\"form-group\">
                            <label for=\"cliente_dni\">DNI/NIE *</label>
                            <input type=\"text\" id=\"cliente_dni\" name=\"cliente_dni\" required
                                   placeholder=\"12345678Z\" pattern=\"[0-9]{8}[A-Za-z]|[XYZ][0-9]{7}[A-Za-z]\"
                                   title=\"Formato: 12345678Z o X1234567Z\">
                        </div>
                        <div class=\"form-group\">
                            <label for=\"cliente_nombre\">Nombre *</label>
                            <input type=\"text\" id=\"cliente_nombre\" name=\"cliente_nombre\" required
                                   placeholder=\"Introduce tu nombre\">
                        </div>
                        <div class=\"form-group\">
                            <label for=\"cliente_apellidos\">Apellidos *</label>
                            <input type=\"text\" id=\"cliente_apellidos\" name=\"cliente_apellidos\" required
                                   placeholder=\"Introduce tus apellidos\">
                        </div>
                        <div class=\"form-group\">
                            <label for=\"cliente_email\">Email *</label>
                            <input type=\"email\" id=\"cliente_email\" name=\"cliente_email\" required
                                   placeholder=\"tu.email@ejemplo.com\">
                        </div>
                        <div class=\"form-group\">
                            <label for=\"cliente_telefono\">Teléfono</label>
                            <input type=\"tel\" id=\"cliente_telefono\" name=\"cliente_telefono\"
                                   placeholder=\"+34 600 123 456\">
                        </div>
                        <div class=\"form-actions-step\">
                            <button type=\"submit\" class=\"next-btn\">Continuar</button>
                        </div>
                    </form>
                </div>";
        } elseif (!empty($datosCliente)) {
            echo "<div class=\"form-step\">
                    <h3 class=\"step-title\">✓ 3. Datos del cliente</h3>
                    <p><strong>" . htmlspecialchars($datosCliente['nombre'] . ' ' . $datosCliente['apellidos']) . "</strong><br>
                       " . htmlspecialchars($datosCliente['email']) . "</p>
                </div>";
        } else {
            echo "<div class=\"form-step inactive\">
                    <h3 class=\"step-title\">3. Datos del cliente</h3>
                    <p class=\"step-description\">Completa los pasos anteriores</p>
                </div>";
        }
        
        // PASO 4: método de pago
        if ($puedeIngresarDatosCliente && !empty($datosCliente) && empty($datosPago)) {
            echo "<div class=\"form-step active\">
                    <h3 class=\"step-title\">4. Método de pago</h3>
                    <form method=\"POST\" action=\"index.php?EntradaForm/guardarPago\">
                        <input type=\"hidden\" name=\"sesion_id\" value=\"" . ($sesion ? $sesion->__get('id') : '') . "\">
                        <input type=\"hidden\" name=\"zona_id\" value=\"{$zonaSeleccionada->__get('id')}\">
                        <input type=\"hidden\" name=\"precio_id\" value=\"{$precioSeleccionado->__get('id')}\">";
            
            if ($asientoSeleccionado) {
                echo "<input type=\"hidden\" name=\"asiento_id\" value=\"{$asientoSeleccionado->__get('id')}\">";
            }
            
            foreach ($datosCliente as $key => $value) {
                echo "<input type=\"hidden\" name=\"cliente_$key\" value=\"" . htmlspecialchars($value) . "\">";
            }
            
            echo "<div class=\"form-group\">
                            <label for=\"metodo_pago\">Método de pago *</label>
                            <select id=\"metodo_pago\" name=\"metodo_pago\" required>
                                <option value=\"\">Selecciona método de pago</option>
                                <option value=\"TARJETA_CREDITO\">💳 Tarjeta de Crédito</option>
                                <option value=\"TARJETA_DEBITO\">💳 Tarjeta de Débito</option>
                                <option value=\"PAYPAL\">🅿️ PayPal</option>
                                <option value=\"TRANSFERENCIA\">🏦 Transferencia Bancaria</option>
                            </select>
                        </div>
                        <div class=\"form-group\">
                            <label for=\"referencia_pago\">Referencia de pago (opcional)</label>
                            <input type=\"text\" id=\"referencia_pago\" name=\"referencia_pago\"
                                   placeholder=\"Introduce referencia si la tienes\">
                        </div>
                        <div class=\"form-actions-step\">
                            <button type=\"submit\" class=\"next-btn\">Continuar</button>
                        </div>
                    </form>
                </div>";
        } elseif (!empty($datosPago)) {
            echo "<div class=\"form-step\">
                    <h3 class=\"step-title\">✓ 4. Método de pago</h3>
                    <p><strong>" . htmlspecialchars($datosPago['metodo']) . "</strong></p>
                </div>";
        } else {
            echo "<div class=\"form-step inactive\">
                    <h3 class=\"step-title\">4. Método de pago</h3>
                    <p class=\"step-description\">Completa los pasos anteriores</p>
                </div>";
        }
        
        // PASO 5: resumen y finalización
        if ($puedeIngresarDatosCliente && !empty($datosCliente) && !empty($datosPago)) {
            echo "<div class=\"form-step active\">
                    <h3 class=\"step-title\">5. Resumen de compra</h3>
                    <div class=\"purchase-summary\">
                        <div class=\"summary-row\">
                            <span>Evento:</span>
                            <span>" . htmlspecialchars($sesion->__get('concierto')->__get('nombre')) . "</span>
                        </div>
                        <div class=\"summary-row\">
                            <span>Artista:</span>
                            <span>" . htmlspecialchars($sesion->__get('concierto')->__get('artista')->__get('nombre')) . "</span>
                        </div>
                        <div class=\"summary-row\">
                            <span>Fecha:</span>
                            <span>" . $this->formatearFechaCompleta($sesion->__get('fecha')) . "</span>
                        </div>
                        <div class=\"summary-row\">
                            <span>Hora:</span>
                            <span>" . $this->formatearHora($sesion->__get('hora')) . "</span>
                        </div>
                        <div class=\"summary-row\">
                            <span>Lugar:</span>
                            <span>" . htmlspecialchars($sesion->__get('lugar')->__get('nombre') . ', ' . $sesion->__get('lugar')->__get('ciudad')) . "</span>
                        </div>
                        <div class=\"summary-row\">
                            <span>Zona:</span>
                            <span>" . htmlspecialchars($zonaSeleccionada->__get('nombre')) . "</span>
                        </div>";
            
            if ($asientoSeleccionado) {
                echo "<div class=\"summary-row\">
                        <span>Asiento:</span>
                        <span>Fila {$asientoSeleccionado->__get('fila')} - Asiento {$asientoSeleccionado->__get('numero')}</span>
                      </div>";
            } else {
                echo "<div class=\"summary-row\">
                        <span>Entrada:</span>
                        <span>General (sin asiento asignado)</span>
                      </div>";
            }
            
            echo "        <div class=\"summary-row\">
                            <span>Cliente:</span>
                            <span>" . htmlspecialchars($datosCliente['nombre'] . ' ' . $datosCliente['apellidos']) . "</span>
                        </div>
                        <div class=\"summary-row\">
                            <span>Método de pago:</span>
                            <span>" . htmlspecialchars($datosPago['metodo']) . "</span>
                        </div>
                        <div class=\"summary-row total\">
                            <span>Total:</span>
                            <span>{$precioSeleccionado->__get('importe')}€</span>
                        </div>
                    </div>
                    
                    <form method=\"POST\" action=\"index.php?EntradaForm/procesar\">
                        <input type=\"hidden\" name=\"sesion_id\" value=\"{$sesion->__get('id')}\">
                        <input type=\"hidden\" name=\"zona_id\" value=\"{$zonaSeleccionada->__get('id')}\">
                        <input type=\"hidden\" name=\"precio_id\" value=\"{$precioSeleccionado->__get('id')}\">";
            
            if ($asientoSeleccionado) {
                echo "<input type=\"hidden\" name=\"asiento_id\" value=\"{$asientoSeleccionado->__get('id')}\">";
            }
            
            foreach ($datosCliente as $key => $value) {
                echo "<input type=\"hidden\" name=\"cliente_$key\" value=\"" . htmlspecialchars($value) . "\">";
            }
            
            foreach ($datosPago as $key => $value) {
                echo "<input type=\"hidden\" name=\"pago_$key\" value=\"" . htmlspecialchars($value) . "\">";
            }
            
            echo "<div class=\"form-actions\">
                            <a href=\"index.php?Home/show\" class=\"cancel-btn\">Cancelar</a>
                            <button type=\"submit\" class=\"submit-btn active\">
                                Finalizar Compra
                            </button>
                        </div>
                    </form>
                </div>";
        } else {
            echo "<div class=\"form-step inactive\">
                    <h3 class=\"step-title\">5. Resumen de compra</h3>
                    <p class=\"step-description\">Completa todos los pasos anteriores para ver el resumen</p>
                </div>";
        }
        
        echo "</div>
            </div>
        </section>
            
        </body>
        </html>";
    }
    
    /**
     * verifica si una Zona requiere asientos específicos
     */
    private function zonaRequiereAsientos($zona, $asientos) {
        if (!is_array($asientos) || empty($asientos)) {
            return false;
        }
        
        foreach ($asientos as $asiento) {
            if ($asiento->__get('zona')['id'] == $zona->__get('id')) {
                return true;
            }
        }
        
        return false;
    }
}