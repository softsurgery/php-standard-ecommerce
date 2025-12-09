<?php

require_once __DIR__ . '/../../controllers/ProductCategoryController.php';
require_once __DIR__ . '/../../models/ProductCategory.php';

$controller = new ProductCategoryController();

// Collect form data
$id = $_POST['id'] ?? null;
$label = $_POST['label'] ?? '';
$description = $_POST['description'] ?? '';
$origin = $_POST['origin'] ?? '';

// Validate
if (empty($id)) {
    header('Location: ../../' . $origin . '?success=false&message=Id is not provided!');
    exit;
}

if (empty($label)) {
    header('Location: ../../' . $origin . '?success=false&message=Label is not provided!');
    exit;
}

try {
    // Create category object with ID for updating
    $category = new ProductCategory($id, $label, $description);

    // Call controller to update
    $updatedCategory = $controller->update($id, $category);

    header('Location: ../../' . $origin . '?success=true&message=Category updated successfully!');
} catch (Exception $e) {
    header('Location: ../../' . $origin . '?success=false&message=' . $e->getMessage());
}
