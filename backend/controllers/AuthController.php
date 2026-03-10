<?php
class AuthController
{
    public static function handle(): void
    {
        global $conn;

        $student_id = trim($_POST['student_id'] ?? '');

        if (empty($student_id)) {
            RedirectHelper::withError('/login', 'Please enter your Student ID.');
        }

        try {
            $voterModel = new VoterModel($conn);

            if ($voterModel->existsByStudentId($student_id)) {
                $voter = $voterModel->getByStudentId($student_id);

                // Block voter if already voted
                if ((int)$voter['IS_VOTED'] === 1) {
                    RedirectHelper::withError('/login', 'You have already voted. Thank you for participating!');
                }

                $_SESSION['student_id'] = $student_id;
                RedirectHelper::to('/old-voter/load');

            } else {
                $voters_id = VoterIdService::generate($conn);
                $_SESSION['student_id'] = $student_id;
                $_SESSION['voters_id']  = $voters_id;
                RedirectHelper::to('/new-voter');
            }

        } catch (Exception $e) {
            RedirectHelper::withError('/login', 'Something went wrong. Please try again.');
        }
    }
}