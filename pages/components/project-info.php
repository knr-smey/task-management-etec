<!-- Project info -->
<div class="bg-white border rounded-lg p-5 mb-5">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800"><?= e($project['name']) ?></h1>
            <p class="text-gray-600 mt-2"><?= e($project['description'] ?? '-') ?></p>

            <div class="flex flex-wrap gap-2 mt-4 text-sm">
                <span class="px-3 py-1 rounded-md bg-gray-100">Status: <?= e($project['status'] ?? 'active') ?></span>
                <span class="px-3 py-1 rounded-md bg-gray-100">Start: <?= e($project['start_date'] ?? '-') ?></span>
                <span class="px-3 py-1 rounded-md bg-gray-100">End: <?= e($project['end_date'] ?? '-') ?></span>
            </div>
        </div>

        <a href="<?= e(BASE_URL) ?>projects" class="px-4 py-2 rounded-md border hover:bg-gray-50">
            ← Back
        </a>
    </div>
</div>