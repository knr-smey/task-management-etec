<?php

declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/layouts/app.php';
?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="mb-8">
        <a href="<?= e(BASE_URL) ?>team/detail?id=<?= (int)($team['id'] ?? 0) ?>"
            class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium text-sm mb-4 transition-all hover:gap-3">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Team Detail
        </a>

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-3xl lg:text-4xl font-bold text-slate-900 tracking-tight">
                    Attendance
                </h1>
                <p class="text-sm text-slate-600 mt-1">
                    <?= e($team['name'] ?? 'Team') ?>
                </p>
            </div>
        </div>
    </div>

    <form
        id="attendanceForm"
        data-api-url="<?= e(BASE_URL) ?>api/attendance.php?url=attendance/save"
        data-page-url="<?= e(BASE_URL) ?>team/attendance?id=<?= (int)($team['id'] ?? 0) ?>"
        class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden"
    >
        <input type="hidden" name="csrf" value="<?= e($token ?? '') ?>">
        <input type="hidden" name="team_id" value="<?= (int)($team['id'] ?? 0) ?>">
        <input type="hidden" name="attendance_date" value="<?= e($attendanceDate ?? date('Y-m-d')) ?>">

        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-800">Team Members</h2>
            <button
                id="attendanceSaveBtn"
                type="submit"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-sm transition-all"
            >
                Save Records
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-slate-700">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Member</th>
                        <th class="px-6 py-3 text-center">Present</th>
                        <th class="px-6 py-3 text-center">Permission</th>
                        <th class="px-6 py-3 text-center">Absence</th>
                        <th class="px-6 py-3 text-left">Reason (permission only)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($members ?? [])): ?>
                        <tr>
                            <td class="px-6 py-6 text-center text-slate-500" colspan="5">No members found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach (($members ?? []) as $member): ?>
                            <?php
                                $memberId = (int)($member['id'] ?? 0);
                                $att = $attendanceMap[$memberId] ?? null;
                                $status = (string)($att['status'] ?? '');
                                $reason = (string)($att['reason'] ?? '');
                            ?>
                            <tr data-member-row data-user-id="<?= $memberId ?>">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800">
                                        <?= e($member['name'] ?? 'Member') ?>
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        <?= e($member['email'] ?? '') ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="status[<?= $memberId ?>]" value="present" <?= $status === 'present' ? 'checked' : '' ?>>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="status[<?= $memberId ?>]" value="permission" <?= $status === 'permission' ? 'checked' : '' ?>>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="status[<?= $memberId ?>]" value="absence" <?= $status === 'absence' ? 'checked' : '' ?>>
                                </td>
                                <td class="px-6 py-4">
                                    <input
                                        type="text"
                                        class="permission-reason w-full px-3 py-2 rounded-lg border border-slate-200 text-sm <?= $status === 'permission' ? '' : 'hidden' ?>"
                                        placeholder="Reason for permission"
                                        value="<?= e($reason) ?>"
                                    />
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../includes/layouts/app-footer.php'; ?>

<script src="<?= e(BASE_URL) ?>assets/js/attendance.js"></script>
