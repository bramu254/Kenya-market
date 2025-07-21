<?php
include 'db.php';
include 'components/navbar.php';

$successMsg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $price = (float) $_POST['price'];
  $category = $_POST['category'];
  $quantity = (int) $_POST['quantity'];
  $description = $_POST['description'];

  // Image upload handler
  $imagePath = 'uploads/default.jpg'; // fallback

  if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
    $targetDir = "uploads/";
    $filename = basename($_FILES["image_file"]["name"]);
    $uniqueName = time() . "_" . $filename;
    $targetFilePath = $targetDir . $uniqueName;

    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileType, $allowed)) {
      if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $targetFilePath)) {
        $imagePath = $targetFilePath;
      }
    }
  }

  // Insert product into database
  $stmt = $conn->prepare("INSERT INTO products (title, price, category, quantity, sold, image, description) VALUES (?, ?, ?, ?, 0, ?, ?)");
  $stmt->bind_param("sdssss", $title, $price, $category, $quantity, $imagePath, $description);

  if ($stmt->execute()) {
    $successMsg = "Product added successfully!";
  }
}

$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
$totalRevenue = 0;
$totalSold = 0;
$totalProducts = $products->num_rows;

$productData = [];
while ($row = $products->fetch_assoc()) {
  $sold = $row['sold'] ?? 0;
  $qty = $row['quantity'] ?? 0;
  $totalRevenue += $row['price'] * $sold;
  $totalSold += $sold;
  $row['remaining'] = $qty - $sold;
  $productData[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Seller Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Seller Dashboard</h1>
      <a href="#add-product" class="bg-green-600 text-white px-4 py-2 rounded">Add Product</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="p-6 bg-white shadow rounded">
        <p class="text-gray-600">Total Revenue</p>
        <p class="text-2xl font-bold">KSh <?= number_format($totalRevenue) ?></p>
      </div>
      <div class="p-6 bg-white shadow rounded">
        <p class="text-gray-600">Total Products</p>
        <p class="text-2xl font-bold"><?= $totalProducts ?></p>
      </div>
      <div class="p-6 bg-white shadow rounded">
        <p class="text-gray-600">Items Sold</p>
        <p class="text-2xl font-bold"><?= $totalSold ?></p>
      </div>
      <div class="p-6 bg-white shadow rounded">
        <p class="text-gray-600">Total Views</p>
        <p class="text-2xl font-bold">1,234</p>
      </div>
    </div>

    <div class="bg-white shadow rounded mb-8">
      <h2 class="text-xl font-bold p-4 border-b">Your Products</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
        <?php foreach ($productData as $product): ?>
        <div class="bg-gray-100 rounded overflow-hidden">
          <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['title']) ?>" class="w-full h-48 object-cover">
          <div class="p-4">
            <h3 class="font-semibold text-lg mb-1"><?= htmlspecialchars($product['title']) ?></h3>
            <p class="text-green-600 font-bold mb-2">KSh <?= number_format($product['price']) ?></p>
            <p class="text-sm text-gray-600">Category: <?= htmlspecialchars($product['category']) ?></p>
            <p class="text-sm text-gray-600">Quantity: <?= $product['quantity'] ?? 0 ?></p>
            <p class="text-sm text-gray-600">Sold: <?= $product['sold'] ?? 0 ?></p>
            <p class="text-sm text-gray-600">Remaining: <?= $product['remaining'] ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Add Product Form -->
    <div id="add-product" class="bg-white shadow rounded p-6">
      <h2 class="text-xl font-bold mb-4">Add New Product</h2>
      <?php if ($successMsg): ?>
        <p class="mb-4 text-green-600 font-medium">✅ <?= $successMsg ?></p>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="text" name="title" required placeholder="Product Title" class="w-full p-2 border rounded">
        <input type="number" name="price" required placeholder="Price" class="w-full p-2 border rounded" step="0.01">
      <select name="category" required class="w-full p-2 border rounded">
        <option value="">Select Category</option>
        <option value="agriculture">Agriculture</option>
        <option value="electronics">Electronics</option>
        <option value="fashion">Fashion</option>
        <option value="automotive">Automotive</option>
        <option value="home-garden">Home & Garden</option>
        <option value="health-beauty">Health & Beauty</option>
      </select>
        <input type="number" name="quantity" required placeholder="Quantity" class="w-full p-2 border rounded">
        <input type="file" name="image_file" accept="image/*" class="w-full p-2 border rounded">
        <textarea name="description" required rows="3" placeholder="Description" class="w-full p-2 border rounded"></textarea>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Add Product</button>
      </form>
    </div>
  </div>

<?php include 'components/footer.php'; ?>
</body>
</html>
