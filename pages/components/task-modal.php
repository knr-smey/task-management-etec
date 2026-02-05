<?php
// expects $token and $project
?>

<!-- Create Task Modal -->
<div id="createTaskModal" class="fixed bg-black/50 backdrop-blur-sm inset-0 hidden items-center justify-center z-50 p-4">
    <div
        id="createTaskModalContent"
        class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0 max-h-[90vh] overflow-y-auto">

        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Create Task</h3>
                <p class="text-sm text-gray-500 mt-1">Add a task for this project</p>
            </div>
            <button type="button" id="closeCreateTaskBtn"
                class="text-gray-400 hover:text-gray-600 hover:bg-white/50 rounded-lg p-2 transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="createTaskForm" class="p-6">
            <input type="hidden" name="csrf" value="<?= e($token) ?>">
            <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
            <input type="hidden" name="status_id" value="1">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required placeholder="Task title"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" placeholder="Brief task details"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Assign to</label>
                    <select name="assignee_id"
                        class="appearance-none w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white transition-all duration-200 cursor-pointer">
                        <option value="">Me (default)</option>
                        <?php foreach (($teamMembers ?? []) as $m): ?>
                            <option value="<?= (int)$m['id'] ?>">
                                <?= e($m['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Priority</label>
                    <select name="priority"
                        class="appearance-none w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white transition-all duration-200 cursor-pointer">
                        <option value="low">low</option>
                        <option value="medium" selected>medium</option>
                        <option value="high">high</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Due Date</label>
                    <input type="date" name="due_date" placeholder="Select date"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-6 mt-6 border-t border-gray-200">
                <button type="button" id="cancelCreateTaskBtn"
                    class="cursor-pointer flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                    Cancel
                </button>
                <button type="submit"
                    class="cursor-pointer flex-1 bg-gradient-to-r from-green-600 to-green-700 text-white py-3 px-6 rounded-lg font-semibold hover:from-green-700 hover:to-green-800 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                    Create Task
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    #createTaskModalContent::-webkit-scrollbar {
        width: 8px;
    }

    #createTaskModalContent::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    #createTaskModalContent::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    #createTaskModalContent::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
