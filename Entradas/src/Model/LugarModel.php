<?php
use Entradas\Entity\Lugar;
use Doctrine\ORM\EntityManager;

class LugarModel {
    
    private EntityManager $entityManager;
    
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    /**
     * inserta lugares a la base de datos
     *
     * @return array $lugaresObj array de lugares
     */
    public function insertarLugares(): array {
        
        $lugares = [
            ['nombre' => 'Palau Sant Jordi', 'direccion' => 'Passeig Olímpic, 5-7', 'ciudad' => 'Barcelona', 'capacidad' => 17000],
            ['nombre' => 'WiZink Center', 'direccion' => 'Av. Felipe II, s/n', 'ciudad' => 'Madrid', 'capacidad' => 15500],
            ['nombre' => 'Estadio La Cartuja', 'direccion' => 'Isla de la Cartuja', 'ciudad' => 'Sevilla', 'capacidad' => 60000],
            ['nombre' => 'Estadio Santiago Bernabéu', 'direccion' => 'Av. de Concha Espina, 1', 'ciudad' => 'Madrid', 'capacidad' => 81044],
            ['nombre' => 'Palacio de Deportes José María Martín Carpena', 'direccion' => 'C. Miguel de Mérida Nicolich, 2', 'ciudad' => 'Málaga', 'capacidad' => 10000],
            ['nombre' => 'Coliseum A Coruña', 'direccion' => 'Av. Alfonso Molina, s/n', 'ciudad' => 'A Coruña', 'capacidad' => 15000],
            ['nombre' => 'Plaza de Toros de Valencia', 'direccion' => 'C. Xàtiva, 28', 'ciudad' => 'Valencia', 'capacidad' => 12000],
            ['nombre' => 'Estadio Olímpico de la Cartuja', 'direccion' => 'Isla de la Cartuja', 'ciudad' => 'Sevilla', 'capacidad' => 57000],
            ['nombre' => 'Wizink Center', 'direccion' => 'Av. Felipe II, s/n', 'ciudad' => 'Madrid', 'capacidad' => 15500],
            ['nombre' => 'Palacio de Vistalegre', 'direccion' => 'C. Utebo, 1', 'ciudad' => 'Madrid', 'capacidad' => 14000]
        ];
        
        $lugaresObj = [];
        foreach ($lugares as $lugarData) {
            if (!$this->entityManager->getRepository(Lugar::class)->findOneBy(['nombre' => $lugarData['nombre']])) {
                $lugar = new Lugar();
                $lugar->setNombre($lugarData['nombre']);
                $lugar->setDireccion($lugarData['direccion']);
                $lugar->setCiudad($lugarData['ciudad']);
                $lugar->setCapacidad($lugarData['capacidad']);
                $this->entityManager->persist($lugar);
                $lugaresObj[$lugarData['nombre']] = $lugar;
            } else {
                $lugaresObj[$lugarData['nombre']] = $this->entityManager->getRepository(Lugar::class)->findOneBy(['nombre' => $lugarData['nombre']]);
            }
        }
        
        return $lugaresObj;
    }
    
}