<?php
class OtpVerifyService
{
    /** Combines the 6 individual OTP box inputs into one string */
    public static function combineInputs(array $post): string
    {
        $otp = '';
        for ($i = 1; $i <= 6; $i++) {
            $otp .= trim($post['otp_' . $i] ?? '');
        }
        return $otp;
    }

    /** Validates the OTP input — must be exactly 6 digits */
    public static function validate(string $otp): bool
    {
        return (bool) preg_match('/^\d{6}$/', $otp); // cast to bool
    }
}