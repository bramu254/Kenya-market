<?php
// Start session before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../db.php');
require_once '../components/navbar.php';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? 'all';

$sql = "SELECT * FROM products WHERE 1";
$params = [];
$types = '';

// Add search filter
if (!empty($search)) {
    $sql .= " AND title LIKE ?";
    $params[] = "%$search%";
    $types .= 's';
}

// Add category filter
if ($category !== 'all') {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= 's';
}

$stmt = $conn->prepare($sql);

// Bind parameters safely if any exist
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Marketplace - Kenyatta Market</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
<div class="min-h-screen">

<!-- Header -->
<div class="bg-white shadow-sm border-b">
  <div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Marketplace</h1>
    <form method="GET" class="flex flex-col md:flex-row gap-4">
      <div class="flex-1 relative">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search products..." class="pl-10 h-12 w-full border rounded" />
        <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </div>
      <select name="category" class="w-full md:w-48 h-12 border rounded">
        <option value="all" <?= $category === 'all' ? 'selected' : '' ?>>All Categories</option>
        <option value="agriculture" <?= $category === 'agriculture' ? 'selected' : '' ?>>Agriculture</option>
        <option value="electronics" <?= $category === 'electronics' ? 'selected' : '' ?>>Electronics</option>
        <option value="fashion" <?= $category === 'fashion' ? 'selected' : '' ?>>Fashion</option>
        <option value="home" <?= $category === 'home' ? 'selected' : '' ?>>Home & Garden</option>
        <option value="automotive" <?= $category === 'automotive' ? 'selected' : '' ?>>Automotive</option>
        <option value="health" <?= $category === 'health' ? 'selected' : '' ?>>Health & Beauty</option>
      </select>
      <button class="h-12 px-4 bg-blue-600 text-white rounded">Filter</button>
    </form>
  </div>
</div>

<!-- Products -->
<div class="max-w-7xl mx-auto px-4 py-8">
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <?php foreach ($products as $product): ?>
      <div class="bg-white rounded shadow hover:shadow-md group cursor-pointer">
        <div class="relative overflow-hidden">
          <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['title']) ?>" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
          <div class="absolute top-2 right-2">
            <div class="bg-green-600 text-white px-2 py-1 rounded-full text-xs font-semibold">
              ★ <?= htmlspecialchars($product['rating'] ?? '4.0') ?>
            </div>
          </div>
        </div>
        <div class="p-4">
          <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2"><?= htmlspecialchars($product['title']) ?></h3>
          <p class="text-2xl font-bold text-green-600 mb-2">KSh <?= number_format($product['price']) ?></p>
          <div class="flex justify-between text-sm text-gray-600 mb-3">
            <span><?= htmlspecialchars($product['seller'] ?? 'Seller') ?></span>
            <span><?= htmlspecialchars($product['location'] ?? 'Nairobi') ?></span>
          </div>
          <form method="POST" action="add-to-cart.php">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <button type="submit" class="w-full h-10 bg-green-600 text-white rounded">Add to Cart</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if (empty($products)): ?>
      <div class="col-span-full text-center py-12">
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No products found</h3>
        <p class="text-gray-600">Try adjusting your search or filter criteria</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Footer -->
<?php include '../components/footer.php'; ?>
</div>
</body>
</html>
