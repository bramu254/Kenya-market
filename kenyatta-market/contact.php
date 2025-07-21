<?php
include 'db.php'; // Connects to MySQL
include 'components/navbar.php'; // Navbar layout

$name = $email = $subject = $message = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    $stmt = $conn->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
    if ($stmt->execute()) {
        $success = "Message sent successfully! We'll get back to you soon.";
        $name = $email = $subject = $message = '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
  <div class="text-center mb-12">
    <h1 class="text-4xl font-bold text-gray-900 mb-4">Contact Us</h1>
    <p class="text-xl text-gray-600">We're here to help! Get in touch with our team</p>
  </div>

  <?php if ($success): ?>
    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-6">
      <?= $success ?>
    </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Contact Info -->
    <div class="space-y-6">
      <div class="bg-white rounded shadow p-6 flex items-start space-x-4">
        <div class="w-12 h-12 bg-green-100 flex items-center justify-center rounded">
          📞
        </div>
        <div>
          <h3 class="font-semibold">Phone</h3>
          <p class="text-gray-600">+254 700 123 456</p>
          <p class="text-gray-600">+254 710 987 654</p>
        </div>
      </div>

      <div class="bg-white rounded shadow p-6 flex items-start space-x-4">
        <div class="w-12 h-12 bg-blue-100 flex items-center justify-center rounded">
          📧
        </div>
        <div>
          <h3 class="font-semibold">Email</h3>
          <p class="text-gray-600">support@kenyattamarket.com</p>
          <p class="text-gray-600">info@kenyattamarket.com</p>
        </div>
      </div>

      <div class="bg-white rounded shadow p-6 flex items-start space-x-4">
        <div class="w-12 h-12 bg-red-100 flex items-center justify-center rounded">
          📍
        </div>
        <div>
          <h3 class="font-semibold">Address</h3>
          <p class="text-gray-600">Kenyatta Avenue, Nairobi</p>
          <p class="text-gray-600">P.O. Box 12345, Nairobi</p>
        </div>
      </div>

      <div class="bg-white rounded shadow p-6 flex items-start space-x-4">
        <div class="w-12 h-12 bg-purple-100 flex items-center justify-center rounded">
          🕒
        </div>
        <div>
          <h3 class="font-semibold">Business Hours</h3>
          <p class="text-gray-600">Mon - Fri: 8:00 AM - 6:00 PM</p>
          <p class="text-gray-600">Sat - Sun: 9:00 AM - 4:00 PM</p>
        </div>
      </div>
    </div>

    <!-- Contact Form -->
    <div class="lg:col-span-2">
      <div class="bg-white rounded shadow">
        <div class="p-6 border-b">
          <h2 class="text-xl font-semibold">Send us a Message</h2>
        </div>
        <form method="POST" class="p-6 space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 mb-1" for="name">Full Name</label>
              <input type="text" name="name" id="name" value="<?= htmlspecialchars($name) ?>" required class="w-full border rounded px-3 py-2">
            </div>
            <div>
              <label class="block text-gray-700 mb-1" for="email">Email</label>
              <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required class="w-full border rounded px-3 py-2">
            </div>
          </div>
          <div>
            <label class="block text-gray-700 mb-1" for="subject">Subject</label>
            <input type="text" name="subject" id="subject" value="<?= htmlspecialchars($subject) ?>" required class="w-full border rounded px-3 py-2">
          </div>
          <div>
            <label class="block text-gray-700 mb-1" for="message">Message</label>
            <textarea name="message" id="message" rows="5" required class="w-full border rounded px-3 py-2"><?= htmlspecialchars($message) ?></textarea>
          </div>
          <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Send Message</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'components/footer.php'; ?>
</body>
</html>
