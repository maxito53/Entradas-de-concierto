<?php

use Entradas\Entity\Concierto;
use Entradas\Entity\Artista;
use Doctrine\ORM\EntityManager;

class ConciertoController extends EntityController {
    
    protected $mediaDir = __DIR__ . '/../../media/';
    
    /**
     * {@inheritdoc}
     */
    protected function setEntityClass(): void {
        $this->entityClass = Concierto::class;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function createEntityFromData(array $data): object {
        
        $concierto = new Concierto();
        
        // pasar los datos básicos primero
        if (isset($data['nombre'])) {
            $concierto->setNombre($data['nombre']);
        }
        
        if (isset($data['descripcion'])) {
            $concierto->setDescripcion($data['descripcion']);
        }
        
        // manejo de la referencia al artista
        if (isset($data['artista_id'])) {
            $artista = $this->entityManager->getRepository(Artista::class)->find($data['artista_id']);
            if (!$artista) {
                throw new Exception("Artista con ID {$data['artista_id']} no encontrado");
            }
            $concierto->setArtista($artista);
        } else {
            throw new Exception("El campo artista_id es obligatorio para crear un concierto");
        }
        
        // verificar y procesar la imagen si se proporciona
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            try {
                $nombreImagen = $this->procesarImagen($_FILES['imagen']);
                $concierto->setImagen($nombreImagen);
            } catch (Exception $e) {
                error_log("Error al procesar imagen: " . $e->getMessage());
                throw $e;
            }
        }
        
        return $concierto;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function updateEntityFromData(object $entity, array $data): object {
        
        error_log("updateEntityFromData recibió: " . json_encode($data));
        error_log("FILES disponibles: " . json_encode($_FILES));
        
        if (isset($data['nombre'])) {
            $entity->setNombre($data['nombre']);
        }
        
        if (isset($data['descripcion'])) {
            $entity->setDescripcion($data['descripcion']);
        }
        
        // manejo de la referenca al artista
        if (isset($data['artista_id'])) {
            $artista = $this->entityManager->getRepository(Artista::class)->find($data['artista_id']);
            if (!$artista) {
                throw new Exception("Artista con ID {$data['artista_id']} no encontrado");
            }
            $entity->setArtista($artista);
        }
        
        // procesar la imagen si se proporciona en una actualización
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            
            try {
                error_log("Procesando archivo de imagen en la actualización");
                
                // si hay una imagen previa, eliminarla
                if ($entity->getImagen() !== null) {
                    $this->eliminarImagen($entity->getImagen());
                }
                
                $nombreImagen = $this->procesarImagen($_FILES['imagen']);
                $entity->setImagen($nombreImagen);
                
                error_log("Imagen procesada con éxito: " . $nombreImagen);
            } catch (Exception $e) {
                error_log("Error al procesar imagen en actualización: " . $e->getMessage());
                throw $e;
            }
            
        } elseif (isset($data['imagen']) && is_string($data['imagen'])) {
            // si se envía el nombre de la imagen directamente en los datos
            error_log("Asignando nombre de imagen directamente: " . $data['imagen']);
            $entity->setImagen($data['imagen']);
        }
        
        return $entity;
    }
    
