<!-- Members Card -->
<?php
$teamId = (int)($team['id'] ?? ($_GET['id'] ?? 0));
$canOpenMembersPage = ($teamId > 0) && !empty($canOpenTeamMemberList);
$membersFilterUrl = e(BASE_URL) . 'team/list-team?id=' . $teamId;
?>
<div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 ">
    <div class="flex items-center justify-between gap-3 mb-5 pb-4 border-b-2 border-slate-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-slate-900">Members</h2>
        </div>
        <?php if ($canOpenMembersPage && $teamId > 0): ?>
            <a href="<?= $membersFilterUrl ?>"
                class="text-xs font-semibold text-blue-600 hover:text-blue-700 underline underline-offset-2">
                Open Team List
            </a>
        <?php endif; ?>
    </div>

    <?php if (empty($groupedMembers)): ?>
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                <svg class="w-8 h-8 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                </svg>
            </div>
            <p class="text-slate-500">No members assigned</p>
        </div>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($groupedMembers as $role => $names): ?>
                <?php if ($canOpenMembersPage && $teamId > 0): ?>
                    <a href="<?= $membersFilterUrl ?>" class="block bg-slate-50 border-l-4 border-blue-600 rounded-lg p-4 hover:bg-blue-50 transition-colors">
                        <div class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-1.5">
                            <?= e($role) ?>
                        </div>
                        <div class="text-sm text-blue-600 font-medium">
                            <?= e(implode(', ', $names)) ?>
                        </div>
                    </a>
                <?php else: ?>
                    <div class="bg-slate-50 border-l-4 border-blue-600 rounded-lg p-4">
                        <div class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-1.5">
                            <?= e($role) ?>
                        </div>
                        <div class="text-sm text-blue-600 font-medium">
                            <?= e(implode(', ', $names)) ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
