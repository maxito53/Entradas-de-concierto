<?php

use Entradas\Entity\Cliente;
use Doctrine\ORM\EntityManager;

class ClienteController extends EntityController {
    
    /**
     * {@inheritdoc}
     */
    protected function setEntityClass(): void {
        $this->entityClass = Cliente::class;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function createEntityFromData(array $data): object {
        $cliente = new Cliente();
        return $this->updateEntityFromData($cliente, $data);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function updateEntityFromData(object $entity, array $data): object {
        if (isset($data['dni'])) {
            $entity->setDni($data['dni']);
        }
        
        if (isset($data['nombre'])) {
            $entity->setNombre($data['nombre']);
        }
        
        if (isset($data['apellidos'])) {
            $entity->setApellidos($data['apellidos']);
        }
        
        if (isset($data['email'])) {
            $entity->setEmail($data['email']);
        }
        
        if (isset($data['telefono'])) {
            $entity->setTelefono($data['telefono']);
        }
        
        if (isset($data['password'])) {
            // !! areglar de lo hashear la contra
            // $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            // $entity->setPassword($hashedPassword);
            
            $entity->setPassword($data['password']);
        }
        
        return $entity;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function serializeEntity(object $entity): array {
        return [
            'id' => $entity->getId(),
            'dni' => $entity->getDni(),
            'nombre' => $entity->getNombre(),
            'apellidos' => $entity->getApellidos(),
            'email' => $entity->getEmail(),
            'telefono' => $entity->getTelefono(),
            // la contrseña no la incluyo pues por seguridad
        ];
    }
    
    /**
     * buscar cliente por DNI
     *
     * @param string $dni DNI del cliente
     */
    public function getClienteByDni(string $dni): void {
        $cliente = $this->repository->findOneBy(['dni' => $dni]);
        
        if (!$cliente) {
            http_response_code(404);
            echo json_encode(['error' => 'Cliente no encontrado']);
            return;
        }
        
        echo json_encode($this->serializeEntity($cliente));
    }
}