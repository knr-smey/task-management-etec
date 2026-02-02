<?php
$user = $_SESSION['user'] ?? null;
?>

<nav class="bg-blue-900 text-white px-6 h-16 flex items-center justify-between fixed top-0 left-0 right-0 z-50 shadow-md">
    <!-- Left -->
    <div class="flex items-center gap-3">
        <span class="text-lg font-semibold tracking-wide">
            KRU <span class="text-blue-300">Solution</span>
        </span>
    </div>

    <!-- Right -->
    <div class="flex items-center gap-4">

        <!-- Search -->
        <div class="relative">
            <input
                type="text"
                placeholder="Search..."
                class="bg-blue-800 text-white px-4 py-2 pl-10 rounded-lg border border-blue-700 w-64
                       placeholder-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span class="absolute left-3 top-2.5 text-blue-300">🔍</span>
        </div>

        <!-- Create Project (only non-member) -->
        <?php foreach ($user['roles'] ?? [] as $role): ?>
            <?php if ($role !== 'member'): ?>
                <button id="openCreateProjectBtn"
                    class="flex items-center gap-1 px-4 py-2 bg-green-600 rounded-lg
                            hover:bg-green-700 transition font-medium shadow">
                    <span class="text-lg">+</span>
                    <span>Project</span>
                </button>
                <?php break; ?>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- Avatar -->
        <div
            class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center
                   font-bold text-sm shadow cursor-pointer">
            <?= strtoupper(substr($user['name'] ?? 'NS', 0, 2)) ?>
        </div>
    </div>
</nav>