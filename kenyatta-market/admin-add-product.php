<?php
session_start();
require 'db.php';

// Only allow admin to add products
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $price = floatval($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);
    $quantity = intval($_POST['quantity']);
    $description = $conn->real_escape_string($_POST['description']);

    // Handle image upload
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($imageExt, $allowed)) {
            $newImageName = uniqid("img_", true) . "." . $imageExt;
            $imagePath = $uploadDir . $newImageName;

            if (!move_uploaded_file($imageTmp, $imagePath)) {
                die("Failed to move uploaded image.");
            }
        } else {
            die("Invalid image type. Allowed: jpg, jpeg, png, gif, webp");
        }
    } else {
        die("Image upload failed. Please select a valid image.");
    }

    // Insert product into database
    $sql = "INSERT INTO products (title, price, category, quantity, description, image)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssss", $title, $price, $category, $quantity, $description, $imagePath);

    if ($stmt->execute()) {
        header("Location: admin-dashboard.php?msg=Product+added+successfully");
        exit();
    } else {
        echo "Error adding product: " . $conn->error;
    }
}
?>
