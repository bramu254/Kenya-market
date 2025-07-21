<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

$id = intval($_GET['id']);
$product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $price = floatval($_POST['price']);
    $category = $_POST['category'];
    $description = $_POST['description'];
    $quantity = intval($_POST['quantity']);

    $conn->query("UPDATE products SET title='$title', price=$price, category='$category', description='$description', stock=$quantity WHERE id=$id");

    header("Location: admin-dashboard.php?msg=Updated");
    exit;
}
?>

<form method="POST" class="p-6 max-w-xl mx-auto bg-white rounded shadow mt-10">
  <h2 class="text-xl font-semibold mb-4">Edit Product</h2>
  <input name="title" value="<?= htmlspecialchars($product['title']) ?>" required class="w-full mb-2 border px-3 py-2 rounded" />
  <input name="price" value="<?= $product['price'] ?>" type="number" required class="w-full mb-2 border px-3 py-2 rounded" />
  <input name="category" value="<?= htmlspecialchars($product['category']) ?>" required class="w-full mb-2 border px-3 py-2 rounded" />
  <input name="quantity" value="<?= $product['stock'] ?>" type="number" required class="w-full mb-2 border px-3 py-2 rounded" />
  <textarea name="description" required class="w-full mb-2 border px-3 py-2 rounded"><?= htmlspecialchars($product['description']) ?></textarea>
  <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save Changes</button>
</form>
