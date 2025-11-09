<?php
include '../config.php';
include_once '../models/ProductCategory.php';

class ProductCategoryController
{
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