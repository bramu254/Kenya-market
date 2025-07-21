<?php
session_start();
include 'db.php';
include 'components/navbar.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

$userId = $_SESSION['user_id'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $first = $conn->real_escape_string($_POST['first_name']);
  $last = $conn->real_escape_string($_POST['last_name']);
  $email = $conn->real_escape_string($_POST['email']);
  $phone = $conn->real_escape_string($_POST['phone']);
  $county = $conn->real_escape_string($_POST['county']);

  $stmt = $conn->prepare("
    UPDATE users SET first_name=?, last_name=?, email=?, phone=?, county=? 
    WHERE id=?
  ");
  $stmt->bind_param("sssssi", $first, $last, $email, $phone, $county, $userId);
  if ($stmt->execute()) {
    $msg = "Profile updated successfully.";
    // Refresh session data
    $_SESSION['user_first'] = $first;
  } else {
    $msg = "Update error: " . $conn->error;
  }
}

$res = $conn->query("SELECT first_name, last_name, email, phone, county FROM users WHERE id = $userId");
$user = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><title>My Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
<?php if ($msg): ?>
  <div class="max-w-3xl mx-auto mt-4 p-4 bg-green-100 text-green-800 rounded"><?php echo $msg ?></div>
<?php endif; ?>
<div class="max-w-4xl mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold mb-4">My Profile</h1>

  <form method="POST" class="bg-white p-6 rounded shadow space-y-6">
    <?php foreach (['first_name'=>'First Name', 'last_name'=>'Last Name', 'email'=>'Email', 'phone'=>'Phone', 'county'=>'County'] as $field=>$label): ?>
      <div>
        <label class="block mb-1 font-medium"><?php echo $label ?></label>
        <input type="text" name="<?php echo $field ?>" value="<?php echo htmlspecialchars($user[$field]) ?>"
               class="w-full p-2 border rounded" required />
      </div>
    <?php endforeach; ?>

    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
      Save Profile
    </button>
  </form>
</div>
<?php include 'components/footer.php'; ?>
</body>
</html>
