<?php
class OtpService
{
    /**
     * Generates a 6-digit numeric OTP
     */
    public static function generate(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Hashes the OTP for secure DB storage
     */
    public static function hash(string $otp): string
    {
        return password_hash($otp, PASSWORD_DEFAULT);
    }

    /**
     * Verifies OTP input against hashed value
     */
    public static function verify(string $otp_input, string $otp_hashed): bool
    {
        return password_verify($otp_input, $otp_hashed);
    }

    /**
     * Returns expiry time 10 minutes from now
     */
    public static function getExpiry(): string
    {
        return date('Y-m-d H:i:s', strtotime('+10 minutes'));
    }

    /**
     * Checks if OTP is still valid
     */
    public static function isValid(string $otp_input, string $otp_hashed, string $otp_expires_at): bool
    {
        $now    = new DateTime();
        $expiry = new DateTime($otp_expires_at);
        return self::verify($otp_input, $otp_hashed) && $now < $expiry;
    }
}