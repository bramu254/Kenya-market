<?php
include(__DIR__ . '/../db.php');
include '../components/navbar.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$searchTerm = $_GET['search'] ?? '';

// Fetch electronics from DB
if ($searchTerm) {
  $stmt = $conn->prepare("SELECT * FROM products WHERE category = 'electronics' AND title LIKE ?");
  $likeTerm = '%' . $searchTerm . '%';
  $stmt->bind_param("s", $likeTerm);
} else {
  $stmt = $conn->prepare("SELECT * FROM products WHERE category = 'electronics'");
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Electronics - Kenyatta Market</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">

<!-- Hero Section -->
<div class="bg-blue-600 text-white py-16">
  <div class="max-w-7xl mx-auto px-4 text-center">
    <h1 class="text-4xl font-bold mb-4">Electronics</h1>
    <p class="text-xl">Latest gadgets and technology from trusted sellers</p>
  </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 py-8">

  <!-- Search -->
  <form method="GET" class="mb-8 max-w-md">
    <div class="relative">
      <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      <input
        type="text"
        name="search"
        value="<?= htmlspecialchars($searchTerm) ?>"
        placeholder="Search electronics..."
        class="pl-10 h-12 w-full rounded border border-gray-300"
      />
    </div>
  </form>

  <!-- Product Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="bg-white rounded-lg overflow-hidden shadow group cursor-pointer">
        <div class="relative">
          <img src="<?= $row['image'] ?>" alt="<?= $row['title'] ?>" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
          <div class="absolute top-2 right-2 bg-blue-600 text-white px-2 py-1 rounded-full text-xs font-semibold">
            ★ <?= number_format($row['rating'], 1) ?>
          </div>
        </div>
        <div class="p-4">
          <h3 class="font-semibold text-gray-900 mb-2"><?= $row['title'] ?></h3>
          <p class="text-2xl font-bold text-blue-600 mb-2">KSh <?= number_format($row['price']) ?></p>
          <div class="flex justify-between text-sm text-gray-600 mb-3">
            <span><?= $row['seller'] ?></span>
            <span><?= $row['location'] ?></span>
          </div>
          <form method="POST" action="cart.php">
            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center justify-center">
              🛒 Add to Cart
            </button>
          </form>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<?php include '../components/footer.php'; ?>
</body>
</html>
