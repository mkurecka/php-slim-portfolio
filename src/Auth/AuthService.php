<?php

namespace App\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService
{
    private string $jwtSecret;
    private string $adminUsername;
    private string $adminPassword;

    public function __construct(
        ?string $jwtSecret = null,
        ?string $adminUsername = null,
        ?string $adminPassword = null
    ) {
        $this->jwtSecret = $jwtSecret ?? $_ENV['JWT_SECRET'] ?? 'default_jwt_secret_change_this';
        $this->adminUsername = $adminUsername ?? $_ENV['ADMIN_USERNAME'] ?? 'admin';
        $this->adminPassword = $adminPassword ?? $_ENV['ADMIN_PASSWORD'] ?? 'changeme';
    }

    public function login(string $username, string $password): ?string
    {
        if ($username === $this->adminUsername && $password === $this->adminPassword) {
            return $this->generateToken();
        }
        
        return null;
    }
    
    public function validateToken(string $token): bool
    {
        try {
            JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function generateToken(): string
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; // Valid for 1 hour
        
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'username' => $this->adminUsername,
            'role' => 'admin'
        ];
        
        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }
}