<?php

use Mpdf\Mpdf;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

class EntradaController {
    
    private EntradaModel $entradaModel;

    public function __construct(EntradaModel $entradaModel) {
        $this->entradaModel = $entradaModel;
    }

    /**
     * genera y muestra un PDF con la entrada correspondiente al código de referencia
     * 
     * @param string $ref Código de referencia de la entrada
     * @return void
     */
    public function generarPDF(string $ref): void {
        
        $datosEntrada = $this->entradaModel->obtenerDatosEntradaCompletos($ref);
        
        if (!$datosEntrada) {
            //http_response_code(404);
            // echo "La entrada con referencia '$ref' no existe.";
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10
            ]);
            $mpdf->Output('Entrada-404' . '.pdf', 'I');
            exit;
        }
        
        // variables de la entrada
        $entrada = $datosEntrada['entrada'];
        $sesion = $datosEntrada['sesion'];
        $concierto = $datosEntrada['concierto'];
        $artista = $datosEntrada['artista'];
        $lugar = $datosEntrada['lugar'];
        $zona = $datosEntrada['zona'];
        $precio = $datosEntrada['precio'];
        $cliente = $datosEntrada['cliente'];
        
        // entrada válida
        if ($entrada->getEstado() !== 'VALIDA') {
            http_response_code(400);
            echo "Esta entrada no es válida. Estado actual: " . $entrada->getEstado();
            return;
        }
        
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10
        ]);
        
        // protección del documento
        $mpdf->SetProtection(['print', 'copy'], '2025@Thos', '2025@Thos');
        
        // marca de agua
        $mpdf->SetWatermarkText('Ticketmaxter');
        $mpdf->showWatermarkText = true;
        $mpdf->watermarkTextAlpha = 0.1;
        
        // metadatos del pdf
        $mpdf->SetTitle('Entrada: ' . $concierto->getNombre());
        $mpdf->SetAuthor('Ticketmaxter');
        $mpdf->SetCreator('Ticketmaxter');
        
        // código QR en base 64
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $qrCode = new QrCode($url);
        $qrOutput = new Output\Png();
        $qrData = base64_encode($qrOutput->output($qrCode, 100));
        
        $html = $this->generarHTMLEntrada($datosEntrada, $qrData);
        $mpdf->WriteHTML($html);
        
        // generar PDF y mostrar
        $mpdf->Output('Entrada-' . $entrada->getCodigo() . '.pdf', 'I');
        exit;
    }
    
    /**
     * genera el HTML para la entrada en PDF, es la plantilla (template) para la entrada
     * el HTML tiene un formato raro ya que el PDF este no aceptaba propiedades CSS como flex y tuve que jugar con tablas para darle un formato aceptable
     * 
     * @param array $datos datos de la entrada
     * @param string $qrData código QR en base64
     * @return string HTML formateado
     */
    private function generarHTMLEntrada(array $datos, string $qrData): string {
        
        $entrada = $datos['entrada'];
        $sesion = $datos['sesion'];
        $concierto = $datos['concierto'];
        $artista = $datos['artista'];
        $lugar = $datos['lugar'];
        $zona = $datos['zona'];
        $precio = $datos['precio'];
        $cliente = $datos['cliente'];
        
        $html = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Entrada: ' . htmlspecialchars($concierto->getNombre()) . '</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        color: #333;
                        font-size: 14px;
                        margin: 0;
                        padding: 0;
                        background-color: #f9f9f9;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    .ticket {
                        border: 2px solid #0067b3;
                        width: 750px;
                        margin: 20px auto;
                        padding: 0;
                        background-color: #fff;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    }
                    .header {
                        background-color: #0067b3;
                        color: #fff;
                    }
                    .header td {
                        padding: 12px 15px;
                    }
                    .event-image {
                        width: 100px;
                        height: 125px;
                        object-fit: cover;
                        border-radius: 5px;
                    }
                    .event-title {
                        font-size: 28px;
                        font-weight: bold;
                        margin-bottom: 5px;
                        color: white;
                    }
                    .artist {
                        font-size: 18px;
                        color: white;
                        margin-bottom: 5px;
                    }
                    .date-time {
                        font-size: 15px;
                        font-weight: bold;
                        color: white;
                    }
                    .content-section {
                        padding: 15px;
                    }
                    .section-title {
                        font-size: 14px;
                        font-weight: bold;
                        color: #0067b3;
                        margin-bottom: 12px;
                        padding-bottom: 5px;
                        height: 15px;
                    }
                    .info-label {
                        font-size: 12px;
                        color: #666;
                        margin-bottom: 4px;
                    }
                    .info-value {
                        font-weight: bold;
                        font-size: 15px;
                        margin-bottom: 15px;
                    }
                    .code-container {
                        background-color: #f5f5f5;
                        padding: 15px;
                        text-align: center;
                        border-left: 1px solid #e0e0e0;
                    }
                    .code-image {
                        width: 140px;
                        height: auto;
                        margin-bottom: 8px;
                    }
                    .barcode-container {
                        background-color: #f5f5f5;
                        padding: 20px 15px;
                        text-align: center;
                        border-top: 1px solid #e0e0e0;
                    }
                    .barcode-large {
                        width: 350px;
                        height: auto;
                        margin-bottom: 8px;
                    }
                    .ref {
                        font-family: monospace;
                        font-size: 13px;
                        color: #333;
                        background-color: #e9e9e9;
                        padding: 5px 10px;
                        display: inline-block;
                        border-radius: 2px;
                    }
                    .footer {
                        text-align: center;
                        font-size: 12px;
                        color: #666;
                        border-top: 1px solid #e0e0e0;
                        padding: 12px 15px;
                        background-color: #f9f9f9;
                    }
                </style>
            </head>
            <body>
                <table class="ticket" cellpadding="0" cellspacing="0">
                    <tr class="header">
                        <td colspan="3">
                            <table width="100%">
                                <tr>
                                    <td width="110"><img src="../../Microservicios/media/' . htmlspecialchars($concierto->getImagen()) . '" class="event-image"></td>
                                    <td>
                                        <div class="event-title">' . htmlspecialchars($concierto->getNombre()) . '</div>
                                        <div class="artist">' . htmlspecialchars($artista->getNombre()) . '</div>
                                        <div class="date-time">' . $sesion->getFecha()->format('d/m/Y') . ' - ' . $sesion->getHora()->format('H:i') . '</div>
                                    </td>
                                    <td align="right"><img src="./media/logo.svg" width="220" height="28"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <!-- Info principal -->
                        <td valign="top" colspan="2" class="content-section" width="75%">
                            <div class="section-title">DETALLES DEL EVENTO</div>
                            <table width="100%" cellspacing="5">
                                <tr>
                                    <td width="50%">
                                        <div class="info-label">Lugar</div>
                                        <div class="info-value">' . htmlspecialchars($lugar->getNombre()) . '</div>
                                    </td>
                                    <td width="50%">
                                        <div class="info-label">Dirección</div>
                                        <div class="info-value">' . htmlspecialchars($lugar->getDireccion()) . ', ' . htmlspecialchars($lugar->getCiudad()) . '</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="info-label">Zona</div>
                                        <div class="info-value">' . htmlspecialchars($zona->getNombre()) . '</div>
                                    </td>';
                
                if ($entrada->getFila() && $entrada->getAsiento_numero()) {
                    $html .= '
                                    <td>
                                        <div class="info-label">Ubicación</div>
                                        <div class="info-value">Fila ' . htmlspecialchars($entrada->getFila()) . ', Asiento ' . htmlspecialchars($entrada->getAsiento_numero()) . '</div>
                                    </td>';
                } else {
                    $html .= '<td></td>';
                }
                
                $html .= '
                                </tr>
                                <tr>
                                    <td>
                                        <div class="info-label">Precio</div>
                                        <div class="info-value">' . number_format($precio->getImporte(), 2, ',', '.') . ' €</div>
                                    </td>
                                    <td>
                                        <div class="info-label">Titular</div>
                                        <div class="info-value">' . htmlspecialchars($cliente->getNombre() . ' ' . $cliente->getApellidos()) . '</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <!-- QR en tercera columna -->
                        <td valign="top" class="code-container" width="25%">
                            <div class="info-label" style="text-align: center; margin-bottom: 10px;">ESCANEA PARA VER MÁS</div>
                            <img src="data:image/png;base64,' . $qrData . '" class="code-image" alt="Código QR">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="barcode-container">
                            <img src="https://barcode.tec-it.com/barcode.ashx?data=' . urlencode($entrada->getCodigo()) . '&code=Code128&dpi=96" alt="Código de barras" class="barcode-large"><br>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="footer">
                            <p>Esta entrada es personal e intransferible. Conserve este documento hasta el final del evento.</p>
                            <p><strong>Ticketmaxter</strong> - Todos los derechos reservados © ' . date('Y') . '</p>
                        </td>
                    </tr>
                </table>
            </body>
            </html>';
        
        return $html;
    }
}