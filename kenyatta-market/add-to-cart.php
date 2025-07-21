<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Fetch product to check stock
    $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $stmt->bind_result($stock);
    $stmt->fetch();
    $stmt->close();

    if ($stock < $quantity) {
        $_SESSION['error_message'] = "Only $stock items in stock. Please adjust quantity.";
        header("Location: marketplace.php");
        exit();
    }

    // Proceed to add to cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][$productId] = $quantity;
    $_SESSION['success_message'] = "Item added to cart.";
    header("Location: cart.php");
    exit();
}
?>
