<?php
class VotingController
{
    public static function handle(): void
    {
        global $conn;

        //Middleware here in controller — not in view
        AuthMiddleware::requireLogin();
        AuthMiddleware::requireToken();

        try {
            $electionModel = new ElectionModel($conn);

            $election = $electionModel->getActiveElection();
            if (!$election) {
                die('No active election found.');
            }

            $positions = $electionModel->getPositionsByElection($election['ELECTION_ID']);
            if (empty($positions)) {
                die('No positions found for this election.');
            }

            $ballot = [];
            foreach ($positions as $position) {
                $candidates = $electionModel->getCandidatesByPosition($position['POSITION_ID']);
                $ballot[] = [
                    'position'   => $position,
                    'candidates' => $candidates
                ];
            }

            $_SESSION['election'] = $election;
            $_SESSION['ballot']   = $ballot;

            require __DIR__ . '/../../views/VoterSide/voting.php';

        } catch (Exception $e) {
            die('Voting Error: ' . $e->getMessage());
        }
    }
}