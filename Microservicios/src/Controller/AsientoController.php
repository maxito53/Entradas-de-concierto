<?php

use Entradas\Entity\Asiento;
use Entradas\Entity\Lugar;
use Entradas\Entity\Zona;
use Entradas\Entity\Entrada;
use Doctrine\ORM\EntityManager;

class AsientoController extends EntityController {

    /**
     * {@inheritdoc}
     */
    protected function setEntityClass(): void {
        $this->entityClass = Asiento::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntityFromData(array $data): object {
        $asiento = new Asiento();
        return $this->updateEntityFromData($asiento, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function updateEntityFromData(object $entity, array $data): object {
        // establecer o actualizar la relación con Lugar
        if (isset($data['lugar_id'])) {
            $lugar = $this->entityManager->getRepository(Lugar::class)->find($data['lugar_id']);
            if (!$lugar) {
                throw new Exception("Lugar con ID {$data['lugar_id']} no encontrado");
            }
            $entity->setLugar($lugar);
        }

        // establecer o actualizar la relación con Zona
        if (isset($data['zona_id'])) {
            $zona = $this->entityManager->getRepository(Zona::class)->find($data['zona_id']);
            if (!$zona) {
                throw new \Exception("Zona con ID {$data['zona_id']} no encontrada");
            }
            $entity->setZona($zona);
        }

        // establecer fila
        if (isset($data['fila'])) {
            // valida longitud de la fila
            if (strlen($data['fila']) > 5) {
                throw new Exception("La fila no puede tener más de 5 caracteres");
            }
            $entity->setFila($data['fila']);
        }

        // establecer numero
        if (isset($data['numero'])) {
            // validar la longitud del número
            if (strlen($data['numero']) > 5) {
                throw new Exception("El número no puede tener más de 5 caracteres");
            }
            $entity->setNumero($data['numero']);
        }

        // establecer estado
        if (isset($data['estado'])) {
            $estado = strtoupper($data['estado']);
            // valida que el estado sea uno de los permitidos
            if (!in_array($estado, ['DISPONIBLE', 'NO DISPONIBLE'])) {
                throw new Exception("Estado inválido. Debe ser 'DISPONIBLE' o 'NO DISPONIBLE'");
            }
            $entity->setEstado($estado);
        }

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    protected function serializeEntity(object $entity): array {
        return [
            'id' => $entity->getId(),
            'lugar' => [
                'id' => $entity->getLugar()->getId(),
                'nombre' => $entity->getLugar()->getNombre() ?? 'N/A' // Asumiendo que Lugar tiene un método getNombre()
            ],
            'zona' => [
                'id' => $entity->getZona()->getId(),
                'nombre' => $entity->getZona()->getNombre()
            ],
            'fila' => $entity->getFila(),
            'numero' => $entity->getNumero(),
            'estado' => $entity->getEstado()
            // No incluimos las entradas por defecto para evitar respuestas muy grandes
        ];
    }

    /**
     * buscar asientos por lugar
     *
     * @param int $lugarId id del lugar
     */
    public function getAsientosByLugar(int $lugarId): void {
        $asientos = $this->repository->findBy(['lugar' => $lugarId]);
        $data = [];
        
        foreach ($asientos as $asiento) {
            $data[] = $this->serializeEntity($asiento);
        }
        
        echo json_encode($data);
    }

    /**
     * buscar asientos por zona
     *
     * @param int $zonaId id de la zona
     */
    public function getAsientosByZona(int $zonaId): void {
        $asientos = $this->repository->findBy(['zona' => $zonaId]);
        $data = [];
        
        foreach ($asientos as $asiento) {
            $data[] = $this->serializeEntity($asiento);
        }
        
        echo json_encode($data);
    }

    /**
     * buscar asientos por zona y estado
     *
     * @param int $zonaId id de la zona
     * @param string $estado estado del asiento ('DISPONIBLE' o 'NO DISPONIBLE')
     */
    public function getAsientosByZonaAndEstado(int $zonaId, string $estado): void {
        $estado = strtoupper($estado);
        
        // valida que el esttado sea uno de los permitidos
        if (!in_array($estado, ['DISPONIBLE', 'NO DISPONIBLE'])) {
            http_response_code(400);
            echo json_encode(['error' => "Estado inválido. Debe ser 'DISPONIBLE' o 'NO DISPONIBLE'"]);
            return;
        }
        
        $asientos = $this->repository->findBy(['zona' => $zonaId, 'estado' => $estado]);
        $data = [];
        
        foreach ($asientos as $asiento) {
            $data[] = $this->serializeEntity($asiento);
        }
        
        echo json_encode($data);
    }

    /**
     * buscar asientos por fila
     *
     * @param int $lugarId id del lugar
     * @param string $fila fila de los asientos
     */
    public function getAsientosByFila(int $lugarId, string $fila): void {
        $asientos = $this->repository->findBy(['lugar' => $lugarId, 'fila' => $fila]);
        $data = [];
        
        foreach ($asientos as $asiento) {
            $data[] = $this->serializeEntity($asiento);
        }
        
        echo json_encode($data);
    }

    /**
     * cambiar el estado de un asiento
     *
     * @param int $id id del asiento
     * @param string $estado nuevo estado ('DISPONIBLE' o 'NO DISPONIBLE')
     */
    public function cambiarEstado(int $id, string $estado): void {
        $estado = strtoupper($estado);
        
        // Validar que el estado sea uno de los permitidos
        if (!in_array($estado, ['DISPONIBLE', 'NO DISPONIBLE'])) {
            http_response_code(400);
            echo json_encode(['error' => "Estado inválido. Debe ser 'DISPONIBLE' o 'NO DISPONIBLE'"]);
            return;
        }
        
        $asiento = $this->repository->find($id);
        
        if (!$asiento) {
            http_response_code(404);
            echo json_encode(['error' => 'Asiento no encontrado']);
            return;
        }
        
        try {
            $asiento->setEstado($estado);
            $this->entityManager->flush();
            
            echo json_encode([
                'message' => 'Estado del asiento actualizado con éxito',
                'asiento' => $this->serializeEntity($asiento)
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar el estado: ' . $e->getMessage()]);
        }
    }

    /**
     * busca un asiento específico por lugar, fila y número
     *
     * @param int $lugarId id del lugar
     * @param string $fila fila del asiento
     * @param string $numero número del asiento
     */
    public function getAsientoByFilaNumero(int $lugarId, string $fila, string $numero): void {
        $asiento = $this->repository->findOneBy([
            'lugar' => $lugarId,
            'fila' => $fila,
            'numero' => $numero
        ]);
        
        if (!$asiento) {
            http_response_code(404);
            echo json_encode(['error' => 'Asiento no encontrado']);
            return;
        }
        
        echo json_encode($this->serializeEntity($asiento));
    }

    /**
     * obtener las entradas asociadas a un asiento
     *
     * @param int $id id del asiento
     */
    public function getEntradasByAsiento(int $id): void {
        $asiento = $this->repository->find($id);
        
        if (!$asiento) {
            http_response_code(404);
            echo json_encode(['error' => 'Asiento no encontrado']);
            return;
        }
        
        $entradas = $asiento->getEntradas();
        $data = [];
        
        foreach ($entradas as $entrada) {
            $data[] = [
                'id' => $entrada->getCodigo(),
                'codigo' => $entrada->getCodigo(),
                'sesion' => [
                    'id' => $entrada->getPrecio()->getSesion()->getId(),
                    'fecha' => $entrada->getPrecio()->getSesion()->getFecha()->format('Y-m-d'),
                    'hora' => $entrada->getPrecio()->getSesion()->getHora()->format('H:i:s')
                ],
            ];
            // !! añadir después los campos de Entrada que faltan
        }
        
        echo json_encode($data);
    }

    /**
     * crear múltiples asientos a la vez
     */
    public function crearMultiplesAsientos(): void {
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data) || !isset($data['asientos']) || !is_array($data['asientos'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos inválidos o faltantes']);
            return;
        }
        
        try {
            $this->entityManager->beginTransaction();
            $asientosCreados = [];
            
            foreach ($data['asientos'] as $asientoData) {
                // vlidar datos mínimos requeridos
                if (!isset($asientoData['lugar_id']) || !isset($asientoData['zona_id']) || 
                    !isset($asientoData['fila']) || !isset($asientoData['numero'])) {
                    throw new Exception("Datos incompletos para algún asiento");
                }
                
                // verificar si ya existe un asiento con la misma fila y número en el mismo lugar
                $asientoExistente = $this->repository->findOneBy([
                    'lugar' => $asientoData['lugar_id'],
                    'fila' => $asientoData['fila'],
                    'numero' => $asientoData['numero']
                ]);
                
                if ($asientoExistente) {
                    continue; // saltar este asiento y continuar con el siguiente
                }
                
                // Crear nuevo asiento
                $asiento = $this->createEntityFromData($asientoData);
                $this->entityManager->persist($asiento);
                $this->entityManager->flush(); // hace flush para que se genere el id
                
                $asientosCreados[] = $this->serializeEntity($asiento);
            }
            
            $this->entityManager->commit();
            
            http_response_code(201);
            echo json_encode([
                'message' => 'Asientos creados con éxito',
                'asientos' => $asientosCreados
            ]);
        } catch (Exception $e) {
            $this->entityManager->rollback();
            http_response_code(500);
            echo json_encode([
                'error' => 'Error al crear asientos: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * generar una estructura de asientos para un lugar y zona
     * 
     * @param int $lugarId id del lugar
     * @param int $zonaId id de la zona
     * @param int $filas número de filas a generar
     * @param int $asientosPorFila número de asientos por fila
     * @param string $prefijo prefijo para las filas (opcional)
     */
    public function generarEstructuraAsientos(int $lugarId, int $zonaId, int $filas, int $asientosPorFila, ?string $prefijo = null): void {
        
        // validar parámetros
        if ($filas <= 0 || $asientosPorFila <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'El número de filas y asientos por fila debe ser mayor que cero']);
            return;
        }
        
        // verificar si el lugar existe
        $lugar = $this->entityManager->getRepository(Lugar::class)->find($lugarId);
        if (!$lugar) {
            http_response_code(404);
            echo json_encode(['error' => "Lugar con ID {$lugarId} no encontrado"]);
            return;
        }
        
        // verificar si la zona existe
        $zona = $this->entityManager->getRepository(Zona::class)->find($zonaId);
        if (!$zona) {
            http_response_code(404);
            echo json_encode(['error' => "Zona con ID {$zonaId} no encontrada"]);
            return;
        }
        
        try {
            $this->entityManager->beginTransaction();
            $asientosCreados = [];
            $asientosExistentes = 0;
            
            for ($i = 1; $i <= $filas; $i++) {
                // formatear el nombre de la fila
                $fila = $prefijo !== null ? $prefijo . $i : (string)$i;
                
                // limitar a 5 caractere si es necesario
                if (strlen($fila) > 5) {
                    $fila = substr($fila, 0, 5);
                }
                
                for ($j = 1; $j <= $asientosPorFila; $j++) {
                    // formatear ell numero de asiento
                    $numero = (string)$j;
                    
                    // verificar si ya existe un asiento con la misma fila y número en el mismo lugar
                    $asientoExistente = $this->repository->findOneBy([
                        'lugar' => $lugar,
                        'fila' => $fila,
                        'numero' => $numero
                    ]);
                    
                    if ($asientoExistente) {
                        $asientosExistentes++;
                        continue; // saltar este asiento y continuar con el siguiente
                    }
                    
                    // crear nuevo asiento
                    $asiento = new Asiento();
                    $asiento->setLugar($lugar);
                    $asiento->setZona($zona);
                    $asiento->setFila($fila);
                    $asiento->setNumero($numero);
                    $asiento->setEstado('DISPONIBLE');
                    
                    $this->entityManager->persist($asiento);
                    
                }
            }
            
            // flush fuera de los bucles por si acaso
            $this->entityManager->flush();
            $this->entityManager->commit();
            
            // obtener los asientos creados para mostrarlos en la respuesta
            $asientosCreados = $this->repository->findBy([
                'lugar' => $lugar,
                'zona' => $zona
            ]);
            
            $totalCreados = count($asientosCreados) - $asientosExistentes;
            
            http_response_code(201);
            echo json_encode([
                'message' => 'Estructura de asientos generada con éxito',
                'total_creados' => $totalCreados,
                'asientos_existentes' => $asientosExistentes,
                'total_general' => count($asientosCreados)
            ]);
        } catch (Exception $e) {
            $this->entityManager->rollback();
            http_response_code(500);
            echo json_encode([
                'error' => 'Error al generar estructura de asientos: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * obtener la disponibilidad de asientos por zona y sesión
     * 
     * @param int $zonaId id de la zona
     * @param int $sesionId id de la sesión
     */
    public function getDisponibilidadAsientosPorZonaYSesion(int $zonaId, int $sesionId): void {
        
        // verificar si la zona existe
        $zona = $this->entityManager->getRepository(Zona::class)->find($zonaId);
        if (!$zona) {
            http_response_code(404);
            echo json_encode(['error' => "Zona con ID {$zonaId} no encontrada"]);
            return;
        }
        
        try {
            // obtener todos los asientos de la zona
            $asientos = $this->repository->findBy(['zona' => $zona]);
            
            if (empty($asientos)) {
                echo json_encode([
                    'zona_id' => $zonaId,
                    'sesion_id' => $sesionId,
                    'total_asientos' => 0,
                    'disponibles' => 0,
                    'ocupados' => 0,
                    'asientos' => []
                ]);
                return;
            }
            
            // construir una lista de ids de asientos
            $asientoIds = array_map(function($asiento) {
                return $asiento->getId();
            }, $asientos);
            
            // consultar las entradas para la sesión dada y los asientos de la zona
            $qb = $this->entityManager->createQueryBuilder();
            $qb->select('e')
               ->from(Entrada::class, 'e')
               ->join('e.precio', 'p')
               ->where('p.sesion = :sesionId')
               ->andWhere('e.asiento IN (:asientoIds)')
               ->setParameter('sesionId', $sesionId)
               ->setParameter('asientoIds', $asientoIds);
            
            $entradas = $qb->getQuery()->getResult();
            
            // construir un mapa de asientos ocupados para la sesión
            $asientosOcupados = [];
            foreach ($entradas as $entrada) {
                $asientosOcupados[$entrada->getAsiento()->getId()] = true;
            }
            
            // preparar la respuesta con todos los asientos y su disponibilidad para la sesión
            $asientosData = [];
            $disponibles = 0;
            $ocupados = 0;
            
            foreach ($asientos as $asiento) {
                $asientoId = $asiento->getId();
                $estaOcupado = isset($asientosOcupados[$asientoId]);
                $estaDisponible = $asiento->getEstado() === 'DISPONIBLE' && !$estaOcupado;
                
                if ($estaDisponible) {
                    $disponibles++;
                } else {
                    $ocupados++;
                }
                
                $asientosData[] = [
                    'id' => $asientoId,
                    'fila' => $asiento->getFila(),
                    'numero' => $asiento->getNumero(),
                    'estado' => $asiento->getEstado(),
                    'disponible_sesion' => $estaDisponible,
                    'ocupado_sesion' => $estaOcupado
                ];
            }
            
            // ordenar asientos por fila y numero para una mejor visualización
            usort($asientosData, function($a, $b) {
                if ($a['fila'] === $b['fila']) {
                    return $a['numero'] <=> $b['numero'];
                }
                return $a['fila'] <=> $b['fila'];
            });
            
            echo json_encode([
                'zona_id' => $zonaId,
                'sesion_id' => $sesionId,
                'total_asientos' => count($asientos),
                'disponibles' => $disponibles,
                'ocupados' => $ocupados,
                'asientos' => $asientosData
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Error al obtener disponibilidad de asientos: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
