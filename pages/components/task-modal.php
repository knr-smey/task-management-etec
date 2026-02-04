<?php
// expects $token and $project
?>

<!-- Create Task Modal -->
<div id="createTaskModal" class="fixed bg-black/50 backdrop-blur-sm inset-0 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl">
        <div class="flex items-center justify-between p-5 border-b">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Create Task</h3>
                <p class="text-sm text-gray-500">Add a task for this project</p>
            </div>
            <button type="button" id="closeCreateTaskBtn" class="text-gray-400 hover:text-gray-600">
                ✕
            </button>
        </div>

        <form id="createTaskForm" class="p-5">
            <input type="hidden" name="csrf" value="<?= e($token) ?>">
            <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
            <input type="hidden" name="status_id" value="1">

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Title *</label>
                    <input type="text" name="title" required
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assign to</label>
                        <select name="assignee_id"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">Me (default)</option>
                            <?php foreach (($members ?? []) as $m): ?>
                                <option value="<?= (int)$m['id'] ?>">
                                    <?= e($m['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Priority</label>
                        <select name="priority"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="low">low</option>
                            <option value="medium" selected>medium</option>
                            <option value="high">high</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Due Date</label>
                        <input type="date" name="due_date"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-5 mt-5 border-t">
                <button type="button" id="cancelCreateTaskBtn"
                    class="flex-1 px-4 py-2 border rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
                    Create Task
                </button>
            </div>
        </form>
    </div>
</div>
