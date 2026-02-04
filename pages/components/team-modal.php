<?php $token = csrf_token(); ?>

<!-- ✅ Create Team Modal (NEW UI like Project modal) -->
<div id="createTeamModal" class="fixed bg-black/50 backdrop-blur-sm inset-0 hidden items-center justify-center z-50 p-4">
    <div
        id="createTeamModalContent"
        class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0 max-h-[90vh] overflow-y-auto">

        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
            <div>
                <h2 class="text-2xl font-bold text-gray-800" id="teamModalTitle">Create Team</h2>
                <p class="text-sm text-gray-500 mt-1" id="teamModalSubtitle">Fill in the information below</p>
            </div>

            <button type="button" id="btnCloseCreateTeam"
                class="text-gray-400 hover:text-gray-600 hover:bg-white/50 rounded-lg p-2 transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form id="createTeamForm" class="p-6">
            <input type="hidden" name="csrf" value="<?= e($token) ?>">
            <input type="hidden" name="team_id" id="team_id" value="">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- Team name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Team name <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4c4.418 0 8 1.79 8 4s-3.582 4-8 4-8-1.79-8-4 3.582-4 8-4zm0 8c4.418 0 8 1.79 8 4s-3.582 4-8 4-8-1.79-8-4 3.582-4 8-4z" />
                            </svg>
                        </div>

                        <input type="text" name="name" id="team_name" required
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
                            placeholder="Frontend Team" />
                    </div>
                </div>

                <!-- Team type -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Team type <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L8 21h8l-1.75-4M3 13h18M5 3h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                            </svg>
                        </div>

                        <select name="team_type" id="team_type" required
                            class="appearance-none w-full pl-10 pr-10 py-3 border border-gray-300 rounded-l focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white transition-all duration-200 cursor-pointer">

                            <option value="">Select team type</option>
                            <option value="web">Web</option>
                            <option value="mobile">Mobile</option>
                            <option value="web_mobile">Web & Mobile</option>
                        </select>


                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Schedule day -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Day <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>

                        <select name="day" id="day" required
                            class="appearance-none w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white transition-all duration-200 cursor-pointer">
                            <option value="">Select day</option>
                            <option value="sat">Saturday</option>
                            <option value="sun">Sunday</option>
                            <option value="mon">Monday</option>
                            <option value="tue">Tuesday</option>
                            <option value="wed">Wednesday</option>
                            <option value="thu">Thursday</option>
                            <option value="fri">Friday</option>
                        </select>

                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <p class="mt-2 text-xs text-gray-500">Choose one day only.</p>
                </div>

                <!-- Start time -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Start time <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <input type="time" name="start_time" id="start_time" required
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200">
                    </div>
                </div>

                <!-- End time -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        End time <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <input type="time" name="end_time" id="end_time" required
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200">
                    </div>

                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 mt-6 border-t border-gray-200">
                <button type="button" id="btnCancelCreateTeam"
                    class="cursor-pointer flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                    Cancel
                </button>

                <button type="submit" id="btnSubmitCreateTeam"
                    class="cursor-pointer flex-1 bg-gradient-to-r from-green-600 to-green-700 text-white py-3 px-6 rounded-lg font-semibold hover:from-green-700 hover:to-green-800 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                    Create Team
                </button>
            </div>
        </form>

    </div>
</div>

<style>
    /* Custom scrollbar */
    #createTeamModalContent::-webkit-scrollbar {
        width: 8px;
    }

    #createTeamModalContent::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    #createTeamModalContent::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    #createTeamModalContent::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>