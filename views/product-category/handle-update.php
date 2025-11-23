<?php

header('Content-Type: application/json');

require_once '../../controllers/ProductCategoryController.php';
require_once '../../models/ProductCategory.php';
require_once '../../lib/error.php';

$controller = new ProductCategoryController();

// Collect form data
$id = $_POST['id'] ?? null;
$label = $_POST['label'] ?? '';
$description = $_POST['description'] ?? '';

// Validate
if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'Category ID is required']);
    exit;
}

if (empty($label)) {
    echo json_encode(['success' => false, 'message' => 'Label is required']);
    exit;
}

try {
    // Create category object with ID for updating
    $category = new ProductCategory($id, $label, $description);

    // Call controller to update
    $updatedCategory = $controller->update($id, $category);

    echo json_encode([
        'success' => true,
        'data' => $updatedCategory->toArray()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

?>
