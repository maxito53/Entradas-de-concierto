<?php
// Microservicios/src/Controller/ArtistaController.php

use Entradas\Entity\Artista;
use Doctrine\ORM\EntityManager;

class ArtistaController extends EntityController {

    /**
     * {@inheritdoc}
     */
    protected function setEntityClass(): void {
        $this->entityClass = Artista::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntityFromData(array $data): object {
        $artista = new Artista();
        return $this->updateEntityFromData($artista, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function updateEntityFromData(object $entity, array $data): object {
        if (isset($data['nombre'])) {
            $entity->setNombre($data['nombre']);
        }

        if (isset($data['pais'])) {
            $entity->setPais($data['pais']);
        }

        if (isset($data['genero'])) {
            $entity->setGenero($data['genero']);
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
            'pais' => $entity->getPais(),
            'genero' => $entity->getGenero()
        ];
    }
}