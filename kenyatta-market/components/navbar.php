<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user = $_SESSION['user'] ?? null;
$totalItems = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<nav class="bg-white shadow-lg sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16 items-center">
      <!-- Logo -->
      <div class="flex items-center">
        <a href="index.php" class="flex items-center text-2xl font-bold">
          <span class="text-green-600">Kenyatta</span><span class="text-red-600 ml-1">Market</span>
        </a>
      </div>

      <!-- Desktop Nav -->
      <div class="hidden md:flex items-center space-x-8">
        <a href="marketplace.php" class="text-gray-700 hover:text-green-600 transition">Marketplace</a>

        <!-- Categories Dropdown (JS-controlled) -->
        <div class="relative">
          <button onclick="toggleDropdown()" class="text-gray-700 hover:text-green-600 transition inline-flex items-center">
            Categories
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          <div id="dropdownMenu" class="absolute left-0 mt-2 w-60 bg-white border rounded shadow-lg hidden z-50">
            <a href="agriculture.php" class="block px-4 py-2 hover:bg-gray-100">🌾 Agriculture</a>
            <a href="electronics.php" class="block px-4 py-2 hover:bg-gray-100">🔌 Electronics</a>
            <a href="fashion.php" class="block px-4 py-2 hover:bg-gray-100">👗 Fashion</a>
            <a href="automotive.php" class="block px-4 py-2 hover:bg-gray-100">🚗 Automotive</a>
            <a href="home-garden.php" class="block px-4 py-2 hover:bg-gray-100">🏡 Home & Garden</a>
            <a href="health-beauty.php" class="block px-4 py-2 hover:bg-gray-100">💅 Health & Beauty</a>
          </div>
        </div>

        <a href="sell.php" class="text-gray-700 hover:text-green-600 transition">Sell</a>
        <a href="cart.php" class="relative text-gray-700 hover:text-green-600">
          🛒
          <?php if ($totalItems > 0): ?>
            <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
              <?= $totalItems ?>
            </span>
          <?php endif; ?>
        </a>

        <?php if ($user): ?>
          <a href="orders.php" class="text-gray-700 hover:text-green-600">📦</a>
          <a href="seller-dashboard.php" class="text-gray-700 hover:text-green-600">🏪</a>
          <a href="chat.php" class="text-gray-700 hover:text-green-600">💬 Chat with Admin</a>
          <a href="profile.php" class="text-gray-700 hover:text-green-600">👤 <?= htmlspecialchars($user) ?></a>
          <a href="logout.php" class="text-red-600 hover:text-red-800">🚪 Logout</a>
        <?php else: ?>
          <a href="login.php" class="text-gray-700 hover:text-green-600">Login</a>
          <a href="signup.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Sign Up</a>
        <?php endif; ?>
      </div>

      <!-- Mobile Menu Button -->
      <div class="md:hidden">
        <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="text-gray-700 hover:text-green-600">
          ☰
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div id="mobile-menu" class="hidden md:hidden px-4 pb-4 bg-white border-t shadow space-y-1">
    <a href="marketplace.php" class="block px-3 py-2 text-gray-700 hover:text-green-600">Marketplace</a>
    <div class="border-t py-2 text-sm text-gray-500 font-semibold">Categories</div>
    <a href="agriculture.php" class="block px-3 py-2 text-gray-700 hover:text-green-600">🌾 Agriculture</a>
    <a href="electronics.php" class="block px-3 py-2 text-gray-700 hover:text-green-600">🔌 Electronics</a>
    <a href="fashion.php" class="block px-3 py-2 text-gray-700 hover:text-green-600">👗 Fashion</a>
    <a href="automotive.php" class="block px-3 py-2 text-gray-700 hover:text-green-600">🚗 Automotive</a>
    <a href="home-garden.php" class="block px-3 py-2 text-gray-700 hover:text-green-600">🏡 Home & Garden</a>
    <a href="health-beauty.php" class="block px-3 py-2 text-gray-700 hover:text-green-600">💅 Health & Beauty</a>
    <a href="sell.php" class="block px-3 py-2 text-gray-700 hover:text-green-600">Sell</a>
    <a href="cart.php" class="block px-3 py-2 text-gray-700 hover:text-green-600">Cart (<?= $totalItems ?>)</a>

    <?php if ($user): ?>
      <a href="orders.php" class="block px-3 py-2 text-gray-700 hover:text-green-600">My Orders</a>
      <a href="seller-dashboard.php" class="block px-3 py-2 text-gray-700 hover:text-green-600">Seller Dashboard</a>
      <a href="chat.php" class="block px-3 py-2 text-gray-700 hover:text-green-600">Chat with Admin</a>
      <a href="profile.php" class="block px-3 py-2 text-gray-700 hover:text-green-600">Profile (<?= htmlspecialchars($user) ?>)</a>
      <a href="logout.php" class="block px-3 py-2 text-red-600 hover:text-red-800">Logout</a>
    <?php else: ?>
      <a href="login.php" class="block px-3 py-2 text-gray-700 hover:text-green-600">Login</a>
      <a href="signup.php" class="block px-3 py-2 text-white bg-green-600 rounded hover:bg-green-700 text-center">Sign Up</a>
    <?php endif; ?>
  </div>
</nav>

<!-- JavaScript to toggle dropdown -->
<script>
  function toggleDropdown() {
    const menu = document.getElementById('dropdownMenu');
    menu.classList.toggle('hidden');
    document.addEventListener('click', function handler(e) {
      if (!menu.contains(e.target) && !e.target.closest('button')) {
        menu.classList.add('hidden');
        document.removeEventListener('click', handler);
      }
    });
  }
</script>
