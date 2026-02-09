<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/layouts/app.php';
?>

<div class="container mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <?php if ($canSeeProjects): ?>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Dashboard</h1>
            <p class="text-gray-600">Welcome back, <?php echo htmlspecialchars($user['name'] ?? 'User'); ?>! Here's what's happening today.</p>
        <?php else: ?>
            <h1 class="text-4xl font-semibold text-gray-800 mb-2 font-khmer">គ្រូ IT Solution សូមស្វាគមន៍មក</h1>
            <p class="text-gray-600 font-khmer">សូមស្វាគមន៍​ <?php echo htmlspecialchars($user['name'] ?? 'User'); ?>! ឈប់នឹកគេនៅ ?</p>
        <?php endif; ?>
    </div>

    <?php if ($canSeeProjects): ?>
    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <?php
        $badgeText = ($projectGrowthPercent > 0 ? '+' : '') . $projectGrowthPercent . '%';
        $gradientClass = 'gradient-1';
        $iconBgClass = 'bg-purple-100';
        $iconSvg = '<svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>';
        $badgeClass = 'text-green-500 bg-green-50';
        $title = 'Total Projects';
        $value = (string)count($projects);
        $footerText = 'Trending up';
        $footerIconSvg = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>';
        require __DIR__ . '/../components/stat-card.php';

        $badgeText = number_format($activeMembers);
        $gradientClass = 'gradient-2';
        $iconBgClass = 'bg-pink-100';
        $iconSvg = '<svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>';
        $badgeClass = 'text-blue-500 bg-blue-50';
        $title = 'Active Members';
        $value = number_format($activeMembers);
        $footerText = 'Last 24 hours';
        $footerIconSvg = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
        require __DIR__ . '/../components/stat-card.php';

        $badgeText = $completionRate . '%';
        $gradientClass = 'gradient-3';
        $iconBgClass = 'bg-blue-100';
        $iconSvg = '<svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
        $badgeClass = 'text-green-500 bg-green-50';
        $title = 'Completed Tasks';
        $value = number_format($doneTasks);
        $footerText = 'This month';
        $footerIconSvg = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>';
        require __DIR__ . '/../components/stat-card.php';

        $badgeText = $performanceScore . '%';
        $gradientClass = 'gradient-4';
        $iconBgClass = 'bg-teal-100';
        $iconSvg = '<svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>';
        $badgeClass = 'text-orange-500 bg-orange-50';
        $title = 'Performance';
        $value = $performanceScore . '%';
        $footerText = 'Overall rating';
        $footerIconSvg = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>';
        require __DIR__ . '/../components/stat-card.php';
        ?>
    </div>
    <?php endif; ?>

    <?php if ($canSeeProjects): ?>
        <!-- Projects Section -->
        <div>
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Projects</h2>
                        <p class="text-gray-600 text-sm mt-1">Manage and track your projects</p>
                    </div>

                    <button id="openCreateProjectBtn"
                        class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl hover:from-green-600 hover:to-green-700 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center space-x-2 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>New Project</span>
                    </button>
                </div>

                <?php require __DIR__ . '/../components/projectTable.php'; ?>
            </div>
        </div>

        <?php require __DIR__ . '/../components/project-modal.php'; ?>
        <?php require __DIR__ . '/../../pages/components/deleteModal.php'; ?>

    <?php else: ?>
        <!-- Member: welcome card in Khmer -->
        <div>
            <div class="bg-gradient-to-r from-blue-50 to-blue-50 border-2 border-blue-200 rounded-2xl p-8 text-center shadow-lg">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4 overflow-hidden ring-2 ring-emerald-200">
                    <img src="<?= e(BASE_URL) ?>public/Image/iamgememe.png" alt="Member" class="w-full h-full object-cover">
                </div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-2 font-khmer">នៅស្រលាញ់គេម្នាក់ឯងដល់ពេលណាទៀត!</h3>
                <p class="text-emerald-700 text-lg font-khmer">ត្រូវចាំថាគេមិនមករកយើងវិញទេកាត់ចិត្តទៅគេមានសង្សារហើយខំរៀនរកលុយវិញ</p>
                <p class="text-gray-600 text-sm mt-2 font-khmer">បើប្រាប់ហើយមិនស្ដាប់ទៀតអោយគេបោកតទៅហត់ណាស់ ​យល់ហេស?😒</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../includes/layouts/app-footer.php'; ?>