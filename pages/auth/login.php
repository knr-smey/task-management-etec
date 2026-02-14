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

  <!-- Particles.js -->
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

  <!-- Particles Background -->
  <div id="particles-js"></div>

  <main class="w-full max-w-md">

    <!-- Card -->
    <div class="bg-white rounded-lg shadow-2xl overflow-hidden">

      <!-- Logo Section -->
      <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-lg mb-3">
          <!-- Lightning Bolt Icon (matching your dashboard) -->
          <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
            <path d="M13 2L3 14h8l-1 8 10-12h-8l1-8z" />
          </svg>
        </div>
        <h1 class="text-2xl font-bold text-white mb-1">
          <?= e(APP_NAME) ?>
        </h1>
        <p class="text-blue-100 text-sm">
          Sign in to your dashboard
        </p>
      </div>

      <!-- Form Section -->
      <div class="px-8 py-6">
        <form id="loginForm" class="space-y-4">
          <input type="hidden" name="csrf" value="<?= e($token) ?>">

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
            <input
              name="email"
              type="email"
              required
              placeholder="you@etec.com"
              class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm
               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
            <div class="relative">
              <input
                id="passwordInput"
                name="password"
                type="password"
                required
                placeholder="Enter your password"
                class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 pr-10 text-sm
                 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
              <button
                id="togglePassword"
                type="button"
                aria-label="Show password"
                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-blue-600">
                <svg id="eyeOpenIcon" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M2 12s4-6 10-6 10 6 10 6-4 6-10 6-10-6-10-6z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
                <svg id="eyeClosedIcon" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M3 3l18 18" />
                  <path d="M10.58 10.58A2 2 0 0012 14a2 2 0 001.42-.58" />
                  <path d="M6.1 6.1C4 7.7 2.7 10 2 12c1.7 4 6 7 10 7 1.9 0 3.7-.6 5.2-1.6" />
                  <path d="M9.9 4.6C10.6 4.4 11.3 4.3 12 4.3c4 0 8 2.6 10 7-0.6 1.4-1.5 2.7-2.6 3.8" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Alert -->
          <div id="loginAlert" class="hidden text-sm px-4 py-3 rounded-lg border"></div>

          <button
            id="btnLogin"
            type="submit"
            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 
             text-white font-semibold py-2.5 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
            Sign In
          </button>
        </form>

        <p class="text-center mt-3">
          <a href="<?= e(BASE_URL) ?>forgot-password" class="text-sm text-blue-600 hover:text-blue-700 font-semibold hover:underline transition">
            Forgot password?
          </a>
        </p>

        <!-- Divider -->
        <div class="relative my-4">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-white text-gray-500">New to <?= e(APP_NAME) ?>?</span>
          </div>
        </div>

        <!-- Register Link -->
        <p class="text-center">
          <a href="<?= e(BASE_URL) ?>register"
            class="text-blue-600 hover:text-blue-700 font-semibold hover:underline transition">
            Create an account
          </a>
        </p>
      </div>

      <!-- Footer -->
      <div class="bg-gray-50 px-8 py-3 border-t border-gray-100">
        <p class="text-center text-xs text-gray-500">
          © <?= date('Y') ?> <?= e(APP_NAME) ?>. All rights reserved.
        </p>
      </div>

    </div>
  </main>

  <!-- Particles.js Configuration -->
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
          type: 'circle',
          stroke: {
            width: 0,
            color: '#000000'
          }
        },
        opacity: {
          value: 0.5,
          random: false,
          anim: {
            enable: false
          }
        },
        size: {
          value: 3,
          random: true,
          anim: {
            enable: false
          }
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
          direction: 'none',
          random: false,
          straight: false,
          out_mode: 'out',
          bounce: false,
          attract: {
            enable: false
          }
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

  const $form = $("#loginForm");
  const $btn = $("#btnLogin");

  function setLoading(isLoading) {
    $btn
      .prop("disabled", isLoading)
      .html(isLoading ? '<span class="flex items-center justify-center"><svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Signing in...</span>' : "Sign In")
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
        const res = xhr.responseJSON || (() => {
          try {
            return JSON.parse(xhr.responseText || "{}");
          } catch (_) {
            return null;
          }
        })();
        if (res && res.message) {
          showAlert("#loginAlert", res.message);
        } else {
          console.error(xhr.responseText);
          showAlert("#loginAlert", "Server error. Check PHP error log.");
        }
        setLoading(false);
      }
    });
  });

  const $passwordInput = $("#passwordInput");
  const $togglePassword = $("#togglePassword");
  const $eyeOpenIcon = $("#eyeOpenIcon");
  const $eyeClosedIcon = $("#eyeClosedIcon");

  $togglePassword.on("click", function() {
    const isHidden = $passwordInput.attr("type") === "password";
    $passwordInput.attr("type", isHidden ? "text" : "password");
    $togglePassword.attr("aria-label", isHidden ? "Hide password" : "Show password");
    $eyeOpenIcon.toggleClass("hidden", isHidden);
    $eyeClosedIcon.toggleClass("hidden", !isHidden);
  });
</script>

</html>
