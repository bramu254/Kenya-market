<?php
// agriculture.php
require_once 'db.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agriculture Products - Kenyatta Market</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-50">
  <?php include 'components/navbar.php'; ?>

  <div class="bg-green-700 py-12 text-white text-center">
    <h1 class="text-4xl font-bold">Agriculture Products</h1>
    <p class="text-lg mt-2">Fresh produce and farming supplies from across Kenya</p>
  </div>

  <div class="max-w-7xl mx-auto px-4 py-10">
    <!-- Search -->
    <form method="GET" class="mb-6 flex items-center max-w-lg mx-auto">
      <input
        type="text"
        name="search"
        placeholder="Search agriculture products..."
        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
        class="flex-grow px-4 py-2 border rounded-l"
      >
      <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-r">Search</button>
    </form>

    <!-- Products Grid -->
    <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
      <?php
      $search = $conn->real_escape_string($_GET['search'] ?? '');
      $query = "SELECT * FROM products WHERE category='agriculture'";
      if (!empty($search)) {
        $query .= " AND title LIKE '%$search%'";
      }
      $result = $conn->query($query);

      if ($result && $result->num_rows > 0):
        while ($product = $result->fetch_assoc()):
          $image = !empty($product['image']) ? $product['image'] : 'assets/default.jpg';
      ?>
        <div class="bg-white rounded shadow overflow-hidden">
          <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($product['title']) ?>" class="w-full h-48 object-cover">
          <div class="p-4">
            <h3 class="font-semibold text-lg mb-1"><?= htmlspecialchars($product['title']) ?></h3>
            <p class="text-green-600 font-bold mb-1">KSh <?= number_format($product['price']) ?></p>
            <p class="text-sm text-gray-500 mb-3"><?= htmlspecialchars($product['seller']) ?> | <?= htmlspecialchars($product['location']) ?></p>
            <form method="POST" action="add-to-cart.php">
              <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
              <button type="submit" class="bg-green-600 text-white w-full py-2 rounded hover:bg-green-700">Add to Cart</button>
            </form>
          </div>
        </div>
      <?php endwhile; else: ?>
        <p class="text-center col-span-full text-gray-600">No agriculture products found.</p>
      <?php endif; ?>
    </div>
  </div>

  <?php include 'components/footer.php'; ?>
</body>
</html>
