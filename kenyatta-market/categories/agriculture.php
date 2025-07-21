<?php
require_once '../db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Agriculture Products - Kenyatta Market</title>
  <script src="https://cdn.tailwindcss.com"></script>
   <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-50">

  <?php include '../components/navbar.php'; ?>

  <!-- Hero Section -->
  <section class="relative bg-[url('https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=1600')] bg-cover bg-center text-white py-24">
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>
    <div class="relative z-10 max-w-4xl mx-auto text-center px-4">
      <h1 class="text-4xl md:text-5xl font-bold mb-4">Agriculture Products</h1>
      <p class="text-lg md:text-xl text-gray-200">Fresh produce and farming supplies from across Kenya</p>
    </div>
  </section>

  <!-- Search and Product Grid -->
  <div class="max-w-7xl mx-auto px-4 py-12">
    <!-- Search Box -->
    <form method="GET" class="flex flex-col sm:flex-row gap-4 mb-10">
      <input type="text" name="search" placeholder="Search agriculture products..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" class="flex-1 h-12 px-4 border rounded focus:outline-none focus:ring focus:border-blue-300" />
      <button type="submit" class="h-12 px-6 bg-green-600 text-white rounded hover:bg-green-700">Search</button>
    </form>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php
      $search = $conn->real_escape_string($_GET['search'] ?? '');
      $query = "SELECT * FROM products WHERE category='agriculture'";
      if (!empty($search)) {
        $query .= " AND title LIKE '%$search%'";
      }
      $result = $conn->query($query);
      if ($result && $result->num_rows > 0):
        while ($product = $result->fetch_assoc()):
      ?>
      <div class="bg-white rounded shadow hover:shadow-md overflow-hidden">
        <img 
  src="<?= !empty($product['image_url']) ? htmlspecialchars($product['image_url']) : '../assets/images/default.jpg' ?>" 
  alt="<?= htmlspecialchars($product['title']) ?>" 
  class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="font-semibold text-lg text-gray-800 mb-1"><?= htmlspecialchars($product['title']) ?></h3>
          <p class="text-green-600 font-bold text-xl mb-2">KSh <?= number_format($product['price']) ?></p>
          <div class="text-sm text-gray-500 mb-3">
            <?= htmlspecialchars($product['seller']) ?> | <?= htmlspecialchars($product['location']) ?>
          </div>
          <form method="POST" action="../add-to-cart.php">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <button type="submit" class="w-full h-10 bg-green-600 text-white rounded hover:bg-green-700">Add to Cart</button>
          </form>
        </div>
      </div>
      <?php endwhile; else: ?>
      <div class="col-span-full text-center py-12">
        <h3 class="text-xl font-semibold text-gray-700 mb-2">No agriculture products found</h3>
        <p class="text-gray-500">Try searching for something else</p>
      </div>
      <?php endif; ?>
    </div>
  </div>

 <?php include(__DIR__ . '/../components/footer.php'); ?>
</body>
</html>
