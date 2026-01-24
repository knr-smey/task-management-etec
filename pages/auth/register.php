<?php $token = csrf_token(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-md shadow-2xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Task Management</h1>
            <p class="text-gray-600">Sign up to get started</p>
        </div>

        <form id="registerForm" class="space-y-4" method="POST">
            <input type="hidden" name="csrf" value="<?= e($token) ?>">
            <div>
                <label for="name" class="block text-sm text-gray-700 mb-1">Full Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 outline-none"
                    placeholder="John Doe">
            </div>

            <div>
                <label for="email" class="block text-sm text-gray-700 mb-1">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 outline-none"
                    placeholder="you@example.com">
            </div>

            <div>
                <label for="password" class="block text-sm text-gray-700 mb-1">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 outline-none"
                    placeholder="••••••••">
            </div>
            <div>
                <label for="course" class="block text-sm text-gray-700 mb-1">Course</label>
                <select name="course" id="course" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 outline-none">
                    <option value="" disabled selected>Select Course</option>
                    <option value="Frontend">Frontend</option>
                    <option value="Backend">Backend</option>
                </select>
            </div>

            <button
                id="btnRegister"
                type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transform hover:scale-105 transition duration-200 shadow-lg">
                Register
            </button>
        </form>

        <p class="text-center text-gray-600 mt-6">
            Already have an account?
            <a href="<?= e(BASE_URL) ?>/login" class="text-indigo-600 hover:text-indigo-700 font-semibold">Sign in</a>
        </p>
    </div>
    <script src="<?= e(BASE_URL) ?>assets/js/alert.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        const BASE_URL = "<?= e(BASE_URL) ?>";

        const $form = $("#registerForm");
        const $btn = $("#btnRegister");

        function setLoading(isLoading) {
            $btn
                .prop("disabled", isLoading)
                .text(isLoading ? "Signing up..." : "Sign Up")
                .toggleClass("opacity-70 cursor-not-allowed", isLoading);
        }

        $form.on("submit", function(e) {
            e.preventDefault();
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
                    console.log(res);
                    
                    const redirectTo = res.data?.redirect || "login";
                    setTimeout(() => {
                        window.location.href = BASE_URL + redirectTo;
                    }, 500);
                },

                error(xhr) {
                    setLoading(false);
                }

            });
        });
    </script>
</body>

</html>