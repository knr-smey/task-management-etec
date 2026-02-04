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

<style>
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .dropdown-enter {
        animation: slideDown 0.2s ease-out;
    }

    .project-item {
        transition: all 0.2s ease;
    }

    .project-item:hover {
        transform: translateX(4px);
    }

    .navbar-blur {
        backdrop-filter: blur(12px);
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.95) 0%, rgba(29, 78, 216, 0.95) 100%);
    }

    .avatar-gradient {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
    }

    .project-badge {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    .search-input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .dropdown-shadow {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
            0 10px 10px -5px rgba(0, 0, 0, 0.04),
            0 0 0 1px rgba(0, 0, 0, 0.05);
    }
</style>

<nav class="navbar-blur text-white px-6 h-16 flex items-center justify-between fixed top-0 left-0 right-0 z-50 border-b border-white/10">
    <!-- Left -->
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2">
            <div class="w-9 h-9 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="text-xl font-bold tracking-tight">
                KRU <span class="text-blue-300 font-normal">Solution</span>
            </span>
        </div>
    </div>

    <!-- Right -->
    <div class="flex items-center gap-4">

        <!-- Project Switcher -->
        <div class="relative" id="projectSwitcher">
            <button
                type="button"
                class="flex items-center gap-3 px-4 py-2.5 bg-white/10 hover:bg-white/20
                       text-white rounded-xl shadow-lg transition-all duration-200 w-60 justify-between 
                       border border-white/20 backdrop-blur-sm group"
                onclick="toggleProjectDropdown()">
                <div class="flex items-center gap-3 truncate">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </div>
                    <span class="truncate font-medium">
                        <?= e($currentProject['name'] ?? 'Select Project') ?>
                    </span>
                </div>
                <svg class="w-4 h-4 opacity-70 transition-transform duration-200 group-hover:opacity-100"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24" id="dropdownArrow">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown -->
            <div
                id="projectDropdown"
                class="absolute right-0 mt-3 w-96 bg-white text-slate-800 rounded-2xl dropdown-shadow
                       border border-slate-200/60 hidden z-50 overflow-hidden dropdown-enter">
                <!-- Header -->
                <div class="px-5 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-slate-200/60">
                    <h3 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        My Projects
                    </h3>
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Search projects..."
                            class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl text-sm
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none
                                   transition-all search-input bg-white"
                            onkeyup="filterProjects(this.value)">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- List -->
                <div class="max-h-80 overflow-y-auto" id="projectList">
                    <?php foreach ($navbarProjects as $proj): ?>
                        <a
                            href="<?= e(BASE_URL) ?>project-detail?id=<?= (int)$proj['id'] ?>"
                            class="block px-5 py-3.5 text-sm hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50
                                   transition-all project-item group
                                   <?= ($currentProject['id'] ?? null) == $proj['id']
                                        ? 'bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500'
                                        : 'border-l-4 border-transparent' ?>">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 <?= ($currentProject['id'] ?? null) == $proj['id']
                                                        ? 'bg-gradient-to-br from-blue-500 to-blue-600'
                                                        : 'bg-gradient-to-br from-slate-200 to-slate-300 group-hover:from-blue-400 group-hover:to-blue-500' ?> 
                                     rounded-lg flex items-center justify-center shadow-sm transition-all">
                                    <svg class="w-4 h-4 <?= ($currentProject['id'] ?? null) == $proj['id']
                                                            ? 'text-white'
                                                            : 'text-slate-600 group-hover:text-white' ?> transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="truncate font-medium <?= ($currentProject['id'] ?? null) == $proj['id']
                                                                        ? 'text-blue-700'
                                                                        : 'text-slate-700 group-hover:text-blue-600' ?> transition-colors">
                                        <?= e($proj['name']) ?>
                                    </p>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Updated recently
                                    </p>
                                </div>
                                <?php if (($currentProject['id'] ?? null) == $proj['id']): ?>
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                                    </svg>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>

                    <?php if (empty($navbarProjects)): ?>
                        <div class="px-5 py-12 text-center">
                            <svg class="w-16 h-16 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                            <p class="text-sm text-slate-500 font-medium">No projects found</p>
                            <p class="text-xs text-slate-400 mt-1">Create your first project to get started</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Footer -->
                <?php if (!empty($navbarProjects)): ?>
                    <div class="px-5 py-3 bg-slate-50 border-t border-slate-200/60">
                        <p class="text-xs text-slate-500">
                            <?= count($navbarProjects) ?> project<?= count($navbarProjects) !== 1 ? 's' : '' ?> available
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Create Project (only non-member) -->
        <?php foreach ($user['roles'] ?? [] as $role): ?>
            <?php if ($role !== 'member'): ?>
                <button id="openCreateProjectBtn"
                    class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 
                           rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 
                           font-semibold shadow-lg hover:shadow-xl hover:scale-105 group">
                    <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>New Project</span>
                </button>
                <?php break; ?>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- Avatar -->
        <div class="relative group">
            <div
                class="w-11 h-11 avatar-gradient rounded-xl flex items-center justify-center
                       font-bold text-sm cursor-pointer transition-all duration-200 
                       hover:scale-105 border-2 border-white/20">
                <?= strtoupper(substr($user['name'] ?? 'NS', 0, 2)) ?>
            </div>
            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-blue-900"></div>
        </div>
    </div>
</nav>

<script>
    function toggleProjectDropdown() {
        const dropdown = document.getElementById('projectDropdown');
        const arrow = document.getElementById('dropdownArrow');
        if (!dropdown || !arrow) return;

        const isHidden = dropdown.classList.contains('hidden');
        dropdown.classList.toggle('hidden');

        // Rotate arrow
        if (isHidden) {
            arrow.style.transform = 'rotate(180deg)';
        } else {
            arrow.style.transform = 'rotate(0deg)';
        }
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
        const arrow = document.getElementById('dropdownArrow');
        if (!switcher || !dropdown) return;

        if (!switcher.contains(event.target)) {
            dropdown.classList.add('hidden');
            if (arrow) arrow.style.transform = 'rotate(0deg)';
        }
    });

    // Close dropdown on escape key
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            const dropdown = document.getElementById('projectDropdown');
            const arrow = document.getElementById('dropdownArrow');
            if (dropdown && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        }
    });
</script>