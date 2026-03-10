<?php
class OtpVerifyController
{
    public static function handle(): void
    {
        global $conn;

        AuthMiddleware::requireLogin();

        $student_id = $_SESSION['student_id'];
        $otp_input  = OtpVerifyService::combineInputs($_POST);

        if (!OtpVerifyService::validate($otp_input)) {
            RedirectHelper::withError('/otp/verify', 'Please enter a valid 6-digit OTP.');
            return;
        }

        try {
            $voterModel  = new VoterModel($conn);
            $otp_data    = $voterModel->getOtpData($student_id);

            if (!$otp_data) {
                RedirectHelper::withError('/otp/verify', 'OTP not found. Please try again.');
                return;
            }

            $otp_hashed  = $otp_data['OTP_CODE'];
            $otp_expires = $otp_data['OTP_EXPIRES_AT'];

            if (!OtpService::isValid($otp_input, $otp_hashed, $otp_expires)) {
                RedirectHelper::withError('/otp/verify', 'Invalid or expired OTP. Please try again.');
                return;
            }

            $voter   = $voterModel->getByStudentId($student_id);
            $user_id = $voter['USER_ID'];

            $_SESSION['auth_token'] = TokenService::generate($user_id, $otp_input);

            unset($_SESSION['otp_code']);
            unset($_SESSION['otp_expires_at']);
            unset($_SESSION['otp_displayed']);
            unset($_SESSION['otp_verified']);

            RedirectHelper::to('/voting');

        } catch (Exception $e) {
            RedirectHelper::withError('/otp/verify', 'Something went wrong. Please try again.');
        }
    }
}