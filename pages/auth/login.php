<?php
declare(strict_types=1);

require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../config/app.php';

// If already logged in
if (!empty($_SESSION['user'])) {
    redirect('dashboard');
}

$token = csrf_token();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login • <?= e(APP_NAME) ?></title>
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>assets/css/app.css">
</head>
<body>
<main class="container">
  <div class="card">
    <h2>Login</h2>
    <p class="small">Demo account (change later): admin@example.com / admin123</p>

    <form method="post" action="<?= e(BASE_URL) ?>api/login">
      <input type="hidden" name="csrf" value="<?= e($token) ?>">
      <div class="row">
        <div>
          <label>Email</label>
          <input name="email" type="email" required>
        </div>
        <div>
          <label>Password</label>
          <input name="password" type="password" required>
        </div>
      </div>
      <div style="margin-top:12px;">
        <button type="submit">Sign in</button>
      </div>
    </form>
  </div>
</main>
</body>
</html>
