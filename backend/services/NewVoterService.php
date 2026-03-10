<?php
class NewVoterService
{
    public static function validate(array $data): array
    {
        $errors = [];
        if (empty($data['gender'])) {
            $errors[] = 'Gender is required.';
        }
        if (empty($data['birthday'])) {
            $errors[] = 'Birthday is required.';
        }
        if (empty($data['course_id'])) {
            $errors[] = 'Course is required.';
        }
        if (!isset($data['age']) || (int)$data['age'] <= 0) {
            $errors[] = 'Invalid age.';
        }
        return $errors;
    }

    public static function sanitize(array $post, string $student_id, string $voters_id): array
    {
        return [
            'student_id' => $student_id,
            'voters_id'  => $voters_id,
            'gender'     => trim($post['gender']   ?? ''),
            'birthday'   => trim($post['birthday'] ?? ''),
            'age'        => intval($post['age']    ?? 0),
            'course_id'     => trim($post['course_id']   ?? ''),
        ];
    }
}