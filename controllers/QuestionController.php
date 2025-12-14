<?php
require_once __DIR__ . '/../models/config.php';
require_once __DIR__ . '/../models/Question.php';

class QuestionController
{
    /**
     * Get paginated questions
     */
    public function getPaginated($page = 1, $pageSize = 10)
    {
        global $pdo;
        $page = max(1, (int)$page);
        $pageSize = max(1, (int)$pageSize);

        try {
            $countSql = "SELECT COUNT(*) AS total FROM `question`";
            $stmt = $pdo->query($countSql);
            $total = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];

            $pageCount = (int)ceil($total / $pageSize);
            $offset = ($page - 1) * $pageSize;

            $dataSql = "SELECT * FROM `question` ORDER BY id DESC LIMIT :limit OFFSET :offset";
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

    // Get all questions
    public function getAll()
    {
        global $pdo;
        $sql = "SELECT * FROM `question`";
        try {
            $query = $pdo->query($sql);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage());
        }
    }

    // Add new question
    public function save($question)
    {
        global $pdo;
        $sql = "INSERT INTO `question` (label, type, rate, details)
                VALUES (:label, :type, :rate, :details)";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                ':label' => $question->getLabel(),
                ':type' => $question->getType(),
                ':rate' => $question->getRate(),
                ':details' => $question->getDetails()
            ]);

            $lastId = $pdo->lastInsertId();
            return $this->getById($lastId);
        } catch (Exception $e) {
            die("Erreur lors de l'enregistrement : " . $e->getMessage());
        }
    }

    // Delete question
    public function delete($id)
    {
        global $pdo;
        $sql = "DELETE FROM `question` WHERE id = :id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([':id' => $id]);
        } catch (Exception $e) {
            die("Erreur lors de la suppression : " . $e->getMessage());
        }
    }

    // Update question
    public function update($id, $question)
    {
        global $pdo;
        $sql = "UPDATE `question` 
                SET label = :label, type = :type, rate = :rate, details = :details
                WHERE id = :id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                ':id' => $id,
                ':label' => $question->getLabel(),
                ':type' => $question->getType(),
                ':rate' => $question->getRate(),
                ':details' => $question->getDetails()
            ]);

            return $this->getById($id);
        } catch (Exception $e) {
            die("Erreur lors de la mise à jour : " . $e->getMessage());
        }
    }

    // Get question by ID
    public function getById($id)
    {
        global $pdo;
        $sql = "SELECT * FROM `question` WHERE id = :id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([':id' => $id]);
            $data = $query->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                return new Question(
                    $data['id'],
                    $data['label'],
                    $data['type'],
                    $data['rate'],
                    $data['details']
                );
            }

            return null;
        } catch (Exception $e) {
            die("Erreur lors de la récupération : " . $e->getMessage());
        }
    }

    public function getByIds($ids)
    {
        if (empty($ids)) {
            return [];
        }

        global $pdo;

        // Ensure all IDs are integers to avoid SQL injection
        $ids = array_map('intval', $ids);
        $idList = implode(',', $ids);

        $sql = "
        SELECT * 
        FROM `question` 
        WHERE id IN ($idList)
        ORDER BY FIELD(id, $idList)
    ";

        try {
            $query = $pdo->prepare($sql);
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);

            $questions = [];
            foreach ($data as $row) {
                $questions[] = new Question(
                    $row['id'],
                    $row['label'],
                    $row['type'],
                    $row['rate'],
                    $row['details']
                );
            }

            return $questions;
        } catch (Exception $e) {
            die("Erreur lors de la récupération : " . $e->getMessage());
        }
    }
}
