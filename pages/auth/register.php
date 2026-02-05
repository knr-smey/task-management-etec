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
    <title>Register • <?= e(APP_NAME) ?></title>

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
                    Create your account
                </p>
            </div>

            <!-- Form Section -->
            <div class="px-8 py-6">
                <form id="registerForm" class="space-y-4">
                    <input type="hidden" name="csrf" value="<?= e($token) ?>">

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
                        <input
                            type="text"
                            name="name"
                            required
                            placeholder="John Doe"
                            class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm
                             focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                        <input
                            type="email"
                            name="email"
                            required
                            placeholder="you@etec.com"
                            class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm
                             focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <div class="relative">
                            <input
                                id="registerPassword"
                                type="password"
                                name="password"
                                required
                                placeholder="Enter your password"
                                class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 pr-10 text-sm
                                 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <button
                                id="toggleRegisterPassword"
                                type="button"
                                aria-label="Show password"
                                aria-pressed="false"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-blue-600">
                                <svg id="registerEyeIcon" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg id="registerEyeOffIcon" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.8 21.8 0 0 1 5.06-6.94"></path>
                                    <path d="M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a21.8 21.8 0 0 1-4.12 5.94"></path>
                                    <path d="M1 1l22 22"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- COURSE (CUSTOM DROPDOWN) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Course</label>

                        <div class="relative" id="courseWrapper">
                            <input type="hidden" name="course" id="courseValue">

                            <button type="button" id="courseBtn"
                                class="w-full border-2 border-gray-200 rounded-lg bg-white px-3 py-2 text-sm text-left
                                   flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <span id="courseLabel" class="text-gray-400">Select Course</span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div id="courseMenu"
                                class="hidden absolute z-10 mt-2 w-full bg-white border-2 border-gray-200 rounded-lg shadow-lg overflow-hidden">
                                <div class="course-item px-3 py-2 text-sm hover:bg-blue-50 cursor-pointer transition" data-value="Frontend">
                                    Frontend
                                </div>
                                <div class="course-item px-3 py-2 text-sm hover:bg-blue-50 cursor-pointer transition" data-value="Backend">
                                    Backend
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alert -->
                    <div id="registerAlert" class="hidden text-sm px-4 py-3 rounded-lg border"></div>

                    <button id="btnRegister" type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 
                         text-white font-semibold py-2.5 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                        Sign Up
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">Already have an account?</span>
                    </div>
                </div>

                <!-- Login Link -->
                <p class="text-center">
                    <a href="<?= e(BASE_URL) ?>login"
                        class="text-blue-600 hover:text-blue-700 font-semibold hover:underline transition">
                        Sign in
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

    <script src="<?= e(BASE_URL) ?>assets/js/alert.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        const BASE_URL = "<?= e(BASE_URL) ?>";
        const $form = $("#registerForm");
        const $btn = $("#btnRegister");
        const $password = $("#registerPassword");
        const $togglePassword = $("#toggleRegisterPassword");
        const $eyeIcon = $("#registerEyeIcon");
        const $eyeOffIcon = $("#registerEyeOffIcon");

        // Custom dropdown logic
        const wrapper = document.getElementById("courseWrapper");
        const btn = document.getElementById("courseBtn");
        const menu = document.getElementById("courseMenu");
        const label = document.getElementById("courseLabel");
        const value = document.getElementById("courseValue");

        btn.onclick = () => menu.classList.toggle("hidden");

        menu.querySelectorAll(".course-item").forEach(item => {
            item.onclick = () => {
                const val = item.dataset.value;
                value.value = val;
                label.textContent = val;
                label.classList.remove("text-gray-400");
                label.classList.add("text-gray-800");
                menu.classList.add("hidden");
            };
        });

        document.addEventListener("click", e => {
            if (!wrapper.contains(e.target)) {
                menu.classList.add("hidden");
            }
        });

        // Ajax submit
        function setLoading(isLoading) {
            $btn
                .prop("disabled", isLoading)
                .html(isLoading ? '<span class="flex items-center justify-center"><svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Signing up...</span>' : "Sign Up")
                .toggleClass("opacity-70 cursor-not-allowed", isLoading);
        }

        $form.on("submit", function(e) {
            e.preventDefault();
            hideAlert("#registerAlert");

            if (!value.value) {
                showAlert("#registerAlert", "Please select a course");
                return;
            }

            setLoading(true);

            $.ajax({
                url: BASE_URL + "api/auth/register",
                method: "POST",
                data: $form.serialize(),
                dataType: "json",
                xhrFields: {
                    withCredentials: true
                },

                success(res) {
                    if (!res.status) {
                        showAlert("#registerAlert", res.message);
                        setLoading(false);
                        return;
                    }
                    showAlert("#registerAlert", res.message, true);
                    setTimeout(() => {
                        window.location.href = BASE_URL + "login";
                    }, 500);
                },

                error(xhr) {
                    const res = xhr.responseJSON;
                    if (res && res.message) {
                        showAlert("#registerAlert", res.message);
                    } else {
                        showAlert("#registerAlert", "Server error");
                    }
                    setLoading(false);
                }
            });
        });

        $togglePassword.on("click", function() {
            const isHidden = $password.attr("type") === "password";
            $password.attr("type", isHidden ? "text" : "password");
            $togglePassword.attr("aria-pressed", String(isHidden));
            $togglePassword.attr("aria-label", isHidden ? "Hide password" : "Show password");
            $eyeIcon.toggleClass("hidden", isHidden);
            $eyeOffIcon.toggleClass("hidden", !isHidden);
        });
    </script>
</body>

</html>