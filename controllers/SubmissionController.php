<?php
require_once __DIR__ . '/../models/config.php';
require_once __DIR__ . '/../models/Submission.php';

class SubmissionController
{
    // ✅ Get all submissions
    public function getAll()
    {
        global $pdo;
        $sql = "SELECT * FROM submission";
        try {
            $query = $pdo->query($sql);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage());
        }
    }

    // ✅ Add new submission
    public function save($submission)
    {
        global $pdo;
        $sql = "INSERT INTO submission (quiz_id, user_id, answers, createdAt)
                VALUES (:quiz_id, :user_id, :answers, :createdAt)";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                ':quiz_id'   => $submission->getQuizId(),
                ':user_id'   => $submission->getUserId(),
                ':answers'   => $submission->getAnswers(), // JSON string
                ':createdAt' => $submission->getCreatedAt()
            ]);
        } catch (Exception $e) {
            die("Erreur lors de l'enregistrement : " . $e->getMessage());
        }
    }

    // ✅ Delete submission by quiz_id and user_id
    public function delete($quiz_id, $user_id)
    {
        global $pdo;
        $sql = "DELETE FROM submission WHERE quiz_id = :quiz_id AND user_id = :user_id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                ':quiz_id' => $quiz_id,
                ':user_id' => $user_id
            ]);
        } catch (Exception $e) {
            die("Erreur lors de la suppression : " . $e->getMessage());
        }
    }

    // ✅ Update submission answers
    public function update($quiz_id, $user_id, $submission)
    {
        global $pdo;
        $sql = "UPDATE submission 
                SET answers = :answers, createdAt = :createdAt
                WHERE quiz_id = :quiz_id AND user_id = :user_id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                ':quiz_id'   => $quiz_id,
                ':user_id'   => $user_id,
                ':answers'   => $submission->getAnswers(),
                ':createdAt' => $submission->getCreatedAt()
            ]);
        } catch (Exception $e) {
            die("Erreur lors de la mise à jour : " . $e->getMessage());
        }
    }

    // ✅ Get submission by quiz_id and user_id
    public function getById($quiz_id, $user_id)
    {
        global $pdo;
        $sql = "SELECT * FROM submission WHERE quiz_id = :quiz_id AND user_id = :user_id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                ':quiz_id' => $quiz_id,
                ':user_id' => $user_id
            ]);
            $data = $query->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                return new Submission(
                    $data['quiz_id'],
                    $data['user_id'],
                    $data['answers'],
                    $data['createdAt']
                );
            }
            return null;
        } catch (Exception $e) {
            die("Erreur lors de la récupération : " . $e->getMessage());
        }
    }

    // ✅ Get all submissions by a specific user
    public function getByUserId($user_id)
    {
        global $pdo;
        $sql = "SELECT * FROM submission WHERE user_id = :user_id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([':user_id' => $user_id]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Erreur lors de la récupération : " . $e->getMessage());
        }
    }

    // ✅ Get all submissions for a specific quiz
    public function getByQuizId($quiz_id)
    {
        global $pdo;
        $sql = "SELECT * FROM submission WHERE quiz_id = :quiz_id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([':quiz_id' => $quiz_id]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Erreur lors de la récupération : " . $e->getMessage());
        }
    }
}
