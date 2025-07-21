<?php
require_once 'db.php';
$token = $_GET['token'] ?? '';
$valid = false;
$error = '';
$success = '';

if ($token) {
    $stmt = $conn->prepare("SELECT id, reset_token_expiry FROM users WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();
        if (strtotime($user['reset_token_expiry']) > time()) {
            $valid = true;

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $id = $user['id'];

                $conn->query("UPDATE users SET password = '$newPassword', reset_token = NULL, reset_token_expiry = NULL WHERE id = $id");
                $success = "Password has been updated. <a href='login.php'>Login now</a>";
                $valid = false;
            }
        } else {
            $error = "Reset link has expired.";
        }
    } else {
        $error = "Invalid token.";
    }
} else {
    $error = "No token provided.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-4 text-center">Reset Your Password</h2>

    <?php if ($error): ?>
        <p class="text-red-500 mb-4 text-center"><?= $error ?></p>
    <?php elseif ($success): ?>
        <p class="text-green-600 mb-4 text-center"><?= $success ?></p>
    <?php elseif ($valid): ?>
        <form method="POST" class="space-y-4">
            <input type="password" name="password" required placeholder="New password"
                class="w-full px-4 py-2 border rounded focus:ring focus:outline-none" />
            <button class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
                Update Password
            </button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
