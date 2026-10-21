<?php

use Entradas\Entity\Sesion;
use Entradas\Entity\Concierto;
use Entradas\Entity\Lugar;
use Doctrine\ORM\EntityManager;
// use DateTime;

class SesionController extends EntityController {

    /**
     * {@inheritdoc}
     */
    protected function setEntityClass(): void {
        $this->entityClass = Sesion::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntityFromData(array $data): object {
        $sesion = new Sesion();
        return $this->updateEntityFromData($sesion, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function updateEntityFromData(object $entity, array $data): object {
        
        // establecer o actualizar la relación con Concierto
        if (isset($data['concierto_id'])) {
            $concierto = $this->entityManager->getRepository(Concierto::class)->find($data['concierto_id']);
            if (!$concierto) {
                throw new Exception("Concierto con ID {$data['concierto_id']} no encontrado");
            }
            $entity->setConcierto($concierto);
        }

        // establecer o actualizar la relación con Lugar
        if (isset($data['lugar_id'])) {
            $lugar = $this->entityManager->getRepository(Lugar::class)->find($data['lugar_id']);
            if (!$lugar) {
                throw new Exception("Lugar con ID {$data['lugar_id']} no encontrado");
            }
            $entity->setLugar($lugar);
        }

        // establecer fecha
        if (isset($data['fecha'])) {
            try {
                $fecha = new DateTime($data['fecha']);
                $entity->setFecha($fecha);
            } catch (Exception $e) {
                throw new Exception("Formato de fecha inválido. Use 'Y-m-d'");
            }
        }

        // establecer hora
        if (isset($data['hora'])) {
            try {
                $hora = new DateTime($data['hora']);
                $entity->setHora($hora);
            } catch (Exception $e) {
                throw new Exception("Formato de hora inválido. Use 'H:i:s'");
            }
        }

        // establecer estado
        if (isset($data['estado'])) {
            // valida que el estado sea uno de los permitidos
            $estadosValidos = ['PROGRAMADO', 'CANCELADO', 'COMPLETADO', 'POSPUESTO'];
            if (!in_array($data['estado'], $estadosValidos)) {
                throw new \Exception("Estado inválido. Los valores permitidos son: " . implode(', ', $estadosValidos));
            }
            $entity->setEstado($data['estado']);
        }

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    protected function serializeEntity(object $entity): array {
        return [
            'id' => $entity->getId(),
            'concierto' => [
                'id' => $entity->getConcierto()->getId(),
                'nombre' => $entity->getConcierto()->getNombre(),
                'descripcion' => $entity->getConcierto()->getDescripcion(),
                'imagen' => $entity->getConcierto()->getImagen() ? '/Microservicios/media/' . $entity->getConcierto()->getImagen() : null,
                'artista' => [
                    'id' => $entity->getConcierto()->getArtista()->getId(),
                    'nombre' => $entity->getConcierto()->getArtista()->getNombre(),
                    'pais' => $entity->getConcierto()->getArtista()->getPais(),
                    'genero' => $entity->getConcierto()->getArtista()->getGenero()
                ]
            ],
            'lugar' => [
                'id' => $entity->getLugar()->getId(),
                'nombre' => $entity->getLugar()->getNombre(),
                'direccion' => $entity->getLugar()->getDireccion(),
                'ciudad' => $entity->getLugar()->getCiudad(),
                'capacidad' => $entity->getLugar()->getCapacidad()
            ],
            'fecha' => $entity->getFecha()->format('Y-m-d'),
            'hora' => $entity->getHora()->format('H:i:s'),
            'estado' => $entity->getEstado()
        ];
    }

    /**
     * buscar sesiones por concierto
     *
     * @param int $conciertoId id del concierto
     */
    public function getSesionesByConcierto(int $conciertoId): void {
        $sesiones = $this->repository->findBy(['concierto' => $conciertoId]);
        $data = [];
        
        foreach ($sesiones as $sesion) {
            $data[] = $this->serializeEntity($sesion);
        }
        
        echo json_encode($data);
    }

    /**
     * buscar sesiones por lugar
     *
     * @param int $lugarId id del lugar
     */
    public function getSesionesByLugar(int $lugarId): void {
        $sesiones = $this->repository->findBy(['lugar' => $lugarId]);
        $data = [];
        
        foreach ($sesiones as $sesion) {
            $data[] = $this->serializeEntity($sesion);
        }
        
        echo json_encode($data);
    }

    /**
     * buscar sesiones por fecha
     *
     * @param string $fecha fecha en formato Y-m-d
     */
    public function getSesionesByFecha(string $fecha): void {
        try {
            $fechaObj = new DateTime($fecha);
            $sesiones = $this->repository->findBy(['fecha' => $fechaObj]);
            $data = [];
            
            foreach ($sesiones as $sesion) {
                $data[] = $this->serializeEntity($sesion);
            }
            
            echo json_encode($data);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => 'Formato de fecha inválido. Use Y-m-d']);
        }
    }

    /**
     * buscar sesiones por estado
     *
     * @param string $estado Estado de la sesión
     */
    public function getSesionesByEstado(string $estado): void {
        
        $estadosValidos = ['PROGRAMADO', 'CANCELADO', 'COMPLETADO', 'POSPUESTO'];
        
        if (!in_array($estado, $estadosValidos)) {
            http_response_code(400);
            echo json_encode(['error' => 'Estado inválido. Los valores permitidos son: ' . implode(', ', $estadosValidos)]);
            return;
        }
        
        $sesiones = $this->repository->findBy(['estado' => $estado]);
        $data = [];
        
        foreach ($sesiones as $sesion) {
            $data[] = $this->serializeEntity($sesion);
        }
        
        echo json_encode($data);
    }
    
}