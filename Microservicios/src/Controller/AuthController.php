<?php

use Doctrine\ORM\EntityManager;
use Entradas\Entity\Cliente;
use Entradas\Entity\Admin;

class AuthController {
    
    private $entityManager;
    private $tokenService;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        $this->tokenService = new TokenService();
    }

    /**
     * manejar el login de usuarios
     */
    public function login(): void {
        
        // obtenemos los datos de la petición
        $data = json_decode(file_get_contents('php://input'), true);
        
        // validar que se hayan enviado email y contraseña
        if (!isset($data['email']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Se requiere email y contraseña']);
            return;
        }

        $email = $data['email'];
        $password = $data['password'];

        // buscar el administrador en la base de datos por email
        $clienteRepository = $this->entityManager->getRepository(Admin::class);
        $cliente = $clienteRepository->findOneBy(['email' => $email]);

        if (!$cliente) {
            http_response_code(401);
            echo json_encode(['error' => 'Credenciales inválidas']);
            return;
        }

        // verificar la contraseña
        if (!$this->verifyPassword($password, $cliente->getPassword())) {
            http_response_code(401);
            echo json_encode(['error' => 'Credenciales inválidas']);
            return;
        }

        // si las credenciales son correctas, se genera un token
        $token = $this->tokenService->generateToken(
            $cliente->getId(),
            $cliente->getEmail()
        );

        // iniciar sesión si es necesario
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['token'] = $token;

        // enviamos el token al cliente en la resupeta JSON
        echo json_encode([
            'token' => $token,
            'user' => [
                'id' => $cliente->getId(),
                'email' => $cliente->getEmail(),
            ]
        ]);
    }

    /**
     * verificar contraseña
     * 
     * @param string $inputPassword contraseña a verificar
     * @param string $storedPassword Contraseña almacenada en db
     * @return bool
     */
    private function verifyPassword(string $inputPassword, string $storedPassword): bool {
        // al poner lo de codificar contraseña aquí poner la logica para verificar eso
        return $inputPassword === $storedPassword;
    }
}