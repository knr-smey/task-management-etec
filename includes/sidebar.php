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
        <!-- <a href="<?= e(BASE_URL) ?>activity"
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
        </a> -->

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
        <div class="border-t border-blue-800 my-3"></div>

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
     class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
    <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-slate-200" role="dialog" aria-modal="true" aria-labelledby="logoutTitle">
        <div class="flex items-center justify-between p-5 border-b border-slate-200">
            <h2 id="logoutTitle" class="text-lg font-semibold text-slate-900">Confirm Logout</h2>
            <button type="button" id="closeLogoutModal" class="text-slate-400 hover:text-slate-600" aria-label="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-5 text-sm text-slate-600">
            Are you sure you want to logout?
        </div>
        <div class="flex gap-3 px-5 pb-5">
            <button type="button" id="cancelLogout" class="flex-1 rounded-lg border border-slate-200 px-4 py-2 text-slate-700 font-semibold hover:bg-slate-50">Cancel</button>
            <a href="<?= e(BASE_URL) ?>logout" class="flex-1 rounded-lg bg-red-500 px-4 py-2 text-center font-semibold text-white hover:bg-red-600">Logout</a>
        </div>
    </div>
</div>