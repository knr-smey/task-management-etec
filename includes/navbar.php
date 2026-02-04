<?php
$user = $_SESSION['user'] ?? null;

$navbarProjects = [];
if (!empty($user['id'])) {
    require_once __DIR__ . '/../app/Models/Project.php';

    $isAdmin = (
        userHasRole($user, 'super_admin') ||
        userHasRole($user, 'admin') ||
        userHasRole($user, 'instructor')
    );

    if ($isAdmin) {
        $navbarProjects = Project::allByCreator((int)$user['id']);
    } else {
        $navbarProjects = Project::fetchProjectBestonUser_id((int)$user['id']);
    }
}

$currentProjectId = (int)($_GET['id'] ?? 0);
$currentProject = null;
if ($currentProjectId > 0 && !empty($navbarProjects)) {
    foreach ($navbarProjects as $p) {
        if ((int)$p['id'] === $currentProjectId) {
            $currentProject = $p;
            break;
        }
    }
}
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

        <!-- Project Switcher -->
        <div class="relative" id="projectSwitcher">
            <button
                type="button"
                class="flex items-center gap-2 px-3 py-2 bg-blue-700 hover:bg-blue-800
                       text-white rounded-lg shadow transition w-52 justify-between border border-blue-600"
                onclick="toggleProjectDropdown()"
            >
                <div class="flex items-center gap-2 truncate">
                    📁
                    <span class="truncate">
                        <?= e($currentProject['name'] ?? 'Select Project') ?>
                    </span>
                </div>
                <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown -->
            <div
                id="projectDropdown"
                  class="absolute right-0 mt-2 w-80 bg-white text-slate-800 rounded-xl shadow-2xl
                      border border-slate-200 hidden z-50 overflow-hidden"
            >
                <!-- Search -->
                <div class="p-3 border-b bg-slate-50">
                    <input
                        type="text"
                        placeholder="Search project..."
                        class="w-full px-3 py-2 border rounded-lg text-sm
                               focus:ring-2 focus:ring-blue-500 outline-none"
                        onkeyup="filterProjects(this.value)"
                    >
                </div>

                <!-- List -->
                <div class="max-h-72 overflow-y-auto" id="projectList">
                    <?php foreach ($navbarProjects as $proj): ?>
                        <a
                            href="<?= e(BASE_URL) ?>project-detail?id=<?= (int)$proj['id'] ?>"
                            class="block px-4 py-2.5 text-sm hover:bg-blue-50 transition
                                   <?= ($currentProject['id'] ?? null) == $proj['id']
                                       ? 'bg-blue-100 font-semibold text-blue-700'
                                       : '' ?>"
                        >
                            <div class="flex items-center gap-2">
                                <span class="inline-block w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                <span class="truncate"><?= e($proj['name']) ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>

                    <?php if (empty($navbarProjects)): ?>
                        <div class="px-4 py-4 text-sm text-slate-500">
                            No projects assigned
                        </div>
                    <?php endif; ?>
                </div>
            </div>
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

<script>
    function toggleProjectDropdown() {
        const dropdown = document.getElementById('projectDropdown');
        if (!dropdown) return;
        dropdown.classList.toggle('hidden');
    }

    function filterProjects(query) {
        const list = document.getElementById('projectList');
        if (!list) return;
        const items = list.querySelectorAll('a');
        const q = (query || '').toLowerCase();

        items.forEach((item) => {
            const text = item.textContent.toLowerCase();
            item.classList.toggle('hidden', !text.includes(q));
        });
    }

    document.addEventListener('click', (event) => {
        const switcher = document.getElementById('projectSwitcher');
        const dropdown = document.getElementById('projectDropdown');
        if (!switcher || !dropdown) return;

        if (!switcher.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>