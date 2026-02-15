<?php

declare(strict_types=1);

require __DIR__ . '/../config/app.php';
require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../includes/auth.php';

require_once __DIR__ . '/../includes/layouts/app.php';

$profileUser = $profileUser ?? ($_SESSION['user'] ?? []);
$user = $profileUser;
$token = $token ?? csrf_token();
$roles = $profileUser['roles'] ?? [];
$rolesLabel = is_array($roles) ? implode(', ', $roles) : (string)$roles;
$memberSince = '-';
if (!empty($user['created_at'])) {
    $ts = strtotime((string)$user['created_at']);
    if ($ts !== false) {
        $memberSince = date('M Y', $ts);
    }
}

// Generate avatar initials
$name = $user['name'] ?? 'User';
$nameParts = explode(' ', $name);
$initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));

// Generate random gradient colors based on user email
$colors = [
    ['from-purple-500', 'to-pink-500'],
    ['from-blue-500', 'to-cyan-500'],
    ['from-green-500', 'to-emerald-500'],
    ['from-orange-500', 'to-red-500'],
    ['from-indigo-500', 'to-purple-500'],
    ['from-teal-500', 'to-green-500'],
];
$colorIndex = crc32($user['email'] ?? '') % count($colors);
$gradient = $colors[$colorIndex];
?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div>
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Profile Settings</h1>
            <p class="text-gray-600 mt-1">Manage your account settings and preferences</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left Profile Card -->
            <aside class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-gray-100">
                    <!-- Gradient Header -->
                    <div class="h-32 bg-gradient-to-br <?= e($gradient[0]) ?> <?= e($gradient[1]) ?> relative">
                        <div class="absolute -bottom-16 left-1/2 transform -translate-x-1/2">
                            <div class="w-32 h-32 rounded-full bg-gradient-to-br <?= e($gradient[0]) ?> <?= e($gradient[1]) ?> flex items-center justify-center text-white text-3xl font-bold shadow-xl ring-4 ring-white">
                                <?= e($initials) ?>
                            </div>
                        </div>
                    </div>

                    <div class="pt-20 pb-6 px-6 text-center">
                        <h2 class="text-2xl font-bold text-gray-900"><?= e($user['name'] ?? 'User') ?></h2>
                        <p class="text-sm text-gray-500 mt-1"><?= e($user['email'] ?? '-') ?></p>

                        <!-- Course Badge -->
                        <div class="mt-4 inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span class="text-sm font-semibold text-blue-900"><?= e($user['course'] ?? '-') ?></span>
                        </div>

                        <!-- Roles -->
                        <?php if (!empty($roles)): ?>
                            <div class="mt-6">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Roles</p>
                                <div class="flex flex-wrap gap-2 justify-center">
                                    <?php foreach ($roles as $role): ?>
                                        <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-slate-100 to-slate-200 text-slate-700 border border-slate-300">
                                            <?= e($role) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="mt-6 bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Account Info</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                Active
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Member Since</span>
                            <span class="text-sm font-semibold text-gray-900"><?= e($memberSince) ?></span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Right Settings Panel -->
            <section class="lg:col-span-2 space-y-6">

                <!-- Profile Information -->
                <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-visible border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Profile Information</h2>
                                <p class="text-sm text-gray-500">Update your account details</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <form id="profileInfoForm" class="space-y-5">
                            <input type="hidden" name="csrf" value="<?= e($token) ?>">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-sm font-semibold text-gray-700 block mb-2">Full Name</label>
                                    <input type="text" name="name" required value="<?= e($user['name'] ?? '') ?>"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all bg-gray-50 hover:bg-white">
                                </div>

                                <div>
                                    <label class="text-sm font-semibold text-gray-700 block mb-2">Email Address</label>
                                    <input id="profileEmail" type="email" name="email" required value="<?= e($user['email'] ?? '') ?>"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all bg-gray-50 hover:bg-white">
                                    <p id="emailValidationText" class="mt-1 text-xs text-gray-500">Email must end with @etec.com</p>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="text-sm font-semibold text-gray-700 block mb-2">Course</label>
                                    <div class="relative">
                                        <input type="hidden" name="course" id="courseValue" value="<?= e($user['course'] ?? 'Frontend') ?>">
                                        <button id="courseDropdownBtn" type="button"
                                            class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm text-left bg-white hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition flex items-center justify-between">
                                            <span id="courseSelectedText"><?= e($user['course'] ?? 'Frontend') ?></span>
                                            <svg id="courseArrow" class="w-4 h-4 text-gray-600 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
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
                                            <div class="course-item px-3 py-2 text-sm hover:bg-blue-50 cursor-pointer transition" data-value="Full-Stack">
                                                Full-Stack
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="profileInfoAlert" class="hidden text-sm px-4 py-3 rounded-xl border"></div>

                            <button id="btnProfileSave" type="submit"
                                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3.5 rounded-xl transition-all shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 transform hover:-translate-y-0.5">
                                Save Profile
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Security Settings</h2>
                                <p class="text-sm text-gray-500">Update your password to keep your account secure</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <form id="changePasswordForm" class="space-y-5">
                            <input type="hidden" name="csrf" value="<?= e($token) ?>">

                            <div>
                                <label class="text-sm font-semibold text-gray-700 block mb-2">New Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                        </svg>
                                    </div>
                                    <input id="newPasswordInput" type="password" name="new_password" required minlength="6"
                                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all bg-gray-50 hover:bg-white">
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-semibold text-gray-700 block mb-2">Confirm New Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <input id="confirmPasswordInput" type="password" name="confirm_password" required minlength="6"
                                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all bg-gray-50 hover:bg-white">
                                </div>
                            </div>
                            <p id="passwordValidationText" class="text-xs text-gray-500">Password must be at least 6 characters</p>

                            <div id="passwordAlert" class="hidden text-sm px-4 py-3 rounded-xl border"></div>

                            <button id="btnPasswordUpdate" type="submit"
                                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3.5 rounded-xl transition-all shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 transform hover:-translate-y-0.5">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Update Password
                                </span>
                            </button>
                        </form>
                    </div>
                </div>

            </section>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/layouts/app-footer.php'; ?>

