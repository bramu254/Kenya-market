<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../db.php');
include '../components/navbar.php';

$sql = "SELECT * FROM products WHERE category = 'automotive'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Automotive - Kenyatta Market</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans">

<div class="min-h-screen max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
  <div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900 mb-2">Automotive</h1>
    <p class="text-xl text-gray-600">Cars, motorcycles, parts & accessories</p>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden group hover:shadow-lg transition duration-300">
          <div class="relative h-48 overflow-hidden">
            <img
              src="<?= !empty($row['image_url']) ? htmlspecialchars($row['image_url']) : 'assets/default-car.jpg' ?>"
              alt="<?= htmlspecialchars($row['title']) ?>"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
            />
            <div class="absolute top-2 right-2 bg-white px-2 py-1 rounded-full text-sm font-semibold">
              ⭐ <?= isset($row['rating']) ? number_format($row['rating'], 1) : '4.5' ?>
            </div>
          </div>
          <div class="p-4 space-y-2">
            <h3 class="font-semibold text-lg line-clamp-2"><?= htmlspecialchars($row['title']) ?></h3>
            <p class="text-sm text-gray-500 line-clamp-2"><?= htmlspecialchars($row['description']) ?></p>
            <p class="text-sm text-gray-500">by <?= htmlspecialchars($row['seller']) ?></p>
            <div class="flex justify-between items-center">
              <span class="text-green-600 font-bold text-xl">KSh <?= number_format($row['price']) ?></span>
              <span class="text-sm text-gray-400"><?= $row['stock'] ?> in stock</span>
            </div>

           <form method="POST" action="/add-to-cart.php" class="space-y-2">
        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
       <input
    type="number"
    name="quantity"
    value="1"
    min="1"
    max="<?= $row['stock'] ?>"
    class="w-full border rounded px-2 py-1 text-sm"
    <?= $row['stock'] == 0 ? 'disabled' : '' ?>
  >
  <button
    type="submit"
    class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg text-sm flex items-center justify-center disabled:opacity-50"
    <?= $row['stock'] == 0 ? 'disabled' : '' ?>
  >
    🛒 <?= $row['stock'] > 0 ? 'Add to Cart' : 'Out of Stock' ?>
  </button>
  </form>

          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-span-full text-center text-gray-600 py-20">
        <p class="text-lg">No automotive products found at the moment.</p>
        <a href="marketplace.php" class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">
          Go to Marketplace
        </a>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include '../components/footer.php'; ?>
</body>
</html>
