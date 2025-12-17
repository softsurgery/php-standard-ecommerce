<?php
require_once __DIR__ . '/../models/config.php';
require_once __DIR__ . '/../models/Submission.php';

class SubmissionController
{
    /**
     * Get paginated quizes
     * @param int $page Current page number (1-based)
     * @param int $pageSize Number of items per page
     * @return array ['data' => [...], 'meta' => ['page'=>..., 'size'=>..., 'pageCount'=>..., 'hasNextPage'=>..., 'hasPreviousPage'=>...]]
     */
    public function getPaginated($page = 1, $pageSize = 10)
    {
        global $pdo;

        $page = max(1, (int)$page);
        $pageSize = max(1, (int)$pageSize);
        $offset = ($page - 1) * $pageSize;

        try {
            // COUNT with search
            $countSql = "
            SELECT COUNT(*) as total 
            FROM `submission`
        ";
            $stmt = $pdo->prepare($countSql);
            $stmt->execute();
            $total = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];

            $pageCount = (int)ceil($total / $pageSize);

            // DATA query with search + pagination
            $dataSql = "
            SELECT Q.name as quiz_name,
                U.email as email,
                 U.name as name, 
                  U.surname as surname,
                   S.score as score,
                    S.createdAt as createdAt,
                     S.quiz_id as quiz_id,
                      S.user_id as user_id
            FROM `submission` S, `quiz` Q, `users` U
            WHERE S.quiz_id = Q.id
            AND S.user_id = U.id
            LIMIT :limit OFFSET :offset
        ";

            $stmt = $pdo->prepare($dataSql);
            $stmt->bindValue(':limit', $pageSize, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'data' => $data,
                'meta' => [
                    'page' => $page,
                    'size' => $pageSize,
                    'pageCount' => $pageCount,
                    'hasNextPage' => $page < $pageCount,
                    'hasPreviousPage' => $page > 1,
                    'total' => $total
                ]
            ];
        } catch (Exception $e) {
            die("Erreur lors de la pagination : " . $e->getMessage());
        }
    }

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
        $sql = "INSERT INTO submission (quiz_id, user_id, score, answers, createdAt)
                VALUES (:quiz_id, :user_id, :score, :answers, :createdAt)";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                ':quiz_id'   => $submission->getQuizId(),
                ':user_id'   => $submission->getUserId(),
                ':score'     => $submission->getScore(),
                ':answers'   => $submission->getAnswers(),
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
                SET score = :score, answers = :answers, createdAt = :createdAt
                WHERE quiz_id = :quiz_id AND user_id = :user_id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                ':quiz_id'   => $quiz_id,
                ':user_id'   => $user_id,
                ':score'     => $submission->getScore(),
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
                    $data['score'],
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
