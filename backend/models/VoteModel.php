<?php
class VoteModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Check if voter already voted in this election
    public function hasVoted(int $user_id, string $election_id): bool
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS TOTAL FROM tbl_votes WHERE voter_id = :voter_id AND election_id = :election_id");
        $stmt->execute([':voter_id' => $user_id, ':election_id' => $election_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['TOTAL'] > 0;
    }

    // Insert a single vote
    public function insertVote(array $data): bool
    {
        $stmt = $this->conn->prepare("
            INSERT INTO tbl_votes (voter_id, vote_timestamp, encrypted_vote_token, election_id, position_id, candidate_id)
            VALUES (:voter_id, :vote_timestamp, :encrypted_vote_token, :election_id, :position_id, :candidate_id)
        ");
        return $stmt->execute([
            ':voter_id'              => $data['voter_id'],
            ':vote_timestamp'        => date('Y-m-d H:i:s'),
            ':encrypted_vote_token'  => $data['encrypted_vote_token'],
            ':election_id'           => $data['election_id'],
            ':position_id'           => $data['position_id'],
            ':candidate_id'          => $data['candidate_id'],
        ]);
    }

    // Mark voter as voted
    public function markAsVoted(int $user_id): bool
    {
        $stmt = $this->conn->prepare("UPDATE tbl_voters SET is_voted = 1 WHERE user_id = :user_id");
        return $stmt->execute([':user_id' => $user_id]);
    }
}