<script src="<?= e(BASE_URL) ?>assets/js/alert.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    const BASE_URL = "<?= e(BASE_URL) ?>";
    const $profileForm = $("#profileInfoForm");
    const $passwordForm = $("#changePasswordForm");
    const $courseBtn = $("#courseDropdownBtn");
    const $courseMenu = $("#courseMenu");
    const $courseValue = $("#courseValue");
    const $courseSelectedText = $("#courseSelectedText");
    const $courseArrow = $("#courseArrow");
    const $profileEmail = $("#profileEmail");
    const $emailValidationText = $("#emailValidationText");
    const $newPasswordInput = $("#newPasswordInput");
    const $confirmPasswordInput = $("#confirmPasswordInput");
    const $passwordValidationText = $("#passwordValidationText");
    const $btnProfileSave = $("#btnProfileSave");
    const $btnPasswordUpdate = $("#btnPasswordUpdate");
    let profileAlertTimer = null;
    let passwordAlertTimer = null;

    function autoHideAlert(selector, timerKey) {
        if (timerKey === "profile" && profileAlertTimer) clearTimeout(profileAlertTimer);
        if (timerKey === "password" && passwordAlertTimer) clearTimeout(passwordAlertTimer);

        const timer = setTimeout(() => hideAlert(selector), 2000);
        if (timerKey === "profile") profileAlertTimer = timer;
        if (timerKey === "password") passwordAlertTimer = timer;
    }

    $courseBtn.on("click", function() {
        $courseMenu.toggleClass("hidden");
        $courseArrow.toggleClass("rotate-180", !$courseMenu.hasClass("hidden"));
    });

    $(document).on("click", function(e) {
        const inside = $(e.target).closest("#courseDropdownBtn, #courseMenu").length > 0;
        if (!inside) {
            $courseMenu.addClass("hidden");
            $courseArrow.removeClass("rotate-180");
        }
    });

    $(".course-item").on("click", function() {
        const value = $(this).data("value");
        $courseValue.val(value);
        $courseSelectedText.text(value);
        $courseMenu.addClass("hidden");
        $courseArrow.removeClass("rotate-180");
    });

    function isEtecEmail(email) {
        return /^[^\s@]+@etec\.com$/i.test((email || "").trim());
    }

    function validateProfileEmail() {
        const ok = isEtecEmail($profileEmail.val());
        $emailValidationText
            .text(ok ? "Valid @etec.com email" : "Email must end with @etec.com")
            .toggleClass("text-green-600", ok)
            .toggleClass("text-red-600", !ok)
            .toggleClass("text-gray-500", false);
        $btnProfileSave.prop("disabled", !ok).toggleClass("opacity-60 cursor-not-allowed", !ok);
        return ok;
    }

    function validatePasswordFields() {
        const newPass = ($newPasswordInput.val() || "");
        const confirmPass = ($confirmPasswordInput.val() || "");
        const lengthOk = newPass.length >= 6;
        const matchOk = confirmPass.length > 0 && newPass === confirmPass;
        const ok = lengthOk && matchOk;

        if (!lengthOk) {
            $passwordValidationText.text("Password must be at least 6 characters");
            $passwordValidationText.removeClass("text-green-600").addClass("text-red-600");
        } else if (!matchOk) {
            $passwordValidationText.text("Confirm password does not match");
            $passwordValidationText.removeClass("text-green-600").addClass("text-red-600");
        } else {
            $passwordValidationText.text("Password looks good");
            $passwordValidationText.removeClass("text-red-600").addClass("text-green-600");
        }

        $btnPasswordUpdate.prop("disabled", !ok).toggleClass("opacity-60 cursor-not-allowed", !ok);
        return ok;
    }

    $profileEmail.on("keyup blur", validateProfileEmail);
    $newPasswordInput.on("keyup blur", validatePasswordFields);
    $confirmPasswordInput.on("keyup blur", validatePasswordFields);
    validateProfileEmail();
    validatePasswordFields();

    $profileForm.on("submit", function(e) {
        e.preventDefault();
        hideAlert("#profileInfoAlert");
        if (!validateProfileEmail()) {
            showAlert("#profileInfoAlert", "Email must end with @etec.com");
            autoHideAlert("#profileInfoAlert", "profile");
            return;
        }

        $.ajax({
            url: BASE_URL + "api/profile/update",
            method: "POST",
            data: $profileForm.serialize(),
            dataType: "json",
            xhrFields: {
                withCredentials: true
            },
            success(res) {
                if (!res.status) {
                    showAlert("#profileInfoAlert", res.message || "Profile update failed");
                    autoHideAlert("#profileInfoAlert", "profile");
                    return;
                }
                showAlert("#profileInfoAlert", res.message || "Profile updated", true);
                autoHideAlert("#profileInfoAlert", "profile");
                setTimeout(() => window.location.reload(), 700);
            },
            error(xhr) {
                const res = xhr.responseJSON;
                showAlert("#profileInfoAlert", (res && res.message) || "Server error");
                autoHideAlert("#profileInfoAlert", "profile");
            }
        });
    });

    $passwordForm.on("submit", function(e) {
        e.preventDefault();
        hideAlert("#passwordAlert");
        if (!validatePasswordFields()) {
            showAlert("#passwordAlert", "Password must be at least 6 characters and match confirmation");
            autoHideAlert("#passwordAlert", "password");
            return;
        }

        $.ajax({
            url: BASE_URL + "api/auth/change-password",
            method: "POST",
            data: $passwordForm.serialize(),
            dataType: "json",
            xhrFields: {
                withCredentials: true
            },
            success(res) {
                if (!res.status) {
                    showAlert("#passwordAlert", res.message || "Update failed");
                    autoHideAlert("#passwordAlert", "password");
                    return;
                }
                showAlert("#passwordAlert", res.message || "Password updated", true);
                autoHideAlert("#passwordAlert", "password");
                $passwordForm.trigger("reset");
            },
            error(xhr) {
                const res = xhr.responseJSON;
                showAlert("#passwordAlert", (res && res.message) || "Server error");
                autoHideAlert("#passwordAlert", "password");
            }
        });
    });
</script>
