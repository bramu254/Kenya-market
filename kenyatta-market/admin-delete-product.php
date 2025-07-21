<?php
session_start();
require 'db.php';

// Allow only admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    // Optional: Fetch the image path to delete the image file from server
    $imageQuery = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $imageQuery->bind_param("i", $product_id);
    $imageQuery->execute();
    $imageResult = $imageQuery->get_result();
    $imageRow = $imageResult->fetch_assoc();
    if ($imageRow && !empty($imageRow['image']) && file_exists($imageRow['image'])) {
        unlink($imageRow['image']); // Delete the image file
    }

    // Delete the product
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        header("Location: admin-dashboard.php?msg=Product+deleted+successfully");
        exit();
    } else {
        echo "Error deleting product: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
