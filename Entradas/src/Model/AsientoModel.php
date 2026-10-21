<?php

use Entradas\Entity\Asiento;
use Entradas\Entity\Lugar;
use Entradas\Entity\Zona;
use Doctrine\ORM\EntityManager;

class AsientoModel {
    
    private EntityManager $entityManager;
    
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    /**
     * inserta asientos a la base de datos
     *
     * @param array $lugaresObj array de lugares
     * @param array $zonasObj array de zonas
     * @return array $asientosObj array de asientos
     */
    public function insertarAsientos(array $lugaresObj, array $zonasObj): array {
        
        // Preparar un array asociativo de zonas indexado por nombre para facilitar búsqueda
        $zonasPorNombre = [];
        foreach ($zonasObj as $zona) {
            $zonasPorNombre[$zona->getNombre()] = $zona;
        }
        
        $asientos = [
            // Asientos para el Wizink Center - Zona Golden
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '1', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '2', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '3', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '4', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '5', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '6', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '7', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '8', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '9', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '10', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '11', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '12', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '13', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '14', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '15', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '16', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '17', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '18', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '19', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Golden', 'fila' => 'A', 'numero' => '20', 'estado' => 'DISPONIBLE'],
            
            // Asientos para el Wizink Center - Zona VIP
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '1', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '2', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '3', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '4', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '5', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '6', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '7', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '8', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '9', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '10', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '11', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '12', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '13', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '14', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '15', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '16', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '17', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '18', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '19', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '20', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'VIP', 'fila' => 'B', 'numero' => '21', 'estado' => 'DISPONIBLE'],
            
            // Asientos para el Wizink Center - Zona Grada
            ['lugar_id' => 1, 'zona_nombre' => 'Grada', 'fila' => 'C', 'numero' => '1', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Grada', 'fila' => 'C', 'numero' => '2', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Grada', 'fila' => 'C', 'numero' => '3', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Grada', 'fila' => 'C', 'numero' => '4', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Grada', 'fila' => 'C', 'numero' => '5', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Grada', 'fila' => 'C', 'numero' => '6', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Grada', 'fila' => 'C', 'numero' => '7', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Grada', 'fila' => 'C', 'numero' => '8', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Grada', 'fila' => 'C', 'numero' => '9', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 1, 'zona_nombre' => 'Grada', 'fila' => 'C', 'numero' => '10', 'estado' => 'DISPONIBLE'],
            
            // Asientos para el Palau Sant Jordi - Zona Diamond
            ['lugar_id' => 2, 'zona_nombre' => 'Diamond', 'fila' => 'A', 'numero' => '1', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'Diamond', 'fila' => 'A', 'numero' => '2', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'Diamond', 'fila' => 'A', 'numero' => '3', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'Diamond', 'fila' => 'A', 'numero' => '4', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'Diamond', 'fila' => 'A', 'numero' => '5', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'Diamond', 'fila' => 'A', 'numero' => '6', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'Diamond', 'fila' => 'A', 'numero' => '7', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'Diamond', 'fila' => 'A', 'numero' => '8', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'Diamond', 'fila' => 'A', 'numero' => '9', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'Diamond', 'fila' => 'A', 'numero' => '10', 'estado' => 'DISPONIBLE'],
            
            // Asientos para el Palau Sant Jordi - Zona Golden
            ['lugar_id' => 2, 'zona_nombre' => 'Golden', 'fila' => 'B', 'numero' => '1', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'Golden', 'fila' => 'B', 'numero' => '2', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'Golden', 'fila' => 'B', 'numero' => '3', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'Golden', 'fila' => 'B', 'numero' => '4', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'Golden', 'fila' => 'B', 'numero' => '5', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'Golden', 'fila' => 'B', 'numero' => '6', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'Golden', 'fila' => 'B', 'numero' => '7', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'Golden', 'fila' => 'B', 'numero' => '8', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'Golden', 'fila' => 'B', 'numero' => '9', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'Golden', 'fila' => 'B', 'numero' => '10', 'estado' => 'DISPONIBLE'],
            
            // Asientos para el Palau Sant Jordi - Zona VIP
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '1', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '2', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '3', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '4', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '5', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '6', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '7', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '8', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '9', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '10', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '11', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '12', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '13', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '14', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '15', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '16', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '17', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '18', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '19', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '20', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 2, 'zona_nombre' => 'VIP', 'fila' => 'C', 'numero' => '21', 'estado' => 'DISPONIBLE'],
            
