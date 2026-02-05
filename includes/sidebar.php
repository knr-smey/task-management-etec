<?php
$user = $_SESSION['user'] ?? null;
require_once __DIR__ . '/helpers.php';
// dump(currentPath());
?>

<style>
    .sidebar-item {
        transition: all 0.2s ease;
    }

    .sidebar-item:hover {
        transform: translateX(4px);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .menu-item {
        animation: fadeIn 0.4s ease-out forwards;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-20px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    #logoutModal:not(.hidden) {
        animation: modalFadeIn 0.2s ease-out;
    }

    #logoutModal:not(.hidden)>div {
        animation: modalSlideIn 0.3s ease-out;
    }

</style>

<aside class="w-64 bg-blue-950 text-white fixed top-16 left-0 h-[calc(100vh-4rem)] shadow-xl overflow-y-auto">
    <div class="p-4 space-y-1">

        <!-- Section title -->
        <p class="text-xs uppercase tracking-wider text-blue-300 px-3 mb-2 font-semibold">
            Main
        </p>

        <!-- Dashboard -->
        <a href="<?= e(BASE_URL) ?>dashboard"
            class="sidebar-item menu-item flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200
           <?= isActive('/dashboard')
                ? 'bg-blue-900 text-white shadow-lg font-semibold border border-blue-700'
                : 'hover:bg-blue-900'
            ?>"
            style="animation-delay: 0.05s">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="font-medium">Overview</span>
        </a>

        <!-- Team -->
        <a href="<?= e(BASE_URL) ?>team"
            class="sidebar-item menu-item flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200
           <?= isActive('/team')
                ? 'bg-blue-900 text-white shadow-lg font-semibold border border-blue-700'
                : 'hover:bg-blue-900'
            ?>"
            style="animation-delay: 0.2s">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span class="font-medium">Team</span>
        </a>


        <!-- Activity -->
        <a href="<?= e(BASE_URL) ?>activity"
            class="sidebar-item menu-item flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200
           <?= isActive('/activity')
                ? 'bg-blue-900 text-white shadow-lg font-semibold border border-blue-700'
                : 'hover:bg-blue-900'
            ?>"
            style="animation-delay: 0.1s">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            <span class="font-medium">Activity</span>
        </a>

        <!-- Task -->
        <!-- <a href="<?= e(BASE_URL) ?>task"
            class="sidebar-item menu-item flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200
           <?= isActive('/task')
                ? 'bg-blue-900 text-white shadow-lg font-semibold border border-blue-700'
                : 'hover:bg-blue-900'
            ?>"
            style="animation-delay: 0.15s">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            <span class="font-medium">Task</span>
        </a> -->


        <!-- Boards -->
        <!-- <a href="<?= e(BASE_URL) ?>boards"
            class="sidebar-item menu-item flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200
           <?= isActive('/boards')
                ? 'bg-blue-900 text-white shadow-lg font-semibold border border-blue-700'
                : 'hover:bg-blue-900'
            ?>"
            style="animation-delay: 0.25s">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
            </svg>
            <span class="font-medium">Boards</span>
        </a> -->

        <!-- Backlogs -->
        <!-- <a href="<?= e(BASE_URL) ?>backlogs"
            class="sidebar-item menu-item flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200
           <?= isActive('/backlogs')
                ? 'bg-blue-900 text-white shadow-lg font-semibold border border-blue-700'
                : 'hover:bg-blue-900'
            ?>"
            style="animation-delay: 0.3s">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
            </svg>
            <span class="font-medium">Backlogs</span>
        </a> -->

        <!-- Divider -->
        <!-- <div class="border-t border-blue-800 my-3"></div> -->

        <!-- Admin Section -->
        <?php if (!empty($user['roles'])): ?>
            <?php foreach ($user['roles'] as $role): ?>
                <?php if ($role !== 'member'): ?>

                    <p class="text-xs uppercase tracking-wider text-blue-300 px-3 mb-2 font-semibold">
                        Management
                    </p>

                    <a href="<?= e(BASE_URL) ?>member"
                        class="sidebar-item menu-item flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200
                       <?= isActive('/member')
                            ? 'bg-blue-900 text-white shadow-lg font-semibold border border-blue-700'
                            : 'hover:bg-blue-900'
                        ?>"
                        style="animation-delay: 0.35s">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="font-medium">Members</span>
                    </a>

                    <?php break; // prevent duplicate Management menu 
                    ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    <div class="p-4 border-t border-blue-800">
        <button id="logoutBtn"
            class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-red-100 hover:bg-blue-900 sidebar-item">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
            </svg>
            <span class="font-medium">Logout</span>
        </button>
    </div>
</aside>

<div id="logoutModal"
    class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-gradient-to-br from-slate-900/60 via-blue-900/40 to-slate-900/60 backdrop-blur-md">
    <div class="w-full max-w-md mx-4 rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden" role="dialog" aria-modal="true" aria-labelledby="logoutTitle">

        <!-- Icon Header with Gradient -->
        <div class="relative bg-gradient-to-br from-red-500 to-red-600 px-6 py-8 text-center">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48cGF0dGVybiBpZD0iZ3JpZCIgd2lkdGg9IjIwIiBoZWlnaHQ9IjIwIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIj48cGF0aCBkPSJNIDIwIDAgTCAwIDAgMCAyMCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLW9wYWNpdHk9IjAuMSIgc3Ryb2tlLXdpZHRoPSIxIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyaWQpIi8+PC9zdmc+')] opacity-20"></div>

            <div class="relative">
                <!-- Animated Icon Container -->
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-lg mb-4 relative">
                    <!-- Logout Icon -->
                    <svg class="w-10 h-10 text-red-500 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                    </svg>
                </div>

                <h2 id="logoutTitle" class="text-2xl font-bold text-white mb-1">Confirm Logout</h2>
                <p class="text-red-100 text-sm">You're about to end your session</p>
            </div>

            <!-- Close Button -->
            <button type="button" id="closeLogoutModal" class="absolute top-4 right-4 text-white/80 hover:text-white transition-colors" aria-label="Close">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="px-6 py-6">
            <div class="flex items-start gap-3 mb-6">
                <div class="flex-shrink-0 w-10 h-10 bg-amber-50 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-slate-800 mb-1">Are you sure?</h3>
                    <p class="text-sm text-slate-600">You'll need to sign in again to access your account.</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button type="button" id="cancelLogout"
                    class="flex-1 rounded-xl border-2 border-slate-200 px-4 py-3 text-slate-700 font-semibold hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 hover:shadow-md">
                    Cancel
                </button>
                <a href="<?= e(BASE_URL) ?>logout"
                    class="flex-1 rounded-xl bg-gradient-to-r from-red-500 to-red-600 px-4 py-3 text-center font-semibold text-white hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
                    Yes, Logout
                </a>
            </div>
        </div>

    </div>
</div>