<div id="modalDelete" class="fixed bg-[#727272b6] inset-0 hidden items-center justify-center z-50 p-4">
    <!-- Modal Container -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform transition-all duration-300 scale-95 opacity-0" id="modalContentDelete">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6">
            <h2 class="text-lg font-bold text-gray-800">Are you sure to delete it ?</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="deleteForm" class="p-6 ">
            <input type="hidden" name="csrf" value="<?= e($token) ?>">
                <div>
                    <input
                        type="hidden"
                        id="delete_id"
                        name="delete_id"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 outline-none"
                    >
                </div>
            <!-- Modal Footer -->
            <div class="flex gap-3 pt-4">
                <button
                    type="button"
                    onclick="closeModal()"
                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition duration-200 cursor-pointer">
                    Cancel
                </button>
                <button
                    id="deleteSubmit"
                    type="submit"
                    class="flex-1 bg-red-600 text-white py-2 rounded-lg font-semibold hover:bg-red-700 transform hover:scale-105 transition duration-200 shadow-lg cursor-pointer">
                    Yes, delete it.
                </button>
            </div>
        </form>
    </div>
</div>