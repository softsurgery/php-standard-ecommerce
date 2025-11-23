<?php

require_once __DIR__ . '/../../controllers/ProductCategoryController.php';
require_once __DIR__ . '/../../models/ProductCategory.php';
$controller = new ProductCategoryController();

$label = $_POST['label'] ?? '';
$description = $_POST['description'] ?? '';
$origin = $_POST['origin'] ?? '';


if (empty($label)) {
    echo json_encode(['success' => false, 'message' => 'Label required']);
    exit;
}

try {
    $category = new ProductCategory(null, $label, $description);
    $category = $controller->save($category);
    header('Location: ../../' . $origin . '?success=true&message=Category added successfully!');
} catch (Exception $e) {
    header('Location: ../../' . $origin . '?success=false&message=' . $e->getMessage());
}
