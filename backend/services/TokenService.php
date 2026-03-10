<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenService
{
    // Generate token with user_id and otp as payload
    public static function generate(int $user_id, string $otp): string
    {
        $payload = [
            'user_id' => $user_id,
            'otp'     => $otp,
            'iat'     => time(),  // issued at
        ];

        return JWT::encode($payload, JWT_SECRET, JWT_ALGO);
    }

    // Decode and verify token
    public static function verify(string $token): object|false
    {
        try {
            return JWT::decode($token, new Key(JWT_SECRET, JWT_ALGO));
        } catch (Exception $e) {
            return false;
        }
    }

    // Invalidate token — called after voting
    public static function invalidate(): void
    {
        unset($_SESSION['auth_token']);
    }

    // Get payload data from token
    public static function getPayload(string $token): object|false
    {
        return self::verify($token);
    }

    // Get user_id from token
    public static function getUserId(): int|false
    {
        if (!isset($_SESSION['auth_token'])) return false;

        $decoded = self::verify($_SESSION['auth_token']);
        return $decoded ? (int)$decoded->user_id : false;
    }
}