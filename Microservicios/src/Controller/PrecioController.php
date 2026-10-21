<?php

use Entradas\Entity\Precio;
use Entradas\Entity\Sesion;
use Entradas\Entity\Zona;
use Doctrine\ORM\EntityManager;

class PrecioController extends EntityController {

    /**
     * {@inheritdoc}
     */
    protected function setEntityClass(): void {
        $this->entityClass = Precio::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntityFromData(array $data): object {
        $precio = new Precio();
        return $this->updateEntityFromData($precio, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function updateEntityFromData(object $entity, array $data): object {
        
        // establecer o actualizar la relación con Sesion
        if (isset($data['sesion_id'])) {
            $sesion = $this->entityManager->getRepository(Sesion::class)->find($data['sesion_id']);
            if (!$sesion) {
                throw new Exception("Sesion con ID {$data['sesion_id']} no encontrada");
            }
            $entity->setSesion($sesion);
        }

        // establecer o actualizar la relación con Zona
        if (isset($data['zona_id'])) {
            $zona = $this->entityManager->getRepository(Zona::class)->find($data['zona_id']);
            if (!$zona) {
                throw new Exception("Zona con ID {$data['zona_id']} no encontrada");
            }
            $entity->setZona($zona);
        }

        // establecer importe
        if (isset($data['importe'])) {
            // valida que el importe sea un número positivo
            $importe = (float)$data['importe'];
            if ($importe < 0) {
                throw new Exception("El importe no puede ser negativo");
            }
            $entity->setImporte($importe);
        }

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    protected function serializeEntity(object $entity): array {
        return [
            'id' => $entity->getId(),
            'sesion' => [
                'id' => $entity->getSesion()->getId(),
                'fecha' => $entity->getSesion()->getFecha()->format('Y-m-d'),
                'hora' => $entity->getSesion()->getHora()->format('H:i:s')
            ],
            'zona' => [
                'id' => $entity->getZona()->getId(),
                'nombre' => $entity->getZona()->getNombre()
            ],
            'importe' => $entity->getImporte()
        ];
    }

    /**
     * buscar precios por sesión
     *
     * @param int $sesionId id de la sesión
     */
    public function getPreciosBySesion(int $sesionId): void {
        $precios = $this->repository->findBy(['sesion' => $sesionId]);
        $data = [];
        
        foreach ($precios as $precio) {
            $data[] = $this->serializeEntity($precio);
        }
        
        echo json_encode($data);
    }

    /**
     * buscar precios por zona
     *
     * @param int $zonaId ID de la zona
     */
    public function getPreciosByZona(int $zonaId): void {
        $precios = $this->repository->findBy(['zona' => $zonaId]);
        $data = [];
        
        foreach ($precios as $precio) {
            $data[] = $this->serializeEntity($precio);
        }
        
        echo json_encode($data);
    }

    /**
     * buscar precios por rango de importe
     *
     * @param float $min importe mínimo
     * @param float $max importe máximo
     */
    public function getPreciosByRango(float $min, float $max): void {
        
        if ($min > $max) {
            http_response_code(400);
            echo json_encode(['error' => 'El importe mínimo no puede ser mayor que el máximo']);
            return;
        }
        
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('p')
           ->from($this->entityClass, 'p')
           ->where('p.importe >= :min')
           ->andWhere('p.importe <= :max')
           ->setParameter('min', $min)
           ->setParameter('max', $max)
           ->orderBy('p.importe', 'ASC');
        
        $precios = $qb->getQuery()->getResult();
        $data = [];
        
        foreach ($precios as $precio) {
            $data[] = $this->serializeEntity($precio);
        }
        
        echo json_encode($data);
    }

    /**
     * obtener el precio específico por sesión y zona
     * 
     * @param int $sesionId id de la sesión
     * @param int $zonaId id de la zona
     */
    public function getPrecioBySesionAndZona(int $sesionId, int $zonaId): void
    {
        // buscar precio específico por sesión y zona
        $precio = $this->repository->findOneBy([
            'sesion' => $sesionId,
            'zona' => $zonaId
        ]);
        
        if (!$precio) {
            http_response_code(404);
            echo json_encode(['error' => 'No se encontró precio para la sesión y zona especificadas']);
            return;
        }
        
        echo json_encode($this->serializeEntity($precio));
    }
    
    /**
     * establecer precios en masa para una sesión
     * 
     * @param int $sesionId ID de la sesión
     */
    public function setMassivePrices(int $sesionId): void {
        
        // verificar si la sesión existe
        $sesion = $this->entityManager->getRepository(Sesion::class)->find($sesionId);
        if (!$sesion) {
            http_response_code(404);
            echo json_encode(['error' => 'Sesión no encontrada']);
            return;
        }
        
        // obtener los datos enviados
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data) || !isset($data['precios']) || !is_array($data['precios'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos de precios inválidos o faltantes']);
            return;
        }
        
        try {
            $this->entityManager->beginTransaction();
            $resultados = [];
            
            foreach ($data['precios'] as $precioData) {
                if (!isset($precioData['zona_id']) || !isset($precioData['importe'])) {
                    throw new Exception("Datos incompletos para algún precio");
                }
                
                // verificar si la zona existe
                $zona = $this->entityManager->getRepository(Zona::class)->find($precioData['zona_id']);
                if (!$zona) {
                    throw new Exception("Zona con ID {$precioData['zona_id']} no encontrada");
                }
                
                // buscar si ya existe un precio para esta sesión y zona
                $precio = $this->repository->findOneBy([
                    'sesion' => $sesion,
                    'zona' => $zona
                ]);
                
                if (!$precio) {
                    // si no existe, creo uno nuevo
                    $precio = new Precio();
                    $precio->setSesion($sesion);
                    $precio->setZona($zona);
                    $this->entityManager->persist($precio);
                }
                
                // establecer o actualixar el importe
                $precio->setImporte((float)$precioData['importe']);
                
                // guardar el resultado para la respuesta
                $resultados[] = [
                    'zona_id' => $zona->getId(),
                    'zona_nombre' => $zona->getNombre(),
                    'importe' => $precio->getImporte()
                ];
            }
            
            $this->entityManager->flush();
            $this->entityManager->commit();
            
            http_response_code(200);
            echo json_encode([
                'message' => 'Precios establecidos con éxito',
                'sesion_id' => $sesionId,
                'precios' => $resultados
            ]);
        } catch (Exception $e) {
            $this->entityManager->rollback();
            http_response_code(500);
            echo json_encode([
                'error' => 'Error al establecer precios: ' . $e->getMessage()
            ]);
        }
    }
}