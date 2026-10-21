<?php

use Entradas\Entity\Zona;
use Doctrine\ORM\EntityManager;

class ZonaModel {
    
    private EntityManager $entityManager;
    
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    /**
     * inserta zonas a la base de datos
     *
     * @return array $zonasObj array de zonas
     */
    public function insertarZonas(): array {
        
        $zonas = [
            ['nombre' => 'Pista', 'descripcion' => 'Zona de pie'],
            ['nombre' => 'VIP', 'descripcion' => 'Zona preferente con servicios especiales'],
            ['nombre' => 'Grada', 'descripcion' => 'Asientos numerados en grada'],
            ['nombre' => 'Golden', 'descripcion' => 'Primera fila frente al escenario'],
            ['nombre' => 'Platea', 'descripcion' => 'Asientos centrales con buena visibilidad'],
            ['nombre' => 'Tribuna', 'descripcion' => 'Asientos elevados con visión panorámica'],
            ['nombre' => 'Fan Pit', 'descripcion' => 'Zona exclusiva cerca del escenario'],
            ['nombre' => 'Diamond', 'descripcion' => 'Experiencia premium con meet & greet']
        ];
        
        $zonasObj = [];
        foreach ($zonas as $zonaData) {
            if (!$this->entityManager->getRepository(Zona::class)->findOneBy(['nombre' => $zonaData['nombre']])) {
                $zona = new Zona();
                $zona->setNombre($zonaData['nombre']);
                $zona->setDescripcion($zonaData['descripcion']);
                $this->entityManager->persist($zona);
                $zonasObj[$zonaData['nombre']] = $zona;
            } else {
                $zonasObj[$zonaData['nombre']] = $this->entityManager->getRepository(Zona::class)->findOneBy(['nombre' => $zonaData['nombre']]);
            }
        }
        
        return $zonasObj;
        
    }
    
}

