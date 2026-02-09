<!-- Header Section -->
<div class="mb-8 animate-[slideDown_0.6s_ease-out]">
    <a href="<?= e(BASE_URL) ?>team"
        class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium text-sm mb-4 transition-all hover:gap-3">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Teams
    </a>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
        <!-- Title Section -->
        <div>
            <h1 class="text-4xl font-bold text-slate-900 mb-2 tracking-tight">
                <?= e($team['name'] ?? 'Team') ?>
            </h1>
            <div class="flex items-center gap-3 text-sm text-slate-600 font-mono">
                <span class="inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                    </svg>
                    <?= e($team['team_type'] ?? '-') ?>
                </span>
                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                <span><?= (int)$memberCount ?> <?= (int)$memberCount === 1 ? 'Member' : 'Members' ?></span>
            </div>
        </div>

        <!-- Action Button -->
         <?php if (!empty($isAdmin)): ?>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <?php if (!empty($canTrackAttendance)): ?>
                    <a href="<?= e(BASE_URL) ?>team/attendance?id=<?= (int)($team['id'] ?? 0) ?>"
                        class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 5h6a2 2 0 012 2v12a2 2 0 01-2 2H9a2 2 0 01-2-2V7a2 2 0 012-2zm0 0V3m6 2V3m-7 8h8m-8 4h6" />
                        </svg>
                        Track Attendance
                    </a>
                <?php endif; ?>

                <?php if (!empty($isAdmin)): ?>
                    <button id="btnOpenAssignProject"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Assign Project
                    </button>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>