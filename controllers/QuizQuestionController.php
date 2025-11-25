<?php
require_once __DIR__ . '/../models/config.php';
require_once __DIR__ . '/../models/QuizQuestion.php';

class QuizQuestionController
{
    // Pagination if needed
    public function getPaginated($page = 1, $pageSize = 20)
    {
        global $pdo;
        $page = max(1, (int)$page);
        $pageSize = max(1, (int)$pageSize);

        try {
            $countSql = "SELECT COUNT(*) AS total FROM `quiz_question`";
            $stmt = $pdo->query($countSql);
            $total = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];

            $pageCount = (int)ceil($total / $pageSize);
            $offset = ($page - 1) * $pageSize;

            $dataSql = "SELECT * FROM `quiz_question` ORDER BY quiz_id, ordering ASC LIMIT :limit OFFSET :offset";
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
                    'hasPreviousPage' => $page > 1
                ]
            ];
        } catch (Exception $e) {
            die("Erreur lors de la pagination : " . $e->getMessage());
        }
    }

    // Get all links
    public function getAll()
    {
        global $pdo;
        $sql = "SELECT * FROM `quiz_question`";
        try {
            $query = $pdo->query($sql);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage());
        }
    }

    // Save link
    public function save($qq)
    {
        global $pdo;
        $sql = "INSERT INTO `quiz_question` (question_id, quiz_id, ordering)
                VALUES (:question_id, :quiz_id, :ordering)";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                ':question_id' => $qq->getQuestionId(),
                ':quiz_id' => $qq->getQuizId(),
                ':ordering' => $qq->getOrdering(),
            ]);

            return true;
        } catch (Exception $e) {
            die("Erreur lors de l'enregistrement : " . $e->getMessage());
        }
    }

    // Delete link
    public function delete($questionId, $quizId)
    {
        global $pdo;
        $sql = "DELETE FROM `quiz_question` WHERE question_id = :question_id AND quiz_id = :quiz_id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                ':question_id' => $questionId,
                ':quiz_id' => $quizId
            ]);
        } catch (Exception $e) {
            die("Erreur lors de la suppression : " . $e->getMessage());
        }
    }

    // Update ordering
    public function update($questionId, $quizId, $qq)
    {
        global $pdo;
        $sql = "UPDATE `quiz_question`
                SET ordering = :ordering
                WHERE question_id = :question_id AND quiz_id = :quiz_id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                ':question_id' => $questionId,
                ':quiz_id' => $quizId,
                ':ordering' => $qq->getOrdering(),
            ]);

            return true;
        } catch (Exception $e) {
            die("Erreur lors de la mise à jour : " . $e->getMessage());
        }
    }

    // Get record
    public function getById($questionId, $quizId)
    {
        global $pdo;
        $sql = "SELECT * FROM `quiz_question` WHERE question_id = :question_id AND quiz_id = :quiz_id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                ':question_id' => $questionId,
                ':quiz_id' => $quizId
            ]);

            $data = $query->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                return new QuizQuestion(
                    $data['question_id'],
                    $data['quiz_id'],
                    $data['ordering']
                );
            }

            return null;
        } catch (Exception $e) {
            die("Erreur lors de la récupération : " . $e->getMessage());
        }
    }
}
?>
