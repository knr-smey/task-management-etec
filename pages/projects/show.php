<?php

declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php';

require_once __DIR__ . '/../../includes/layouts/app.php';
?>

<div class="p-6 max-w-6xl mx-auto">

    <div class="bg-white border rounded-2xl p-6 mb-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800"><?= e($project['name']) ?></h1>
                <p class="text-gray-600 mt-2"><?= e($project['description'] ?? '-') ?></p>

                <div class="flex flex-wrap gap-2 mt-4 text-sm">
                    <span class="px-3 py-1 rounded-full bg-gray-100">Status: <?= e($project['status'] ?? 'active') ?></span>
                    <span class="px-3 py-1 rounded-full bg-gray-100">Start: <?= e($project['start_date'] ?? '-') ?></span>
                    <span class="px-3 py-1 rounded-full bg-gray-100">End: <?= e($project['end_date'] ?? '-') ?></span>
                </div>
            </div>

            <a href="<?= e(BASE_URL) ?>projects" class="px-4 py-2 rounded-lg border hover:bg-gray-50">
                ← Back
            </a>
        </div>
    </div>

    <div class="bg-white border rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800">Assign members</h2>
            <span class="text-sm text-gray-500">Multiple select</span>
        </div>

        <form id="assignForm" method="post" action="<?= e(BASE_URL) ?>assign-project-members">
            <input type="hidden" name="csrf" value="<?= e($token) ?>">
            <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">

            <div class="mb-4">
                <input id="memberSearch" type="text"
                    class="w-full border rounded-lg px-4 py-2"
                    placeholder="Search member name/email...">
            </div>

            <div id="memberList" class="max-h-80 overflow-auto border rounded-xl p-3 space-y-2">
                <?php if (empty($members)): ?>
                    <div class="text-center text-gray-500 py-6">
                        No member available
                    </div>
                <?php else: ?>
                    <?php foreach ($members as $m): ?>
                        <label class="memberItem flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox"
                                class="w-4 h-4"
                                name="member_ids[]"
                                value="<?= (int)$m['id'] ?>">
                            <div>
                                <div class="memberName font-medium text-gray-800">
                                    <?= e($m['name']) ?>
                                </div>
                                <div class="memberEmail text-sm text-gray-500">
                                    <?= e($m['email']) ?>
                                </div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>

            <div class="flex items-center justify-between mt-4">
                <div class="flex gap-2">
                    <button type="button" id="selectAll" class="px-4 py-2 rounded-lg border hover:bg-gray-50">Select all</button>
                    <button type="button" id="clearAll" class="px-4 py-2 rounded-lg border hover:bg-gray-50">Clear</button>
                </div>

                <button type="submit" class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
                    Save
                </button>
            </div>
        </form>
    </div>

</div>

<?php require_once __DIR__ . '/../../includes/layouts/app-footer.php'; ?>
<script>
$(function() {
    const ITEMS_PER_PAGE = 6;
    let currentPage = 1;

    const $search = $("#memberSearch");
    const $memberList = $("#memberList");
    const $items = $memberList.find(".memberItem");

    // create pagination container
    const $pagination = $('<div class="flex justify-center gap-2 mt-4"></div>');
    $memberList.after($pagination);

    function renderPagination(totalPages) {
        $pagination.empty();
        if (totalPages <= 1) return;

        for (let i = 1; i <= totalPages; i++) {
            const isActive = i === currentPage;

            const $btn = $(`
                <button type="button"
                    class="px-3 py-1 rounded border ${isActive ? "bg-green-600 text-white" : "bg-white hover:bg-gray-100"}">
                    ${i}
                </button>
            `);

            $btn.on("click", function() {
                currentPage = i;
                renderMembers();
            });

            $pagination.append($btn);
        }
    }

    function renderMembers() {
        const q = ($search.val() || "").toLowerCase().trim();

        // filter items
        const $filtered = $items.filter(function() {
            const name = $(this).find(".memberName").text().toLowerCase();
            const email = $(this).find(".memberEmail").text().toLowerCase();
            return name.includes(q) || email.includes(q);
        });

        const totalPages = Math.ceil($filtered.length / ITEMS_PER_PAGE) || 1;
        if (currentPage > totalPages) currentPage = 1;

        // hide all first
        $items.hide();

        // show current page only
        const start = (currentPage - 1) * ITEMS_PER_PAGE;
        const end = start + ITEMS_PER_PAGE;

        $filtered.slice(start, end).css("display", "flex");

        renderPagination(totalPages);
    }

    // search event
    $search.on("input", function() {
        currentPage = 1;
        renderMembers();
    });

    // initial render
    renderMembers();
});
</script>