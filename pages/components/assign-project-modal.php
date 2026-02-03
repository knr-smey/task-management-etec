<?php $token = csrf_token(); ?>

<!-- Assign Project Modal -->
<div id="assignProjectModal"
    class="fixed bg-black/50 backdrop-blur-sm inset-0 hidden items-center justify-center z-50 p-4">

    <div id="assignProjectModalContent"
        class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95 opacity-0">

        <!-- Header -->
        <div
            class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Assign Project</h2>
                <p class="text-sm text-gray-500 mt-1">Assign an existing project to this team</p>
            </div>

            <button type="button" id="btnCloseAssignProject"
                class="text-gray-400 hover:text-gray-600 hover:bg-white/50 rounded-lg p-2 transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form id="assignProjectForm" class="p-6">
            <input type="hidden" name="csrf" value="<?= e($token) ?>">
            <input type="hidden" name="team_id" value="<?= (int)$team['id'] ?>">

            <!-- Project Select -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Select Project <span class="text-red-500">*</span>
                </label>

                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>

                    <select name="project_id" required
                        class="appearance-none w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white transition-all duration-200 cursor-pointer">
                        <option value="">Choose a project...</option>
                        <?php foreach ($assignableProjects as $p): ?>
                            <option value="<?= (int)$p['id'] ?>">
                                <?= e($p['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 mt-6 border-t border-gray-200">
                <button type="submit"
                    class="cursor-pointer flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-6 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                    Assign Project
                </button>
            </div>
        </form>
    </div>
</div>
<style>
    #assignProjectModal.flex #assignProjectModalContent {
        animation: assignModalSlideIn 0.3s ease-out forwards;
    }

    @keyframes assignModalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-20px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
</style>