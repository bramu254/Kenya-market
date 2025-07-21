<?php
session_start();
include 'db.php';

// Only allow admins
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied. Admins only.";
    exit;
}

// Fetch users and products
$users_sql = "SELECT id, CONCAT(first_name, ' ', last_name) AS name, email, role FROM users";
$users = $conn->query($users_sql);

$products_sql = "SELECT id, title, price, category, image, quantity FROM products";
$products = $conn->query($products_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen p-6">
  <div class="max-w-7xl mx-auto space-y-16">

 <div class="flex justify-between items-center mb-6">
  <h1 class="text-3xl font-bold text-gray-800">🛠️ Admin Panel</h1>
  <div class="space-x-2">
    <a href="admin-chat.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
      💬 Manage Chats
    </a>
    <a href="chat.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
      🗨️ Admin Chat
    </a>
    <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
      🚪 Logout
    </a>
  </div>
</div>


    <!-- USERS SECTION -->
    <section>
      <h2 class="text-2xl font-semibold mb-4 text-gray-700">👥 All Users</h2>
      <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
        <?php while ($u = $users->fetch_assoc()): ?>
          <div class="bg-white p-4 rounded shadow space-y-2">
            <div><strong>Name:</strong> <?= htmlspecialchars($u['name']) ?></div>
            <div><strong>Email:</strong> <?= htmlspecialchars($u['email']) ?></div>
            <div><strong>Role:</strong> <?= htmlspecialchars($u['role']) ?></div>
            <div>
              <?php if ($u['role'] !== 'admin'): ?>
                <form method="POST" action="admin-delete-user.php" onsubmit="return confirm('Delete this user?');">
                  <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                  <button type="submit" class="text-red-600 hover:underline">🗑️ Delete</button>
                </form>
              <?php else: ?>
                <span class="text-gray-400 italic">Admin</span>
              <?php endif; ?>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </section>

    <!-- ADD USER FORM -->
    <section>
      <h2 class="text-2xl font-semibold mb-4 text-gray-700">➕ Add New User</h2>
      <form method="POST" action="admin-add-user.php" class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-6 rounded shadow">
        <input type="text" name="first_name" placeholder="First Name" class="border rounded px-3 py-2" required>
        <input type="text" name="last_name" placeholder="Last Name" class="border rounded px-3 py-2" required>
        <input type="email" name="email" placeholder="Email" class="border rounded px-3 py-2" required>
        <input type="text" name="phone" placeholder="Phone" class="border rounded px-3 py-2" required>
        <input type="text" name="county" placeholder="County" class="border rounded px-3 py-2" required>
        <input type="password" name="password" placeholder="Password" class="border rounded px-3 py-2" required>
        <select name="role" class="border rounded px-3 py-2" required>
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
        <div class="md:col-span-2">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add User</button>
        </div>
      </form>
    </section>

    <!-- PRODUCTS SECTION -->
    <section>
      <h2 class="text-2xl font-semibold mb-4 text-gray-700">🛒 All Products</h2>
      <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
        <?php while ($p = $products->fetch_assoc()): ?>
          <div class="bg-white p-4 rounded shadow space-y-2">
            <?php if (!empty($p['image'])): ?>
              <img src="<?= htmlspecialchars($p['image']) ?>" alt="Product Image" class="w-full h-48 object-cover rounded">
            <?php endif; ?>
            <div><strong>Title:</strong> <?= htmlspecialchars($p['title']) ?></div>
            <div><strong>Price:</strong> KSh <?= number_format($p['price']) ?></div>
            <div><strong>Category:</strong> <?= htmlspecialchars($p['category']) ?></div>
            <div><strong>Quantity:</strong> <?= htmlspecialchars($p['quantity']) ?></div>
            <form method="POST" action="admin-delete-product.php" onsubmit="return confirm('Delete this product?');">
              <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
              <button type="submit" class="text-red-600 hover:underline">🗑️ Delete</button>
            </form>
          </div>
        <?php endwhile; ?>
      </div>
    </section>

    <!-- ADD PRODUCT FORM -->
    <section>
      <h2 class="text-2xl font-semibold mb-4 text-gray-700">➕ Add New Product</h2>
      <form method="POST" action="admin-add-product.php" enctype="multipart/form-data"
            class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-6 rounded shadow">
        <input type="text" name="title" placeholder="Product Title" class="border rounded px-3 py-2" required>
        <input type="number" name="price" placeholder="Price (KSh)" class="border rounded px-3 py-2" required>
        <input type="text" name="category" placeholder="Category" class="border rounded px-3 py-2" required>
        <input type="number" name="quantity" placeholder="Quantity in Stock" class="border rounded px-3 py-2" required>
        <input type="file" name="image" accept="image/*" class="md:col-span-2 border rounded px-3 py-2 bg-white" required>
        <textarea name="description" placeholder="Product Description"
                  class="md:col-span-2 border rounded px-3 py-2" required></textarea>
        <div class="md:col-span-2">
          <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Add Product</button>
        </div>
      </form>
    </section>

  </div>
</body>
</html>
