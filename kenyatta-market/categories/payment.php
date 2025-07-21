<?php
session_start();
include(__DIR__ . '/../db.php');
include(__DIR__ . '/../components/navbar.php');

if (!isset($_SESSION['user_id'])) {
  header('Location: /kenyatta-market/login.php');
  exit;
}

$userId = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];

if (!$cart) {
  header('Location: /kenyatta-market/marketplace.php');
  exit;
}

// Flatten cart values if stored by product ID
$cartItems = array_values($cart);

$subtotal = array_reduce($cartItems, fn($acc, $it) => $acc + $it['price'] * $it['quantity'], 0);
$shipping = 200;
$total = $subtotal + $shipping;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $method = $_POST['payment_method'];
  // TODO: Validate payment inputs

  $stmt = $conn->prepare("INSERT INTO orders (user_id, order_number, total) VALUES (?, ?, ?)");
  $ordNum = uniqid('ORD-');
  $stmt->bind_param("isd", $userId, $ordNum, $total);
  $stmt->execute();
  $orderId = $conn->insert_id;

  $itmStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
  foreach ($cartItems as $it) {
    $itmStmt->bind_param("iiid", $orderId, $it['id'], $it['quantity'], $it['price']);
    $itmStmt->execute();
  }

  unset($_SESSION['cart']);
  $_SESSION['message'] = "Payment successful! Order $ordNum created.";
  header('Location: /kenyatta-market/dashboard.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><title>Payment – Kenyatta Market</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="max-w-4xl mx-auto p-6">
  <a href="/kenyatta-market/cart.php" class="text-green-600 hover:underline flex items-center mb-4">
    &#8592; Back to Cart
  </a>
  <h1 class="text-2xl font-bold mb-6">Payment</h1>
  <form method="POST" class="space-y-6">
    <?php foreach (['mpesa'=>'M‑Pesa','card'=>'Card','bank'=>'Bank'] as $val=>$label): ?>
      <label class="flex items-center space-x-2">
        <input type="radio" name="payment_method" value="<?= $val ?>" <?= $val==='mpesa' ? 'checked' : '' ?>>
        <span><?= $label ?></span>
      </label>
    <?php endforeach; ?>
    <div>
      <label>M‑Pesa Phone</label>
      <input name="mpesa_phone" class="w-full p-2 border" required />
    </div>
    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded">
      Pay KSh <?= number_format($total) ?>
    </button>
  </form>

  <div class="mt-8 bg-white p-4 rounded shadow">
    <h2 class="font-bold">Order Summary</h2>
    <?php foreach ($cartItems as $it): ?>
      <div class="flex justify-between py-1">
        <span><?= htmlspecialchars($it['title']) ?> x<?= $it['quantity'] ?></span>
        <span>KSh <?= number_format($it['quantity'] * $it['price']) ?></span>
      </div>
    <?php endforeach; ?>
    <hr class="my-2"/>
    <div class="flex justify-between"><span>Subtotal</span><span>KSh <?= number_format($subtotal) ?></span></div>
    <div class="flex justify-between"><span>Shipping</span><span>KSh <?= number_format($shipping) ?></span></div>
    <div class="font-bold flex justify-between"><span>Total</span><span>KSh <?= number_format($total) ?></span></div>
  </div>
</div>
<?php include(__DIR__ . '/../components/footer.php'); ?>
</body>
</html>
