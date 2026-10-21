<?php

use Entradas\Entity\Cliente;
use Doctrine\ORM\EntityManager;

class ClienteModel {
    
    private EntityManager $entityManager;
    
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    /**
     * inserta clientes a la base de datos
     *
     * @return array $clientesObj array de clientes
     */
    public function insertarClientes(): array {
        
        $clientes = [
            ['dni' => '12345678A', 'nombre' => 'Juan', 'apellidos' => 'García López', 'email' => 'juan.garcia@ejemplo.com', 'telefono' => '666111222', 'password' => 'password'],
            ['dni' => '23456789B', 'nombre' => 'María', 'apellidos' => 'Martínez Pérez', 'email' => 'maria.martinez@ejemplo.com', 'telefono' => '666222333', 'password' => 'password'],
            ['dni' => '34567890C', 'nombre' => 'Carlos', 'apellidos' => 'Rodríguez Sánchez', 'email' => 'carlos.rodriguez@ejemplo.com', 'telefono' => '666333444', 'password' => 'password'],
            ['dni' => '45678901D', 'nombre' => 'Ana', 'apellidos' => 'Fernández Gómez', 'email' => 'ana.fernandez@ejemplo.com', 'telefono' => '666444555', 'password' => 'password'],
            ['dni' => '56789012E', 'nombre' => 'Pedro', 'apellidos' => 'González Ruiz', 'email' => 'pedro.gonzalez@ejemplo.com', 'telefono' => '666555666', 'password' => 'password'],
            ['dni' => '67890123F', 'nombre' => 'Laura', 'apellidos' => 'López Castro', 'email' => 'laura.lopez@ejemplo.com', 'telefono' => '666666777', 'password' => 'password'],
            ['dni' => '78901234G', 'nombre' => 'Miguel', 'apellidos' => 'Sánchez Torres', 'email' => 'miguel.sanchez@ejemplo.com', 'telefono' => '666777888', 'password' => 'password'],
            ['dni' => '89012345H', 'nombre' => 'Carmen', 'apellidos' => 'Díaz Romero', 'email' => 'carmen.diaz@ejemplo.com', 'telefono' => '666888999', 'password' => 'password'],
            ['dni' => '90123456I', 'nombre' => 'Javier', 'apellidos' => 'Moreno Ortega', 'email' => 'javier.moreno@ejemplo.com', 'telefono' => '666999000', 'password' => 'password'],
            ['dni' => '01234567J', 'nombre' => 'Lucía', 'apellidos' => 'Jiménez Navarro', 'email' => 'lucia.jimenez@ejemplo.com', 'telefono' => '677000111', 'password' => 'password']
        ];
        
        $clientesObj = [];
        foreach ($clientes as $clienteData) {
            if (!$this->entityManager->getRepository(Cliente::class)->findOneBy(['dni' => $clienteData['dni']])) {
                $cliente = new Cliente();
                $cliente->setDni($clienteData['dni']);
                $cliente->setNombre($clienteData['nombre']);
                $cliente->setApellidos($clienteData['apellidos']);
                $cliente->setEmail($clienteData['email']);
                $cliente->setTelefono($clienteData['telefono']);
                $cliente->setPassword($clienteData['password']);
                $this->entityManager->persist($cliente);
                $clientesObj[$clienteData['dni']] = $cliente;
            } else {
                $clientesObj[$clienteData['dni']] = $this->entityManager->getRepository(Cliente::class)->findOneBy(['dni' => $clienteData['dni']]);
            }
        }
        
        return $clientesObj;
        
    }
    
}

