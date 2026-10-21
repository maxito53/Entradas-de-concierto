<?php

use Entradas\Entity\Entrada;
use Entradas\Entity\Zona;
use Entradas\Entity\Asiento;
use Doctrine\ORM\EntityManager;

class EntradaModel {
    
    private EntityManager $entityManager;
    
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    /**
     * busca una entrada por su código de referencia
     *
     * @param string $codigo código de referencia de la entrada
     * @return Entrada|null la entrada o null si no existe
     */
    public function buscarPorCodigo(string $codigo): ?Entrada {
        return $this->entityManager->getRepository(Entrada::class)->find($codigo);
    }
    
    /**
     * obtiene todos los datos necesarios para generar la entrada en formato PDF
     *
     * @param string $codigo código de referencia de la entrada
     * @return array|null datos completos de la entrada de la db o null si no existe
     */
    public function obtenerDatosEntradaCompletos(string $codigo): ?array {
        
        $entrada = $this->buscarPorCodigo($codigo);
        
        if (!$entrada) {
            return null;
        }
        
        $sesion = $entrada->getSesion();
        $concierto = $sesion->getConcierto();
        $artista = $concierto->getArtista();
        $lugar = $sesion->getLugar();
        $zona = $entrada->getZona();
        $asiento = $entrada->getAsiento(); // Nuevo: obtener el asiento
        $precio = $entrada->getPrecio();
        $pago = $entrada->getPago();
        $cliente = $pago->getCliente();
        
        return [
            'entrada' => $entrada,
            'sesion' => $sesion,
            'concierto' => $concierto,
            'artista' => $artista,
            'lugar' => $lugar,
            'zona' => $zona,
            'asiento' => $asiento, // Nuevo: incluir el asiento en los datos
            'precio' => $precio,
            'pago' => $pago,
            'cliente' => $cliente
        ];
    }
    
