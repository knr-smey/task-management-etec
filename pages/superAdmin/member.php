<?php

declare(strict_types=1);

$token = csrf_token();
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/auth.php';

$user = $_SESSION['user'];

$membersPagination = $membersPagination ?? [
    'page' => 1,
    'per_page' => 7,
    'total' => is_array($members ?? null) ? count($members) : 0,
    'total_pages' => 1,
    'start' => 0,
    'end' => 0,
];

$memberStats = $memberStats ?? [
    'active' => is_array($members ?? null) ? count(array_filter($members, static fn(array $m): bool => (int)($m['is_active'] ?? 0) === 1)) : 0,
    'inactive' => is_array($members ?? null) ? count(array_filter($members, static fn(array $m): bool => (int)($m['is_active'] ?? 0) === 0)) : 0,
];

$selectedRole = trim((string)($_GET['role'] ?? ''));
$selectedStatus = (string)($_GET['status'] ?? '');

require_once __DIR__ . '/../../includes/layouts/app.php';
?>

<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
    <div>
        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight">Members</h1>
        <p class="text-sm text-gray-500 mt-1">Manage your team members and their roles</p>
        <?php if (!empty($teamFilterId)): ?>
            <div class="mt-3 inline-flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-sm text-blue-700">
                <span>Filtered by team #<?= (int)$teamFilterId ?></span>
                <a href="<?= e(BASE_URL) ?>member" class="font-semibold underline hover:text-blue-800">Clear</a>
            </div>
        <?php endif; ?>
    </div>

    <form id="memberFilterForm" method="get" action="<?= e(BASE_URL) ?>member" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
        <?php if (!empty($teamFilterId)): ?>
            <input type="hidden" name="team_id" value="<?= (int)$teamFilterId ?>">
        <?php endif; ?>

        <input type="hidden" name="page" id="memberPageInput" value="<?= (int)($membersPagination['page'] ?? 1) ?>">
        <input type="hidden" name="per_page" value="7">

        <div class="relative">
            <select id="filterRole" name="role"
                class="appearance-none bg-white border border-gray-300 text-gray-700 px-4 py-2.5 pr-10 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 cursor-pointer hover:border-gray-400 font-medium min-w-[140px]">
                <option value="" <?= $selectedRole === '' ? 'selected' : '' ?>>All Roles</option>
                <?php if (userHasRole($user, 'super_admin')): ?>
                    <option value="super_admin" <?= $selectedRole === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                <?php endif; ?>
                <option value="admin" <?= $selectedRole === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="instructor" <?= $selectedRole === 'instructor' ? 'selected' : '' ?>>Instructor</option>
                <option value="member" <?= $selectedRole === 'member' ? 'selected' : '' ?>>Member</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        <div class="relative">
            <select id="filterStatus" name="status"
                class="appearance-none bg-white border border-gray-300 text-gray-700 px-4 py-2.5 pr-10 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 cursor-pointer hover:border-gray-400 font-medium min-w-[140px]">
                <option value="" <?= $selectedStatus === '' ? 'selected' : '' ?>>All Status</option>
                <option value="1" <?= $selectedStatus === '1' ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= $selectedStatus === '0' ? 'selected' : '' ?>>Inactive</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        <button type="button"
            class="cursor-pointer bg-gradient-to-r from-green-500 to-green-600 text-white px-5 py-2.5 rounded-lg flex items-center justify-center gap-2 hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium whitespace-nowrap"
            onclick="openModalCreate()"
            id="addMember">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Add Member</span>
        </button>
    </form>
</div>

<div id="memberFilterLoading" class="hidden mb-4 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
    Loading members...
</div>

<?php require __DIR__ . '/../components/memberTable.php'; ?>

<?php require __DIR__ . '/../components/member-modal.php'; ?>

<?php require __DIR__ . '/../../pages/components/deleteModal.php'; ?>

<script>
    (function() {
        const form = document.getElementById('memberFilterForm');
        const loading = document.getElementById('memberFilterLoading');
        const pageInput = document.getElementById('memberPageInput');

        if (!form || !loading || !pageInput) {
            return;
        }

        const showLoading = () => {
            loading.classList.remove('hidden');
        };

        const autoSubmit = () => {
            pageInput.value = '1';
            showLoading();
            form.submit();
        };

        ['filterRole', 'filterStatus'].forEach((id) => {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('change', autoSubmit);
        });

        form.addEventListener('submit', showLoading);

        document.addEventListener('click', function(event) {
            const link = event.target.closest('a.member-page-link');
            if (!link) return;
            showLoading();
        });
    })();
</script>

<?php require_once __DIR__ . '/../../includes/layouts/app-footer.php'; ?>
