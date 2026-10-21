<?php

use Doctrine\ORM\EntityManager;

abstract class EntityController {
    
    protected $entityManager;
    protected $repository;
    protected $entityClass;
    protected $useStringId = false; // esto para la Entrada que su "id" es "codigo" que no es int, es string
    
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        $this->setEntityClass();
        $this->repository = $entityManager->getRepository($this->entityClass);
        // que las clases hijas establezcan si usan "id" de tipo string
        $this->configureIdType();
    }
    
    /**
     * establecer la entidad a manejar
     */
    abstract protected function setEntityClass(): void;
    
    /**
     * configurar el tipo de id a usar
     * ! puede ser sobrescrito por clases hijas
     */
    protected function configureIdType(): void {
        $this->useStringId = false;
    }
    
    /**
     * obtener todos los registros de la entidad (un GET que devuelve todo)
     */
    public function getAll(): void {
        $entities = $this->repository->findAll();
        $data = [];
        
        foreach ($entities as $entity) {
            $data[] = $this->serializeEntity($entity);
        }
        
        echo json_encode($data);
    }
    
    /**
     * obtener un registro por id
     *
     * @param mixed $id id del registro (puede ser int o string)
     */
    public function get($id): void {
        // convertir ID a string si es necesario
        if (!$this->useStringId && is_string($id) && is_numeric($id)) {
            $id = intval($id);
        }
        
        $entity = $this->repository->find($id);
        
        if (!$entity) {
            http_response_code(404);
            echo json_encode(['error' => 'Registro no encontrado']);
            return;
        }
        
        echo json_encode($this->serializeEntity($entity));
    }
    
    /**
     * crear un nuevo registro
     */
    public function create(): void {
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
        
        // verificar si la solicitud es multipart/form-data para manejo de files (esto en el caso de pasarle imagen)
        if (strpos($contentType, 'multipart/form-data') !== false) {
            // para solicitudes con files, usar $_POST para datos y $_FILES para archivos
            $data = $_POST;
            // en el caso de necesitar procesar $_FILES, se tiene que hacer en la clase hija
        } else {
            // para solicitudes con body raw JSON normales
            $data = json_decode(file_get_contents('php://input'), true);
        }
        
        if (empty($data) && empty($_FILES)) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos inválidos o faltantes']);
            return;
        }
        
        try {
            $entity = $this->createEntityFromData($data);
            
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
            
            http_response_code(201);
            echo json_encode([
                'message' => 'Registro creado con éxito',
                'id' => method_exists($entity, 'getId') ? $entity->getId() :
                (method_exists($entity, 'getCodigo') ? $entity->getCodigo() : null)
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Error al crear: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    
    /**
     * actualizar un registro existente
     *
     * @param mixed $id id del registro a actualizar (puede ser int o string)
     */
    public function update($id): void {
        // convertir id a int si es necesario
        if (!$this->useStringId && is_string($id) && is_numeric($id)) {
            $id = intval($id);
        }
        
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
        $method = $_SERVER['REQUEST_METHOD'];
        $data = [];
        
        // verificar si la solicitud es multipart/form-data para manejo de files, por si hay imagen
        if (strpos($contentType, 'multipart/form-data') !== false) {
            // el PUT necesita ajustes especiales con las imágenes
            if ($method === 'PUT') {
                // leer los datos del form-data para PUT
                parse_str(file_get_contents('php://input'), $putData);
                $data = $putData;
                
                if (isset($_FILES) && !empty($_FILES)) {
                    error_log("Archivos detectados en PUT request: " . json_encode($_FILES));
                } else {
                    error_log("No se detectaron archivos en PUT request, intentando procesar manualmente");
                    $this->procesarFormDataPut();
                }
            } else {
                $data = $_POST;
            }
        } else {
            // para solicitudes body raw JSON normales
            $data = json_decode(file_get_contents('php://input'), true);
        }
        
        // erroess
        error_log("Método: " . $method);
        error_log("Content-Type: " . $contentType);
        error_log("Datos recibidos: " . json_encode($data));
        error_log("FILES: " . json_encode($_FILES));
        
        // si no hay datos ni hay archivos, fuera
        if (empty($data) && empty($_FILES)) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos inválidos o faltantes']);
            return;
        }
        
        $entity = $this->repository->find($id);
        
        if (!$entity) {
            http_response_code(404);
            echo json_encode(['error' => 'Registro no encontrado']);
            return;
        }
        
        try {
            $entity = $this->updateEntityFromData($entity, $data);
            
            $this->entityManager->flush();
            
            echo json_encode([
                'message' => 'Registro actualizado con éxito'
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Error al actualizar: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    
    /**
     * procesa manualmente los datos de form-data en solicitudes PUT, intenta extraer tanto campos normales como archivos
     */
    private function procesarFormDataPut(): void {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        // solo procesa si realmente es multipart/form-data
        if (strpos($contentType, 'multipart/form-data') === false) {
            return;
        }
        
        // obtener el boundary del Content-Type
        if (preg_match('/boundary=(.*)$/', $contentType, $matches)) {
            $boundary = $matches[1];
            
            // leer los datos enviados
            $putdata = file_get_contents('php://input');
            
            // dividir el contenido por el boundary
            $blocks = preg_split("/-+$boundary/", $putdata);
            
            // el primer bloque es vacío, eliminarlo
            array_shift($blocks);
            
            // el último bloque termina con boundary--
            $lastBlock = array_pop($blocks);
            
            // procesar cada bloque
            foreach ($blocks as $block) {
                if (empty($block)) {
                    continue;
                }
                
                // separar headers y contenido
                if (preg_match('/Content-Disposition: form-data; name="([^"]+)"(?:; filename="([^"]+)")?(?:\r\n|\n)(?:Content-Type: ([^\r\n]+))?(?:\r\n|\n){2}([\s\S]*)(?:\r\n|\n)/', $block, $matches)) {
                    $name = $matches[1];
                    $filename = $matches[2] ?? null;
                    $contentType = $matches[3] ?? null;
                    $content = $matches[4];
                    
                    if ($filename !== null) {
                        // ss un archivo
                        $tempFile = tempnam(sys_get_temp_dir(), 'upload_');
                        file_put_contents($tempFile, $content);
                        
                        // crear entrada en $_FILES simulando una subida normal
                        $_FILES[$name] = [
                            'name' => $filename,
                            'type' => $contentType,
                            'tmp_name' => $tempFile,
                            'error' => UPLOAD_ERR_OK,
                            'size' => strlen($content)
                        ];
                        
                        error_log("Archivo procesado manualmente: $name, $filename, $tempFile");
                    } else {
                        // es un campo normal
                        $_POST[$name] = $content;
                        error_log("Campo procesado manualmente: $name = $content");
                    }
                }
            }
        }
    }
    
    /**
     * eliminar un registro
     *
     * @param mixed $id id del registro a eliminar (puede ser int o string)
     */
    public function delete($id): void {
        // convertir id a int si es Entrada
        if (!$this->useStringId && is_string($id) && is_numeric($id)) {
            $id = intval($id);
        }
        
        $entity = $this->repository->find($id);
        
        if (!$entity) {
            http_response_code(404);
            echo json_encode(['error' => 'Registro no encontrado']);
            return;
        }
        
        try {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
            
            echo json_encode([
                'message' => 'Registro eliminado con éxito'
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al eliminar: ' . $e->getMessage()]);
        }
    }
    
    /**
     * crear una entidad a partir de los datos proporcionados
     *
     * @param array $data datos para crear la entidad
     * @return object entidad creada
     */
    abstract protected function createEntityFromData(array $data): object;
    
    /**
     * actualizar una entidad con los datos proporcionados
     *
     * @param object $entity entidad a actualizar
     * @param array $data datos para la actualización
     * @return object entidad actualizada
     */
    abstract protected function updateEntityFromData(object $entity, array $data): object;
    
    /**
     * serializar una entidad para la respuesta JSON
     *
     * @param object $entity entidad a serializar
     * @return array datos serializados
     */
    abstract protected function serializeEntity(object $entity): array;
}