<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$userId = $_SESSION['user_id'];

// Fetch user's orders
$stmt = $conn->prepare("
  SELECT o.id, o.order_number, o.order_date, o.status
  FROM orders o
  WHERE o.user_id = ?
  ORDER BY o.order_date DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$ordersResult = $stmt->get_result();

$orders = [];
while ($o = $ordersResult->fetch_assoc()) {
    $itemsStmt = $conn->prepare("
      SELECT product_name, quantity, price 
      FROM order_items 
      WHERE order_id = ?
    ");
    $itemsStmt->bind_param("i", $o['id']);
    $itemsStmt->execute();
    $items = $itemsStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $total = 0;
    foreach ($items as $it) $total += $it['quantity'] * $it['price'];
    $orders[] = [
      'id' => $o['order_number'],
      'date' => $o['order_date'],
      'status' => $o['status'],
      'total' => $total,
      'items' => $items
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders - Kenyatta Market</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
<?php include 'components/navbar.php'; ?>

<div class="max-w-6xl mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold mb-2">My Orders</h1>
  <p class="text-gray-600 mb-8">Track and manage your orders</p>

  <!-- Stats -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <?php
    $counts = ['total' => count($orders), 'delivered' => 0, 'in_transit' => 0, 'pending' => 0];
    foreach ($orders as $ord) {
      if ($ord['status'] === 'delivered') $counts['delivered']++;
      if (in_array($ord['status'], ['shipped', 'processing'])) $counts['in_transit']++;
      if ($ord['status'] === 'pending') $counts['pending']++;
    }
    $stats = [
      ['icon' => 'Package', 'count' => $counts['total'], 'label' => 'Total Orders', 'color' => 'bg-blue-100 text-blue-600'],
      ['icon' => 'CheckCircle', 'count' => $counts['delivered'], 'label' => 'Delivered', 'color' => 'bg-green-100 text-green-600'],
      ['icon' => 'Truck', 'count' => $counts['in_transit'], 'label' => 'In Transit', 'color' => 'bg-orange-100 text-orange-600'],
      ['icon' => 'Clock', 'count' => $counts['pending'], 'label' => 'Pending', 'color' => 'bg-gray-100 text-gray-600'],
    ];
    foreach ($stats as $st):
    ?>
    <div class="bg-white rounded shadow p-6 text-center">
      <div class="mx-auto w-12 h-12 mb-4 rounded-lg <?= $st['color'] ?> flex items-center justify-center">
        <i class="lucide-<?= strtolower($st['icon']) ?> h-6 w-6"></i>
      </div>
      <h3 class="text-2xl font-bold"><?= $st['count'] ?></h3>
      <p class="text-gray-600"><?= $st['label'] ?></p>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Orders List -->
  <?php foreach ($orders as $ord): 
    $status = $ord['status'] ?? 'pending';
    $statusColorMap = [
      'delivered' => 'bg-green-100 text-green-800',
      'shipped' => 'bg-blue-100 text-blue-800',
      'processing' => 'bg-orange-100 text-orange-800',
      'pending' => 'bg-gray-100 text-gray-800'
    ];
    $statusIconMap = [
      'delivered' => 'check-circle',
      'shipped' => 'truck',
      'processing' => 'package',
      'pending' => 'clock'
    ];
    $statusColor = $statusColorMap[$status] ?? 'bg-gray-100 text-gray-800';
    $statusIcon = $statusIconMap[$status] ?? 'clock';
  ?>
    <div class="bg-white shadow rounded mb-6">
      <div class="flex justify-between items-center p-6 border-b">
        <div>
          <h2 class="font-semibold text-lg">Order #<?= htmlspecialchars($ord['id']) ?></h2>
         <p class="text-gray-600">
           Placed on <?= date('M j, Y h:i A') ?>
        </p>

        </div>
        <div class="flex items-center space-x-4">
          <span class="<?= $statusColor ?> px-3 py-1 rounded-full flex items-center space-x-1">
            <i class="lucide-<?= $statusIcon ?> h-4 w-4"></i>
            <span class="capitalize"><?= htmlspecialchars($status) ?></span>
          </span>
          <a href="order-details.php?id=<?= urlencode($ord['id']) ?>" class="text-blue-600 hover:underline">
            <i class="lucide-eye h-4 w-4 mr-1"></i> View Details
          </a>
        </div>
      </div>
      <div class="p-6">
        <table class="w-full text-sm mb-4">
          <thead>
            <tr class="text-left border-b">
              <th>Product</th><th>Qty</th><th>Price</th><th class="text-right">Total</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($ord['items'] as $it): ?>
            <tr class="border-b">
              <td><?= htmlspecialchars($it['product_name']) ?></td>
              <td><?= $it['quantity'] ?></td>
              <td>KSh <?= number_format($it['price']) ?></td>
              <td class="text-right">KSh <?= number_format($it['price'] * $it['quantity']) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div class="flex justify-between items-center text-lg font-bold">
          <div><?= count($ord['items']) ?> item(s)</div>
          <div>Total: KSh <?= number_format($ord['total']) ?></div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php include 'components/footer.php'; ?>
</body>
</html>
