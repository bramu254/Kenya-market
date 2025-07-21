<?php
include 'db.php';
include 'components/navbar.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Privacy Policy - Kenyatta Market</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
<div class="min-h-screen">

<div class="max-w-4xl mx-auto px-4 py-12">
  <div class="text-center mb-10">
    <h1 class="text-4xl font-bold text-gray-900 mb-4">Privacy Policy</h1>
    <p class="text-gray-600 text-lg">Effective as of July 2025</p>
  </div>

  <div class="bg-white shadow rounded-lg p-6 space-y-6 text-gray-700 leading-relaxed">

    <div>
      <h2 class="text-xl font-semibold mb-2">1. Introduction</h2>
      <p>
        At Kenyatta Market, we value your privacy. This policy outlines how we collect, use, and safeguard your information when you use our website and services.
      </p>
    </div>

    <div>
      <h2 class="text-xl font-semibold mb-2">2. Information We Collect</h2>
      <ul class="list-disc ml-5">
        <li><strong>Personal Data:</strong> When you register or make a purchase, we collect your name, email, phone number, and delivery address.</li>
        <li><strong>Usage Data:</strong> We collect anonymous data about your device, browser, and interactions with our website.</li>
        <li><strong>Cookies:</strong> We use cookies to enhance your user experience and for analytics purposes.</li>
      </ul>
    </div>

    <div>
      <h2 class="text-xl font-semibold mb-2">3. How We Use Your Information</h2>
      <ul class="list-disc ml-5">
        <li>To process orders and provide customer support</li>
        <li>To send order confirmations, shipping updates, and important account notices</li>
        <li>To improve our platform and user experience</li>
        <li>To prevent fraud and ensure security</li>
      </ul>
    </div>

    <div>
      <h2 class="text-xl font-semibold mb-2">4. Data Sharing</h2>
      <p>
        We do not sell or rent your personal information. Your data may only be shared with trusted service providers for order fulfillment, payment processing, or legal compliance.
      </p>
    </div>

    <div>
      <h2 class="text-xl font-semibold mb-2">5. Data Security</h2>
      <p>
        We implement industry-standard security measures, such as encrypted connections (HTTPS), to protect your personal information from unauthorized access or disclosure.
      </p>
    </div>

    <div>
      <h2 class="text-xl font-semibold mb-2">6. Your Rights</h2>
      <ul class="list-disc ml-5">
        <li>Access or update your personal data</li>
        <li>Request deletion of your account</li>
        <li>Opt-out of marketing communications</li>
      </ul>
    </div>

    <div>
      <h2 class="text-xl font-semibold mb-2">7. Third-Party Links</h2>
      <p>
        Our site may contain links to third-party websites. We are not responsible for the privacy practices of those websites.
      </p>
    </div>

    <div>
      <h2 class="text-xl font-semibold mb-2">8. Changes to This Policy</h2>
      <p>
        We may update this policy periodically. Changes will be reflected on this page with an updated effective date.
      </p>
    </div>

    <div>
      <h2 class="text-xl font-semibold mb-2">9. Contact Us</h2>
      <p>
        If you have any questions or concerns about this policy, please <a href="contact.php" class="text-green-600 underline">contact our support team</a>.
      </p>
    </div>

  </div>
</div>

<?php include 'components/footer.php'; ?>
</div>
</body>
</html>
