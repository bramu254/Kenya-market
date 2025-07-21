<?php include 'components/Navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Terms of Service</title>
  <link rel="stylesheet" href="assets/styles.css">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-50">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
      <h1 class="text-4xl font-bold text-gray-900 mb-4">Terms of Service</h1>
      <p class="text-gray-600">Last updated: January 2024</p>
    </div>

    <div class="space-y-8">

      <?php
      $terms = [
        ["title" => "1. Acceptance of Terms", "content" => "By accessing and using Kenyatta Market Hub, you accept and agree to be bound by the terms and provision of this agreement."],
        ["title" => "2. User Accounts", "content" => "When you create an account with us, you must provide information that is accurate, complete, and current at all times. You are responsible for safeguarding the password and for keeping your account secure."],
        ["title" => "3. Buying and Selling", "content" => "Kenyatta Market Hub provides a platform for buyers and sellers to connect. We are not party to any agreements between buyers and sellers. All transactions are between users.",
          "list" => [
            "Sellers are responsible for accurate product descriptions",
            "Buyers are responsible for reading product details carefully",
            "All sales are final unless otherwise specified by the seller",
            "Disputes should be resolved directly between parties"
          ]
        ],
        ["title" => "4. Payments and Fees", "content" => "Payment processing is handled by third-party providers. We may charge fees for certain services, which will be clearly disclosed before any transaction."],
        ["title" => "5. Prohibited Items and Activities", "content" => "The following items and activities are prohibited on our platform:",
          "list" => [
            "Illegal goods or services",
            "Counterfeit or stolen items",
            "Harmful or dangerous products",
            "Fraudulent activities",
            "Spam or misleading content"
          ]
        ],
        ["title" => "6. Privacy and Data Protection", "content" => "Your privacy is important to us. Please review our Privacy Policy to understand how we collect, use, and protect your information."],
        ["title" => "7. Limitation of Liability", "content" => "Kenyatta Market Hub shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use of the platform."],
        ["title" => "8. Termination", "content" => "We may terminate or suspend your account immediately, without prior notice or liability, for any reason whatsoever, including breach of the Terms."],
        ["title" => "9. Governing Law", "content" => "These Terms shall be governed by and construed in accordance with the laws of Kenya, without regard to its conflict of law provisions."],
        ["title" => "10. Contact Information", "content" => "If you have any questions about these Terms of Service, please contact us at legal@kenyattamarket.com"]
      ];

      foreach ($terms as $section) {
        echo '<div class="bg-white shadow rounded p-6">';
        echo '<h2 class="text-xl font-semibold mb-4">' . $section["title"] . '</h2>';
        echo '<p class="text-gray-700">' . $section["content"] . '</p>';
        if (isset($section["list"])) {
          echo '<ul class="list-disc pl-6 mt-4">';
          foreach ($section["list"] as $item) {
            echo '<li>' . $item . '</li>';
          }
          echo '</ul>';
        }
        echo '</div>';
      }
      ?>

    </div>
  </div>

  <?php include 'components/Footer.php'; ?>
</body>
</html>
