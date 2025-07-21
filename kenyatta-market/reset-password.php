<?php
require_once 'db.php';

$token = $_GET['token'] ?? '';
$valid = false;
$error = '';
$success = '';

// Check if token is present and valid
if ($token) {
    $stmt = $conn->prepare("SELECT id, reset_token_expiry FROM users WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();

        // Check token expiration
        if (strtotime($user['reset_token_expiry']) > time()) {
            $valid = true;

            // Handle form submission
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $newPassword = $_POST['password'];
                $confirmPassword = $_POST['confirm_password'];

                if ($newPassword !== $confirmPassword) {
                    $error = "Passwords do not match.";
                } else {
                    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
                    $id = $user['id'];

                    // Update password and clear reset token
                    $conn->query("UPDATE users SET password = '$hashed', reset_token = NULL, reset_token_expiry = NULL WHERE id = $id");

                    $success = "✅ Password updated successfully. <a href='login.php' class='underline text-blue-600'>Login now</a>.";
                    $valid = false;
                }
            }
        } else {
            $error = "Reset link has expired.";
        }
    } else {
        $error = "Invalid or used reset token.";
    }
} else {
    $error = "No token provided.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
  <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-md text-center">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Reset Your Password</h2>

    <?php if ($error): ?>
      <p class="text-red-500 mb-4"><?= $error ?></p>
    <?php elseif ($success): ?>
      <p class="text-green-600 mb-4"><?= $success ?></p>
    <?php elseif ($valid): ?>
      <form method="POST" class="space-y-4 text-left">
        <div>
          <label class="block mb-1 text-sm text-gray-700">New Password</label>
          <input type="password" name="password" required
                 class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-green-500" />
        </div>
        <div>
          <label class="block mb-1 text-sm text-gray-700">Confirm Password</label>
          <input type="password" name="confirm_password" required
                 class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-green-500" />
        </div>
        <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded">
          Update Password
        </button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
