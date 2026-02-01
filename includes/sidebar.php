<?php
$user = $_SESSION['user'] ?? null;
require_once __DIR__ . '/helpers.php';
// dump(currentPath());
?>

<aside class="w-64 bg-blue-950 text-white h-[91vh] fixed top-[9vh] shadow-xl">
    <div class="p-4 space-y-1">

        <!-- Section title -->
        <p class="text-xs uppercase tracking-wider text-blue-300 px-3 mb-2">
            Main
        </p>

        <!-- Dashboard -->
        <a href="<?= e(BASE_URL) ?>dashboard"
            class="sidebar-item flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200
           <?= isActive('/dashboard')
                ? 'bg-blue-900 text-white shadow-inner font-semibold border border-blue-700'
                : 'hover:bg-blue-900 hover:translate-x-1'
            ?>">
            <span class="font-medium">Overview</span>
        </a>

        <!-- Activity -->
        <a href="<?= e(BASE_URL) ?>activity"
            class="sidebar-item flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200
           <?= isActive('/activity')
                ? 'bg-blue-900 text-white shadow-inner font-semibold border border-blue-700'
                : 'hover:bg-blue-900 hover:translate-x-1'
            ?>">
            <span class="font-medium">Activity</span>
        </a>

        <!-- Task -->
        <a href="<?= e(BASE_URL) ?>task"
            class="sidebar-item flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200
           <?= isActive('/task')
                ? 'bg-blue-900 text-white shadow-inner font-semibold border border-blue-700'
                : 'hover:bg-blue-900 hover:translate-x-1'
            ?>">
            <span class="font-medium">Task</span>
        </a>

        <!-- Task -->
        <a href="<?= e(BASE_URL) ?>team"
            class="sidebar-item flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200
           <?= isActive('/team')
                ? 'bg-blue-900 text-white shadow-inner font-semibold border border-blue-700'
                : 'hover:bg-blue-900 hover:translate-x-1'
            ?>">
            <span class="font-medium">Team</span>
        </a>

        <!-- Boards -->
        <a href="<?= e(BASE_URL) ?>boards"
            class="sidebar-item flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200
           <?= isActive('/boards')
                ? 'bg-blue-900 text-white shadow-inner font-semibold border border-blue-700'
                : 'hover:bg-blue-900 hover:translate-x-1'
            ?>">
            <span class="font-medium">Boards</span>
        </a>

        <!-- Backlogs -->
        <a href="<?= e(BASE_URL) ?>backlogs"
            class="sidebar-item flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200
           <?= isActive('/backlogs')
                ? 'bg-blue-900 text-white shadow-inner font-semibold border border-blue-700'
                : 'hover:bg-blue-900 hover:translate-x-1'
            ?>">
            <span class="font-medium">Backlogs</span>
        </a>

        <!-- Divider -->
        <div class="border-t border-blue-800 my-3"></div>

        <!-- Admin Section -->
        <?php if (!empty($user['roles'])): ?>
            <?php foreach ($user['roles'] as $role): ?>
                <?php if ($role !== 'member'): ?>

                    <p class="text-xs uppercase tracking-wider text-blue-300 px-3 mb-2">
                        Management
                    </p>

                    <a href="<?= e(BASE_URL) ?>member"
                        class="sidebar-item flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200
                       <?= isActive('/member')
                            ? 'bg-blue-900 text-white shadow-inner font-semibold border border-blue-700'
                            : 'hover:bg-blue-900 hover:translate-x-1'
                        ?>">
                        <span class="font-medium">Members</span>
                    </a>

                    <?php break; // prevent duplicate Management menu 
                    ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</aside>