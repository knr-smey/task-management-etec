<?php

declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php';

require_once __DIR__ . '/../../includes/layouts/app.php';
?>

<div class="max-w-7xl mx-auto">

    <?php require __DIR__ . '/../components/project-info.php'; ?>
    <?php if (!empty($project['team'])): ?>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-5">
            <h3 class="text-lg font-semibold text-blue-800 mb-1">
                Assigned Team
            </h3>

            <div class="text-gray-700">
                <strong><?= e($project['team']['name']) ?></strong>
                <span class="text-sm text-gray-500">
                    (<?= (int)$project['team']['member_count'] ?> members)
                </span>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-5">
            <p class="text-yellow-800">No team assigned to this project.</p>
        </div>
    <?php endif; ?>

    <!-- Assign members -->
    <!-- <div class="bg-white border rounded-lg p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">Assign members</h2>
        </div>

        <form id="assignForm" method="post" action="<?= e(BASE_URL) ?>assign-project-members">
            <input type="hidden" name="csrf" value="<?= e($token) ?>">
            <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">

            <div class="mb-4 flex justify-between">
                <input id="memberSearch" type="text"
                    class="w-[30%] border rounded-md px-4 py-2"
                    placeholder="Search member name/email...">
                <button type="submit" class="px-4 py-1 rounded-md bg-green-600 text-white hover:bg-green-700">
                    Save
                </button>
            </div>

            <div class="rounded-md overflow-hidden bg-white">
                <?php // require __DIR__ . '/../components/assign-members-table.php'; ?>
            </div>

            <div class="flex items-center justify-between mt-4">
                <div class="flex gap-2">
                    <button type="button" id="toggleSelectAll" class="px-4 py-2 rounded-md border hover:bg-gray-50">Select all</button>
                    <button
                        type="button"
                        id="clearAll"
                        class="p-2 rounded-md border text-gray-500 hover:text-red-600 hover:border-red-300 hover:bg-red-50 transition"
                        title="Clear selection">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>

                <div id="assignPagination" class="flex justify-center items-center gap-2 mt-4 flex-wrap"></div>
            </div>
        </form>
    </div> -->

</div>

<?php require_once __DIR__ . '/../../includes/layouts/app-footer.php'; ?>

<script>
    $(function() {

        // pagination component (FIXED: add noResultSelector)
        Paginator({
            itemsSelector: "#memberList tr.memberItem",
            searchInputSelector: "#memberSearch",
            paginationSelector: "#assignPagination",
            itemsPerPage: 20,
            noResultSelector: "#noResultRow" // MUST
        });

    });
</script>