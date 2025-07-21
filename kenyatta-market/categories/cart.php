<?php
session_start();
include(__DIR__ . '/../db.php');
include(__DIR__ . '/../components/navbar.php');

// Ensure cart exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle form submission (update or remove)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = intval($_POST['product_id']);

    if (isset($_POST['remove'])) {
        unset($_SESSION['cart'][$productId]);
        $_SESSION['success_message'] = "Item removed from cart.";
    } elseif (isset($_POST['quantity'])) {
        $quantity = intval($_POST['quantity']);
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$productId]);
            $_SESSION['success_message'] = "Item removed (quantity zero).";
        } else {
            $_SESSION['cart'][$productId] = $quantity;
            $_SESSION['success_message'] = "Cart updated.";
        }
    }

    header("Location: cart.php");
    exit;
}

// Fetch products in cart
$cartItems = [];
$totalItems = 0;
$totalPrice = 0;
$cartIds = array_keys($_SESSION['cart']);

if (!empty($cartIds)) {
    $placeholders = implode(',', array_map('intval', $cartIds));
    $query = $conn->query("SELECT * FROM products WHERE id IN ($placeholders)");

    while ($row = $query->fetch_assoc()) {
        $pid = $row['id'];
        $quantity = $_SESSION['cart'][$pid];
        $row['quantity'] = $quantity;
        $cartItems[] = $row;
        $totalItems += $quantity;
        $totalPrice += $row['price'] * $quantity;
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
<body class="bg-gray-100 font-sans">

<?php if (isset($_SESSION['success_message'])): ?>
  <div id="toast" class="fixed top-4 right-4 bg-green-600 text-white px-4 py-3 rounded shadow z-50">
    <?= $_SESSION['success_message'] ?>
  </div>
  <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<div class="max-w-6xl mx-auto px-4 py-10">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">🛒 Shopping Cart (<?= $totalItems ?> items)</h1>
    <a href="/kenyatta-market/marketplace.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
      ⬅ Continue Shopping
    </a>
  </div>

  <?php if (empty($cartItems)): ?>
    <div class="text-center text-gray-600 py-20">
      <p class="text-lg">Your cart is empty.</p>
      <a href="/kenyatta-market/marketplace.php" class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">Go to Marketplace</a>
    </div>
  <?php else: ?>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Cart Items -->
  <!-- Cart Items -->
<div class="lg:col-span-2 space-y-6">
<?php foreach ($cartItems as $item): ?>
  <div class="bg-white rounded shadow p-4 flex items-center justify-between">
    <div class="flex items-center space-x-4">
      <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="w-20 h-20 object-cover rounded">
      <div>
        <h3 class="font-semibold text-lg"><?= htmlspecialchars($item['title']) ?></h3>
        <p class="text-green-600 font-bold">KSh <?= number_format($item['price']) ?></p>
        <p class="text-sm text-gray-500">Category: <?= htmlspecialchars($item['category']) ?></p>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <a href="/kenyatta-market/edit-product.php?id=<?= $item['id'] ?>" class="text-sm text-yellow-600 hover:underline mt-1 inline-block">✏️ Edit Product</a>
        <?php endif; ?>
      </div>
    </div>
    <div class="flex items-center space-x-2">
      <!-- Update form -->
      <form method="POST" action="cart.php" class="flex items-center space-x-2">
        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
        <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="w-16 border text-center rounded">
        <button type="submit" class="text-blue-600 hover:underline text-sm">Update</button>
      </form>
      <!-- Remove form -->
      <form method="POST" action="cart.php">
        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
        <button type="submit" name="remove" class="text-red-600 hover:underline text-sm">Remove</button>
      </form>
    </div>
  </div>
<?php endforeach; ?>
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
        <a href="/kenyatta-market/payment.php" class="mt-6 block bg-green-600 hover:bg-green-700 text-white text-center px-4 py-2 rounded">
          Proceed to Payment
        </a>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>

<?php include(__DIR__ . '/../components/footer.php'); ?>
</body>
</html>
