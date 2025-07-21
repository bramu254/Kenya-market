<?php
ob_start();
session_start();
include 'db.php';
include 'components/navbar.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

$userId = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
  header('Location: marketplace.php');
  exit;
}

$cartItems = [];
$subtotal = 0;
$shipping = 200;

// Fetch product details
$productIds = implode(',', array_keys($cart));
$query = $conn->query("SELECT * FROM products WHERE id IN ($productIds)");
while ($row = $query->fetch_assoc()) {
  $pid = $row['id'];
  $quantity = $cart[$pid];

  // Check if enough stock is available
  if ($row['stock'] < $quantity) {
    $_SESSION['message'] = "❌ Not enough stock for {$row['title']}. Available: {$row['stock']}";
    header('Location: cart.php');
    exit;
  }

  $row['quantity'] = $quantity;
  $cartItems[] = $row;
  $subtotal += $row['price'] * $quantity;
}

$total = $subtotal + $shipping;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $method = $_POST['payment_method'] ?? 'mpesa';

  // Save order
  $orderNumber = uniqid('ORD-');
  $stmt = $conn->prepare("INSERT INTO orders (user_id, order_number, total, payment_method) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("isds", $userId, $orderNumber, $total, $method);
  $stmt->execute();
  $orderId = $stmt->insert_id;
  $stmt->close();

  // Insert order items & update stock
  $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
  $stockStmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

  foreach ($cartItems as $item) {
    $itemStmt->bind_param("iiid", $orderId, $item['id'], $item['quantity'], $item['price']);
    $itemStmt->execute();

    $stockStmt->bind_param("ii", $item['quantity'], $item['id']);
    $stockStmt->execute();
  }

  $itemStmt->close();
  $stockStmt->close();

  unset($_SESSION['cart']);
  $_SESSION['message'] = "✅ Payment successful! Order $orderNumber has been created.";
  header('Location: dashboard.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment – Kenyatta Market</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script>
    function toggleFields(method) {
      document.getElementById('mpesa-fields').style.display = method === 'mpesa' ? 'block' : 'none';
      document.getElementById('card-fields').style.display = method === 'card' ? 'block' : 'none';
      document.getElementById('bank-fields').style.display = method === 'bank' ? 'block' : 'none';
    }
  </script>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="max-w-4xl mx-auto p-6">
  <a href="cart.php" class="text-green-600 hover:underline flex items-center mb-4">&#8592; Back to Cart</a>

  <h1 class="text-2xl font-bold mb-6">Payment</h1>

  <form method="POST" class="space-y-6">
    <div class="space-y-2">
      <?php foreach (['mpesa'=>'M‑Pesa','card'=>'Card','bank'=>'Bank Transfer'] as $val => $label): ?>
        <label class="flex items-center space-x-2">
          <input type="radio" name="payment_method" value="<?= $val ?>" <?= $val === 'mpesa' ? 'checked' : '' ?> onchange="toggleFields(this.value)">
          <span><?= $label ?></span>
        </label>
      <?php endforeach; ?>
    </div>

    <!-- M-Pesa Fields -->
    <div id="mpesa-fields" class="space-y-2">
      <label class="block text-sm font-medium">M‑Pesa Phone Number</label>
      <input type="text" name="mpesa_phone" class="w-full p-2 border rounded" placeholder="e.g., 07XXXXXXXX">
    </div>

    <!-- Card Fields -->
    <div id="card-fields" class="space-y-2 hidden">
      <label class="block text-sm font-medium">Card Number</label>
      <input type="text" name="card_number" class="w-full p-2 border rounded" placeholder="e.g., 1234 5678 9012 3456">

      <label class="block text-sm font-medium">Cardholder Name</label>
      <input type="text" name="card_name" class="w-full p-2 border rounded">

      <div class="flex space-x-4">
        <div class="w-1/2">
          <label class="block text-sm font-medium">Expiry</label>
          <input type="text" name="expiry" class="w-full p-2 border rounded" placeholder="MM/YY">
        </div>
        <div class="w-1/2">
          <label class="block text-sm font-medium">CVV</label>
          <input type="text" name="cvv" class="w-full p-2 border rounded" placeholder="123">
        </div>
      </div>
    </div>

    <!-- Bank Fields -->
    <div id="bank-fields" class="space-y-2 hidden">
      <label class="block text-sm font-medium">Bank Name</label>
      <input type="text" name="bank_name" class="w-full p-2 border rounded">

      <label class="block text-sm font-medium">Account Name</label>
      <input type="text" name="account_name" class="w-full p-2 border rounded">

      <label class="block text-sm font-medium">Account Number</label>
      <input type="text" name="account_number" class="w-full p-2 border rounded">
    </div>

    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded text-lg font-semibold">
      Pay KSh <?= number_format($total) ?>
    </button>
  </form>

  <!-- Order Summary -->
  <div class="mt-8 bg-white p-4 rounded shadow">
    <h2 class="font-bold mb-2">Order Summary</h2>
    <?php foreach ($cartItems as $item): ?>
      <div class="flex justify-between py-1">
        <span><?= htmlspecialchars($item['title']) ?> x<?= $item['quantity'] ?></span>
        <span>KSh <?= number_format($item['quantity'] * $item['price']) ?></span>
      </div>
    <?php endforeach; ?>
    <hr class="my-2"/>
    <div class="flex justify-between"><span>Subtotal</span><span>KSh <?= number_format($subtotal) ?></span></div>
    <div class="flex justify-between"><span>Shipping</span><span>KSh <?= number_format($shipping) ?></span></div>
    <div class="font-bold flex justify-between text-lg"><span>Total</span><span>KSh <?= number_format($total) ?></span></div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    toggleFields(document.querySelector('input[name="payment_method"]:checked').value);
  });
</script>

<?php include 'components/footer.php'; ?>
</body>
</html>
