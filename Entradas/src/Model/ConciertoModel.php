<?php

use Entradas\Entity\Concierto;
use Entradas\Entity\Artista;
use Doctrine\ORM\EntityManager;

class ConciertoModel {
    
    private EntityManager $entityManager;
    
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    /**
     * inserta conciertos a la base de datos
     *
     * @return array $conciertosObj array de conciertos
     */
    public function insertarConciertos(): array {
        
        $conciertos = [
            ['nombre' => 'Gotti A Tour', 'descripcion' => 'Gira internacional de Tiago PZK con sus grandes éxitos', 'artista_nombre' => 'Tiago PZK', 'imagen' => 'tiago.png'],
            ['nombre' => 'MP3 Tour', 'descripcion' => 'Gira mundial de Emilia presentando su nuevo álbum', 'artista_nombre' => 'Emilia', 'imagen' => 'emilia.png'],
            ['nombre' => 'Alpha Tour', 'descripcion' => 'Gira de Aitana con su último álbum', 'artista_nombre' => 'Aitana', 'imagen' => 'aitana.png'],
            ['nombre' => 'Agua Tour', 'descripcion' => 'Gira de Chanel tras su primer disco', 'artista_nombre' => 'Chanel', 'imagen' => 'chanel.png'],
            ['nombre' => 'Melodifestivalen Tour', 'descripcion' => 'Eric Saade en gira por Europa', 'artista_nombre' => 'Eric Saade', 'imagen' => 'eric.png'],
            ['nombre' => 'ABBA Voyage', 'descripcion' => 'Espectáculo conmemorativo de ABBA', 'artista_nombre' => 'ABBA', 'imagen' => 'abba.png'],
            ['nombre' => '24K Magic World Tour', 'descripcion' => 'Bruno Mars en gira mundial', 'artista_nombre' => 'Bruno Mars', 'imagen' => 'bruno.png'],
            ['nombre' => 'Chromatica Ball', 'descripcion' => 'Lady Gaga presenta su álbum Chromatica', 'artista_nombre' => 'Lady Gaga', 'imagen' => 'gaga.png'],
            ['nombre' => 'Insomnio Tour', 'descripcion' => 'Abraham Mateo en gira con su nuevo álbum', 'artista_nombre' => 'Abraham Mateo', 'imagen' => 'abraham.png'],
            ['nombre' => 'Un Susurro en la Tormenta', 'descripcion' => 'La Oreja de Van Gogh en gira por España', 'artista_nombre' => 'La Oreja de Van Gogh', 'imagen' => 'gogh.png'],
            ['nombre' => 'After Hours Tour', 'descripcion' => 'Gira mundial de The Weeknd', 'artista_nombre' => 'The Weeknd', 'imagen' => 'weeknd.png'],
            ['nombre' => 'Motomami World Tour', 'descripcion' => 'Gira internacional de Rosalía', 'artista_nombre' => 'Rosalía', 'imagen' => 'rosalia.png'],
            ['nombre' => 'M72 World Tour', 'descripcion' => 'Gira mundial de Metallica', 'artista_nombre' => 'Metallica', 'imagen' => 'metallica.png']
        ];
        
        $conciertosObj = [];
        foreach ($conciertos as $conciertoData) {
            if (!$this->entityManager->getRepository(Concierto::class)->findOneBy(['nombre' => $conciertoData['nombre']])) {
                $artista = $this->entityManager->getRepository(Artista::class)->findOneBy(['nombre' => $conciertoData['artista_nombre']]);
                
                if ($artista) {
                    $concierto = new Concierto();
                    $concierto->setNombre($conciertoData['nombre']);
                    $concierto->setDescripcion($conciertoData['descripcion']);
                    $concierto->setArtista($artista);
                    $concierto->setImagen($conciertoData['imagen']);
                    $this->entityManager->persist($concierto);
                    $conciertosObj[$conciertoData['nombre']] = $concierto;
                }
            } else {
                $conciertosObj[$conciertoData['nombre']] = $this->entityManager->getRepository(Concierto::class)->findOneBy(['nombre' => $conciertoData['nombre']]);
            }
        }
        
        return $conciertosObj;
        
    }   
}