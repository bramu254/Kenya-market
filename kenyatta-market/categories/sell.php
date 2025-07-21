<?php
include '../db.php';

$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $businessName = $conn->real_escape_string($_POST['business_name']);
  $category = $conn->real_escape_string($_POST['category']);
  $phone = $conn->real_escape_string($_POST['phone']);
  $location = $conn->real_escape_string($_POST['location']);
  $experience = $conn->real_escape_string($_POST['experience']);
  $description = $conn->real_escape_string($_POST['description']);

  $sql = "INSERT INTO sellers (business_name, category, phone, location, experience, description)
          VALUES ('$businessName', '$category', '$phone', '$location', '$experience', '$description')";

  if ($conn->query($sql) === TRUE) {
    $successMsg = "Application submitted! You can now access your seller dashboard.";
  } else {
    $successMsg = "Error: " . $conn->error;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Become a Seller</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
  <?php include '../components/navbar.php'; ?>
  <!-- Hero Section -->
  <div class="bg-gradient-to-r from-green-600 to-red-600 text-white py-16 text-center">
    <h1 class="text-4xl font-bold mb-4">Start Selling Today</h1>
    <p class="text-xl mb-8">Join thousands of successful sellers on Kenya's premier marketplace</p>
    <div class="flex justify-center space-x-4">
      <a href="#form">
        <button class="bg-white text-green-600 px-6 py-2 rounded hover:bg-gray-100">Become a Seller</button>
      </a>
      <a href="seller-dashboard.php">
        <button class="border border-white text-white px-6 py-2 rounded hover:bg-white hover:text-green-600">Seller Dashboard</button>
      </a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="max-w-7xl mx-auto px-4 py-16">
    <?php if ($successMsg): ?>
      <div class="mb-6 p-4 bg-green-100 text-green-700 rounded"><?php echo $successMsg; ?></div>
    <?php endif; ?>

    <!-- Benefits Section -->
    <div class="text-center mb-16">
      <h2 class="text-3xl font-bold mb-4">Why Sell With Us?</h2>
      <p class="text-lg text-gray-600 mb-12">Everything you need to grow your business online</p>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php
          $benefits = [
            ['icon' => '🏪', 'title' => 'Easy Setup', 'desc' => 'Get your store up and running in minutes.'],
            ['icon' => '📈', 'title' => 'Reach More Customers', 'desc' => 'Access millions of buyers across Kenya.'],
            ['icon' => '🔒', 'title' => 'Secure Payments', 'desc' => 'Get paid securely and on time.'],
            ['icon' => '🙋‍♀️', 'title' => '24/7 Support', 'desc' => 'We’re always here to help.']
          ];
          foreach ($benefits as $b) {
            echo "<div class='p-6 bg-white shadow rounded text-center'>
                    <div class='text-3xl mb-3'>{$b['icon']}</div>
                    <h3 class='font-semibold text-lg mb-2'>{$b['title']}</h3>
                    <p class='text-gray-600'>{$b['desc']}</p>
                  </div>";
          }
        ?>
      </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-12">
      <!-- How it Works -->
      <div>
        <div class="bg-white shadow rounded p-6 mb-8">
          <h3 class="text-xl font-bold mb-4">How It Works</h3>
          <ol class="space-y-3 text-gray-700">
            <li>1. Complete the seller application form</li>
            <li>2. Verify your business information</li>
            <li>3. Set up your seller profile</li>
            <li>4. Start listing your products</li>
            <li>5. Begin selling and earning</li>
          </ol>
        </div>

        <div class="bg-white shadow rounded p-6">
          <h3 class="text-xl font-bold mb-4">Seller Benefits</h3>
          <ul class="space-y-2 text-gray-700">
            <?php
              $sellerBenefits = [
                "No monthly fees - only pay when you sell",
                "Free listing for unlimited products",
                "Marketing tools to promote your products",
                "Analytics dashboard to track performance",
                "Mobile app for managing on the go",
                "Dedicated seller support team"
              ];
              foreach ($sellerBenefits as $benefit) {
                echo "<li class='flex items-center'><span class='text-green-600 mr-2'>✔️</span>$benefit</li>";
              }
            ?>
          </ul>
        </div>
      </div>

      <!-- Seller Application Form -->
      <form method="POST" id="form" class="bg-white shadow rounded p-6 space-y-4">
        <h3 class="text-xl font-bold mb-4">Seller Application</h3>
        <div>
          <label class="block mb-1 font-medium">Business Name</label>
          <input type="text" name="business_name" required class="w-full p-2 border rounded" />
        </div>
        <div>
          <label class="block mb-1 font-medium">Primary Category</label>
          <select name="category" required class="w-full p-2 border rounded">
            <option value="">Select your category</option>
            <option value="agriculture">Agriculture</option>
            <option value="electronics">Electronics</option>
            <option value="fashion">Fashion</option>
            <option value="automotive">Automotive</option>
            <option value="home">Home & Garden</option>
            <option value="health">Health & Beauty</option>
          </select>
        </div>
        <div>
          <label class="block mb-1 font-medium">Phone Number</label>
          <input type="text" name="phone" required class="w-full p-2 border rounded" />
        </div>
        <div>
          <label class="block mb-1 font-medium">Location</label>
          <input type="text" name="location" required class="w-full p-2 border rounded" />
        </div>
        <div>
          <label class="block mb-1 font-medium">Experience</label>
          <select name="experience" required class="w-full p-2 border rounded">
            <option value="">Select experience</option>
            <option value="new">New to selling</option>
            <option value="1year">Less than 1 year</option>
            <option value="2years">1-2 years</option>
            <option value="5years">2-5 years</option>
            <option value="more">More than 5 years</option>
          </select>
        </div>
        <div>
          <label class="block mb-1 font-medium">Business Description</label>
          <textarea name="description" rows="4" required class="w-full p-2 border rounded"></textarea>
        </div>
        <button type="submit" class="bg-green-600 text-white w-full p-2 rounded hover:bg-green-700">
          Submit Application
        </button>
      </form>
    </div>
  </div>

<?php include '../components/footer.php'; ?>
</body>
</html>