    /**
     * inserta entradas a la base de datos
     *
     * @param array $pagosObj array de pagos
     * @param array $sesionesObj array de sesiones
     * @param array $zonasObj array de zonas
     * @param array $preciosObj array de precios
     * @param array $asientosObj array de asientos (nuevo parámetro)
     * @return void
     */
    public function insertarEntradas(array $pagosObj, array $sesionesObj, array $zonasObj, array $preciosObj, array $asientosObj = []): void {
        
        $entradas = [
            // Entradas para conciertos de Tiago PZK
            ['codigo' => 'ENT-2025-00001', 'pago_id' => 1, 'sesion_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '15', 'precio_id' => 4, 'estado' => 'VALIDA'],
            ['codigo' => 'ENT-2025-00002', 'pago_id' => 1, 'sesion_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '16', 'precio_id' => 4, 'estado' => 'VALIDA'],
            
            // Entradas para conciertos de Emilia
            ['codigo' => 'ENT-2025-00003', 'pago_id' => 2, 'sesion_id' => 3, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '10', 'precio_id' => 10, 'estado' => 'VALIDA'],
            
            // Entradas para conciertos de Aitana
            ['codigo' => 'ENT-2025-00004', 'pago_id' => 3, 'sesion_id' => 5, 'zona_nombre' => 'Grada', 'fila' => 'C', 'numero' => '5', 'precio_id' => 18, 'estado' => 'VALIDA'],
            ['codigo' => 'ENT-2025-00005', 'pago_id' => 3, 'sesion_id' => 5, 'zona_nombre' => 'Grada', 'fila' => 'C', 'numero' => '6', 'precio_id' => 18, 'estado' => 'VALIDA'],
            
            // Entradas para conciertos de Chanel
            ['codigo' => 'ENT-2025-00006', 'pago_id' => 4, 'sesion_id' => 7, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '8', 'precio_id' => 28, 'estado' => 'VALIDA'],
            
            // Entradas para conciertos de Eric Saade
            ['codigo' => 'ENT-2025-00007', 'pago_id' => 5, 'sesion_id' => 9, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '20', 'precio_id' => 34, 'estado' => 'VALIDA'],
            ['codigo' => 'ENT-2025-00008', 'pago_id' => 5, 'sesion_id' => 9, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '21', 'precio_id' => 34, 'estado' => 'VALIDA'],
            
            // Entradas para conciertos de ABBA
            ['codigo' => 'ENT-2025-00009', 'pago_id' => 6, 'sesion_id' => 11, 'zona_nombre' => 'Diamond', 'fila' => 'A', 'numero' => '5', 'precio_id' => 47, 'estado' => 'VALIDA'],
            
            // Entradas para conciertos de Bruno Mars
            ['codigo' => 'ENT-2025-00010', 'pago_id' => 7, 'sesion_id' => 13, 'zona_nombre' => 'Grada', 'fila' => 'D', 'numero' => '12', 'precio_id' => 53, 'estado' => 'VALIDA'],
            ['codigo' => 'ENT-2025-00011', 'pago_id' => 7, 'sesion_id' => 13, 'zona_nombre' => 'Grada', 'fila' => 'D', 'numero' => '13', 'precio_id' => 53, 'estado' => 'VALIDA'],
            
            // Entradas para conciertos de Lady Gaga
            ['codigo' => 'ENT-2025-00012', 'pago_id' => 8, 'sesion_id' => 15, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '1', 'precio_id' => 64, 'estado' => 'VALIDA'],
            
            // Entradas para conciertos de Abraham Mateo
            ['codigo' => 'ENT-2025-00013', 'pago_id' => 9, 'sesion_id' => 17, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '15', 'precio_id' => 72, 'estado' => 'VALIDA'],
            ['codigo' => 'ENT-2025-00014', 'pago_id' => 9, 'sesion_id' => 17, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '16', 'precio_id' => 72, 'estado' => 'VALIDA'],
            
            // Entradas para conciertos de La Oreja de Van Gogh
            ['codigo' => 'ENT-2025-00015', 'pago_id' => 10, 'sesion_id' => 19, 'zona_nombre' => 'Pista', 'fila' => null, 'numero' => null, 'precio_id' => 79, 'estado' => 'VALIDA'],
            ['codigo' => 'ENT-2025-00016', 'pago_id' => 10, 'sesion_id' => 19, 'zona_nombre' => 'Pista', 'fila' => null, 'numero' => null, 'precio_id' => 79, 'estado' => 'VALIDA'],
            
            // Entradas para conciertos de The Weeknd
            ['codigo' => 'ENT-2025-00017', 'pago_id' => 1, 'sesion_id' => 21, 'zona_nombre' => 'Diamond', 'fila' => 'A', 'numero' => '10', 'precio_id' => 85, 'estado' => 'VALIDA'],
            
            // Entradas para conciertos de Rosalía
            ['codigo' => 'ENT-2025-00018', 'pago_id' => 2, 'sesion_id' => 23, 'zona_nombre' => 'Golden', 'fila' => 'B', 'numero' => '5', 'precio_id' => 94, 'estado' => 'VALIDA'],
            
            // Entradas para conciertos de Metallica
            ['codigo' => 'ENT-2025-00019', 'pago_id' => 3, 'sesion_id' => 25, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '20', 'precio_id' => 102, 'estado' => 'VALIDA'],
            ['codigo' => 'ENT-2025-00020', 'pago_id' => 3, 'sesion_id' => 25, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '21', 'precio_id' => 102, 'estado' => 'VALIDA']
        ];
        
        foreach ($entradas as $entradaData) {
            if (!$this->entityManager->getRepository(Entrada::class)->findOneBy(['codigo' => $entradaData['codigo']])) {
                $pago = $pagosObj[$entradaData['pago_id']] ?? null;
                $sesion = $sesionesObj[$entradaData['sesion_id']] ?? null;
                $zona = $zonasObj[$entradaData['zona_nombre']] ?? $this->entityManager->getRepository(Zona::class)->findOneBy(['nombre' => $entradaData['zona_nombre']]);
                $precio = $preciosObj[$entradaData['precio_id']] ?? null;
                
                // Buscar el asiento correspondiente
                $asiento = null;
                if ($entradaData['fila'] !== null && $entradaData['numero'] !== null) {
                    // Primero intentamos encontrarlo en el array de asientos pasado
                    foreach ($asientosObj as $asientoObj) {
                        if ($asientoObj->getZona()->getNombre() === $entradaData['zona_nombre'] &&
                            $asientoObj->getFila() === $entradaData['fila'] &&
                            $asientoObj->getNumero() === $entradaData['numero'] &&
                            $asientoObj->getEstado() === 'DISPONIBLE') {
                                $asiento = $asientoObj;
                                break;
                            }
                    }
                    
                    // Si no lo encontramos, lo buscamos en la base de datos
                    if ($asiento === null) {
                        $lugar = $sesion->getLugar();
                        $asiento = $this->entityManager->getRepository(Asiento::class)->findOneBy([
                            'lugar' => $lugar,
                            'zona' => $zona,
                            'fila' => $entradaData['fila'],
                            'numero' => $entradaData['numero'],
                            'estado' => 'DISPONIBLE'
                        ]);
                    }
                    
                    // Si encontramos el asiento, actualizamos su estado
                    if ($asiento !== null) {
                        $asiento->setEstado('NO DISPONIBLE');
                        $this->entityManager->persist($asiento);
                    }
                }
                
                if ($pago && $sesion && $zona && $precio) {
                    $entrada = new Entrada();
                    $entrada->setCodigo($entradaData['codigo']);
                    $entrada->setPago($pago);
                    $entrada->setSesion($sesion);
                    $entrada->setZona($zona);
                    
                    // Asignar el asiento si existe
                    if ($asiento !== null) {
                        $entrada->setAsiento($asiento);
                    } else {
                        // Si no, mantenemos los campos de fila y asiento por compatibilidad
                        $entrada->setFila($entradaData['fila']);
                        $entrada->setAsientoNumero($entradaData['numero']);
                    }
                    
                    $entrada->setPrecio($precio);
                    $entrada->setEstado($entradaData['estado']);
                    $this->entityManager->persist($entrada);
                }
            }
        }
    }
}