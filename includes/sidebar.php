<?php $user = $_SESSION['user'] ?? null; ?>

<aside class="w-60 bg-gray-800 text-white h-[91vh] fixed top-[9vh]">
    <div class="p-4 space-y-1">

        <a href="<?= e(BASE_URL) ?>dashboard" class="sidebar-item flex items-center gap-3 px-3 py-2 rounded">
            <span>Overview</span>
        </a>

        <a href="#" class="sidebar-item flex items-center gap-3 px-3 py-2 rounded">
            <span>Activity</span>
        </a>

        <a href="#" class="sidebar-item active flex items-center gap-3 px-3 py-2 rounded">
            <span>Boards</span>
        </a>

        <a href="#" class="sidebar-item flex items-center gap-3 px-3 py-2 rounded">
            <span>Backlogs</span>
        </a>

        <?php foreach ($user['roles'] ?? [] as $role): ?>
            <?php if ($role !== 'member'): ?>
                <a href="<?= e(BASE_URL) ?>member" class="sidebar-item flex items-center gap-3 px-3 py-2 rounded">
                    <span>Members</span>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>

    </div>
</aside>