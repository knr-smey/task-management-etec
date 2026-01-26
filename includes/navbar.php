<?php
$user = $_SESSION['user'] ?? null;
?>

<nav class="bg-blue-900 text-white px-4 py-3 flex items-center justify-between sticky top-0 z-50">
    <div class="flex items-center gap-4">
        <span class="text-sm font-bold">KRU Solution</span>
    </div>

    <div class="flex items-center gap-4">
        <input
            type="text"
            placeholder="Search ..."
            class="bg-blue-800 text-white px-4 py-2 rounded border border-blue-700 w-64 placeholder-blue-300 focus:outline-none">

        <?php foreach ($user['roles'] ?? [] as $role): ?>
            <?php if ($role !== 'member'): ?>
                <a href="<?= e(BASE_URL) ?>create-project"
                    class="p-2 bg-green-600 rounded-full hover:bg-green-700"
                    title="Create project">
                    +
                </a>
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="w-10 h-10 bg-green-400 rounded-full flex items-center justify-center font-bold">
            NS
        </div>
    </div>
</nav>