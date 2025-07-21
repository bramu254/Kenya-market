<?php
session_start();
ob_start(); // Prevent "headers already sent" issues
include 'db.php';

// ✅ Ensure user is admin before rendering anything
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

include 'components/navbar.php';

// ✅ Handle message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['message'])) {
    $adminId = $_SESSION['user_id'];
    $userId = intval($_POST['user_id']);
    $message = trim($_POST['message']);

    if ($message !== '') {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, is_admin) VALUES (?, ?, ?, 1)");
        $stmt->bind_param("iis", $adminId, $userId, $message);
        $stmt->execute();
    }
}

// ✅ Fetch users who have chatted
$users = $conn->query("
    SELECT DISTINCT u.id, CONCAT(u.first_name, ' ', u.last_name) AS username
    FROM users u
    JOIN messages m ON u.id = m.sender_id OR u.id = m.receiver_id
    WHERE u.role != 'admin'
")->fetch_all(MYSQLI_ASSOC);

// ✅ Fetch chat history if a user is selected
$selectedUser = $_GET['user_id'] ?? null;
$messages = [];

if ($selectedUser) {
    $adminId = $_SESSION['user_id'];
    $uid = intval($selectedUser);
    $stmt = $conn->prepare("
        SELECT * FROM messages
        WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
        ORDER BY timestamp ASC
    ");
    $stmt->bind_param("iiii", $uid, $adminId, $adminId, $uid);
    $stmt->execute();
    $messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Chat Panel</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="max-w-7xl mx-auto mt-10 p-6 bg-white rounded shadow">
  <h2 class="text-2xl font-bold mb-6">💬 Admin Chat Panel</h2>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- User List -->
    <div class="bg-gray-50 border rounded p-4 h-[500px] overflow-y-auto">
      <h3 class="text-lg font-semibold mb-4">Users</h3>
      <?php foreach ($users as $user): ?>
        <a href="?user_id=<?= $user['id'] ?>" class="block px-3 py-2 rounded hover:bg-green-100 <?= $selectedUser == $user['id'] ? 'bg-green-200 font-bold' : '' ?>">
          <?= htmlspecialchars($user['username']) ?>
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Chat Area -->
    <div class="md:col-span-2 flex flex-col justify-between h-[500px] border rounded">
      <div class="overflow-y-auto p-4 space-y-3 bg-gray-50">
        <?php if (!$selectedUser): ?>
          <p class="text-gray-500">Select a user to start chatting.</p>
        <?php else: ?>
          <?php foreach ($messages as $msg): ?>
            <div class="flex <?= $msg['is_admin'] ? 'justify-end' : 'justify-start' ?>">
              <div class="px-4 py-2 rounded-lg max-w-xs <?= $msg['is_admin'] ? 'bg-green-200' : 'bg-blue-100' ?>">
                <p class="text-sm"><?= htmlspecialchars($msg['message']) ?></p>
                <p class="text-xs text-gray-500"><?= date('M d, h:i A', strtotime($msg['timestamp'])) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <?php if ($selectedUser): ?>
        <!-- Message Form -->
        <form method="POST" class="flex border-t p-4 gap-2 bg-white">
          <input type="hidden" name="user_id" value="<?= $selectedUser ?>">
          <input type="text" name="message" class="flex-1 border rounded p-2" placeholder="Type a message..." required>
          <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Send</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include 'components/footer.php'; ?>
</body>
</html>
