<?php
include 'db.php';
include 'components/navbar.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$search = $_GET['search'] ?? '';

// Prepare and execute query
if ($search) {
    $stmt = $conn->prepare("
        SELECT s.id as section_id, s.title, q.id as question_id, q.question, q.answer
        FROM faq_sections s
        JOIN faq_questions q ON s.id = q.section_id
        WHERE s.title LIKE ? OR q.question LIKE ? OR q.answer LIKE ?
        ORDER BY s.id, q.id
    ");
    $term = '%' . $search . '%';
    $stmt->bind_param("sss", $term, $term, $term);
} else {
    $stmt = $conn->prepare("
        SELECT s.id as section_id, s.title, q.id as question_id, q.question, q.answer
        FROM faq_sections s
        JOIN faq_questions q ON s.id = q.section_id
        ORDER BY s.id, q.id
    ");
}
$stmt->execute();
$result = $stmt->get_result();

// Group results by section
$faqData = [];
while ($row = $result->fetch_assoc()) {
    $sid = $row['section_id'];
    if (!isset($faqData[$sid])) {
        $faqData[$sid] = [
            'title' => $row['title'],
            'questions' => []
        ];
    }
    $faqData[$sid]['questions'][] = [
        'question' => $row['question'],
        'answer' => $row['answer']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Help Center - Kenyatta Market</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
<div class="min-h-screen">

<!-- Main -->
<div class="max-w-4xl mx-auto px-4 py-12">
  <div class="text-center mb-12">
    <h1 class="text-4xl font-bold text-gray-900 mb-4">Help Center</h1>
    <p class="text-xl text-gray-600">Find answers to frequently asked questions</p>
  </div>

  <!-- Search -->
  <form method="GET" class="mb-8 max-w-md mx-auto">
    <div class="relative">
      <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <input
        type="text"
        name="search"
        value="<?= htmlspecialchars($search) ?>"
        placeholder="Search for help..."
        class="pl-10 h-12 w-full rounded border border-gray-300"
      />
    </div>
  </form>

  <!-- FAQ Sections -->
  <div class="space-y-4" id="faq">
    <?php foreach ($faqData as $sid => $section): ?>
      <div class="bg-white shadow rounded-lg">
        <button
          class="w-full flex justify-between items-center px-6 py-4 text-left font-semibold text-gray-900 hover:bg-gray-100"
          onclick="toggleSection(<?= $sid ?>)">
          <?= htmlspecialchars($section['title']) ?>
          <svg id="icon-<?= $sid ?>" class="h-5 w-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
        <div id="content-<?= $sid ?>" class="px-6 pb-4 hidden">
          <?php foreach ($section['questions'] as $qa): ?>
            <div class="border-l-4 border-green-500 pl-4 mb-4">
              <h4 class="font-semibold text-gray-900 mb-1"><?= htmlspecialchars($qa['question']) ?></h4>
              <p class="text-gray-600"><?= htmlspecialchars($qa['answer']) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Contact Support -->
  <div class="mt-12 bg-white shadow rounded-lg p-8 text-center">
    <h3 class="text-xl font-semibold mb-4">Still need help?</h3>
    <p class="text-gray-600 mb-6">
      Can't find what you're looking for? Our support team is here to help.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="contact.php" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">Contact Support</a>
      <a href="chat.php" class="border border-gray-300 text-gray-700 px-6 py-2 rounded">Live Chat</a>
    </div>
  </div>
</div>

<?php include 'components/footer.php'; ?>

</div>

<!-- Toggle Script -->
<script>
  function toggleSection(id) {
    const content = document.getElementById('content-' + id);
    const icon = document.getElementById('icon-' + id);
    content.classList.toggle('hidden');
    icon.classList.toggle('rotate-90');
  }
</script>
</body>
</html>
