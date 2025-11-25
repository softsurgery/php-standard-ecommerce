<?php
require_once  __DIR__ . '/../models/config.php';
require_once  __DIR__ . '/../models/Quiz.php';

class QuizController
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

        try {
            $countSql = "SELECT COUNT(*) as total FROM `quiz`";
            $stmt = $pdo->query($countSql);
            $total = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];

            $pageCount = (int)ceil($total / $pageSize);
            $offset = ($page - 1) * $pageSize;

            $dataSql = "SELECT * FROM `quiz` ORDER BY id DESC LIMIT :limit OFFSET :offset";
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

    // ✅ Get all quizes
    public function getAll()
    {
        global $pdo;
        $sql = "SELECT * FROM `quiz`";
        try {
            $query = $pdo->query($sql);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage());
        }
    }

    // ✅ Add new quiz
    public function save($quiz)
    {
        global $pdo;

        $sql = "INSERT INTO `quiz` (name, description)
            VALUES (:name, :description)";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                ':name' => $quiz->getName(),
                ':description' => $quiz->getDescription()
            ]);

            // ✅ Get the last inserted ID
            $lastId = $pdo->lastInsertId();

            return $this->getById($lastId);
        } catch (Exception $e) {
            die("Erreur lors de l'enregistrement : " . $e->getMessage());
        }
    }

    // ✅ Delete quiz by ID
    public function delete($id)
    {
        global $pdo;
        $sql = "DELETE FROM `quiz` WHERE id = :id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([':id' => $id]);
        } catch (Exception $e) {
            die("Erreur lors de la suppression : " . $e->getMessage());
        }
    }

    // ✅ Update quiz info
    public function update($id, $quiz)
    {
        global $pdo;
        $sql = "UPDATE `quiz` 
                SET name = :name, description = :description
                WHERE id = :id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                ':id' => $id,
                ':label' => $quiz->getName(),
                ':description' => $quiz->getDescription()
            ]);
            return $this->getById($id);
        } catch (Exception $e) {
            die("Erreur lors de la mise à jour : " . $e->getMessage());
        }
    }

    // ✅ Get quiz by ID
    public function getById($id)
    {
        global $pdo;
        $sql = "SELECT * FROM `quiz` WHERE id = :id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([':id' => $id]);
            $data = $query->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                return new Quiz(
                    $data['id'],
                    $data['name'],
                    $data['description'],
                );
            }
            return null;
        } catch (Exception $e) {
            die("Erreur lors de la récupération : " . $e->getMessage());
        }
    }
}
?>