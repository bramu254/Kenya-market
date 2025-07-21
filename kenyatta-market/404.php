<?php
// Log the attempted URL (optional server-side logging)
$requested_path = $_SERVER['REQUEST_URI'];
error_log("404 Error: User attempted to access non-existent route: $requested_path", 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>404 - Page Not Found</title>
  <script>
    console.error("404 Error: User attempted to access non-existent route: <?php echo htmlspecialchars($requested_path); ?>");
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
  <div class="text-center">
    <h1 class="text-4xl font-bold mb-4">404</h1>
    <p class="text-xl text-gray-600 mb-4">Oops! Page not found</p>
    <a href="/" class="text-blue-500 hover:text-blue-700 underline">
      Return to Home
    </a>
  </div>
</body>
</html>
