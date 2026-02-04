<!-- Project info -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold text-xl">
                    <?= strtoupper(substr($project['name'], 0, 1)) ?>
                </div>
                <h1 class="text-2xl font-bold text-gray-900"><?= e($project['name']) ?></h1>
            </div>

            <?php
            $user = $_SESSION['user'] ?? [];
            $isAdmin = (
                userHasRole($user, 'super_admin') ||
                userHasRole($user, 'admin') ||
                userHasRole($user, 'instructor')
            );
            $backUrl = $isAdmin ? 'projects' : 'dashboard';
            ?>
            <a href="<?= e(BASE_URL . $backUrl) ?>"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="px-6 py-4">
        <!-- Description -->
        <?php if (!empty($project['description'])): ?>
            <div class="mb-4">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-600 text-sm leading-relaxed"><?= e($project['description']) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Status, Dates, and Team -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Status -->
            <div class="flex items-center gap-3 p-3 rounded-lg bg-green-50 border border-green-100">
                <div class="w-9 h-9 rounded-lg bg-green-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-green-700 uppercase tracking-wide">Status</p>
                    <p class="text-sm font-bold text-gray-900 capitalize truncate"><?= e($project['status'] ?? 'active') ?></p>
                </div>
            </div>

            <!-- Start Date -->
            <div class="flex items-center gap-3 p-3 rounded-lg bg-blue-50 border border-blue-100">
                <div class="w-9 h-9 rounded-lg bg-blue-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-blue-700 uppercase tracking-wide">Start Date</p>
                    <p class="text-sm font-bold text-gray-900 truncate"><?= e($project['start_date'] ?? '-') ?></p>
                </div>
            </div>

            <!-- End Date -->
            <div class="flex items-center gap-3 p-3 rounded-lg bg-purple-50 border border-purple-100">
                <div class="w-9 h-9 rounded-lg bg-purple-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-purple-700 uppercase tracking-wide">End Date</p>
                    <p class="text-sm font-bold text-gray-900 truncate"><?= e($project['end_date'] ?? '-') ?></p>
                </div>
            </div>

            <!-- Team -->
            <?php if (!empty($project['team'])): ?>
                <div class="flex items-center gap-3 p-3 rounded-lg bg-blue-50 border border-blue-100">
                    <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-medium text-blue-700 uppercase tracking-wide">Assigned Team</p>
                        <div class="flex items-center gap-1.5">
                            <p class="text-sm font-bold text-gray-900 truncate"><?= e($project['team']['name']) ?></p>
                            <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full bg-blue-600 text-white text-xs font-bold flex-shrink-0 mt-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <?= (int)$project['team']['member_count'] ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="flex items-center gap-3 p-3 rounded-lg bg-yellow-50 border border-yellow-200">
                    <div class="w-9 h-9 rounded-lg bg-yellow-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-yellow-700 uppercase tracking-wide">Team</p>
                        <p class="text-sm font-bold text-gray-900">Not Assigned</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>