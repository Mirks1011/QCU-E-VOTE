<?php
class AuthMiddleware
{
    // Requires valid session only (login check)
    public static function requireLogin(): void
    {
      if (!isset($_SESSION['student_id'])) {
        RedirectHelper::withError('/login', 'Session expired. Please scan again.');
    }

    }

    // Requires valid auth token (post OTP check)
    public static function requireToken(): void
    {
        self::requireLogin();

        if (!isset($_SESSION['auth_token'])) {
            RedirectHelper::withError('/otp/verify', 'Please verify your OTP first.');
        }

        $decoded = TokenService::verify($_SESSION['auth_token']);

        if (!$decoded) {
            unset($_SESSION['auth_token']);
            RedirectHelper::withError('/login', 'Session expired. Please scan again.');
        }
    }

    // Requires OTP page access 
    public static function requireOtpDisplayed(): void
    {
        self::requireLogin();

        if (!isset($_SESSION['otp_displayed'])) {
            RedirectHelper::withError('/login', 'Session expired. Please scan again.');
        }
    }

    // Blocks already voted voters
    public static function requireNotVoted(): void
    {
        if (isset($_SESSION['auth_token'])) {
            $decoded = TokenService::verify($_SESSION['auth_token']);
            if (!$decoded) {
                RedirectHelper::withError('/login', 'Session expired.');
                exit;
            }
        }
    }
}