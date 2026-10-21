<?php

use Entradas\Entity\Precio;
use Entradas\Entity\Zona;
use Doctrine\ORM\EntityManager;

class PrecioModel {
    
    private EntityManager $entityManager;
    
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    /**
     * inserta precios a la base de datos
     *
     * @param array $sesionesObj array de sesiones
     * @param array $zonasObj array de zonas
     * @return array $preciosObj array de precios
     */
    public function insertarPrecios(array $sesionesObj, array $zonasObj): array {
        
        $precios = [
            // sesiones de Gotti A Tour (Tiago PZK)
            ['sesion_id' => 1, 'zona_nombre' => 'Pista', 'importe' => 55.00],
            ['sesion_id' => 1, 'zona_nombre' => 'VIP', 'importe' => 110.00],
            ['sesion_id' => 1, 'zona_nombre' => 'Grada', 'importe' => 40.00],
            ['sesion_id' => 1, 'zona_nombre' => 'Golden', 'importe' => 180.00],
            
            // sesiones de MP3 Tour (Emilia)
            ['sesion_id' => 2, 'zona_nombre' => 'Pista', 'importe' => 60.00],
            ['sesion_id' => 2, 'zona_nombre' => 'VIP', 'importe' => 120.00],
            ['sesion_id' => 2, 'zona_nombre' => 'Grada', 'importe' => 45.00],
            ['sesion_id' => 2, 'zona_nombre' => 'Golden', 'importe' => 200.00],
            
            // sesiones de Alpha Tour (Aitana)
            ['sesion_id' => 3, 'zona_nombre' => 'Pista', 'importe' => 50.00],
            ['sesion_id' => 3, 'zona_nombre' => 'VIP', 'importe' => 105.00],
            ['sesion_id' => 3, 'zona_nombre' => 'Grada', 'importe' => 38.00],
            ['sesion_id' => 3, 'zona_nombre' => 'Golden', 'importe' => 175.00],
            
            // sesiones de Agua Tour (Chanel)
            ['sesion_id' => 4, 'zona_nombre' => 'Pista', 'importe' => 48.00],
            ['sesion_id' => 4, 'zona_nombre' => 'VIP', 'importe' => 100.00],
            ['sesion_id' => 4, 'zona_nombre' => 'Grada', 'importe' => 35.00],
            ['sesion_id' => 4, 'zona_nombre' => 'Golden', 'importe' => 165.00],
            
            // sesiones de Melodifestivalen Tour (Eric Saade)
            ['sesion_id' => 5, 'zona_nombre' => 'Pista', 'importe' => 65.00],
            ['sesion_id' => 5, 'zona_nombre' => 'VIP', 'importe' => 130.00],
            ['sesion_id' => 5, 'zona_nombre' => 'Grada', 'importe' => 50.00],
            ['sesion_id' => 5, 'zona_nombre' => 'Golden', 'importe' => 220.00],
            ['sesion_id' => 5, 'zona_nombre' => 'Fan Pit', 'importe' => 150.00],
            
            // sesiones de ABBA Voyage
            ['sesion_id' => 6, 'zona_nombre' => 'Pista', 'importe' => 60.00],
            ['sesion_id' => 6, 'zona_nombre' => 'VIP', 'importe' => 125.00],
            ['sesion_id' => 6, 'zona_nombre' => 'Grada', 'importe' => 45.00],
            ['sesion_id' => 6, 'zona_nombre' => 'Golden', 'importe' => 210.00],
            ['sesion_id' => 6, 'zona_nombre' => 'Fan Pit', 'importe' => 140.00],
            
            // sesiones de 24K Magic World Tour (Bruno Mars)
            ['sesion_id' => 7, 'zona_nombre' => 'Pista', 'importe' => 58.00],
            ['sesion_id' => 7, 'zona_nombre' => 'VIP', 'importe' => 115.00],
            ['sesion_id' => 7, 'zona_nombre' => 'Grada', 'importe' => 42.00],
            ['sesion_id' => 7, 'zona_nombre' => 'Golden', 'importe' => 190.00],
            
            // sesiones de Chromatica Ball (Lady Gaga)
            ['sesion_id' => 8, 'zona_nombre' => 'Pista', 'importe' => 62.00],
            ['sesion_id' => 8, 'zona_nombre' => 'VIP', 'importe' => 125.00],
            ['sesion_id' => 8, 'zona_nombre' => 'Grada', 'importe' => 48.00],
            ['sesion_id' => 8, 'zona_nombre' => 'Golden', 'importe' => 205.00],
            
            // sesiones de Insomnio Tour (Abraham Mateo)
            ['sesion_id' => 9, 'zona_nombre' => 'Pista', 'importe' => 52.00],
            ['sesion_id' => 9, 'zona_nombre' => 'VIP', 'importe' => 105.00],
            ['sesion_id' => 9, 'zona_nombre' => 'Grada', 'importe' => 40.00],
            ['sesion_id' => 9, 'zona_nombre' => 'Golden', 'importe' => 170.00],
            
            // sesiones de Un Susurro en la Tormenta (La Oreja de Van Gogh)
            ['sesion_id' => 10, 'zona_nombre' => 'Pista', 'importe' => 50.00],
            ['sesion_id' => 10, 'zona_nombre' => 'VIP', 'importe' => 100.00],
            ['sesion_id' => 10, 'zona_nombre' => 'Grada', 'importe' => 38.00],
            ['sesion_id' => 10, 'zona_nombre' => 'Golden', 'importe' => 165.00],
            
            // sesiones de After Hours Tour (The Weeknd)
            ['sesion_id' => 11, 'zona_nombre' => 'Pista', 'importe' => 75.00],
            ['sesion_id' => 11, 'zona_nombre' => 'VIP', 'importe' => 150.00],
            ['sesion_id' => 11, 'zona_nombre' => 'Grada', 'importe' => 60.00],
            ['sesion_id' => 11, 'zona_nombre' => 'Golden', 'importe' => 250.00],
            ['sesion_id' => 11, 'zona_nombre' => 'Diamond', 'importe' => 300.00],
            
            // sesiones de Motomami World Tour (Rosalía)
            ['sesion_id' => 12, 'zona_nombre' => 'Pista', 'importe' => 80.00],
            ['sesion_id' => 12, 'zona_nombre' => 'VIP', 'importe' => 160.00],
            ['sesion_id' => 12, 'zona_nombre' => 'Grada', 'importe' => 65.00],
            ['sesion_id' => 12, 'zona_nombre' => 'Golden', 'importe' => 270.00],
            ['sesion_id' => 12, 'zona_nombre' => 'Diamond', 'importe' => 320.00],
            
            // sesiones de M72 World Tour (Metallica)
            ['sesion_id' => 13, 'zona_nombre' => 'Pista', 'importe' => 85.00],
            ['sesion_id' => 13, 'zona_nombre' => 'VIP', 'importe' => 170.00],
            ['sesion_id' => 13, 'zona_nombre' => 'Grada', 'importe' => 70.00],
            ['sesion_id' => 13, 'zona_nombre' => 'Golden', 'importe' => 280.00],
            ['sesion_id' => 13, 'zona_nombre' => 'Diamond', 'importe' => 350.00],
            
            // sesiones de Gotti A Tour (Tiago PZK) en otras fechas
            ['sesion_id' => 14, 'zona_nombre' => 'Pista', 'importe' => 90.00],
            ['sesion_id' => 14, 'zona_nombre' => 'VIP', 'importe' => 180.00],
            ['sesion_id' => 14, 'zona_nombre' => 'Grada', 'importe' => 75.00],
            ['sesion_id' => 14, 'zona_nombre' => 'Golden', 'importe' => 300.00],
            ['sesion_id' => 14, 'zona_nombre' => 'Diamond', 'importe' => 380.00],
            
            ['sesion_id' => 15, 'zona_nombre' => 'Pista', 'importe' => 95.00],
            ['sesion_id' => 15, 'zona_nombre' => 'VIP', 'importe' => 190.00],
            ['sesion_id' => 15, 'zona_nombre' => 'Grada', 'importe' => 80.00],
            ['sesion_id' => 15, 'zona_nombre' => 'Golden', 'importe' => 320.00],
            ['sesion_id' => 15, 'zona_nombre' => 'Diamond', 'importe' => 400.00],
            
            // sesiones de MP3 Tour (Emilia) en otras fechas
            ['sesion_id' => 16, 'zona_nombre' => 'Pista', 'importe' => 90.00],
            ['sesion_id' => 16, 'zona_nombre' => 'VIP', 'importe' => 180.00],
            ['sesion_id' => 16, 'zona_nombre' => 'Grada', 'importe' => 75.00],
            ['sesion_id' => 16, 'zona_nombre' => 'Golden', 'importe' => 300.00],
            ['sesion_id' => 16, 'zona_nombre' => 'Diamond', 'importe' => 380.00],
            
            ['sesion_id' => 17, 'zona_nombre' => 'Pista', 'importe' => 45.00],
            ['sesion_id' => 17, 'zona_nombre' => 'VIP', 'importe' => 90.00],
            ['sesion_id' => 17, 'zona_nombre' => 'Grada', 'importe' => 35.00],
            ['sesion_id' => 17, 'zona_nombre' => 'Golden', 'importe' => 150.00],
            
            // para el resto de sesiones
            ['sesion_id' => 18, 'zona_nombre' => 'Pista', 'importe' => 48.00],
            ['sesion_id' => 18, 'zona_nombre' => 'VIP', 'importe' => 95.00],
            ['sesion_id' => 18, 'zona_nombre' => 'Grada', 'importe' => 38.00],
            ['sesion_id' => 18, 'zona_nombre' => 'Golden', 'importe' => 160.00],
            
            ['sesion_id' => 19, 'zona_nombre' => 'Pista', 'importe' => 55.00],
            ['sesion_id' => 19, 'zona_nombre' => 'VIP', 'importe' => 110.00],
            ['sesion_id' => 19, 'zona_nombre' => 'Grada', 'importe' => 42.00],
            ['sesion_id' => 19, 'zona_nombre' => 'Golden', 'importe' => 180.00],
            
            ['sesion_id' => 20, 'zona_nombre' => 'Pista', 'importe' => 58.00],
            ['sesion_id' => 20, 'zona_nombre' => 'VIP', 'importe' => 115.00],
            ['sesion_id' => 20, 'zona_nombre' => 'Grada', 'importe' => 45.00],
            ['sesion_id' => 20, 'zona_nombre' => 'Golden', 'importe' => 190.00],
            
            ['sesion_id' => 21, 'zona_nombre' => 'Pista', 'importe' => 100.00],
            ['sesion_id' => 21, 'zona_nombre' => 'VIP', 'importe' => 200.00],
            ['sesion_id' => 21, 'zona_nombre' => 'Grada', 'importe' => 85.00],
            ['sesion_id' => 21, 'zona_nombre' => 'Golden', 'importe' => 350.00],
            ['sesion_id' => 21, 'zona_nombre' => 'Diamond', 'importe' => 450.00],
            
            ['sesion_id' => 22, 'zona_nombre' => 'Pista', 'importe' => 105.00],
            ['sesion_id' => 22, 'zona_nombre' => 'VIP', 'importe' => 210.00],
            ['sesion_id' => 22, 'zona_nombre' => 'Grada', 'importe' => 90.00],
            ['sesion_id' => 22, 'zona_nombre' => 'Golden', 'importe' => 370.00],
            ['sesion_id' => 22, 'zona_nombre' => 'Diamond', 'importe' => 480.00],
            
            ['sesion_id' => 23, 'zona_nombre' => 'Pista', 'importe' => 75.00],
            ['sesion_id' => 23, 'zona_nombre' => 'VIP', 'importe' => 150.00],
            ['sesion_id' => 23, 'zona_nombre' => 'Grada', 'importe' => 60.00],
            ['sesion_id' => 23, 'zona_nombre' => 'Golden', 'importe' => 250.00],
            
            ['sesion_id' => 24, 'zona_nombre' => 'Pista', 'importe' => 80.00],
            ['sesion_id' => 24, 'zona_nombre' => 'VIP', 'importe' => 160.00],
            ['sesion_id' => 24, 'zona_nombre' => 'Grada', 'importe' => 65.00],
            ['sesion_id' => 24, 'zona_nombre' => 'Golden', 'importe' => 270.00],
            
            ['sesion_id' => 25, 'zona_nombre' => 'Pista', 'importe' => 90.00],
            ['sesion_id' => 25, 'zona_nombre' => 'VIP', 'importe' => 180.00],
            ['sesion_id' => 25, 'zona_nombre' => 'Grada', 'importe' => 70.00],
            ['sesion_id' => 25, 'zona_nombre' => 'Golden', 'importe' => 300.00],
            
            ['sesion_id' => 26, 'zona_nombre' => 'Pista', 'importe' => 95.00],
            ['sesion_id' => 26, 'zona_nombre' => 'VIP', 'importe' => 190.00],
            ['sesion_id' => 26, 'zona_nombre' => 'Grada', 'importe' => 75.00],
            ['sesion_id' => 26, 'zona_nombre' => 'Golden', 'importe' => 320.00]
        ];
        
        $preciosObj = [];
        $precioCount = 1;
        foreach ($precios as $precioData) {
            $sesion = $sesionesObj[$precioData['sesion_id']] ?? null;
            $zona = $zonasObj[$precioData['zona_nombre']] ?? $this->entityManager->getRepository(Zona::class)->findOneBy(['nombre' => $precioData['zona_nombre']]);
            
            if ($sesion && $zona) {
                $existePrecio = $this->entityManager->getRepository(Precio::class)->findOneBy([
                    'sesion' => $sesion,
                    'zona' => $zona
                ]);
                
                if (!$existePrecio) {
                    $precio = new Precio();
                    $precio->setSesion($sesion);
                    $precio->setZona($zona);
                    $precio->setImporte($precioData['importe']);
                    $this->entityManager->persist($precio);
                    $preciosObj[$precioCount] = $precio;
                    $precioCount++;
                } else {
                    $preciosObj[$precioCount] = $existePrecio;
                    $precioCount++;
                }
            }
        }
        
        return $preciosObj;
        
    }
    
}

