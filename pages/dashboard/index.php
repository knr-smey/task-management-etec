<?php
declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php';

require_login();

$user = $_SESSION['user'] ?? [];
$isMember = userHasRole($user, 'member');

// only these roles can see projects
$canSeeProjects =
    userHasRole($user, 'super_admin') ||
    userHasRole($user, 'admin') ||
    userHasRole($user, 'instructor');

$projects = [];

if ($canSeeProjects) {
    require_once __DIR__ . '/../../app/Models/Project.php';
    // show only his own projects (recommended)
    $projects = Project::allByCreator((int)$user['id']);
}

require_once __DIR__ . '/../../includes/layouts/app.php';
?>


<h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard</h1>

<!-- Member can still see dashboard content -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
    <div class="bg-slate-50 p-4 rounded-xl border">Overview card 1</div>
    <div class="bg-slate-50 p-4 rounded-xl border">Overview card 2</div>
    <div class="bg-slate-50 p-4 rounded-xl border">Overview card 3</div>
</div>

<?php if ($canSeeProjects): ?>
    <!-- Only admin/super/instructor see projects section -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Projects</h2>

        <button id="openCreateProjectBtn"
            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            + Project
        </button>
    </div>

    <?php require __DIR__ . '/../components/projectTable.php'; ?>
    <?php require __DIR__ . '/../components/project-modal.php'; ?>
    <?php require __DIR__ . '/../../pages/components/deleteModal.php'; ?>

<?php else: ?>
    <!-- Member: hide project UI -->
    <div class="bg-blue-50 border border-blue-200 text-blue-700 p-4 rounded-xl">
        You don’t have permission to view projects.
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/layouts/app-footer.php'; ?>
