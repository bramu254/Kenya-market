<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$userId = $_SESSION['user_id'];
$orderNumber = $_GET['id'] ?? null;

if (!$orderNumber) {
  echo "Invalid order ID.";
  exit;
}

// Fetch order info
$stmt = $conn->prepare("
  SELECT o.id, o.order_number, o.order_date, o.status
  FROM orders o
  WHERE o.order_number = ? AND o.user_id = ?
");
$stmt->bind_param("si", $orderNumber, $userId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
  echo "Order not found or you don’t have permission.";
  exit;
}

// Fetch order items
$itemsStmt = $conn->prepare("
  SELECT product_name, quantity, price 
  FROM order_items 
  WHERE order_id = ?
");
$itemsStmt->bind_param("i", $order['id']);
$itemsStmt->execute();
$items = $itemsStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate total
$total = 0;
foreach ($items as $it) {
  $total += $it['quantity'] * $it['price'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order #<?= htmlspecialchars($orderNumber) ?> - Kenyatta Market</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
<?php include 'components/navbar.php'; ?>

<div class="max-w-4xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-2">Order #<?= htmlspecialchars($orderNumber) ?></h1>
  <p class="text-gray-600 mb-6">Placed on <?= date('M j, Y', strtotime($order['order_date'])) ?> · Status: 
    <span class="capitalize font-semibold"><?= htmlspecialchars($order['status']) ?></span>
  </p>

  <div class="bg-white shadow rounded p-6">
    <h2 class="text-lg font-bold mb-4">Items</h2>
    <table class="w-full text-sm mb-4">
      <thead>
        <tr class="border-b">
          <th>Product</th><th>Qty</th><th>Price</th><th class="text-right">Total</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr class="border-b">
            <td><?= htmlspecialchars($item['product_name']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>KSh <?= number_format($item['price']) ?></td>
            <td class="text-right">KSh <?= number_format($item['price'] * $item['quantity']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="flex justify-between items-center text-lg font-bold">
      <span>Total Items: <?= count($items) ?></span>
      <span>Order Total: KSh <?= number_format($total) ?></span>
    </div>
  </div>

  <a href="orders.php" class="inline-block mt-6 text-green-600 hover:underline">← Back to Orders</a>
</div>

<?php include 'components/footer.php'; ?>
</body>
</html>
