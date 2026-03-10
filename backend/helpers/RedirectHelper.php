<?php
class RedirectHelper
{
    // Redirect with session error message
    public static function withError(string $location, string $error): void
    {
        $_SESSION['login_error'] = $error;
        header('Location: ' . BASE_URL . $location);
        exit;
    }

    // Redirect with session success message (useful later)
    public static function withSuccess(string $location, string $message): void
    {
        $_SESSION['login_success'] = $message;
        header('Location: ' . BASE_URL . $location);
        exit;
    }

    // Plain redirect
    public static function to(string $location): void
    {
        header('Location: ' . BASE_URL . $location);
        exit;
    }
}