            // Asientos para el Estadio Santiago Bernabéu - Zona Grada
            ['lugar_id' => 3, 'zona_nombre' => 'Grada', 'fila' => 'D', 'numero' => '1', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Grada', 'fila' => 'D', 'numero' => '2', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Grada', 'fila' => 'D', 'numero' => '3', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Grada', 'fila' => 'D', 'numero' => '4', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Grada', 'fila' => 'D', 'numero' => '5', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Grada', 'fila' => 'D', 'numero' => '6', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Grada', 'fila' => 'D', 'numero' => '7', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Grada', 'fila' => 'D', 'numero' => '8', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Grada', 'fila' => 'D', 'numero' => '9', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Grada', 'fila' => 'D', 'numero' => '10', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Grada', 'fila' => 'D', 'numero' => '11', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Grada', 'fila' => 'D', 'numero' => '12', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Grada', 'fila' => 'D', 'numero' => '13', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Grada', 'fila' => 'D', 'numero' => '14', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Grada', 'fila' => 'D', 'numero' => '15', 'estado' => 'DISPONIBLE'],
            
            // Asientos para el Estadio Santiago Bernabéu - Zona Fan Pit
            ['lugar_id' => 3, 'zona_nombre' => 'Fan Pit', 'fila' => 'E', 'numero' => '1', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Fan Pit', 'fila' => 'E', 'numero' => '2', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Fan Pit', 'fila' => 'E', 'numero' => '3', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Fan Pit', 'fila' => 'E', 'numero' => '4', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Fan Pit', 'fila' => 'E', 'numero' => '5', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Fan Pit', 'fila' => 'E', 'numero' => '6', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Fan Pit', 'fila' => 'E', 'numero' => '7', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Fan Pit', 'fila' => 'E', 'numero' => '8', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Fan Pit', 'fila' => 'E', 'numero' => '9', 'estado' => 'DISPONIBLE'],
            ['lugar_id' => 3, 'zona_nombre' => 'Fan Pit', 'fila' => 'E', 'numero' => '10', 'estado' => 'DISPONIBLE']
        ];
        
        $asientosObj = [];
        $asientoCount = 1;
        
        foreach ($asientos as $asientoData) {
            // Obtener el lugar directamente por ID en lugar de índice
            $lugar = null;
            foreach ($lugaresObj as $lugarObj) {
                if ($lugarObj->getId() == $asientoData['lugar_id']) {
                    $lugar = $lugarObj;
                    break;
                }
            }
            
            // Buscar la zona primero en nuestro array indexado
            $zona = $zonasPorNombre[$asientoData['zona_nombre']] ?? null;
            
            // Si no se encuentra, intenta buscarla en la base de datos
            if (!$zona) {
                $zona = $this->entityManager->getRepository(Zona::class)->findOneBy(['nombre' => $asientoData['zona_nombre']]);
                // Si la encontramos, la guardamos en nuestro array indexado para futuras búsquedas
                if ($zona) {
                    $zonasPorNombre[$asientoData['zona_nombre']] = $zona;
                }
            }
            
            if ($lugar && $zona) {
                // Verificar si el asiento ya existe para evitar duplicados
                $existeAsiento = $this->entityManager->getRepository(Asiento::class)->findOneBy([
                    'lugar' => $lugar,
                    'zona' => $zona,
                    'fila' => $asientoData['fila'],
                    'numero' => $asientoData['numero']
                ]);
                
                if (!$existeAsiento) {
                    $asiento = new Asiento();
                    $asiento->setLugar($lugar);
                    $asiento->setZona($zona);
                    $asiento->setFila($asientoData['fila']);
                    $asiento->setNumero($asientoData['numero']);
                    $asiento->setEstado($asientoData['estado']);
                    $this->entityManager->persist($asiento);
                    $asientosObj[$asientoCount] = $asiento;
                    $asientoCount++;
                } else {
                    $asientosObj[$asientoCount] = $existeAsiento;
                    $asientoCount++;
                }
            }
        }
        
        return $asientosObj;
    }
}