<?php
// shipping.php
include 'components/navbar.php';
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shipping Information</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
  <main class="min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Shipping Information</h1>
        <p class="text-xl text-gray-600">Everything you need to know about our delivery services</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <div class="card">
          <div class="card-header text-center">
            <img src="assets/icons/truck.svg" class="h-12 w-12 mx-auto mb-4" />
            <h2 class="text-lg font-semibold">Free Shipping</h2>
          </div>
          <div class="card-body text-center text-gray-600">
            Free shipping on orders above KSh 2,000 within major cities including Nairobi, Mombasa, Kisumu, and Nakuru.
          </div>
        </div>

        <div class="card">
          <div class="card-header text-center">
            <img src="assets/icons/clock.svg" class="h-12 w-12 mx-auto mb-4" />
            <h2 class="text-lg font-semibold">Fast Delivery</h2>
          </div>
          <div class="card-body text-center text-gray-600">
            Same-day delivery in Nairobi CBD, 1-2 days for major cities, and 2-5 days for other locations across Kenya.
          </div>
        </div>

        <div class="card">
          <div class="card-header text-center">
            <img src="assets/icons/mappin.svg" class="h-12 w-12 mx-auto mb-4" />
            <h2 class="text-lg font-semibold">Nationwide Coverage</h2>
          </div>
          <div class="card-body text-center text-gray-600">
            We deliver to all 47 counties in Kenya. Remote areas may require additional 1-2 days for delivery.
          </div>
        </div>

        <div class="card">
          <div class="card-header text-center">
            <img src="assets/icons/shield.svg" class="h-12 w-12 mx-auto mb-4" />
            <h2 class="text-lg font-semibold">Secure Packaging</h2>
          </div>
          <div class="card-body text-center text-gray-600">
            All items are carefully packaged to ensure they arrive in perfect condition. Fragile items receive extra protection.
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h2 class="text-lg font-bold">Shipping Rates</h2>
        </div>
        <div class="card-body overflow-x-auto">
          <table class="w-full text-left">
            <thead>
              <tr class="border-b">
                <th class="py-3">Location</th>
                <th class="py-3">Standard Shipping</th>
                <th class="py-3">Express Shipping</th>
                <th class="py-3">Delivery Time</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b">
                <td class="py-3">Nairobi CBD</td>
                <td class="py-3">KSh 200</td>
                <td class="py-3">KSh 500</td>
                <td class="py-3">Same day / 2-4 hours</td>
              </tr>
              <tr class="border-b">
                <td class="py-3">Major Cities</td>
                <td class="py-3">KSh 300</td>
                <td class="py-3">KSh 600</td>
                <td class="py-3">1-2 days / Next day</td>
              </tr>
              <tr class="border-b">
                <td class="py-3">Other Towns</td>
                <td class="py-3">KSh 400</td>
                <td class="py-3">KSh 800</td>
                <td class="py-3">2-3 days / 1-2 days</td>
              </tr>
              <tr>
                <td class="py-3">Remote Areas</td>
                <td class="py-3">KSh 600</td>
                <td class="py-3">KSh 1,000</td>
                <td class="py-3">3-5 days / 2-3 days</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card mt-8">
        <div class="card-header">
          <h2 class="text-lg font-bold">Shipping Policy</h2>
        </div>
        <div class="card-body space-y-4">
          <div>
            <h3 class="font-semibold mb-1">Order Processing</h3>
            <p class="text-gray-600">Orders are processed within 24 hours on weekdays. Weekend orders are processed on the next business day.</p>
          </div>
          <div>
            <h3 class="font-semibold mb-1">Tracking</h3>
            <p class="text-gray-600">All orders come with tracking information sent via SMS and email once shipped.</p>
          </div>
          <div>
            <h3 class="font-semibold mb-1">Delivery Attempts</h3>
            <p class="text-gray-600">We make up to 3 delivery attempts. Failed deliveries are returned to the seller after 7 days.</p>
          </div>
          <div>
            <h3 class="font-semibold mb-1">Special Items</h3>
            <p class="text-gray-600">Large items, electronics, and fragile goods may require special handling and longer delivery times.</p>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include 'components/footer.php'; ?>
</body>
</html>
