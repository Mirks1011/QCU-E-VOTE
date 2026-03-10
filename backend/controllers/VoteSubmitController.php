<?php
class VoteSubmitController
{
    public static function handle(): void
    {
        global $conn;

        // Middleware replaces manual guards
        AuthMiddleware::requireLogin();
        AuthMiddleware::requireToken();

        $votes    = $_POST['votes']      ?? '';
        $election = $_SESSION['election'] ?? null;

        if (!$election || empty($votes)) {
            header('Location: ' . BASE_URL . '/voting?error=No+votes+submitted.');
            exit;
        }

        $votes_data = json_decode($votes, true);

        if (!is_array($votes_data)) {
            header('Location: ' . BASE_URL . '/voting?error=Invalid+vote+data.');
            exit;
        }

        try {
            $voterModel = new VoterModel($conn);
            $voteModel  = new VoteModel($conn);

            $voter = $voterModel->getByStudentId($_SESSION['student_id']);

            if (!$voter) {
                header('Location: ' . BASE_URL . '/login?error=Voter+not+found.');
                exit;
            }

            $user_id     = $voter['USER_ID'];
            $election_id = $election['ELECTION_ID'];

            // Check if already voted
            if ($voteModel->hasVoted($user_id, $election_id)) {
                header('Location: ' . BASE_URL . '/voted?error=You+have+already+voted.');
                exit;
            }

            // Insert each vote
            foreach ($votes_data as $position_id => $candidate_id) {
                if ($candidate_id === null) continue;

                $token = hash('sha256', $user_id . $position_id . $candidate_id . time() . uniqid());

                $voteModel->insertVote([
                    'voter_id'             => $user_id,
                    'election_id'          => $election_id,
                    'position_id'          => $position_id,
                    'candidate_id'         => $candidate_id,
                    'encrypted_vote_token' => $token,
                ]);
            }

            // Mark as voted, clear OTP from DB, destroy token
            $voteModel->markAsVoted($user_id);
            $voterModel->clearOtp($_SESSION['student_id']);
            TokenService::invalidate();

            // Store summary and clear progress
            unset($_SESSION['vote_progress']);
            $_SESSION['vote_summary'] = $votes_data;

            header('Location: ' . BASE_URL . '/voted');
            exit;

        } catch (Exception $e) {
            die('Vote Submit Error: ' . $e->getMessage());
        }
    }
}