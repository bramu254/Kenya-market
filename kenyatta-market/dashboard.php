<?php
include 'db.php';
include 'components/navbar.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simulate logged-in user (replace with actual session check)
$userId = $_SESSION['user_id'] ?? 1;

$user = null;
$cartItems = 0;
$cartTotal = 0;

// Fetch user data
$stmt = $conn->prepare("SELECT first_name FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  $user = $result->fetch_assoc();
}

// Fetch cart stats
$stmt = $conn->prepare("SELECT COUNT(*) AS total_items, SUM(p.price * c.quantity) AS total_price
  FROM cart c
  JOIN products p ON p.id = c.product_id
  WHERE c.user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
  $cartItems = $row['total_items'];
  $cartTotal = $row['total_price'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Kenyatta Market</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Welcome -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Welcome, <?= htmlspecialchars($user['first_name'] ?? 'Guest') ?>! 👋</h1>
      <p class="text-gray-600">Here's what's happening with your Kenyatta Market account today.</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded shadow p-6">
        <div class="flex justify-between items-center">
          <div>
            <p class="text-sm text-gray-600">Cart Items</p>
            <p class="text-2xl font-bold text-gray-900"><?= $cartItems ?></p>
          </div>
          <div class="text-blue-600 text-3xl">🛍️</div>
        </div>
      </div>
      <div class="bg-white rounded shadow p-6">
        <div class="flex justify-between items-center">
          <div>
            <p class="text-sm text-gray-600">Cart Value</p>
            <p class="text-2xl font-bold text-gray-900">KSh <?= number_format($cartTotal) ?></p>
          </div>
          <div class="text-green-600 text-3xl">📦</div>
        </div>
      </div>
      <div class="bg-white rounded shadow p-6">
        <div class="flex justify-between items-center">
          <div>
            <p class="text-sm text-gray-600">Wishlist</p>
            <p class="text-2xl font-bold text-gray-900">12</p>
          </div>
          <div class="text-red-600 text-3xl">❤️</div>
        </div>
      </div>
      <div class="bg-white rounded shadow p-6">
        <div class="flex justify-between items-center">
          <div>
            <p class="text-sm text-gray-600">Orders</p>
            <p class="text-2xl font-bold text-gray-900">3</p>
          </div>
          <div class="text-purple-600 text-3xl">📈</div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded shadow mb-8">
      <div class="border-b px-6 py-4 font-semibold text-lg">Quick Actions</div>
      <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="marketplace.php" class="block text-white text-center bg-blue-500 rounded py-4 font-semibold hover:opacity-90">🛍️ Browse Marketplace</a>
        <a href="sell.php" class="block text-white text-center bg-green-500 rounded py-4 font-semibold hover:opacity-90">📦 Start Selling</a>
        <a href="cart.php" class="block text-white text-center bg-purple-500 rounded py-4 font-semibold hover:opacity-90">🛒 View Cart</a>
        <a href="orders.php" class="block text-white text-center bg-orange-500 rounded py-4 font-semibold hover:opacity-90">📈 My Orders</a>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Recent Orders -->
      <div class="bg-white rounded shadow">
        <div class="border-b px-6 py-4 font-semibold text-lg">Recent Orders</div>
        <div class="p-6 space-y-4">
          <div class="flex justify-between items-center bg-gray-50 p-4 rounded">
            <div>
              <p class="font-semibold">Order #1234</p>
              <p class="text-sm text-gray-600">2 items • KSh 3,500</p>
            </div>
            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">Delivered</span>
          </div>
          <div class="flex justify-between items-center bg-gray-50 p-4 rounded">
            <div>
              <p class="font-semibold">Order #1235</p>
              <p class="text-sm text-gray-600">1 item • KSh 55,000</p>
            </div>
            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">Shipping</span>
          </div>
        </div>
      </div>

      <!-- Favorite Categories -->
      <div class="bg-white rounded shadow">
        <div class="border-b px-6 py-4 font-semibold text-lg">Categories You Love</div>
        <div class="p-6 space-y-3">
          <a href="category.php?name=electronics" class="flex justify-between hover:bg-gray-50 p-3 rounded transition">
            <span>Electronics</span>
            <span class="text-sm text-gray-500">15 purchases</span>
          </a>
          <a href="category.php?name=agriculture" class="flex justify-between hover:bg-gray-50 p-3 rounded transition">
            <span>Agriculture</span>
            <span class="text-sm text-gray-500">8 purchases</span>
          </a>
          <a href="category.php?name=fashion" class="flex justify-between hover:bg-gray-50 p-3 rounded transition">
            <span>Fashion</span>
            <span class="text-sm text-gray-500">5 purchases</span>
          </a>
        </div>
      </div>
    </div>

  </div>

<?php include 'components/footer.php'; ?>
</body>
</html>
