<?php

use Entradas\Entity\Sesion;
use Entradas\Entity\Concierto;
use Entradas\Entity\Lugar;
use DateTime;
use Doctrine\ORM\EntityManager;

class SesionModel {
    
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * busca todas las sesiones programadas para una fecha específica
     * 
     * @param string $fecha fecha en formato YYYY-MM-DD
     * @return array array de sesiones que se encontraron en la db
     */
    public function buscarPorFecha(string $fecha): array {
        $fechaObj = new DateTime($fecha);
        
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('s')
           ->from(Sesion::class, 's')
           ->where('s.fecha = :fecha')
           ->setParameter('fecha', $fechaObj->format('Y-m-d'))
           ->orderBy('s.hora', 'ASC');
        
        return $qb->getQuery()->getResult();
    }

    /**
     * obtiene datos completos de todas las sesiones de una fecha incluyendo información de conciertos, artistas, lugares y precios
     * 
     * @param string $fecha fecha en formato YYYY-MM-DD
     * @return array información completa de los eventos de esa fecha
     */
    public function obtenerEventosPorFecha(string $fecha): array {
        $sesiones = $this->buscarPorFecha($fecha);
        $eventos = [];
        
        foreach ($sesiones as $sesion) {
            $concierto = $sesion->getConcierto();
            $artista = $concierto->getArtista();
            $lugar = $sesion->getLugar();
            $precios = [];
            
            foreach ($sesion->getPrecios() as $precio) {
                $precios[] = [
                    'zona' => $precio->getZona()->getNombre(),
                    'descripcion' => $precio->getZona()->getDescripcion(),
                    'importe' => $precio->getImporte()
                ];
            }
            
            $eventos[] = [
                'id' => $sesion->getId(),
                'titulo' => $concierto->getNombre(),
                'descripcion' => $concierto->getDescripcion(),
                'artista' => $artista->getNombre(),
                'genero' => $artista->getGenero(),
                'fecha' => $sesion->getFecha()->format('Y-m-d'),
                'hora' => $sesion->getHora()->format('H:i'),
                'lugar' => [
                    'nombre' => $lugar->getNombre(),
                    'direccion' => $lugar->getDireccion(),
                    'ciudad' => $lugar->getCiudad(),
                    'capacidad' => $lugar->getCapacidad()
                ],
                'estado' => $sesion->getEstado(),
                'precios' => $precios
            ];
        }
        
        return $eventos;
    }
    
