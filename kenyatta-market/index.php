<?php
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kenyatta Market | Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-br from-green-50 via-white to-red-50">
  <!-- Navbar -->
  <?php include 'components/navbar.php'; ?>

  <!-- Hero Section -->
  <section class="relative overflow-hidden bg-gradient-to-r from-green-600 via-black to-red-600 text-white">
    <div class="max-w-7xl mx-auto px-4 py-20 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <div>
        <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
          Kenya's Premier
          <span class="block text-yellow-400">Online Marketplace</span>
        </h1>
        <p class="text-xl mb-8 text-gray-200">
          Connect with buyers and sellers across Kenya. From fresh agricultural products to the latest electronics.
        </p>
        <div class="flex flex-col sm:flex-row gap-4">
          <a href="marketplace.php">
            <button class="bg-white text-green-600 hover:bg-gray-100 text-lg px-8 py-4 rounded">Start Shopping</button>
          </a>
          <a href="signup.php">
            <button class="border-2 border-white text-white hover:bg-white hover:text-black text-lg px-8 py-4 rounded">Join as Seller</button>
          </a>
        </div>
      </div>
      <div>
        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600" alt="Kenya Market" class="rounded-2xl shadow-2xl">
      </div>
    </div>
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
      <svg class="h-8 w-8 text-white opacity-70" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
    </div>
  </section>

  <!-- Categories Section -->
  <section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Explore Our Categories</h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
          Discover a wide range of products across various categories.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php
        $categories = [
          ["name" => "Agriculture", "desc" => "Fresh produce, farming tools", "image" => "https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=400", "color" => "from-green-400 to-green-600", "link" => "agriculture.php"],
          ["name" => "Fashion", "desc" => "Clothing & accessories", "image" => "https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400", "color" => "from-pink-400 to-red-600", "link" => "fashion.php"],
          ["name" => "Electronics", "desc" => "Phones, gadgets, accessories", "image" => "https://images.unsplash.com/photo-1468495244123-6c6c332eeece?w=400", "color" => "from-blue-400 to-purple-600", "link" => "electronics.php"],
          ["name" => "Home & Garden", "desc" => "Furniture, tools, decor", "image" => "https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400", "color" => "from-orange-400 to-yellow-600", "link" => "categories/home-garden.php"],
          ["name" => "Automotive", "desc" => "Vehicles, parts, tools", "image" => "https://images.unsplash.com/photo-1493238792000-8113da705763?w=400", "color" => "from-gray-400 to-gray-600", "link" => "automotive.php"],
          ["name" => "Health & Beauty", "desc" => "Cosmetics & health items", "image" => "https://images.unsplash.com/photo-1556228720-195a672e8a03?w=400", "color" => "from-purple-400 to-pink-600", "link" => "categories/health-beauty.php"]
        ];
        foreach ($categories as $cat): ?>
        <a href="<?= $cat['link'] ?>" class="group overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition">
          <div class="relative h-48 overflow-hidden">
            <img src="<?= $cat['image'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" />
            <div class="absolute inset-0 bg-gradient-to-t <?= $cat['color'] ?> opacity-80"></div>
            <div class="absolute inset-0 flex items-center justify-center">
              <h3 class="text-2xl font-bold text-white"><?= $cat['name'] ?></h3>
            </div>
          </div>
          <div class="p-6 bg-white">
            <p class="text-gray-600"><?= $cat['desc'] ?></p>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <div>
        <h2 class="text-4xl font-bold text-gray-900 mb-6">Why Choose Kenyatta Market?</h2>
        <p class="text-xl text-gray-600 mb-8">We provide the best online marketplace experience for Kenyan buyers and sellers.</p>
        <ul class="space-y-4">
          <?php
          $features = [
            ["title" => "Secure payment processing", "link" => "terms.php"],
            ["title" => "Nationwide delivery network", "link" => "shipping.php"],
            ["title" => "Verified seller system", "link" => "signup.php"],
            ["title" => "24/7 customer support", "link" => "help.php"],
            ["title" => "Mobile-friendly platform", "link" => "#"],
            ["title" => "Local language support", "link" => "#"]
          ];
          foreach ($features as $f): ?>
          <li class="flex items-center gap-2">
            <span class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center text-white font-bold">&#10003;</span>
            <a href="<?= $f['link'] ?>" class="text-gray-700 hover:underline"><?= $f['title'] ?></a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div>
        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600" class="rounded-2xl shadow-xl" />
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-20 bg-gradient-to-r from-green-600 to-red-600 text-white text-center">
    <div class="max-w-4xl mx-auto">
      <h2 class="text-4xl font-bold mb-6">Ready to Start Your Journey?</h2>
      <p class="text-xl mb-8">Join thousands of Kenyans who are already buying and selling on our platform.</p>
      <div class="flex flex-col sm:flex-row justify-center gap-4">
        <a href="signup.php"><button class="bg-white text-green-600 px-8 py-4 text-lg rounded">Get Started Today</button></a>
        <a href="marketplace.php"><button class="border-2 border-white px-8 py-4 text-lg rounded hover:bg-white hover:text-green-600">Browse Products</button></a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <?php include 'components/footer.php'; ?>
</body>
</html>
