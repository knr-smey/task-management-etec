<?php

declare(strict_types=1);

require __DIR__ . '/../../config/app.php';

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
  <title>Forgot Password • <?= e(APP_NAME) ?></title>

  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>

  <style>
    #particles-js {
      position: fixed;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      z-index: 0;
    }

    main {
      position: relative;
      z-index: 1;
    }
  </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-blue-950 flex items-center justify-center p-4">
  <div id="particles-js"></div>

  <main class="w-full max-w-md">
    <div class="bg-white rounded-lg shadow-2xl overflow-hidden">
      <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-lg mb-3">
          <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
            <path d="M13 2L3 14h8l-1 8 10-12h-8l1-8z" />
          </svg>
        </div>
        <h1 class="text-2xl font-bold text-white mb-1"><?= e(APP_NAME) ?></h1>
        <p class="text-blue-100 text-sm">Reset your account password</p>
      </div>

      <div class="px-8 py-6">
        <form id="forgotForm" class="space-y-4">
          <input type="hidden" name="csrf" value="<?= e($token) ?>">

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
            <input
              name="email"
              type="email"
              required
              placeholder="you@etec.com"
              class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">New Password</label>
            <input
              name="new_password"
              type="password"
              required
              minlength="6"
              placeholder="Enter new password"
              class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm New Password</label>
            <input
              name="confirm_password"
              type="password"
              required
              minlength="6"
              placeholder="Confirm new password"
              class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
          </div>

          <div id="forgotAlert" class="hidden text-sm px-4 py-3 rounded-lg border"></div>

          <button
            id="btnForgotPassword"
            type="submit"
            class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2.5 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
            Reset Password
          </button>
        </form>

        <p class="text-center mt-4">
          <a href="<?= e(BASE_URL) ?>login" class="text-sm text-blue-600 hover:text-blue-700 font-semibold hover:underline transition">
            Back to Sign In
          </a>
        </p>
      </div>

      <div class="bg-gray-50 px-8 py-3 border-t border-gray-100">
        <p class="text-center text-xs text-gray-500">
          © <?= date('Y') ?> <?= e(APP_NAME) ?>. All rights reserved.
        </p>
      </div>
    </div>
  </main>

  <script>
    particlesJS('particles-js', {
      particles: {
        number: {
          value: 80,
          density: {
            enable: true,
            value_area: 800
          }
        },
        color: {
          value: '#ffffff'
        },
        shape: {
          type: 'circle'
        },
        opacity: {
          value: 0.5
        },
        size: {
          value: 3,
          random: true
        },
        line_linked: {
          enable: true,
          distance: 150,
          color: '#ffffff',
          opacity: 0.4,
          width: 1
        },
        move: {
          enable: true,
          speed: 2,
          out_mode: 'out'
        }
      },
      interactivity: {
        detect_on: 'canvas',
        events: {
          onhover: {
            enable: true,
            mode: 'grab'
          },
          onclick: {
            enable: true,
            mode: 'push'
          },
          resize: true
        },
        modes: {
          grab: {
            distance: 140,
            line_linked: {
              opacity: 1
            }
          },
          push: {
            particles_nb: 4
          }
        }
      },
      retina_detect: true
    });
  </script>
</body>
<script src="<?= e(BASE_URL) ?>assets/js/alert.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
  const BASE_URL = "<?= e(BASE_URL) ?>";
  const $forgotForm = $("#forgotForm");
  const $forgotBtn = $("#btnForgotPassword");

  function setForgotLoading(isLoading) {
    $forgotBtn
      .prop("disabled", isLoading)
      .html(isLoading ? '<span class="flex items-center justify-center"><svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Resetting...</span>' : "Reset Password")
      .toggleClass("opacity-70 cursor-not-allowed", isLoading);
  }

  $forgotForm.on("submit", function(e) {
    e.preventDefault();
    hideAlert("#forgotAlert");
    setForgotLoading(true);

    $.ajax({
      url: BASE_URL + "api/auth/forgot-password",
      method: "POST",
      data: $forgotForm.serialize(),
      dataType: "json",
      success(res) {
        if (!res.status) {
          showAlert("#forgotAlert", res.message || "Reset failed");
          setForgotLoading(false);
          return;
        }

        showAlert("#forgotAlert", res.message || "Password reset successful", true);
        setForgotLoading(false);
        setTimeout(() => {
          window.location.href = BASE_URL + "login";
        }, 1000);
      },
      error(xhr) {
        const res = xhr.responseJSON || (() => {
          try {
            return JSON.parse(xhr.responseText || "{}");
          } catch (_) {
            return null;
          }
        })();
        if (res && res.message) {
          showAlert("#forgotAlert", res.message);
        } else {
          console.error(xhr.responseText);
          showAlert("#forgotAlert", "Server error. Check PHP error log.");
        }
        setForgotLoading(false);
      }
    });
  });
</script>

</html>
