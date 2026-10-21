<?php

use Entradas\Entity\Pago;
use Entradas\Entity\Cliente;
use Entradas\Entity\Entrada;
use Doctrine\ORM\EntityManager;

class PagoController extends EntityController {

    /**
     * {@inheritdoc}
     */
    protected function setEntityClass(): void {
        $this->entityClass = Pago::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntityFromData(array $data): object {
        $pago = new Pago();
        return $this->updateEntityFromData($pago, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function updateEntityFromData(object $entity, array $data): object {
        
        // establecer o actualizar la relación con Cliente
        if (isset($data['cliente_id'])) {
            $cliente = $this->entityManager->getRepository(Cliente::class)->find($data['cliente_id']);
            if (!$cliente) {
                throw new Exception("Cliente con ID {$data['cliente_id']} no encontrado");
            }
            $entity->setCliente($cliente);
        }

        // establecer fecha
        if (isset($data['fecha'])) {
            try {
                $fecha = new DateTime($data['fecha']);
                $entity->setFecha($fecha);
            } catch (Exception $e) {
                throw new Exception("Formato de fecha inválido: " . $e->getMessage());
            }
        } elseif ($entity->getId() === null) {
            // si es una creación y no se proporciona fecha, usar la fecha actual
            $entity->setFecha(new DateTime());
        }

        // establecer total
        if (isset($data['total'])) {
            // valida que el total sea un número positivo
            $total = (float)$data['total'];
            if ($total < 0) {
                throw new Exception("El total no puede ser negativo");
            }
            $entity->setTotal($total);
        }

        // establecer método de pago
        if (isset($data['metodo'])) {
            $entity->setMetodo($data['metodo']);
        }

        // establecer referencia
        if (isset($data['referencia'])) {
            $entity->setReferencia($data['referencia']);
        }

        // establecer estado
        if (isset($data['estado'])) {
            $estado = strtoupper($data['estado']);
            // valida que el estado sea uno de los permitidos
            if (!in_array($estado, ['PENDIENTE', 'EFECTUADO'])) {
                throw new Exception("Estado inválido. Debe ser 'PENDIENTE' o 'EFECTUADO'");
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
            'cliente' => [
                'id' => $entity->getCliente()->getId(),
                'nombre' => $entity->getCliente()->getNombre() ?? 'N/A',
                'email' => $entity->getCliente()->getEmail() ?? 'N/A'
            ],
            'fecha' => $entity->getFecha()->format('Y-m-d'),
            'total' => $entity->getTotal(),
            'metodo' => $entity->getMetodo(),
            'referencia' => $entity->getReferencia(),
            'estado' => $entity->getEstado(),
        ];
        // añadir más datos si es necesario !!
    }

    /**
     * buscar pagos por cliente
     *
     * @param int $clienteId id del cliente
     */
    public function getPagosByCliente(int $clienteId): void {
        $pagos = $this->repository->findBy(['cliente' => $clienteId]);
        $data = [];
        
        foreach ($pagos as $pago) {
            $data[] = $this->serializeEntity($pago);
        }
        
        echo json_encode($data);
    }

    /**
     * buscar pagos por fecha
     *
     * @param string $fechaInicio fecha de inicio en formato Y-m-d
     * @param string $fechaFin fecha de fin en formato Y-m-d
     */
    public function getPagosByFecha(string $fechaInicio, string $fechaFin): void {
        
        try {
            $inicio = new DateTime($fechaInicio);
            $fin = new DateTime($fechaFin);
            
            // asegurar que la fecha fin sea el último momento del día
            $fin->setTime(23, 59, 59);
            
            if ($inicio > $fin) {
                http_response_code(400);
                echo json_encode(['error' => 'La fecha de inicio no puede ser posterior a la fecha de fin']);
                return;
            }
            
            // usando QueryBuilder porque es una consulta vastante avanzada
            $qb = $this->entityManager->createQueryBuilder();
            $qb->select('p')
               ->from($this->entityClass, 'p')
               ->where('p.fecha >= :inicio')
               ->andWhere('p.fecha <= :fin')
               ->setParameter('inicio', $inicio)
               ->setParameter('fin', $fin)
               ->orderBy('p.fecha', 'ASC');
            
            $pagos = $qb->getQuery()->getResult();
            $data = [];
            
            foreach ($pagos as $pago) {
                $data[] = $this->serializeEntity($pago);
            }
            
            echo json_encode($data);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => 'Formato de fecha inválido: ' . $e->getMessage()]);
        }
    }

    /**
     * buscar pagos por estado
     *
     * @param string $estado estado del pago ('PENDIENTE' o 'EFECTUADO')
     */
    public function getPagosByEstado(string $estado): void {
        $estado = strtoupper($estado);
        
        // valida que el estado sea uno de los permitidos
        if (!in_array($estado, ['PENDIENTE', 'EFECTUADO'])) {
            http_response_code(400);
            echo json_encode(['error' => "Estado inválido. Debe ser 'PENDIENTE' o 'EFECTUADO'"]);
            return;
        }
        
        $pagos = $this->repository->findBy(['estado' => $estado]);
        $data = [];
        
        foreach ($pagos as $pago) {
            $data[] = $this->serializeEntity($pago);
        }
        
        echo json_encode($data);
    }

    /**
     * buscar pagos por método de pago
     *
     * @param string $metodo método de pago
     */
    public function getPagosByMetodo(string $metodo): void {
        $pagos = $this->repository->findBy(['metodo' => $metodo]);
        $data = [];
        
        foreach ($pagos as $pago) {
            $data[] = $this->serializeEntity($pago);
        }
        
        echo json_encode($data);
    }

    /**
     * cambiar el estado de un pago
     *
     * @param int $id ID del pago
     * @param string $estado Nuevo estado ('PENDIENTE' o 'EFECTUADO')
     */
    public function cambiarEstado(int $id, string $estado): void {
        $estado = strtoupper($estado);
        
        // valida que el estado sea uno de los permitidos
        if (!in_array($estado, ['PENDIENTE', 'EFECTUADO'])) {
            http_response_code(400);
            echo json_encode(['error' => "Estado inválido. Debe ser 'PENDIENTE' o 'EFECTUADO'"]);
            return;
        }
        
        $pago = $this->repository->find($id);
        
        if (!$pago) {
            http_response_code(404);
            echo json_encode(['error' => 'Pago no encontrado']);
            return;
        }
        
        try {
            $pago->setEstado($estado);
            $this->entityManager->flush();
            
            echo json_encode([
                'message' => 'Estado del pago actualizado con éxito',
                'pago' => $this->serializeEntity($pago)
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar el estado: ' . $e->getMessage()]);
        }
    }

    /**
     * obtener las entradas asociadas a un pago
     *
     * @param int $id ID del pago
     */
    public function getEntradasByPago(int $id): void {
        
        $pago = $this->repository->find($id);
        
        if (!$pago) {
            http_response_code(404);
            echo json_encode(['error' => 'Pago no encontrado']);
            return;
        }
        
        $entradas = $pago->getEntradas();
        $data = [];
        
        foreach ($entradas as $entrada) {
            $data[] = [
                'id' => $entrada->getCodigo(),
                'codigo' => $entrada->getCodigo(),
                'precio' => [
                    'id' => $entrada->getPrecio()->getId(),
                    'importe' => $entrada->getPrecio()->getImporte()
                ],
                'sesion' => [
                    'id' => $entrada->getPrecio()->getSesion()->getId(),
                    'fecha' => $entrada->getPrecio()->getSesion()->getFecha()->format('Y-m-d'),
                    'hora' => $entrada->getPrecio()->getSesion()->getHora()->format('H:i:s')
                ],
                'zona' => [
                    'id' => $entrada->getPrecio()->getZona()->getId(),
                    'nombre' => $entrada->getPrecio()->getZona()->getNombre()
                ]
                // acabar de poner los datos
            ];
        }
        
        echo json_encode($data);
    }

    /**
     * calcular estadísticas de pagos
     * 
     * @param string $fechaInicio fecha de inicio en formato Y-m-d (opcional)
     * @param string $fechaFin fecha de fin en formato Y-m-d (opcional)
     */
    public function getEstadisticas(?string $fechaInicio = null, ?string $fechaFin = null): void {
        try {
            $qb = $this->entityManager->createQueryBuilder();
            $qb->select('p')
               ->from($this->entityClass, 'p');
            
            // aplicar filtro de fechas si se proporcionan
            if ($fechaInicio !== null && $fechaFin !== null) {
                $inicio = new DateTime($fechaInicio);
                $fin = new DateTime($fechaFin);
                $fin->setTime(23, 59, 59);
                
                $qb->where('p.fecha >= :inicio')
                   ->andWhere('p.fecha <= :fin')
                   ->setParameter('inicio', $inicio)
                   ->setParameter('fin', $fin);
            }
            
            $pagos = $qb->getQuery()->getResult();
            
            // calcular estadísticas
            $totalPagos = count($pagos);
            $totalImporte = 0;
            $pagosPendientes = 0;
            $pagosEfectuados = 0;
            $importePendiente = 0;
            $importeEfectuado = 0;
            $metodosPago = [];
            
            foreach ($pagos as $pago) {
                $totalImporte += $pago->getTotal();
                
                // contar por estado
                if ($pago->getEstado() === 'PENDIENTE') {
                    $pagosPendientes++;
                    $importePendiente += $pago->getTotal();
                } else {
                    $pagosEfectuados++;
                    $importeEfectuado += $pago->getTotal();
                }
                
                // contar por método de pago
                $metodo = $pago->getMetodo() ?: 'NO ESPECIFICADO';
                if (!isset($metodosPago[$metodo])) {
                    $metodosPago[$metodo] = [
                        'cantidad' => 0,
                        'importe' => 0
                    ];
                }
                $metodosPago[$metodo]['cantidad']++;
                $metodosPago[$metodo]['importe'] += $pago->getTotal();
            }
            
            // respuesta
            $estadisticas = [
                'total_pagos' => $totalPagos,
                'total_importe' => $totalImporte,
                'por_estado' => [
                    'pendientes' => [
                        'cantidad' => $pagosPendientes,
                        'importe' => $importePendiente,
                        'porcentaje' => $totalPagos > 0 ? round(($pagosPendientes / $totalPagos) * 100, 2) : 0
                    ],
                    'efectuados' => [
                        'cantidad' => $pagosEfectuados,
                        'importe' => $importeEfectuado,
                        'porcentaje' => $totalPagos > 0 ? round(($pagosEfectuados / $totalPagos) * 100, 2) : 0
                    ]
                ],
                'por_metodo' => $metodosPago
            ];
            
            echo json_encode($estadisticas);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al generar estadísticas: ' . $e->getMessage()]);
        }
    }
    
}
