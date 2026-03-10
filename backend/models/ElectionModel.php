<?php
class ElectionModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Get active election
    public function getActiveElection(): array|false
    {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_elections WHERE status = 'ongoing' AND ROWNUM = 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all positions for an election
    public function getPositionsByElection(string $election_id): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_positions WHERE election_id = :election_id ORDER BY position_id");
        $stmt->execute([':election_id' => $election_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all candidates for a position
    public function getCandidatesByPosition(int $position_id): array
    {
        $stmt = $this->conn->prepare("
            SELECT 
                c.candidate_id,
                c.first_name || ' ' || 
                CASE WHEN c.middle_name IS NOT NULL THEN c.middle_name || ' ' ELSE '' END ||
                c.last_name ||
                CASE WHEN c.suffix IS NOT NULL THEN ' ' || c.suffix ELSE '' END AS name,
                c.campaign,
                c.image,
                p.partylist
            FROM tbl_candidates c
            JOIN tbl_partylist p ON c.partylist_id = p.partylist_id
            WHERE c.position_id = :position_id
            ORDER BY c.candidate_id
        ");
        $stmt->execute([':position_id' => $position_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}