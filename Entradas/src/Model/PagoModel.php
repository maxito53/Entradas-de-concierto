<?php

use Entradas\Entity\Pago;
use Entradas\Entity\Cliente;
use Doctrine\ORM\EntityManager;

class PagoModel {
    
    private EntityManager $entityManager;
    
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    /**
     * inserta pagos a la base de datos
     *
     * @param array $clientesObj array de clientes
     * @return array $pagosObj array de pagos
     */
    public function insertarPagos(array $clientesObj): array {
        
        $pagos = [
            ['cliente_dni' => '12345678A', 'fecha' => '2025-04-15', 'total' => 220.00, 'metodo' => 'Tarjeta', 'referencia' => 'PAGO12345', 'estado' => 'EFECTUADO'],
            ['cliente_dni' => '23456789B', 'fecha' => '2025-04-16', 'total' => 110.00, 'metodo' => 'PayPal', 'referencia' => 'PAGO23456', 'estado' => 'EFECTUADO'],
            ['cliente_dni' => '34567890C', 'fecha' => '2025-04-17', 'total' => 140.00, 'metodo' => 'Transferencia', 'referencia' => 'PAGO34567', 'estado' => 'EFECTUADO'],
            ['cliente_dni' => '45678901D', 'fecha' => '2025-04-18', 'total' => 180.00, 'metodo' => 'Tarjeta', 'referencia' => 'PAGO45678', 'estado' => 'EFECTUADO'],
            ['cliente_dni' => '56789012E', 'fecha' => '2025-04-19', 'total' => 250.00, 'metodo' => 'PayPal', 'referencia' => 'PAGO56789', 'estado' => 'EFECTUADO'],
            ['cliente_dni' => '67890123F', 'fecha' => '2025-04-20', 'total' => 350.00, 'metodo' => 'Tarjeta', 'referencia' => 'PAGO67890', 'estado' => 'EFECTUADO'],
            ['cliente_dni' => '78901234G', 'fecha' => '2025-04-21', 'total' => 190.00, 'metodo' => 'Transferencia', 'referencia' => 'PAGO78901', 'estado' => 'EFECTUADO'],
            ['cliente_dni' => '89012345H', 'fecha' => '2025-04-22', 'total' => 300.00, 'metodo' => 'Tarjeta', 'referencia' => 'PAGO89012', 'estado' => 'EFECTUADO'],
            ['cliente_dni' => '90123456I', 'fecha' => '2025-04-23', 'total' => 160.00, 'metodo' => 'PayPal', 'referencia' => 'PAGO90123', 'estado' => 'EFECTUADO'],
            ['cliente_dni' => '01234567J', 'fecha' => '2025-04-24', 'total' => 210.00, 'metodo' => 'Tarjeta', 'referencia' => 'PAGO01234', 'estado' => 'EFECTUADO']
        ];
        
        $pagosObj = [];
        $pagoCount = 1;
        foreach ($pagos as $pagoData) {
            $cliente = $clientesObj[$pagoData['cliente_dni']] ?? $this->entityManager->getRepository(Cliente::class)->findOneBy(['dni' => $pagoData['cliente_dni']]);
            
            if ($cliente) {
                // Comprobar si existe un pago con los mismos datos
                $existePago = $this->entityManager->getRepository(Pago::class)->findOneBy([
                    'cliente' => $cliente,
                    'referencia' => $pagoData['referencia']
                ]);
                
                if (!$existePago) {
                    $pago = new Pago();
                    $pago->setCliente($cliente);
                    $pago->setFecha(new \DateTime($pagoData['fecha']));
                    $pago->setTotal($pagoData['total']);
                    $pago->setMetodo($pagoData['metodo']);
                    $pago->setReferencia($pagoData['referencia']);
                    $pago->setEstado($pagoData['estado']);
                    $this->entityManager->persist($pago);
                    $pagosObj[$pagoCount] = $pago;
                    $pagoCount++;
                } else {
                    $pagosObj[$pagoCount] = $existePago;
                    $pagoCount++;
                }
            }
        }
        
        return $pagosObj;
        
    }   
}