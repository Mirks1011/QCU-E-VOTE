<?php
class OldVoterController
{
    // GET — show the old voter info page
    public static function showForm(): void
    {
        global $conn;

        AuthMiddleware::requireLogin();

        $voterModel = new VoterModel($conn);
        $voter      = $voterModel->getByStudentIdWithCourse($_SESSION['student_id']);

        if (!$voter) {
            header('Location: ' . BASE_URL . '/login?error=Voter+not+found.');
            exit;
        }

        // ✅ Pass directly to view as variables
        $voters_id  = $voter['VOTERS_ID'];
        $student_id = $_SESSION['student_id'];
        $gender     = $voter['GENDER'];
        $birthday   = $voter['BIRTHDAY'];
        $age        = $voter['AGE'];
        $course     = $voter['COURSE_CODE'];

        require __DIR__ . '/../../views/VoterSide/oldvoter.php';
    }

    // POST — load voter from DB then redirect to form
    public static function handle(): void
    {
        global $conn;

        AuthMiddleware::requireLogin();

        $student_id = $_SESSION['student_id'];

        try {
            $voterModel = new VoterModel($conn);
            $voter      = $voterModel->getByStudentIdWithCourse($student_id);

            if (!$voter) {
                header('Location: ' . BASE_URL . '/login?error=Voter+not+found.');
                exit;
            }

            // Store only what's needed in session
            $_SESSION['voter_gender']   = $voter['GENDER'];
            $_SESSION['voter_birthday'] = $voter['BIRTHDAY'];
            $_SESSION['voter_age']      = $voter['AGE'];
            $_SESSION['voter_course']   = $voter['COURSE_CODE'];
            $_SESSION['voters_id']      = $voter['VOTERS_ID'];

            header('Location: ' . BASE_URL . '/old-voter');
            exit;

        } catch (Exception $e) {
            header('Location: ' . BASE_URL . '/login?error=Something+went+wrong.+Please+try+again.');
            exit;
        }
    }
}