<?php

declare(strict_types=1); ?>
<!-- ✅ Global Delete Modal (Reusable) -->
<div id="deleteModal" class="fixed inset-0 hidden items-center justify-center z-[9999] bg-[#727272b6] p-4">
    <div id="deleteModalContent"
        class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95 opacity-0">

        <div class="flex items-center justify-between p-5 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800" id="deleteTitle">Confirm Delete</h2>

            <button type="button" onclick="closeDeleteModal()"
                class="text-gray-400 hover:text-gray-600 transition cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="deleteForm" class="p-5 space-y-4" method="post">
            <!-- csrf token from parent page -->
            <input type="hidden" name="csrf" value="<?= e($token ?? '') ?>">
            <input type="hidden" name="delete_id" id="delete_id">

            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-sm text-red-700" id="deleteMessage">
                    Are you sure you want to delete this item?
                </p>

                <!-- <p class="text-sm text-gray-700 mt-2">
                    Item: <span id="delete_name" class="font-semibold">-</span>
                </p> -->
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50">
                    Cancel
                </button>

                <button type="submit"
                    class="flex-1 bg-red-600 text-white py-2 rounded-lg font-semibold hover:bg-red-700 shadow-lg">
                    Yes, Delete
                </button>
            </div>
        </form>
    </div>
</div>