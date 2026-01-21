<?php
declare(strict_types=1);
require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../config/app.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>404 • <?= e(APP_NAME) ?></title>
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>assets/css/app.css">
</head>
<body>
<main class="container">
  <div class="card">
    <h2>404 Not Found</h2>
    <p class="small">The page you requested doesn’t exist.</p>
    <p><a href="<?= e(BASE_URL) ?>login">Go to login</a></p>
  </div>
</main>
</body>
</html>
