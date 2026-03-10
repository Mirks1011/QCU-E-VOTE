<?php
session_start();
define('BASE_URL', '/QCU-E-VOTE');

    // 1. COMPOSER AUTOLOAD — always first
    require_once __DIR__ . '/../../vendor/autoload.php';

    // 2. CONFIG — needed by everything
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../config/security.php';
    require_once __DIR__ . '/../config/jwt.php';

    // 3. HELPERS — needed by middleware
    require_once __DIR__ . '/../helpers/RedirectHelper.php';

    // 4. SERVICES — needed by middleware and controllers
    require_once __DIR__ . '/../services/TokenService.php';
    require_once __DIR__ . '/../services/VoterIdService.php';
    require_once __DIR__ . '/../services/NewVoterService.php';
    require_once __DIR__ . '/../services/OtpService.php';
    require_once __DIR__ . '/../services/OtpVerifyService.php';

    // 5. MIDDLEWARE — needed by controllers
    require_once __DIR__ . '/../middleware/AuthMiddleware.php';

    // 6. MODELS — needed by controllers
    require_once __DIR__ . '/../models/VoterModel.php';
    require_once __DIR__ . '/../models/CourseModel.php';
    require_once __DIR__ . '/../models/ElectionModel.php';
    require_once __DIR__ . '/../models/VoteModel.php';

    // 7. CONTROLLERS — last since they depend on everything above
    require_once __DIR__ . '/../controllers/AuthController.php';
    require_once __DIR__ . '/../controllers/NewVoterController.php';
    require_once __DIR__ . '/../controllers/OldVoterController.php';
    require_once __DIR__ . '/../controllers/OtpController.php';
    require_once __DIR__ . '/../controllers/OtpVerifyController.php';
    require_once __DIR__ . '/../controllers/VotingController.php';
    require_once __DIR__ . '/../controllers/VoteSubmitController.php';
    require_once __DIR__ . '/../controllers/SaveProgressController.php';

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri    = str_replace('/QCU-E-VOTE', '', $uri);
$method = $_SERVER['REQUEST_METHOD'];

// ==============================
//         VOTER ROUTES
// ==============================

if ($uri === '/login' && $method === 'GET') {
    require __DIR__ . '/../../views/VoterSide/login.php';

} elseif ($uri === '/login' && $method === 'POST') {
    AuthController::handle();

} elseif ($uri === '/new-voter' && $method === 'GET') {
    NewVoterController::showForm();  // shows form with DB data

} elseif ($uri === '/new-voter' && $method === 'POST') {
    NewVoterController::handle();    // handles form submission

} elseif ($uri === '/old-voter' && $method === 'GET') {
    OldVoterController::showForm();  // fetches from DB, passes to view

} elseif ($uri === '/old-voter/load' && $method === 'GET') {
    OldVoterController::handle();    // stores in session, redirects

} elseif ($uri === '/datapriv' && $method === 'GET') {
    require __DIR__ . '/../../views/VoterSide/datapriv.html'; 

} elseif ($uri === '/otp' && $method === 'GET') {
    OtpController::handle();       // generates OTP, redirects to display

} elseif ($uri === '/otp-display' && $method === 'GET') {
    OtpController::showForm();     // shows OTP page with variables

} elseif ($uri === '/otp/verify' && $method === 'GET') {
    require __DIR__ . '/../../views/VoterSide/inputotp.php'; 

} elseif ($uri === '/otp/verify' && $method === 'POST') {
    OtpVerifyController::handle();

} elseif ($uri === '/voting' && $method === 'GET') {
    VotingController::handle(); // 

} elseif ($uri === '/voted' && $method === 'GET') {
    require __DIR__ . '/../../views/VoterSide/voted.php';

} elseif ($uri === '/vote/submit' && $method === 'POST') {
    VoteSubmitController::handle();

} elseif ($uri === '/voted' && $method === 'GET') {
    require __DIR__ . '/../../views/VoterSide/voted.php';

} elseif ($uri === '/vote/progress' && $method === 'POST') {
    SaveProgressController::handle();

} elseif ($uri === '/update' && $method === 'GET') {
    require __DIR__ . '/../../views/VoterSide/updateacc.php';

} elseif ($uri === '/logout' && $method === 'GET') {
    session_unset();
    session_destroy();
    header('Location: ' . BASE_URL . '/login');
    exit;

} else {
    http_response_code(404);
    die('404 — Page not found.');
}