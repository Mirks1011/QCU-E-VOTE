<?php
class OtpController
{
    // GET — show OTP display page
    public static function showForm(): void
    {
        global $conn;

        AuthMiddleware::requireLogin();

        if (!isset($_SESSION['otp_code']) || !isset($_SESSION['otp_expires_at'])) {
            RedirectHelper::withError('/login', 'Session expired. Please scan again.');
        }

        // Pass directly to view as variables
        $otp        = $_SESSION['otp_code'];
        $expires_at = $_SESSION['otp_expires_at'];

        // One-way door
        $_SESSION['otp_displayed'] = true;

        require __DIR__ . '/../../views/VoterSide/otp.php';
    }

    // POST — generate and save OTP then redirect
    public static function handle(): void
    {
        global $conn;

        AuthMiddleware::requireLogin();

        $student_id = $_SESSION['student_id'];

        try {
            $voterModel = new VoterModel($conn);
            $otp        = OtpService::generate();
            $expires_at = OtpService::getExpiry();
            $otp_hashed = OtpService::hash($otp);

            $voterModel->saveOtp($student_id, $otp_hashed, $expires_at);

            // Clear previous display flag — fresh start
            unset($_SESSION['otp_displayed']);
            $_SESSION['otp_code']       = $otp;
            $_SESSION['otp_expires_at'] = $expires_at;

            RedirectHelper::to('/otp-display');

        } catch (Exception $e) {
            RedirectHelper::withError('/login', 'Something went wrong. Please try again.');
        }
    }
}