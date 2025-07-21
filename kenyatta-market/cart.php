<?php
ob_start();
session_start();
require_once 'db.php';
include 'components/navbar.php';

// Init cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart (rarely reached via this file now)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && !isset($_POST['update']) && !isset($_POST['remove'])) {
    $productId = intval($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }

    $_SESSION['success_message'] = "Item added to cart successfully!";
    header("Location: cart.php");
    exit();
}

// Update quantity
if (isset($_POST['update'])) {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    $stockCheck = $conn->prepare("SELECT stock FROM products WHERE id = ?");
    $stockCheck->bind_param("i", $productId);
    $stockCheck->execute();
    $stockCheck->bind_result($stock);
    $stockCheck->fetch();
    $stockCheck->close();

    if ($quantity <= 0) {
        unset($_SESSION['cart'][$productId]);
        $_SESSION['success_message'] = "Item removed from cart.";
    } elseif ($quantity > $stock) {
        $_SESSION['success_message'] = "Only $stock item(s) available in stock.";
    } else {
        $_SESSION['cart'][$productId] = $quantity;
        $_SESSION['success_message'] = "Quantity updated.";
    }

    header("Location: cart.php");
    exit();
}

// Remove item
if (isset($_POST['remove'])) {
    $productId = intval($_POST['product_id']);
    unset($_SESSION['cart'][$productId]);
    $_SESSION['success_message'] = "Item removed from cart.";
    header("Location: cart.php");
    exit();
}

// Fetch products in cart
$cartItems = [];
$totalItems = 0;
$totalPrice = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
    $query = $conn->query("SELECT * FROM products WHERE id IN ($ids)");

    while ($row = $query->fetch_assoc()) {
        $pid = $row['id'];
        $row['quantity'] = $_SESSION['cart'][$pid];
        $cartItems[] = $row;
        $totalItems += $row['quantity'];
        $totalPrice += $row['price'] * $row['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shopping Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script>
    setTimeout(() => {
      const toast = document.getElementById('toast');
      if (toast) toast.remove();
    }, 3000);
  </script>
</head>
<body class="bg-gray-50 font-sans">

<?php
if (isset($_SESSION['success_message'])) {
    echo "<div id='toast' class='fixed top-4 right-4 bg-green-600 text-white px-4 py-3 rounded shadow z-50'>
            {$_SESSION['success_message']}
          </div>";
    unset($_SESSION['success_message']);
}
?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
  <h1 class="text-3xl font-bold mb-6">Shopping Cart (<?= $totalItems ?> items)</h1>

  <?php if (empty($cartItems)): ?>
    <div class="text-center text-gray-600 py-20">
      <p class="text-lg">Your cart is empty.</p>
      <a href="marketplace.php" class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">Go to Marketplace</a>
    </div>
  <?php else: ?>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Cart Items -->
    <div class="lg:col-span-2 space-y-6">
      <?php foreach ($cartItems as $item): ?>
        <div class="bg-white rounded shadow p-4 flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="w-20 h-20 object-cover rounded">
            <div>
              <h3 class="font-semibold text-lg"><?= htmlspecialchars($item['title']) ?></h3>
              <p class="text-gray-500"><?= htmlspecialchars($item['seller']) ?></p>
              <p class="text-green-600 font-bold">KSh <?= number_format($item['price']) ?></p>
              <p class="text-sm text-gray-400">Stock: <?= $item['stock'] ?></p>
            </div>
          </div>
          <div class="flex items-center space-x-2">
            <form method="POST" action="cart.php" class="flex items-center space-x-2">
              <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
              <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>" class="w-16 border text-center rounded">
              <button type="submit" name="update" class="text-sm text-blue-600 hover:underline">Update</button>
            </form>
            <form method="POST" action="cart.php">
              <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
              <button type="submit" name="remove" class="text-red-600 hover:underline text-sm">Remove</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>

      <!-- Continue Shopping -->
      <div class="mt-6">
        <a href="marketplace.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Continue Shopping</a>
      </div>
    </div>

    <!-- Order Summary -->
    <div>
      <div class="bg-white rounded shadow p-6 sticky top-6">
        <h3 class="text-xl font-semibold mb-4">Order Summary</h3>
        <div class="flex justify-between mb-2">
          <span>Subtotal</span>
          <span>KSh <?= number_format($totalPrice) ?></span>
        </div>
        <div class="flex justify-between mb-2">
          <span>Shipping</span>
          <span>KSh 200</span>
        </div>
        <hr class="my-2">
        <div class="flex justify-between text-lg font-bold">
          <span>Total</span>
          <span>KSh <?= number_format($totalPrice + 200) ?></span>
        </div>
        <a href="payment.php" class="mt-6 block bg-green-600 hover:bg-green-700 text-white text-center px-4 py-2 rounded">
          Proceed to Payment
        </a>
      </div>
    </div>
  </div>

  <?php endif; ?>
</div>

<?php include 'components/footer.php'; ?>
</body>
</html>