    /**
     * elimina el registro y su imagen asociada
     *
     * @param int $id id del registro a eliminar
     */
    public function delete(int $id): void {
        
        $entity = $this->repository->find($id);
        
        if (!$entity) {
            http_response_code(404);
            echo json_encode(['error' => 'Registro no encontrado']);
            return;
        }
        
        try {
            // eliminar la imagen si existe
            if ($entity->getImagen() !== null) {
                $this->eliminarImagen($entity->getImagen());
            }
            
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
     * {@inheritdoc}
     */
    protected function serializeEntity(object $entity): array {
        $artista = $entity->getArtista();
        
        return [
            'id' => $entity->getId(),
            'nombre' => $entity->getNombre(),
            'descripcion' => $entity->getDescripcion(),
            'artista_id' => $artista->getId(),
            'artista_nombre' => $artista->getNombre(),
            'imagen' => $entity->getImagen(),
            'imagen_url' => $entity->getImagen() ? '/Microservicios/media/' . $entity->getImagen() : null
        ];
    }
    
    /**
     * procesa la imagen subida y la guarda en el directorio media
     *
     * @param array $fileInfo información del archivo subido ($_FILES['imagen'])
     * @return string nombre del archivo guardado
     * @throws Exception si hay un error al subir la imagen
     */
    private function procesarImagen(array $fileInfo): string
    {
        // información detallada de depuración
        error_log("Procesando imagen: " . json_encode($fileInfo));
        
        // validaciones para la imagen
        if (!isset($fileInfo['tmp_name']) || empty($fileInfo['tmp_name'])) {
            throw new Exception("No se recibió ningún archivo");
        }
        
        if (!file_exists($fileInfo['tmp_name'])) {
            throw new Exception("El archivo temporal no existe: " . $fileInfo['tmp_name']);
        }
        
        // validación de tipo de archivo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($fileInfo['tmp_name']);
        
        error_log("Tipo MIME detectado: " . $mimeType);
        
        if (!in_array($mimeType, $allowedTypes)) {
            throw new \Exception("Tipo de archivo no permitido. Se permite: " . implode(', ', $allowedTypes));
        }
        
        // verificar que el directorio media existe, si no, lo creaa
        if (!is_dir($this->mediaDir)) {
            error_log("Creando directorio de medios: " . $this->mediaDir);
            if (!mkdir($this->mediaDir, 0755, true)) {
                throw new \Exception("No se pudo crear el directorio de medios");
            }
        }
        
        // verifica si el directorio es escribible
        if (!is_writable($this->mediaDir)) {
            throw new \Exception("No se puede escribir en el directorio destino: {$this->mediaDir}");
        }
        
        // generar nombre único
        $extension = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
        if (empty($extension)) {
            // si no hay extensión, intentar determinarla por el MIME type
            $extensions = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp'
            ];
            $extension = $extensions[$mimeType] ?? 'jpg';
        }
        
        $nombreBase = pathinfo($fileInfo['name'], PATHINFO_FILENAME);
        if (empty($nombreBase)) {
            $nombreBase = 'image';
        }
        
        // sanitiza el nombre del archivo
        $nombreBase = str_replace(' ', '_', $nombreBase);
        $nombreBase = preg_replace('/[^a-zA-Z0-9_-]/', '', $nombreBase);
        $nombreImagen = $nombreBase . '_' . uniqid() . '.' . $extension;
        $rutaDestino = $this->mediaDir . $nombreImagen;
        
        error_log("Ruta destino para la imagen: " . $rutaDestino);
        
        // usa copy para evitar problemas que me estaban dando con is_uploaded_file
        // primero prueba con move_uploaded_file por seguridad si esta en un contexto POST
        $metodo = $_SERVER['REQUEST_METHOD'] ?? '';
        
        if ($metodo === 'POST' && is_uploaded_file($fileInfo['tmp_name'])) {
            error_log("Usando move_uploaded_file para método " . $metodo);
            $resultado = move_uploaded_file($fileInfo['tmp_name'], $rutaDestino);
        } else {
            error_log("Usando copy para método " . $metodo);
            $resultado = copy($fileInfo['tmp_name'], $rutaDestino);
        }
        
        if (!$resultado) {
            throw new Exception("Error al copiar la imagen desde " . $fileInfo['tmp_name'] . " a " . $rutaDestino);
        }
        
        error_log("Imagen guardada exitosamente: " . $nombreImagen);
        return $nombreImagen;
    }
    
    /**
     * elimina una imagen del directorio media
     *
     * @param string $nombreImagen nombre del archivo a eliminar
     * @return bool true si se eliminó correctamente, false si no
     */
    private function eliminarImagen(string $nombreImagen): bool {
        $rutaImagen = $this->mediaDir . $nombreImagen;
        if (file_exists($rutaImagen)) {
            return unlink($rutaImagen);
        }
        return false;
    }
    
}