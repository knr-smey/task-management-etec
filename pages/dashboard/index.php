<?php
declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php'; // must start session + protect

require_login();

$user = $_SESSION['user'] ?? null;

require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/navbar.php';
require __DIR__ . '/../../includes/sidebar.php';
?>

<main class="ms-60 mt-[9vh] overflow-y-auto min-h-[91vh] bg-white">
  <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>

  <!-- page content here -->
</main>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
