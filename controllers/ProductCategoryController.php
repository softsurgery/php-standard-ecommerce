<?php
require_once  __DIR__ . '/../models/config.php';
require_once  __DIR__ . '/../models/ProductCategory.php';

class ProductCategoryController
{

     /**
     * Get paginated categories
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
            $countSql = "SELECT COUNT(*) as total FROM `product-category`";
            $stmt = $pdo->query($countSql);
            $total = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];

            $pageCount = (int)ceil($total / $pageSize);
            $offset = ($page - 1) * $pageSize;

            $dataSql = "SELECT * FROM `product-category` ORDER BY id DESC LIMIT :limit OFFSET :offset";
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

    // ✅ Get all categories
    public function getAll()
    {
        global $pdo;
        $sql = "SELECT * FROM `product-category`";
        try {
            $query = $pdo->query($sql);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage());
        }
    }

    // ✅ Add new category
    public function save($category)
    {
        global $pdo;

        $sql = "INSERT INTO `product-category` (label, description)
            VALUES (:label, :description)";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                ':label' => $category->getLabel(),
                ':description' => $category->getDescription()
            ]);

            // ✅ Get the last inserted ID
            $lastId = $pdo->lastInsertId();

            return $this->getById($lastId);
        } catch (Exception $e) {
            die("Erreur lors de l'enregistrement : " . $e->getMessage());
        }
    }

    // ✅ Delete category by ID
    public function delete($id)
    {
        global $pdo;
        $sql = "DELETE FROM `product-category` WHERE id = :id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([':id' => $id]);
        } catch (Exception $e) {
            die("Erreur lors de la suppression : " . $e->getMessage());
        }
    }

    // ✅ Update category info
    public function update($id, $category)
    {
        global $pdo;
        $sql = "UPDATE `product-category` 
                SET label = :label, description = :description
                WHERE id = :id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([
                ':id' => $id,
                ':label' => $category->getLabel(),
                ':description' => $category->getDescription()
            ]);
            return $this->getById($id);
        } catch (Exception $e) {
            die("Erreur lors de la mise à jour : " . $e->getMessage());
        }
    }

    // ✅ Get category by ID
    public function getById($id)
    {
        global $pdo;
        $sql = "SELECT * FROM `product-category` WHERE id = :id";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([':id' => $id]);
            $data = $query->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                return new ProductCategory(
                    $data['id'],
                    $data['label'],
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