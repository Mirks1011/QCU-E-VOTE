<?php
class NewVoterController
{
    // GET — show the form
    public static function showForm(): void
    {
        global $conn;

        AuthMiddleware::requireLogin();

        $voterModel  = new VoterModel($conn);
        $courseModel = new CourseModel($conn);

        $voter     = $voterModel->getByStudentId($_SESSION['student_id']);
        $voters_id = $voter ? $voter['VOTERS_ID'] : ($_SESSION['voters_id'] ?? '');
        $courses   = $courseModel->getAll();

        require __DIR__ . '/../../views/VoterSide/newvoter.php';
    }

    // POST — handle form submission
    public static function handle(): void
    {
        global $conn;

        AuthMiddleware::requireLogin();

        $student_id = $_SESSION['student_id'];
        $voters_id  = $_SESSION['voters_id'];

        $data   = NewVoterService::sanitize($_POST, $student_id, $voters_id);
        $errors = NewVoterService::validate($data);

        if (!empty($errors)) {
            $error_msg = urlencode(implode(' ', $errors));
            header('Location: ' . BASE_URL . "/new-voter?error=$error_msg");
            exit;
        }

        try {
            $voterModel = new VoterModel($conn);

            if ($voterModel->existsByStudentId($student_id)) {
                header('Location: ' . BASE_URL . '/old-voter');
                exit;
            }

            $voterModel->insert($data);

            $_SESSION['voter_course_id'] = $data['course_id'];
            $_SESSION['voter_gender']    = $data['gender'];
            $_SESSION['voter_birthday']  = $data['birthday'];
            $_SESSION['voter_age']       = $data['age'];

            header('Location: ' . BASE_URL . '/otp');
            exit;

        } catch (PDOException $e) {
            header('Location: ' . BASE_URL . '/new-voter?error=Something+went+wrong.+Please+try+again.');
            exit;
        }
    }
}