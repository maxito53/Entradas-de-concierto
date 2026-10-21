<?php

use Entradas\Entity\Lugar;
use Entradas\Entity\Zona;
use Doctrine\ORM\EntityManager;

class ZonaController extends EntityController {

    /**
     * {@inheritdoc}
     */
    protected function setEntityClass(): void {
        $this->entityClass = Zona::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntityFromData(array $data): object {
        $zona = new Zona();
        return $this->updateEntityFromData($zona, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function updateEntityFromData(object $entity, array $data): object {
        if (isset($data['nombre'])) {
            $entity->setNombre($data['nombre']);
        }

        if (isset($data['descripcion'])) {
            $entity->setDescripcion($data['descripcion']);
        }

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    protected function serializeEntity(object $entity): array {
        $data = [
            'id' => $entity->getId(),
            'nombre' => $entity->getNombre(),
            'descripcion' => $entity->getDescripcion()
        ];

        return $data;
    }
    
}