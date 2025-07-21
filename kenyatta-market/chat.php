<?php
session_start();
include 'db.php';
include 'components/navbar.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
  header("Location: login.php");
  exit;
}

$currentUserId = $_SESSION['user_id'];
$currentRole = $_SESSION['role'];
$isAdmin = $currentRole === 'admin';
$adminId = 1; // Change if your actual admin ID is different

// Handle sending message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'], $_POST['receiver_id'])) {
  $receiverId = intval($_POST['receiver_id']);
  $message = trim($_POST['message']);

  if ($message !== '') {
    $isAdminSender = $isAdmin ? 1 : 0;
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, is_admin) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $currentUserId, $receiverId, $message, $isAdminSender);
    $stmt->execute();
  }
}

// Determine the receiver
if ($isAdmin) {
  $selectedUserId = $_GET['user_id'] ?? null;
  if (!$selectedUserId) {
    // Show user list (Admin selects)
    $result = $conn->query("
      SELECT DISTINCT u.id, CONCAT(u.first_name, ' ', u.last_name) AS name
      FROM users u
      JOIN messages m ON u.id = m.sender_id OR u.id = m.receiver_id
      WHERE u.role = 'user'
    ");
    $users = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
  }
} else {
  $selectedUserId = $adminId; // User chats directly with admin
}

// Load conversation if selected
$messages = [];
if ($selectedUserId) {
  $uid = intval($selectedUserId);
  $stmt = $conn->prepare("
    SELECT * FROM messages
    WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
    ORDER BY timestamp ASC
  ");
  $stmt->bind_param("iiii", $currentUserId, $uid, $uid, $currentUserId);
  $stmt->execute();
  $messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Chat</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded shadow">
  <h2 class="text-2xl font-bold mb-4"><?= $isAdmin ? 'Admin' : 'User' ?> Chat Panel</h2>

  <?php if ($isAdmin && !$selectedUserId): ?>
    <h3 class="text-lg font-semibold mb-4">Select a user to chat with:</h3>
    <ul class="space-y-2">
      <?php foreach ($users as $u): ?>
        <li>
          <a href="chat.php?user_id=<?= $u['id'] ?>" class="text-blue-600 hover:underline">
            <?= htmlspecialchars($u['name']) ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <div class="h-[400px] overflow-y-auto border rounded p-4 space-y-3 bg-gray-50">
      <?php if (empty($messages)): ?>
        <p class="text-gray-500">No messages yet.</p>
      <?php else: ?>
        <?php foreach ($messages as $msg): ?>
          <div class="flex <?= $msg['sender_id'] === $currentUserId ? 'justify-end' : 'justify-start' ?>">
            <div class="px-4 py-2 rounded-lg max-w-sm <?= $msg['is_admin'] ? 'bg-green-200' : 'bg-blue-100' ?>">
              <p><?= htmlspecialchars($msg['message']) ?></p>
              <p class="text-xs text-gray-600"><?= date('M d, h:i A', strtotime($msg['timestamp'])) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Message Form -->
    <form method="POST" class="flex gap-2 mt-4">
      <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($selectedUserId) ?>">
      <input type="text" name="message" placeholder="Type your message..." class="flex-1 border px-3 py-2 rounded" required>
      <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Send</button>
    </form>
  <?php endif; ?>
</div>

<?php include 'components/footer.php'; ?>
</body>
</html>
