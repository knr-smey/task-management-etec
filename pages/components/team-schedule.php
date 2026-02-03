<!-- Schedule Card -->
<div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 hover:shadow-xl transition-all duration-300 animate-[fadeInUp_0.6s_ease-out_0.1s_backwards]">
    <div class="flex items-center gap-3 mb-5 pb-4 border-b-2 border-slate-100">
        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" />
            </svg>
        </div>
        <h2 class="text-xl font-bold text-slate-900">Schedule</h2>
    </div>

    <?php if (empty($sessions)): ?>
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                <svg class="w-8 h-8 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" />
                </svg>
            </div>
            <p class="text-slate-500">No sessions scheduled</p>
        </div>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($sessions as $s): ?>
                <div class="group relative bg-gradient-to-br from-slate-50 to-slate-100 border-2 border-slate-200 rounded-xl p-4 hover:border-blue-400 hover:shadow-md transition-all duration-300 overflow-hidden">

                    <!-- Accent bar -->
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-600 to-blue-700 scale-y-0 group-hover:scale-y-100 transition-transform duration-300"></div>

                    <!-- Content -->
                    <div class="flex items-center gap-4 ml-3">

                        <!-- Day Badge -->
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex flex-col items-center justify-center shadow-md group-hover:scale-105 transition-transform duration-300">
                                <span class="text-white font-bold text-xs uppercase tracking-wide">
                                    <?= substr(strtoupper(e($s['day_of_week'])), 0, 3) ?>
                                </span>
                                <svg class="w-4 h-4 text-blue-200 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Time Details -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-blue-100 text-blue-700 text-xs font-semibold">
                                    <?= strtoupper(e($s['day_of_week'])) ?>
                                </span>
                            </div>

                            <div class="flex items-center gap-2 text-slate-700">
                                <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-mono font-semibold text-sm">
                                    <?= e($s['start_time']) ?>
                                </span>
                                <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                                <span class="font-mono font-semibold text-sm">
                                    <?= e($s['end_time']) ?>
                                </span>
                            </div>

                            <!-- Duration -->
                            <?php
                                $start = strtotime($s['start_time']);
                                $end = strtotime($s['end_time']);
                                $duration = ($end - $start) / 3600;
                            ?>
                            <div class="flex items-center gap-1.5 mt-2 text-xs text-slate-500">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span><?= number_format($duration, 1) ?> hours</span>
                            </div>
                        </div>

                        <!-- Status Indicator -->
                        <div class="flex-shrink-0">
                            <div class="w-3 h-3 bg-emerald-500 rounded-full ring-4 ring-emerald-100 group-hover:ring-emerald-200 transition-all duration-300"></div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>