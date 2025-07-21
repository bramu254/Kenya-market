<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = $conn->query($query);

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // ✅ Set session values
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = $user['first_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role']; // ✅ Needed for admin access

            // ✅ Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: admin-dashboard.php");
                exit();
            } else {
                header("Location: dashboard.php");
                exit();
            }
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No user found with that email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Kenyatta Market</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center px-4 py-12">
  <form method="POST" class="bg-white p-8 rounded-xl shadow-xl w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-6 text-gray-900">Sign in to Kenyatta Market</h2>

    <?php if (!empty($error)): ?>
      <p class="text-red-500 text-sm mb-4 text-center"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
      <input type="email" name="email" required
        class="w-full px-4 py-3 border rounded focus:outline-none focus:ring-2 focus:ring-green-500">
    </div>

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
      <input type="password" name="password" required
        class="w-full px-4 py-3 border rounded focus:outline-none focus:ring-2 focus:ring-green-500">
    </div>

    <div class="flex items-center justify-between text-sm mb-4">
      <label class="flex items-center">
        <input type="checkbox" class="mr-2 rounded border-gray-300"> Remember me
      </label>
      <a href="forgot-password.php" class="text-blue-600 hover:underline">Forgot password?</a>
    </div>

    <button type="submit"
      class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded transition duration-200">
      Login
    </button>

    <p class="text-center text-sm text-gray-600 mt-6">
      Don't have an account?
      <a href="signup.php" class="text-green-600 hover:underline font-medium">Create one here</a>
    </p>
  </form>
</body>
</html>
