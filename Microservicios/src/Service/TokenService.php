<?php

class TokenService {

    private const TOKEN_EXPIRATION = 3600; // 1 hora (= 3600 segundos)
    private const TOKEN_SECRET = 'nZ!tW9f8gE$3A2kMpL%yqX0jH4cT7rD1'; // clave segura random
    
    /**
     * genera un token para un usuario específico
     *
     * @param int $userId ID del usuario
     * @param string $email email del usuario
     * @return string token generado
     */
    public function generateToken(int $userId, string $email): string {
        $payload = [
            'user_id' => $userId,
            'email' => $email,
            'exp' => time() + self::TOKEN_EXPIRATION,
            'iat' => time()
        ];
        
        // que el JSOn se codifique correctamente
        $jsonPayload = json_encode($payload);
        if ($jsonPayload === false) {
            throw new \Exception('Error al codificar el payload: ' . json_last_error_msg());
        }
        
        // se codifican los datos en base64 para la primera parte del token
        $payloadEncoded = base64_encode($jsonPayload);
        
        // se genera la firma (la segunda parte del token)
        $signature = hash_hmac('sha256', $payloadEncoded, self::TOKEN_SECRET);
        
        // formato: base64(payload).firma
        return $payloadEncoded . '.' . $signature;
    }
    
    /**
     * verifica si un token es válido
     *
     * @param string $token token a verificar
     * @return bool true si el token es válido, false si no
     */
    public function verifyToken(string $token): bool {
        error_log("Verificando token: " . substr($token, 0, 20) . "...");
        
        if (empty($token)) {
            error_log("Token vacío");
            return false;
        }
        
        // se divide el token en sus dos partes, apartir del .
        $parts = explode('.', $token);
        if (count($parts) !== 2) {
            error_log("Formato de token inválido: no contiene 2 partes");
            return false;
        }
        
        [$payloadEncoded, $signature] = $parts;
        
        // se verifica la firma
        $expectedSignature = hash_hmac('sha256', $payloadEncoded, self::TOKEN_SECRET);
        if (!hash_equals($expectedSignature, $signature)) {
            error_log("Firma inválida. Esperada: " . substr($expectedSignature, 0, 10) . ", Recibida: " . substr($signature, 0, 10));
            return false;
        }
        
        // se descodifica el payload
        $payloadJson = base64_decode($payloadEncoded);
        if ($payloadJson === false) {
            error_log("Error decodificando base64");
            return false;
        }
        
        $payload = json_decode($payloadJson, true);
        if ($payload === null) {
            error_log("Error decodificando JSON: " . json_last_error_msg());
            return false;
        }
        
        if (!isset($payload['exp'])) {
            error_log("El token no contiene fecha de expiración");
            return false;
        }
        
        // se mira si el token caducó
        if ($payload['exp'] < time()) {
            error_log("Token expirado. Expira: " . date('Y-m-d H:i:s', $payload['exp']) . ", Ahora: " . date('Y-m-d H:i:s'));
            return false;
        }
        
        return true;
    }
    
    /**
     * obtiene la información del usuario desde el token
     *
     * @param string $token el token
     * @return array|null información del usuario o null si el token es inválido
     */
    public function getUserFromToken(string $token): ?array {
        if (!$this->verifyToken($token)) {
            return null;
        }
        
        $parts = explode('.', $token);
        $payloadJson = base64_decode($parts[0]);
        $payload = json_decode($payloadJson, true);
        
        return [
            'user_id' => $payload['user_id'],
            'email' => $payload['email']
        ];
    }
    
}