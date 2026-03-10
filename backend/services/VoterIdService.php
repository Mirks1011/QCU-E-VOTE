<?php
class VoterIdService
{
    public static function generate(PDO $conn): string
    {
        // Use MAX user_id instead of COUNT to avoid duplicate on re-inserts
        $stmt = $conn->prepare("SELECT MAX(user_id) AS max_id FROM tbl_voters");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $next = (int)($result['MAX_ID'] ?? 0) + 1; // Oracle returns uppercase

        $part1 = str_pad(floor(($next - 1) / 10000) + 1, 2, '0', STR_PAD_LEFT);
        $part2 = str_pad(($next - 1) % 10000 + 1,        4, '0', STR_PAD_LEFT);

        return 'v' . $part1 . '-' . $part2;
    }
}