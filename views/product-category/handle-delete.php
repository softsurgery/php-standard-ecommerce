<?php

require_once __DIR__ . '/../../controllers/ProductCategoryController.php';
$controller = new ProductCategoryController();

$id = $_GET['id'] ?? '';
$origin = $_GET['origin'] ?? '';


if (empty($id)) {
    header('Location: '. $origin . '?success=false&message=Id required!');
    exit;
}

try {
    $category = $controller->delete($id);
    header('Location: '.  $origin . '?success=true&message=Category deleted successfully!');
} catch (Exception $e) {
    header('Location: ' . $origin . '?success=false&message=' . $e->getMessage());
}
