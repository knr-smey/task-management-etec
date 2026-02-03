<!-- Projects Card -->
<div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 hover:shadow-xl transition-all duration-300">

    <div class="flex items-center gap-3 mb-5 pb-4 border-b-2 border-slate-100">
        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
            </svg>
        </div>
        <h2 class="text-xl font-bold text-slate-900">Projects</h2>
    </div>

    <?php if (empty($projects)): ?>
        <p class="text-slate-500 text-center py-10">No projects assigned</p>
    <?php else: ?>
        <div class="space-y-4" id="projectsList">

            <?php foreach ($projects as $p): ?>
                <div class="swipe-wrapper" data-project-id="<?= (int)$p['id'] ?>">

                    <!-- DELETE AREA -->
                    <div class="swipe-delete">
                        <button class="btn-delete" title="Remove project">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>

                    <!-- CARD -->
                    <div class="swipe-card bg-slate-50 border-2 border-slate-200 rounded-xl p-5">

                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-slate-900 mb-1">
                                    <?= e($p['name']) ?>
                                </h3>

                                <?php if (!empty($p['description'])): ?>
                                    <p class="text-sm text-slate-600 leading-relaxed">
                                        <?= e($p['description']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap
                                <?= $p['status'] === 'active'
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : 'bg-slate-200 text-slate-600' ?>">
                                <?= e(ucfirst($p['status'])) ?>
                            </span>
                        </div>

                        <?php if (!empty($p['start_date']) || !empty($p['end_date'])): ?>
                            <div class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-200 text-xs text-slate-500 font-mono">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span><?= e($p['start_date'] ?? '-') ?></span>
                                <span class="text-slate-400">→</span>
                                <span><?= e($p['end_date'] ?? '-') ?></span>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>
</div>

<style>
    .swipe-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 14px;
        touch-action: pan-y;
        user-select: none;
    }

    /* CARD */
    .swipe-card {
        background: #f8fafc;
        transition: transform 0.25s ease;
        will-change: transform;
        position: relative;
        z-index: 2;
        cursor: grab;
    }

    .swipe-card:active {
        cursor: grabbing;
    }

    .swipe-card.dragging {
        transition: none;
    }

    /* DELETE AREA (RIGHT SIDE) */
    .swipe-delete {
        position: absolute;
        top: 0;
        right: 0;
        width: 120px;
        height: 99%;
        background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }

    .btn-delete {
        color: white;
        background: none;
        border: none;
        cursor: pointer;
        padding: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-delete:active {
        transform: scale(0.9);
    }

    /* OPEN STATE */
    /* .swipe-wrapper.active .swipe-card {
        transform: translateX(-96px);
    } */

    /* prevent selection */
    .swipe-wrapper * {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* MEMBER: disable delete */
    .is-member .swipe-delete {
        display: none;
    }

    .is-member .swipe-card {
        cursor: default;
    }

    .swipe-card {
        touch-action: pan-y;
        will-change: transform;
    }
</style>

<script>
    const CAN_MANAGE_PROJECT = <?= json_encode(
                                    userHasRole($_SESSION['user'], 'super_admin') ||
                                        userHasRole($_SESSION['user'], 'admin') ||
                                        userHasRole($_SESSION['user'], 'instructor')
                                ) ?>;

    $(function() {

        if (!CAN_MANAGE_PROJECT) {
            $(".swipe-wrapper").addClass("is-member");
            return;
        }

        const API_PROJECT = "<?= e(BASE_URL) ?>api/project.php?url=";
        const OPEN_X = 96;

        let startX = 0;
        let currentX = 0;
        let baseX = 0; // 👈 IMPORTANT
        let activeWrapper = null;
        let activeCard = null;
        let rafId = null;
        let pointerId = null;

        /* ===== POINTER DOWN ===== */
        $(".swipe-card").on("pointerdown", function(e) {

            activeWrapper = $(this).closest(".swipe-wrapper");
            activeCard = $(this);

            startX = e.clientX;
            currentX = startX;
            baseX = activeWrapper.hasClass("active") ? OPEN_X : 0;

            pointerId = e.pointerId;
            this.setPointerCapture(pointerId);

            activeCard.addClass("dragging");
        });

        /* ===== POINTER MOVE ===== */
        $(document).on("pointermove", function(e) {
            if (!activeCard) return;

            e.preventDefault();

            currentX = e.clientX;

            if (!rafId) {
                rafId = requestAnimationFrame(updateSwipe);
            }
        });

        function updateSwipe() {
            if (!activeCard) return;

            let diff = startX - currentX + baseX;
            diff = Math.max(0, Math.min(diff, OPEN_X));

            activeCard.css("transform", `translateX(${-diff}px)`);
            rafId = null;
        }

        /* ===== POINTER UP ===== */
        $(document).on("pointerup pointercancel", function() {
            if (!activeCard) return;

            if (rafId) {
                cancelAnimationFrame(rafId);
                rafId = null;
            }

            let finalX = startX - currentX + baseX;

            try {
                activeCard[0].releasePointerCapture(pointerId);
            } catch (e) {}

            if (finalX > OPEN_X / 2) {
                activeWrapper.addClass("active");
                activeCard.css("transform", `translateX(-${OPEN_X}px)`);
            } else {
                activeWrapper.removeClass("active");
                activeCard.css("transform", "translateX(0)");
            }

            activeCard.removeClass("dragging");

            activeWrapper = null;
            activeCard = null;
            pointerId = null;
        });

        /* ===== CLICK OUTSIDE ===== */
        $(document).on("click", function(e) {
            if (!$(e.target).closest(".swipe-wrapper").length) {
                $(".swipe-wrapper").removeClass("active")
                    .find(".swipe-card")
                    .css("transform", "translateX(0)");
            }
        });


        /* ===== DELETE BUTTON ===== */
        $(".btn-delete").on("click", function(e) {
            e.stopPropagation(); // ⛔ prevent closing swipe
            e.preventDefault();

            const wrapper = $(this).closest(".swipe-wrapper");
            const projectId = wrapper.data("project-id");

            Swal.fire({
                title: "Remove project?",
                text: "This will unassign the project from this team",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Remove",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#ef4444",
                cancelButtonColor: "#64748b"
            }).then(result => {
                if (!result.isConfirmed) return;

                Swal.fire({
                    title: "Removing...",
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.post("<?= e(BASE_URL) ?>api/project.php?url=unassign-project-team", {
                    csrf: "<?= e($token) ?>",
                    project_id: projectId
                }, function(res) {
                    if (res.status) {
                        Swal.close();

                        // smooth remove
                        wrapper.slideUp(250, function() {
                            wrapper.remove();

                            if ($("#projectsList .swipe-wrapper").length === 0) {
                                $("#projectsList").html(
                                    '<p class="text-slate-500 text-center py-10">No projects assigned</p>'
                                );
                            }
                        });

                    } else {
                        Swal.fire("Error", res.message || "Failed", "error");
                    }
                }, "json").fail(function() {
                    Swal.fire("Error", "Network error", "error");
                });
            });
        });

    });
</script>