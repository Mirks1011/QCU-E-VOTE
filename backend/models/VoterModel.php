<?php
class VoterModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Fetch full voter record by student_id
    public function getByStudentId(string $student_id): array|false
    {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_voters WHERE student_id = :student_id");
        $stmt->execute([':student_id' => $student_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch full voter's course record by joining tbl_voter and tbl_course
    // ONLY USED SO WE CAN FETCH OldVoter's Course
    public function getByStudentIdWithCourse(string $student_id): array|false
    {
        $stmt = $this->conn->prepare("
            SELECT v.*, c.course_code, c.course_name
            FROM tbl_voters v
            LEFT JOIN tbl_course c ON v.course_id = c.course_id
            WHERE v.student_id = :student_id
        ");
        $stmt->execute([':student_id' => $student_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existsByStudentId(string $student_id): bool
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS TOTAL FROM tbl_voters WHERE student_id = :student_id");
        $stmt->execute([':student_id' => $student_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['TOTAL'] > 0;
    }

    public function insert(array $data): bool
    {
        $sql = "INSERT INTO tbl_voters (student_id, voters_id, gender, birthday, age, course_id, created_at)
                VALUES (:student_id, :voters_id, :gender, :birthday, :age, :course_id, :created_at)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':student_id' => $data['student_id'],
            ':voters_id'  => $data['voters_id'],
            ':gender'     => $data['gender'],
            ':birthday'   => $data['birthday'],
            ':age'        => $data['age'],
            ':course_id'  => $data['course_id'],
            ':created_at' => date('Y-m-d H:i:s'), 
        ]);
    }
       
    //Fetch OTP
    public function saveOtp(string $student_id, string $otp, string $expires_at): bool
    {
        $stmt = $this->conn->prepare("UPDATE tbl_voters SET otp_code = :otp, otp_expires_at = :expires_at WHERE student_id = :student_id");
        return $stmt->execute([
            ':otp'        => $otp,
            ':expires_at' => $expires_at,
            ':student_id' => $student_id,
        ]);
    }

    //VERIFY OTP
    public function getOtpData(string $student_id): array|false
    {
        $stmt = $this->conn->prepare("SELECT otp_code, otp_expires_at FROM tbl_voters WHERE student_id = :student_id");
        $stmt->execute([':student_id' => $student_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //CLEARS OTP AFTER USE
    public function clearOtp(string $student_id): bool
{
    $stmt = $this->conn->prepare("UPDATE tbl_voters SET otp_code = NULL, otp_expires_at = NULL WHERE student_id = :student_id");
    return $stmt->execute([':student_id' => $student_id]);
}
}