<?php
class SaveProgressController
{
    public static function handle(): void
    {
        // Guard
        if (!isset($_SESSION['student_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Session expired.']);
            exit;
        }

        if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
            http_response_code(401);
            echo json_encode(['error' => 'OTP not verified.']);
            exit;
        }

        // Get votes from POST body
        $body  = file_get_contents('php://input');
        $votes = json_decode($body, true);

        if (!is_array($votes)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid data.']);
            exit;
        }

        // Save progress to session
        $_SESSION['vote_progress'] = $votes;

        echo json_encode(['success' => true]);
        exit;
    }
}