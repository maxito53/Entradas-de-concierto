<?php

use Entradas\Entity\Artista;
use Doctrine\ORM\EntityManager;

class ArtistaModel {
    
    private EntityManager $entityManager;
    
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    /**
     * inserta artistas a la base de datos
     * 
     * @return void
     */
    public function insertarArtistas(): void {
        
        $artistas = [
            ['nombre' => 'Tiago PZK', 'pais' => 'Argentina', 'genero' => 'Urbano/R&B'],
            ['nombre' => 'Emilia', 'pais' => 'Argentina', 'genero' => 'Pop/Urbano'],
            ['nombre' => 'Aitana', 'pais' => 'España', 'genero' => 'Pop'],
            ['nombre' => 'Chanel', 'pais' => 'España', 'genero' => 'Pop/Dance'],
            ['nombre' => 'Eric Saade', 'pais' => 'Suecia', 'genero' => 'Pop'],
            ['nombre' => 'ABBA', 'pais' => 'Suecia', 'genero' => 'Pop/Disco'],
            ['nombre' => 'Bruno Mars', 'pais' => 'Estados Unidos', 'genero' => 'Pop/R&B/Funk'],
            ['nombre' => 'Lady Gaga', 'pais' => 'Estados Unidos', 'genero' => 'Pop/Dance'],
            ['nombre' => 'Abraham Mateo', 'pais' => 'España', 'genero' => 'Pop/Urbano'],
            ['nombre' => 'La Oreja de Van Gogh', 'pais' => 'España', 'genero' => 'Pop/Rock'],
            ['nombre' => 'The Weeknd', 'pais' => 'Canadá', 'genero' => 'R&B/Pop'],
            ['nombre' => 'Rosalía', 'pais' => 'España', 'genero' => 'Flamenco/Urbano'],
            ['nombre' => 'Metallica', 'pais' => 'Estados Unidos', 'genero' => 'Heavy Metal']
        ];
        
        foreach ($artistas as $artistaData) {
            if (!$this->entityManager->getRepository(Artista::class)->findOneBy(['nombre' => $artistaData['nombre']])) {
                $artista = new Artista();
                $artista->setNombre($artistaData['nombre']);
                $artista->setPais($artistaData['pais']);
                $artista->setGenero($artistaData['genero']);
                $this->entityManager->persist($artista);
            }
        }
        
    }
    
}