    /**
     * inserta sesiones a la base de datos
     *
     * @param array $conciertosObj array de conciertos
     * @return array $sesionesObj array de sesiones
     */
    public function insertarSesiones(array $conciertosObj): array {
        
        $sesiones = [
            // Mayo 10
            ['concierto_nombre' => 'Gotti A Tour', 'lugar_nombre' => 'Palau Sant Jordi', 'fecha' => '2025-05-10', 'hora' => '21:00:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => 'MP3 Tour', 'lugar_nombre' => 'WiZink Center', 'fecha' => '2025-05-10', 'hora' => '20:30:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => 'Alpha Tour', 'lugar_nombre' => 'Estadio Santiago Bernabéu', 'fecha' => '2025-05-10', 'hora' => '22:00:00', 'estado' => 'PROGRAMADO'],
            
            // Mayo 15
            ['concierto_nombre' => 'Agua Tour', 'lugar_nombre' => 'Plaza de Toros de Valencia', 'fecha' => '2025-05-15', 'hora' => '20:00:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => 'Melodifestivalen Tour', 'lugar_nombre' => 'Estadio Olímpico de la Cartuja', 'fecha' => '2025-05-15', 'hora' => '21:30:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => 'ABBA Voyage', 'lugar_nombre' => 'WiZink Center', 'fecha' => '2025-05-15', 'hora' => '19:30:00', 'estado' => 'PROGRAMADO'],
            
            // Mayo 20
            ['concierto_nombre' => '24K Magic World Tour', 'lugar_nombre' => 'Estadio La Cartuja', 'fecha' => '2025-05-20', 'hora' => '20:00:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => 'Chromatica Ball', 'lugar_nombre' => 'Estadio Santiago Bernabéu', 'fecha' => '2025-05-20', 'hora' => '21:30:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => 'Insomnio Tour', 'lugar_nombre' => 'Palacio de Deportes José María Martín Carpena', 'fecha' => '2025-05-20', 'hora' => '19:00:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => 'Un Susurro en la Tormenta', 'lugar_nombre' => 'Coliseum A Coruña', 'fecha' => '2025-05-20', 'hora' => '22:00:00', 'estado' => 'PROGRAMADO'],
            
            // Junio 10
            ['concierto_nombre' => 'After Hours Tour', 'lugar_nombre' => 'Wizink Center', 'fecha' => '2025-06-10', 'hora' => '20:30:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => 'Motomami World Tour', 'lugar_nombre' => 'Estadio La Cartuja', 'fecha' => '2025-06-10', 'hora' => '21:00:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => 'M72 World Tour', 'lugar_nombre' => 'Palau Sant Jordi', 'fecha' => '2025-06-10', 'hora' => '19:00:00', 'estado' => 'PROGRAMADO'],
            
            // Julio 15
            ['concierto_nombre' => 'Gotti A Tour', 'lugar_nombre' => 'Estadio La Cartuja', 'fecha' => '2025-07-15', 'hora' => '20:00:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => 'Gotti A Tour', 'lugar_nombre' => 'Palacio de Vistalegre', 'fecha' => '2025-07-15', 'hora' => '22:30:00', 'estado' => 'PROGRAMADO'],
            
            // Julio 20
            ['concierto_nombre' => 'MP3 Tour', 'lugar_nombre' => 'Palacio de Deportes José María Martín Carpena', 'fecha' => '2025-07-20', 'hora' => '16:00:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => 'MP3 Tour', 'lugar_nombre' => 'Palacio de Deportes José María Martín Carpena', 'fecha' => '2025-07-20', 'hora' => '21:00:00', 'estado' => 'PROGRAMADO'],
            
            // Agosto
            ['concierto_nombre' => 'Alpha Tour', 'lugar_nombre' => 'Coliseum A Coruña', 'fecha' => '2025-08-05', 'hora' => '21:00:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => 'Agua Tour', 'lugar_nombre' => 'Wizink Center', 'fecha' => '2025-08-10', 'hora' => '20:30:00', 'estado' => 'PROGRAMADO'],
            
            // Agosto 15
            ['concierto_nombre' => 'Melodifestivalen Tour', 'lugar_nombre' => 'Palacio de Vistalegre', 'fecha' => '2025-08-15', 'hora' => '19:00:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => 'ABBA Voyage', 'lugar_nombre' => 'Estadio Santiago Bernabéu', 'fecha' => '2025-08-15', 'hora' => '21:30:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => '24K Magic World Tour', 'lugar_nombre' => 'Estadio Olímpico de la Cartuja', 'fecha' => '2025-08-15', 'hora' => '20:00:00', 'estado' => 'PROGRAMADO'],
            
            // Septiembre 5
            ['concierto_nombre' => 'Chromatica Ball', 'lugar_nombre' => 'Palau Sant Jordi', 'fecha' => '2025-09-05', 'hora' => '20:30:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => 'Insomnio Tour', 'lugar_nombre' => 'Plaza de Toros de Valencia', 'fecha' => '2025-09-05', 'hora' => '22:00:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => 'Un Susurro en la Tormenta', 'lugar_nombre' => 'Palacio de Vistalegre', 'fecha' => '2025-09-05', 'hora' => '19:00:00', 'estado' => 'PROGRAMADO'],
            
            // Octubre
            ['concierto_nombre' => 'After Hours Tour', 'lugar_nombre' => 'Estadio Olímpico de la Cartuja', 'fecha' => '2025-10-12', 'hora' => '21:00:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => 'Motomami World Tour', 'lugar_nombre' => 'Wizink Center', 'fecha' => '2025-10-12', 'hora' => '20:00:00', 'estado' => 'PROGRAMADO'],
            
            // Octubre 20
            ['concierto_nombre' => 'M72 World Tour', 'lugar_nombre' => 'WiZink Center', 'fecha' => '2025-10-20', 'hora' => '16:00:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => 'M72 World Tour', 'lugar_nombre' => 'WiZink Center', 'fecha' => '2025-10-20', 'hora' => '21:30:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => 'Un Susurro en la Tormenta', 'lugar_nombre' => 'Coliseum A Coruña', 'fecha' => '2025-10-20', 'hora' => '20:00:00', 'estado' => 'PROGRAMADO'],
            ['concierto_nombre' => '24K Magic World Tour', 'lugar_nombre' => 'Estadio Santiago Bernabéu', 'fecha' => '2025-10-20', 'hora' => '22:00:00', 'estado' => 'PROGRAMADO']
        ];
        
        $sesionesObj = [];
        $sesionCount = 1;
        foreach ($sesiones as $sesionData) {
            $concierto = $conciertosObj[$sesionData['concierto_nombre']] ?? $this->entityManager->getRepository(Concierto::class)->findOneBy(['nombre' => $sesionData['concierto_nombre']]);
            $lugar = $this->entityManager->getRepository(Lugar::class)->findOneBy(['nombre' => $sesionData['lugar_nombre']]);
            
            $existeSesion = $this->entityManager->getRepository(Sesion::class)->findOneBy([
                'concierto' => $concierto,
                'lugar' => $lugar,
                'fecha' => new \DateTime($sesionData['fecha']),
                'hora' => new \DateTime($sesionData['hora'])
            ]);
            
            if (!$existeSesion && $concierto && $lugar) {
                $sesion = new Sesion();
                $sesion->setConcierto($concierto);
                $sesion->setLugar($lugar);
                $sesion->setFecha(new \DateTime($sesionData['fecha']));
                $sesion->setHora(new \DateTime($sesionData['hora']));
                $sesion->setEstado($sesionData['estado']);
                $this->entityManager->persist($sesion);
                $sesionesObj[$sesionCount] = $sesion;
                $sesionCount++;
            } else if ($existeSesion) {
                $sesionesObj[$sesionCount] = $existeSesion;
                $sesionCount++;
            }
        }
        
        return $sesionesObj;
        
    }
    
}