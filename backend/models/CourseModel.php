<?php
class CourseModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Get all courses
    public function getAll(): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_course ORDER BY course_code");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}