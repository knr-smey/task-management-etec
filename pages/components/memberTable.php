<?php

/**
 * expects:
 * - $members
 * - $user
 * - $membersPagination
 * - $memberStats
 */

$members = $members ?? [];
$membersPagination = $membersPagination ?? [
    'page' => 1,
    'per_page' => 7,
    'total' => count($members),
    'total_pages' => 1,
    'start' => 0,
    'end' => 0,
];
$memberStats = $memberStats ?? [
    'active' => count(array_filter($members, static fn(array $m): bool => (int)($m['is_active'] ?? 0) === 1)),
    'inactive' => count(array_filter($members, static fn(array $m): bool => (int)($m['is_active'] ?? 0) === 0)),
];

$total = (int)($membersPagination['total'] ?? 0);
$currentPage = max(1, (int)($membersPagination['page'] ?? 1));
$perPage = (int)($membersPagination['per_page'] ?? 25);
$totalPages = max(0, (int)($membersPagination['total_pages'] ?? 0));
$start = (int)($membersPagination['start'] ?? 0);
$end = (int)($membersPagination['end'] ?? 0);

$queryParams = $_GET;
unset($queryParams['page']);
unset($queryParams['per_page']);

$buildPageUrl = static function (int $targetPage) use ($queryParams): string {
    $params = $queryParams;
    $params['page'] = max(1, $targetPage);
    $query = http_build_query($params);
    $suffix = $query !== '' ? ('?' . $query) : '';
    return e(BASE_URL . 'member' . $suffix);
};

$visiblePages = [];
if ($totalPages > 0) {
    $visiblePages[] = 1;
    for ($p = max(1, $currentPage - 2); $p <= min($totalPages, $currentPage + 2); $p++) {
        $visiblePages[] = $p;
    }
    $visiblePages[] = $totalPages;
    $visiblePages = array_values(array_unique($visiblePages));
    sort($visiblePages);
}
?>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        height: 8px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<div class="bg-white rounded-lg overflow-hidden border border-gray-200">
    <div class="bg-blue-600 px-6 py-4 border-b border-blue-700">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <div>
                    <h2 class="text-white font-semibold text-lg">Team Members</h2>
                    <p class="text-blue-200 text-sm"><?= $total ?> total members</p>
                </div>
            </div>
            <div class="flex gap-2">
                <span class="bg-white/10 text-white px-3 py-1.5 rounded-md text-sm font-medium border border-white/20">
                    Active: <?= (int)($memberStats['active'] ?? 0) ?>
                </span>
                <span class="bg-pink-500 text-white px-3 py-1.5 rounded-md text-sm font-medium">
                    Inactive: <?= (int)($memberStats['inactive'] ?? 0) ?>
                </span>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full min-w-max">
            <thead>
                <tr class="bg-white border-b border-gray-200">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"># ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>

            <tbody id="membersTableBody" class="divide-y divide-gray-100 bg-white">
                <?php if (empty($members)): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">No members found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($members as $m): ?>
                        <?php
                        $rolesRaw = (string)($m['roles'] ?? 'member');
                        $roleList = array_values(array_filter(array_map('trim', explode(',', $rolesRaw))));
                        if (!$roleList) {
                            $roleList = ['member'];
                        }
                        $primaryRole = strtolower($roleList[0]);
                        $roleLabel = implode(', ', $roleList);
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-150"
                            data-id="<?= (int)$m['id'] ?>"
                            data-name="<?= e($m['name']) ?>"
                            data-email="<?= e($m['email']) ?>"
                            data-course="<?= e($m['course'] ?? '') ?>"
                            data-role="<?= e($primaryRole) ?>"
                            data-active="<?= (int)$m['is_active'] ?>">

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-blue-600">#<?= str_pad((int)$m['id'], 4, '0', STR_PAD_LEFT) ?></span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="relative flex-shrink-0 h-10 w-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-semibold text-sm">
                                        <?= strtoupper(substr(e($m['name']), 0, 1)) ?>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900"><?= e($m['name']) ?></span>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <?= e($m['email']) ?>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if (!empty($m['course'])): ?>
                                    <span class="text-sm text-gray-700"><?= e($m['course']) ?></span>
                                <?php else: ?>
                                    <span class="text-sm text-gray-400 italic">No course</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                    <?= e($roleLabel) ?>
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ((int)$m['is_active'] === 1): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-green-50 text-green-700 border border-green-200">Active</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-red-50 text-red-700 border border-red-200">Inactive</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= e($m['created_at']) ?>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <?php if (!userHasRole($user, 'member')): ?>
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            type="button"
                                            class="editBtn inline-flex items-center justify-center w-9 h-9 rounded-lg text-white bg-green-500 hover:bg-green-600 transition-colors duration-150 cursor-pointer"
                                            data-id="<?= (int)$m['id'] ?>"
                                            title="Edit member">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>

                                        <button
                                            type="button"
                                            class="deleteBtn inline-flex items-center justify-center w-9 h-9 rounded-lg text-white bg-red-500 hover:bg-red-600 transition-colors duration-150 cursor-pointer"
                                            data-id="<?= (int)$m['id'] ?>"
                                            data-name="<?= e($m['name']) ?>"
                                            data-url="<?= e(BASE_URL) ?>delete-member"
                                            title="Delete member">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="bg-white px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="text-sm text-gray-600">
                Showing <span class="font-semibold text-gray-900"><?= $start ?></span> to
                <span class="font-semibold text-gray-900"><?= $end ?></span> of
                <span class="font-semibold text-gray-900"><?= $total ?></span> members
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <?php if ($totalPages > 1): ?>
                    <?php if ($currentPage > 1): ?>
                        <a class="member-page-link px-3 py-1 rounded border bg-white hover:bg-gray-100" href="<?= $buildPageUrl($currentPage - 1) ?>">Prev</a>
                    <?php else: ?>
                        <span class="px-3 py-1 rounded border bg-gray-100 text-gray-400 cursor-not-allowed">Prev</span>
                    <?php endif; ?>

                    <?php $prevRendered = null; ?>
                    <?php foreach ($visiblePages as $p): ?>
                        <?php if ($prevRendered !== null && $p - $prevRendered > 1): ?>
                            <span class="px-2 py-1 text-gray-500">...</span>
                        <?php endif; ?>

                        <?php if ($p === $currentPage): ?>
                            <span class="px-3 py-1 rounded border bg-green-600 text-white"><?= $p ?></span>
                        <?php else: ?>
                            <a class="member-page-link px-3 py-1 rounded border bg-white hover:bg-gray-100" href="<?= $buildPageUrl($p) ?>"><?= $p ?></a>
                        <?php endif; ?>

                        <?php $prevRendered = $p; ?>
                    <?php endforeach; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a class="member-page-link px-3 py-1 rounded border bg-white hover:bg-gray-100" href="<?= $buildPageUrl($currentPage + 1) ?>">Next</a>
                    <?php else: ?>
                        <span class="px-3 py-1 rounded border bg-gray-100 text-gray-400 cursor-not-allowed">Next</span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="bg-gray-50 px-6 py-2 border-t border-gray-200 text-center lg:hidden">
        <p class="text-xs text-gray-500">Scroll horizontally to see more</p>
    </div>
</div>
