<?php
session_start();
require_once 'db.php'; // ✅ use external connection file

// Fetch counties from the database
$counties = [];
$result = $conn->query("SELECT name FROM counties ORDER BY name ASC");
while ($row = $result->fetch_assoc()) {
  $counties[] = $row['name'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $conn->real_escape_string($_POST['first_name']);
    $lastName = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $county = $conn->real_escape_string($_POST['county']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $agree = isset($_POST['agree_to_terms']) ? 1 : 0;

    $sql = "INSERT INTO users (first_name, last_name, email, phone, county, password, agree_to_terms) 
            VALUES ('$firstName', '$lastName', '$email', '$phone', '$county', '$password', $agree)";

    if ($conn->query($sql)) {
        $_SESSION['user'] = $firstName;
        header("Location: login.php");
    } else {
        $error = "Email already exists or input error.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Signup</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4">
  <form method="POST" class="bg-white p-8 rounded-xl shadow-xl w-full max-w-2xl">
    <h2 class="text-2xl font-bold text-center mb-6">Join Kenyatta Market</h2>
    <?php if (!empty($error)) echo "<p class='text-red-500 mb-4'>$error</p>"; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label>First Name</label>
        <input type="text" name="first_name" required class="w-full p-3 border rounded" />
      </div>
      <div>
        <label>Last Name</label>
        <input type="text" name="last_name" required class="w-full p-3 border rounded" />
      </div>
    </div>

    <div class="mt-4">
      <label>Email Address</label>
      <input type="email" name="email" required class="w-full p-3 border rounded" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
      <div>
        <label>Phone Number</label>
        <input type="text" name="phone" required class="w-full p-3 border rounded" />
      </div>
      <div>
        <label>County</label>
        <select name="county" required class="w-full p-3 border rounded">
          <option value="">Select your county</option>
          <?php foreach ($counties as $county): ?>
            <option value="<?= htmlspecialchars($county) ?>"><?= htmlspecialchars($county) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
      <div>
        <label>Password</label>
        <input type="password" name="password" required class="w-full p-3 border rounded" />
      </div>
      <div>
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required class="w-full p-3 border rounded" />
      </div>
    </div>

    <div class="mt-4 flex items-center">
      <input type="checkbox" name="agree_to_terms" required class="mr-2" />
      <label class="text-sm text-gray-600">I agree to the 
        <a href="terms.php" class="text-blue-600">Terms of Service</a> and 
        <a href="privacy.php" class="text-blue-600">Privacy Policy</a>.
      </label>
    </div>

    <button class="mt-6 w-full bg-green-600 text-white py-3 rounded hover:bg-green-700" type="submit">
      Create Account
    </button>
    <p class="text-center mt-4 text-sm">Already have an account? 
      <a href="login.php" class="text-blue-600">Sign in here</a>
    </p>
  </form>
</body>
</html>
