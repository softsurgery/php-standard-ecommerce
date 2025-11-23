<?php

    header('Content-Type: application/json');

    require_once '../../controllers/ProductCategoryController.php';
    require_once '../../models/ProductCategory.php';
    require_once '../../lib/error.php';
    $controller = new ProductCategoryController();

    $label = $_POST['label'] ?? '';
    $description = $_POST['description'] ?? '';


    if (empty($label)) {
        echo json_encode(['success' => false, 'message' => 'Label required']);
        exit;
    }

try {
    $category = new ProductCategory(null, $label, $description);
    $category = $controller->save($category);

    echo json_encode([
        'success' => true,
        'data' => $category->toArray()
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>