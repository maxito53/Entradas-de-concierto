<?php

use Entradas\Entity\Lugar;
use Doctrine\ORM\EntityManager;

class LugarController extends EntityController {

    /**
     * {@inheritdoc}
     */
    protected function setEntityClass(): void {
        $this->entityClass = Lugar::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntityFromData(array $data): object {
        $lugar = new Lugar();
        return $this->updateEntityFromData($lugar, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function updateEntityFromData(object $entity, array $data): object {
        if (isset($data['nombre'])) {
            $entity->setNombre($data['nombre']);
        }

        if (isset($data['direccion'])) {
            $entity->setDireccion($data['direccion']);
        }

        if (isset($data['ciudad'])) {
            $entity->setCiudad($data['ciudad']);
        }

        if (isset($data['capacidad'])) {
            $entity->setCapacidad($data['capacidad']);
        }

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    protected function serializeEntity(object $entity): array {
        return [
            'id' => $entity->getId(),
            'nombre' => $entity->getNombre(),
            'direccion' => $entity->getDireccion(),
            'ciudad' => $entity->getCiudad(),
            'capacidad' => $entity->getCapacidad()
        ];
    }
    
}