<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-1">

    <?php if (empty($teams)): ?>
        <div class="col-span-full text-center py-16">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 mb-4">
                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">No teams found</h3>
            <p class="text-sm text-gray-500">Create your first team to get started</p>
        </div>
    <?php else: ?>
        <?php foreach ($teams as $team): ?>
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100 hover:border-blue-200 group">

                <!-- Header -->
                <div class="flex items-start justify-between mb-5">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-xl font-bold text-gray-900 mb-3 truncate group-hover:text-blue-600 transition-colors">
                            <?= e($team['name']) ?>
                        </h3>
                        <span
                            class="inline-flex items-center gap-1.5 text-sm font-medium capitalize bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                            <?= e($team['team_type']) ?>
                        </span>
                    </div>

                    <!-- Action dropdown -->
                    <div class="relative ml-3 flex-shrink-0">
                        <button type="button" class="teamMenuBtn inline-flex items-center justify-center w-9 h-9 rounded-lg hover:bg-gray-100 transition">
                            <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="5" cy="12" r="2" />
                                <circle cx="12" cy="12" r="2" />
                                <circle cx="19" cy="12" r="2" />
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div class="teamMenu hidden absolute right-0 mt-2 w-52 bg-white border border-gray-200 rounded-xl shadow-xl overflow-hidden z-20">

                            <?php if (!empty($canCreateTeam)): ?>
                                <!-- Invite members -->
                                <button type="button"
                                    class="btnInviteTeam w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors"
                                    data-id="<?= (int)$team['id'] ?>">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span class="font-medium">Invite members</span>
                                </button>
                            <?php endif; ?>

                            <!-- Team detail (EVERYONE) -->
                            <a href="<?= e(BASE_URL) ?>team/detail?id=<?= (int)$team['id'] ?>"
                                class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span class="font-medium">Team detail</span>
                            </a>

                            <?php if (!empty($canCreateTeam)): ?>
                                <!-- Edit team -->
                                <button type="button"
                                    class="btnEditTeam w-full text-left flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors"
                                    data-id="<?= (int)$team['id'] ?>">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span class="font-medium">Edit team</span>
                                </button>

                                <div class="border-t border-gray-100"></div>

                                <!-- Delete team -->
                                <button type="button"
                                    class="btnDeleteTeam w-full text-left flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                    data-id="<?= (int)$team['id'] ?>">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m2 0V5a2 2 0 012-2h2a2 2 0 012 2v2" />
                                    </svg>
                                    <span class="font-medium">Delete team</span>
                                </button>
                            <?php endif; ?>

                        </div>

                    </div>

                </div>

                <!-- Schedule Section -->
                <div class="mt-4">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">
                            Schedule
                        </p>
                    </div>

                    <?php if (empty($team['sessions'])): ?>
                        <div class="text-sm text-gray-400 bg-gray-50 rounded-lg px-4 py-3">
                            No schedule
                        </div>
                    <?php else: ?>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($team['sessions'] as $s): ?>
                                <span
                                    class="inline-flex items-center gap-2 text-sm font-medium bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    <span><?= strtoupper(e($s['day'])) ?> · <?= e(substr($s['start'], 0, 5)) ?>–<?= e(substr($s['end'], 0, 5)) ?></span>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<style>
    .teamMenuBtn[aria-expanded="true"] {
        background-color: #f3f4f6;
    }
</style>

<script>
    $(document).ready(function() {

        // Toggle dropdown
        $(document).on("click", ".teamMenuBtn", function(e) {
            e.stopPropagation();

            const $menu = $(this).closest(".relative").find(".teamMenu");
            const isHidden = $menu.hasClass("hidden");

            // Close all menus
            $(".teamMenu").addClass("hidden");

            // Toggle this menu
            if (isHidden) {
                $menu.removeClass("hidden");
            }
        });

        // Click outside to close all
        $(document).on("click", function() {
            $(".teamMenu").addClass("hidden");
        });

        // Prevent menu click from closing
        $(document).on("click", ".teamMenu", function(e) {
            e.stopPropagation();
        });

        // Close menu on action
        $(document).on("click", ".teamMenu a, .teamMenu button", function() {
            $(".teamMenu").addClass("hidden");
        });

        $(document).on("click", ".btnInviteTeam", function() {
            const teamId = $(this).data("id");
            const csrf = $("input[name='csrf']").val(); // hidden input in your page
            const BASE_URL = window.BASE_URL || "<?= e(BASE_URL) ?>";
            const API_TEAM = BASE_URL + "api/team.php?url=";


            $.ajax({
                url: API_TEAM + "create-invite",
                method: "POST",
                dataType: "json",
                data: {
                    team_id: teamId,
                    csrf
                },
                success: function(res) {
                    if (!res.status) {
                        Swal.fire({
                            icon: "error",
                            title: "Failed",
                            text: res.message || "Cannot create invite"
                        });
                        return;
                    }

                    const link = res.data?.link || res.link;

                    Swal.fire({
                        icon: "success",
                        title: "Invite link ready",
                        html: `
                            <div class="text-left">
                                <p class="text-sm text-gray-600 mb-2">Copy and send this to member:</p>
                                <input id="inviteLinkInput" class="w-full border px-3 py-2 rounded" value="${link}" readonly />
                            </div>
                            `,
                        confirmButtonText: "Copy link",
                        showCancelButton: true,
                        cancelButtonText: "Close",
                        preConfirm: () => {
                            const el = document.getElementById("inviteLinkInput");
                            el.select();
                            navigator.clipboard.writeText(el.value);
                        }
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: "error",
                        title: "Server error"
                    });
                }
            });
        });

    });
</script>