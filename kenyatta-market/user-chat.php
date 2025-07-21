<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$adminId = 1; // Default admin

// Handle user sending message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if ($message !== '') {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, is_admin) VALUES (?, ?, ?, 0)");
        $stmt->bind_param("iis", $userId, $adminId, $message);
        $stmt->execute();
    }
}

// Fetch messages
$stmt = $conn->prepare("
    SELECT * FROM messages 
    WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
    ORDER BY timestamp ASC
");
$stmt->bind_param("iiii", $userId, $adminId, $adminId, $userId);
$stmt->execute();
$messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Support Chat</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="max-w-3xl mx-auto mt-10 p-6 bg-white rounded shadow">
  <h2 class="text-2xl font-bold mb-6">💬 Chat with Admin</h2>

  <div class="flex flex-col justify-between h-[500px] border rounded">
    <div class="overflow-y-auto p-4 space-y-3 bg-gray-50">
      <?php if (empty($messages)): ?>
        <p class="text-gray-500">No messages yet. Say hello!</p>
      <?php else: ?>
        <?php foreach ($messages as $msg): ?>
          <div class="flex <?= $msg['is_admin'] ? 'justify-start' : 'justify-end' ?>">
            <div class="px-4 py-2 rounded-lg max-w-xs <?= $msg['is_admin'] ? 'bg-blue-100' : 'bg-green-200' ?>">
              <p class="text-sm"><?= htmlspecialchars($msg['message']) ?></p>
              <p class="text-xs text-gray-500"><?= date('M d, h:i A', strtotime($msg['timestamp'])) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Message Form -->
    <form method="POST" class="flex border-t p-4 gap-2 bg-white">
      <input type="text" name="message" class="flex-1 border rounded p-2" placeholder="Type your message..." required>
      <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Send</button>
    </form>
  </div>
</div>
</body>
</html>
