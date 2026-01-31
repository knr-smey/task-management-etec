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
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
    <main class="w-full max-w-md px-4">
        <div class="bg-white shadow-xl border border-slate-200 p-8">

            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-slate-800"><?= e(APP_NAME) ?></h1>
                <p class="text-sm text-slate-500 mt-1">Create your account</p>
            </div>

            <form id="registerForm" class="space-y-4">
                <input type="hidden" name="csrf" value="<?= e($token) ?>">

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Full Name</label>
                    <input type="text" name="name" required placeholder="John Doe"
                        class="w-full border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" required placeholder="you@example.com"
                        class="w-full border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="w-full border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- COURSE (CUSTOM DROPDOWN) -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Course</label>

                    <div class="relative" id="courseWrapper">
                        <input type="hidden" name="course" id="courseValue">

                        <button type="button" id="courseBtn"
                            class="w-full border border-slate-300 bg-white px-3 py-2 text-sm text-left
                               flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <span id="courseLabel" class="text-slate-400">Select Course</span>
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div id="courseMenu"
                            class="hidden absolute z-10 mt-1 w-full bg-white border border-slate-200 shadow-lg">
                            <div class="course-item px-3 py-2 text-sm hover:bg-blue-50 cursor-pointer" data-value="Frontend">
                                Frontend
                            </div>
                            <div class="course-item px-3 py-2 text-sm hover:bg-blue-50 cursor-pointer" data-value="Backend">
                                Backend
                            </div>
                        </div>
                    </div>
                </div>

                <div id="registerAlert" class="hidden text-sm px-3 py-2 border"></div>

                <button id="btnRegister" type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 border border-blue-500
                       text-white font-medium py-2 transition">
                    Sign Up
                </button>
            </form>

            <p class="text-center text-gray-600 mt-6">
                Already have an account?
                <a href="<?= e(BASE_URL) ?>login" class="text-indigo-600 hover:text-indigo-700 font-semibold">
                    Sign in
                </a>
            </p>

            <div class="mt-6 text-center text-xs text-slate-500">
                © <?= date('Y') ?> <?= e(APP_NAME) ?>. All rights reserved.
            </div>

        </div>
    </main>

    <script src="<?= e(BASE_URL) ?>assets/js/alert.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        const BASE_URL = "<?= e(BASE_URL) ?>";
        const $form = $("#registerForm");
        const $btn = $("#btnRegister");

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
                label.classList.remove("text-slate-400");
                label.classList.add("text-slate-800");
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
            $btn.prop("disabled", isLoading)
                .text(isLoading ? "Signing up..." : "Sign Up")
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

                error() {
                    showAlert("#registerAlert", "Server error");
                    setLoading(false);
                }
            });
        });
    </script>
</body>

</html>