<?php

declare(strict_types=1);

// require __DIR__ . '/../../includes/helpers.php';
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

  <!-- Tailwind -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">

  <main class="w-full max-w-md px-4">

    <!-- Card -->
    <div class="bg-white shadow-xl border border-slate-200 p-8">

      <!-- Title -->
      <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">
          <?= e(APP_NAME) ?>
        </h1>
        <p class="text-sm text-slate-500 mt-1">
          Sign in to your dashboard
        </p>
      </div>
      <!-- Form -->
      <form id="loginForm" class="space-y-4">
        <input type="hidden" name="csrf" value="<?= e($token) ?>">

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
          <input
            name="email"
            type="email"
            required
            placeholder="you@example.com"
            class="w-full border border-slate-300 px-3 py-2 text-sm
             focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
          <input
            name="password"
            type="password"
            required
            placeholder="••••••••"
            class="w-full border border-slate-300 px-3 py-2 text-sm
             focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Alert -->
        <div id="loginAlert" class="hidden text-sm px-3 py-2 border"></div>

        <button
          id="btnLogin"
          type="submit"
          class="w-full mt-2 bg-blue-600 hover:bg-blue-700 cursor-pointer border border-blue-500
           text-white font-medium py-1.5 transition">
          Sign In
        </button>
      </form>
    <p class="text-center text-gray-600 mt-6">
            Don`t have an account?
            <a href="<?= e(BASE_URL) ?>register" class="text-indigo-600 hover:text-indigo-700 font-semibold">Sign up</a>
        </p>

      <!-- Footer -->
      <div class="mt-6 text-center text-xs text-slate-500">
        © <?= date('Y') ?> <?= e(APP_NAME) ?>. All rights reserved.
      </div>

    </div>
  </main>

</body>
<script src="<?= e(BASE_URL) ?>assets/js/alert.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
  const BASE_URL = "<?= e(BASE_URL) ?>";

  const $form = $("#loginForm");
  const $btn = $("#btnLogin");

  function setLoading(isLoading) {
    $btn
      .prop("disabled", isLoading)
      .text(isLoading ? "Signing in..." : "Sign In")
      .toggleClass("opacity-70 cursor-not-allowed", isLoading);
  }

  $form.on("submit", function(e) {
    e.preventDefault();

    hideAlert("#loginAlert");
    setLoading(true);

    $.ajax({
      url: BASE_URL + "api/auth/login",
      method: "POST",
      data: $form.serialize(),
      dataType: "json",
      xhrFields: {
        withCredentials: true
      },

      success(res) {
        if (!res.status) {
          showAlert("#loginAlert", res.message || "Login failed");
          setLoading(false);
          return;
        }

        showAlert("#loginAlert", res.message || "Login successful", true);

        const redirectTo = res.data?.redirect || "dashboard";
        setTimeout(() => {
          window.location.href = BASE_URL + redirectTo;
        }, 500);
      },

      error(xhr) {
        console.error(xhr.responseText);
        showAlert("#loginAlert", "Server error. Check PHP error log.");
        setLoading(false);
      }
    });
  });
</script>


</html>