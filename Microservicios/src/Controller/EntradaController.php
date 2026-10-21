<?php

use Entradas\Entity\Entrada;
use Entradas\Entity\Pago;
use Entradas\Entity\Sesion;
use Entradas\Entity\Zona;
use Entradas\Entity\Asiento;
use Entradas\Entity\Precio;
use Doctrine\ORM\EntityManager;

class EntradaController extends EntityController {
    
    /**
     * {@inheritdoc}
     */
    protected function setEntityClass(): void {
        $this->entityClass = Entrada::class;
    }
    
    /**
     * configurar que este controlador usa ids de tipo string porque el id de Entrada es codigo 
     */
    protected function configureIdType(): void {
        $this->useStringId = true;
    }
    
    /**
     * genera un nuevo código para una entrada
     * formato: "ENT-YYYY-#####" donde YYYY es el año actual y ##### es un número secuencial que empieza en 00001
     *
     * @return string nuevo código generado
     */
    private function generarNuevoCodigo(): string {
        
        $anioActual = date('Y');
        $prefijo = "ENT-{$anioActual}-";
        
        // buscar el código más alto actual
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('e.codigo')
        ->from($this->entityClass, 'e')
        ->where($queryBuilder->expr()->like('e.codigo', ':prefijo'))
        ->setParameter('prefijo', $prefijo . '%')
        ->orderBy('e.codigo', 'DESC')
        ->setMaxResults(1);
        
        $ultimoCodigo = $queryBuilder->getQuery()->getOneOrNullResult();
        
        if ($ultimoCodigo) {
            // extraer el número de la última entrada
            $ultimoNumero = (int)substr($ultimoCodigo['codigo'], strlen($prefijo));
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            // no hay entradas previas con el prefijo actual
            $nuevoNumero = 1;
        }
        
        // formatear el nuevo número con ceros a la izquierda
        $nuevoNumeroFormateado = str_pad($nuevoNumero, 5, '0', STR_PAD_LEFT);
        
        return $prefijo . $nuevoNumeroFormateado;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function createEntityFromData(array $data): object {
        $entrada = new Entrada();
        
        // generar un nuevo código único para la entrada
        $codigo = $this->generarNuevoCodigo();
        $entrada->setCodigo($codigo);
        
        return $this->updateEntityFromData($entrada, $data);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function updateEntityFromData(object $entity, array $data): object {
        // establecer o actualizar la relación con Pago
        if (isset($data['pago_id'])) {
            $pago = $this->entityManager->getRepository(Pago::class)->find($data['pago_id']);
            if (!$pago) {
                throw new Exception("Pago con ID {$data['pago_id']} no encontrado");
            }
            $entity->setPago($pago);
        }
        
        // establecer o actualizar la relación con Sesion
        if (isset($data['sesion_id'])) {
            $sesion = $this->entityManager->getRepository(Sesion::class)->find($data['sesion_id']);
            if (!$sesion) {
                throw new Exception("Sesión con ID {$data['sesion_id']} no encontrada");
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
        
        // establecer o actualizar la relación con Asiento (opcional)
        if (isset($data['asiento_id'])) {
            
            if (empty($data['asiento_id'])) {
                $entity->setAsiento(null);
                // establecer fila y número manualmente si se proporcionan
                if (isset($data['fila'])) {
                    $entity->setFila($data['fila']);
                }
                if (isset($data['asiento_numero'])) {
                    $entity->setAsientoNumero($data['asiento_numero']);
                }
            } else {
                $asiento = $this->entityManager->getRepository(Asiento::class)->find($data['asiento_id']);
                if (!$asiento) {
                    throw new Exception("Asiento con ID {$data['asiento_id']} no encontrado");
                }
                $entity->setAsiento($asiento);
                // al establecer el asiento, fila y asiento_numero se establecen automáticamente
            }
            
        } else {
            // si no se establece la relación con Asiento, establecer fila y número manualmente
            if (isset($data['fila'])) {
                $entity->setFila($data['fila']);
            }
            if (isset($data['asiento_numero'])) {
                $entity->setAsientoNumero($data['asiento_numero']);
            }
        }
        
        // establecer o actualizar la relación con Precio
        if (isset($data['precio_id'])) {
            $precio = $this->entityManager->getRepository(Precio::class)->find($data['precio_id']);
            if (!$precio) {
                throw new Exception("Precio con ID {$data['precio_id']} no encontrado");
            }
            $entity->setPrecio($precio);
        }
        
        // establecer estado
        if (isset($data['estado'])) {
            $estado = strtoupper($data['estado']);
            // validar que el estado sea uno de los permitidos
            if (!in_array($estado, ['VALIDA', 'USADA', 'CANCELADA', 'REEMBOLSADA'])) {
                throw new Exception("Estado inválido. Debe ser 'VALIDA', 'USADA', 'CANCELADA' o 'REEMBOLSADA'");
            }
            $entity->setEstado($estado);
        }
        
        return $entity;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function serializeEntity(object $entity): array {
        // acabar de añadir los datos
        return [
            'id' => $entity->getCodigo(),
            'codigo' => $entity->getCodigo(),
            'pago' => [
                'id' => $entity->getPago()->getId(),
                'cliente' => [
                    'id' => $entity->getPago()->getCliente()->getId(),
                    'nombre' => $entity->getPago()->getCliente()->getNombre(),
                    'email' => $entity->getPago()->getCliente()->getEmail()
                ],
                'fecha' => $entity->getPago()->getFecha()->format('Y-m-d'),
                'total' => $entity->getPago()->getTotal(),
                'metodo' => $entity->getPago()->getMetodo(),
                'referencia' => $entity->getPago()->getReferencia(),
                'estado' => $entity->getPago()->getEstado()
            ],
            'sesion' => [
                'id' => $entity->getSesion()->getId(),
                'concierto' => [
                    'id' => $entity->getSesion()->getConcierto()->getId(),
                    'nombre' => $entity->getSesion()->getConcierto()->getNombre(),
                    'descripcion' => $entity->getSesion()->getConcierto()->getDescripcion(),
                    'imagen' => $entity->getSesion()->getConcierto()->getImagen() ? '/Microservicios/media/' . $entity->getSesion()->getConcierto()->getImagen() : null,
                    'artista' => [
                        'id' => $entity->getSesion()->getConcierto()->getArtista()->getId(),
                        'nombre' => $entity->getSesion()->getConcierto()->getArtista()->getNombre(),
                        'pais' => $entity->getSesion()->getConcierto()->getArtista()->getPais(),
                        'genero' => $entity->getSesion()->getConcierto()->getArtista()->getGenero()
                    ],
                ],
                'lugar' => [
                    'id' => $entity->getSesion()->getLugar()->getId(),
                    'nombre' => $entity->getSesion()->getLugar()->getNombre(),
                    'direccion' => $entity->getSesion()->getLugar()->getDireccion(),
                    'ciudad' => $entity->getSesion()->getLugar()->getCiudad(),
                    'capacidad' => $entity->getSesion()->getLugar()->getCapacidad()
                ],
                'fecha' => $entity->getSesion()->getFecha()->format('Y-m-d'),
                'hora' => $entity->getSesion()->getHora()->format('H:i:s'),
                'estado' => $entity->getSesion()->getEstado()
                
            ],
            'zona' => [
                'id' => $entity->getZona()->getId(),
                'nombre' => $entity->getZona()->getNombre(),
                'descripcion' => $entity->getZona()->getDescripcion()
            ],
            'asiento' => $entity->getAsiento() ? [
                'id' => $entity->getAsiento()->getId(),
                'fila' => $entity->getAsiento()->getFila(),
                'numero' => $entity->getAsiento()->getNumero(),
                'estado' => $entity->getAsiento()->getEstado(),
                'zona' => $entity->getAsiento()->getZona()->getId(),
                'lugar' => $entity->getAsiento()->getLugar()->getId()
            ] : null,
            'fila' => $entity->getFila(),
            'asiento_numero' => $entity->getAsientoNumero(),
            'precio' => [
                'id' => $entity->getPrecio()->getId(),
                'importe' => $entity->getPrecio()->getImporte(),
                'sesion' => $entity->getPrecio()->getSesion()->getId(),
                'zona' => $entity->getPrecio()->getZona()->getId()
            ],
            'estado' => $entity->getEstado()
        ];
    }
    
    /**
     * buscar entradas por sesión
     *
     * @param int $sesionId id de la sesión
     */
    public function getEntradasBySesion(int $sesionId): void {
        $entradas = $this->repository->findBy(['sesion' => $sesionId]);
        $data = [];
        
        foreach ($entradas as $entrada) {
            $data[] = $this->serializeEntity($entrada);
        }
        
        echo json_encode($data);
    }
    
    /**
     * buscar entradas por pago
     *
     * @param int $pagoId id del pago
     */
    public function getEntradasByPago(int $pagoId): void {
        $entradas = $this->repository->findBy(['pago' => $pagoId]);
        $data = [];
        
        foreach ($entradas as $entrada) {
            $data[] = $this->serializeEntity($entrada);
        }
        
        echo json_encode($data);
    }
    
    /**
     * buscar entradas por zona y sesión
     *
     * @param int $zonaId id de la zona
     * @param int $sesionId id de la sesión
     */
    public function getEntradasByZonaAndSesion(int $zonaId, int $sesionId): void {
        $entradas = $this->repository->findBy(['zona' => $zonaId, 'sesion' => $sesionId]);
        $data = [];
        
        foreach ($entradas as $entrada) {
            $data[] = $this->serializeEntity($entrada);
        }
        
        echo json_encode($data);
    }
    
    /**
     * buscar entradas por estado
     *
     * @param string $estado Estado de la entrada
     */
    public function getEntradasByEstado(string $estado): void {
        $estado = strtoupper($estado);
        
        // valida que el estado sea uno de los permitidos
        if (!in_array($estado, ['VALIDA', 'USADA', 'CANCELADA', 'REEMBOLSADA'])) {
            http_response_code(400);
            echo json_encode(['error' => "Estado inválido. Debe ser 'VALIDA', 'USADA', 'CANCELADA' o 'REEMBOLSADA'"]);
            return;
        }
        
        $entradas = $this->repository->findBy(['estado' => $estado]);
        $data = [];
        
        foreach ($entradas as $entrada) {
            $data[] = $this->serializeEntity($entrada);
        }
        
        echo json_encode($data);
    }
    
    /**
     * cambia el estado de una entrada
     *
     * @param string $codigo código de la entrada
     * @param string $estado nuevo estado
     */
    public function cambiarEstado(string $codigo, string $estado): void {
        
        $estado = strtoupper($estado);
        
        // valida que el estado ses uno de los permitidos
        if (!in_array($estado, ['VALIDA', 'USADA', 'CANCELADA', 'REEMBOLSADA'])) {
            http_response_code(400);
            echo json_encode(['error' => "Estado inválido. Debe ser 'VALIDA', 'USADA', 'CANCELADA' o 'REEMBOLSADA'"]);
            return;
        }
        
        $entrada = $this->repository->find($codigo);
        
        if (!$entrada) {
            http_response_code(404);
            echo json_encode(['error' => 'Entrada no encontrada']);
            return;
        }
        
        try {
            $entrada->setEstado($estado);
            $this->entityManager->flush();
            
            echo json_encode([
                'message' => 'Estado de la entrada actualizado con éxito',
                'entrada' => $this->serializeEntity($entrada)
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar el estado: ' . $e->getMessage()]);
        }
    }
    
    /**
     * valida una entrada (cambiar estado a USADA)
     *
     * @param string $codigo código de la entrada
     */
    public function validarEntrada(string $codigo): void {
        
        $entrada = $this->repository->find($codigo);
        
        if (!$entrada) {
            http_response_code(404);
            echo json_encode(['error' => 'Entrada no encontrada']);
            return;
        }
        
        // verifica si la entrada ya está usada o cancelada
        if ($entrada->getEstado() === 'USADA') {
            http_response_code(400);
            echo json_encode(['error' => 'La entrada ya ha sido validada previamente']);
            return;
        }
        
        if ($entrada->getEstado() === 'CANCELADA' || $entrada->getEstado() === 'REEMBOLSADA') {
            http_response_code(400);
            echo json_encode(['error' => 'No se puede validar una entrada cancelada o reembolsada']);
            return;
        }
        
        try {
            $entrada->setEstado('USADA');
            $this->entityManager->flush();
            
            echo json_encode([
                'message' => 'Entrada validada con éxito',
                'entrada' => $this->serializeEntity($entrada)
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al validar la entrada: ' . $e->getMessage()]);
        }
    }
    
    /**
     * cancelar una entrada
     *
     * @param string $codigo código de la entrada
     */
    public function cancelarEntrada(string $codigo): void {
        
        $entrada = $this->repository->find($codigo);
        
        if (!$entrada) {
            http_response_code(404);
            echo json_encode(['error' => 'Entrada no encontrada']);
            return;
        }
        
        // verifica si la entrada ya está usada
        if ($entrada->getEstado() === 'USADA') {
            http_response_code(400);
            echo json_encode(['error' => 'No se puede cancelar una entrada ya utilizada']);
            return;
        }
        
        // verifica si la entrada ya está cancelada o reeembolsada
        if ($entrada->getEstado() === 'CANCELADA' || $entrada->getEstado() === 'REEMBOLSADA') {
            http_response_code(400);
            echo json_encode(['error' => 'La entrada ya ha sido cancelada o reembolsada']);
            return;
        }
        
        try {
            $entrada->setEstado('CANCELADA');
            $this->entityManager->flush();
            
            echo json_encode([
                'message' => 'Entrada cancelada con éxito',
                'entrada' => $this->serializeEntity($entrada)
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al cancelar la entrada: ' . $e->getMessage()]);
        }
    }
    
    /**
     * crea múltiples entradas a la vez
     */
    public function crearMultiplesEntradas(): void {
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data) || !isset($data['entradas']) || !is_array($data['entradas'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos inválidos o faltantes']);
            return;
        }
        
        try {
            $this->entityManager->beginTransaction();
            $entradasCreadas = [];
            
            foreach ($data['entradas'] as $entradaData) {
                // validar datos mínimos requeridos
                if (!isset($entradaData['pago_id']) || !isset($entradaData['sesion_id']) ||
                    !isset($entradaData['zona_id']) || !isset($entradaData['precio_id'])) {
                        throw new Exception("Datos incompletos para alguna entrada");
                    }
                    
                    // crear nueva entrada
                    $entrada = $this->createEntityFromData($entradaData);
                    $this->entityManager->persist($entrada);
                    $this->entityManager->flush(); // flush para que se genere el código
                    
                    $entradasCreadas[] = $this->serializeEntity($entrada);
            }
            
            $this->entityManager->commit();
            
            http_response_code(201);
            echo json_encode([
                'message' => 'Entradas creadas con éxito',
                'entradas' => $entradasCreadas
            ]);
        } catch (Exception $e) {
            $this->entityManager->rollback();
            http_response_code(500);
            echo json_encode([
                'error' => 'Error al crear entradas: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    
    /**
     * buscar una entrada por código
     * al principio lo pensé y después me di cuenta que es absurdo, pero aquí se queda
     *
     * @param string $codigo código de la entrada
     */
    public function getEntradaByCodigo(string $codigo): void {
        $this->get($codigo);
    }
    
}