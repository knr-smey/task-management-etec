<?php

declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php';

require_once __DIR__ . '/../../includes/layouts/app.php';
?>

<div class="max-w-7xl mx-auto">

    <?php require __DIR__ . '/../components/project-info.php'; ?>

    <!-- Task Table -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <?php
        $teamMembers = $project['team']['members'] ?? [];
        require __DIR__ . '/../components/task-table.php';
        ?>
    </div>

</div>

<?php require __DIR__ . '/../components/task-modal.php'; ?>

<?php require_once __DIR__ . '/../../includes/layouts/app-footer.php'; ?>

<script>
    $(function() {

        // pagination component (FIXED: add noResultSelector)
        Paginator({
            itemsSelector: "#memberList tr.memberItem",
            searchInputSelector: "#memberSearch",
            paginationSelector: "#assignPagination",
            itemsPerPage: 20,
            noResultSelector: "#noResultRow" // MUST
        });

        const $taskModal = $("#createTaskModal");

        $("#openCreateTaskBtn").on("click", function() {
            $taskModal.removeClass("hidden").addClass("flex");
        });

        $("#closeCreateTaskBtn, #cancelCreateTaskBtn").on("click", function() {
            $taskModal.addClass("hidden").removeClass("flex");
        });

        $("#createTaskForm").on("submit", function(e) {
            e.preventDefault();

            $.ajax({
                url: "<?= e(BASE_URL) ?>task/create",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(res) {
                    if (res.status) {
                        $taskModal.addClass("hidden").removeClass("flex");
                        if (window.Swal) {
                            Swal.fire("Success", "Task created", "success").then(() => location.reload());
                        } else {
                            alert("Task created");
                            location.reload();
                        }
                    } else {
                        if (window.Swal) {
                            Swal.fire("Error", res.message || "Create failed", "error");
                        } else {
                            alert(res.message || "Create failed");
                        }
                    }
                },
                error: function() {
                    if (window.Swal) {
                        Swal.fire("Error", "Server error", "error");
                    } else {
                        alert("Server error");
                    }
                }
            });
        });

        const openMenu = ($menu) => {
            $menu.removeClass("hidden opacity-0 scale-95").addClass("opacity-100 scale-100");
        };

        const closeMenu = ($menu) => {
            $menu.addClass("hidden opacity-0 scale-95").removeClass("opacity-100 scale-100");
            $menu.removeAttr("style");
        };

        const positionMenu = (buttonEl, $menu) => {
            const rect = buttonEl.getBoundingClientRect();

            $menu.css({ visibility: "hidden", display: "block", position: "fixed" });

            const menuHeight = $menu[0].offsetHeight;
            const menuWidth = $menu[0].offsetWidth;
            const gap = 8;

            let top = rect.top - menuHeight - gap;
            if (top < 8) {
                top = rect.bottom + gap;
            }

            let left = rect.right - menuWidth;
            if (left < 8) {
                left = Math.max(8, rect.left);
            }

            $menu.css({ top: `${top}px`, left: `${left}px`, visibility: "visible" });
        };

        $(document).on("click", ".taskActionToggle", function(e) {
            e.preventDefault();
            const $menu = $(this).siblings(".taskActionMenu");

            $(".taskActionMenu").not($menu).each(function() {
                closeMenu($(this));
            });

            if ($menu.hasClass("hidden")) {
                openMenu($menu);
            } else {
                closeMenu($menu);
            }
            $menu.find(".menu-main").removeClass("hidden");
            $menu.find(".menu-assign, .menu-status").addClass("hidden");

            if (!$menu.hasClass("hidden")) {
                positionMenu(this, $menu);
            }
        });

        $(document).on("click", ".menu-item", function() {
            const target = $(this).data("menu");
            const $menu = $(this).closest(".taskActionMenu");

            $menu.find(".menu-main").addClass("hidden");
            $menu.find(`.menu-${target}`).removeClass("hidden");
        });

        $(document).on("input", ".assign-search", function() {
            const query = $(this).val().toLowerCase();
            const $menu = $(this).closest(".menu-assign");
            const $items = $menu.find(".assign-list .assign-member");
            let visibleCount = 0;

            $items.each(function() {
                const text = $(this).text().toLowerCase();
                const isMatch = text.includes(query);
                $(this).toggleClass("hidden", !isMatch);
                if (isMatch) visibleCount += 1;
            });

            $menu.find(".assign-no-results").toggleClass("hidden", visibleCount > 0);
        });

        $(document).on("click", ".menu-back", function() {
            const $menu = $(this).closest(".taskActionMenu");
            $menu.find(".menu-main").removeClass("hidden");
            $menu.find(".menu-assign, .menu-status").addClass("hidden");
        });

        $(document).on("click", ".menu-log", function() {
            if (window.Swal) {
                Swal.fire("Log", "Coming soon", "info");
            } else {
                alert("Coming soon");
            }
        });

        $(document).on("click", ".assign-member", function() {
            const $btn = $(this);
            $btn.find(".assign-spinner").removeClass("hidden");

            const $menu = $(this).closest(".taskActionMenu");

            const taskId = $(this).data("task-id");
            const assigneeId = $(this).data("user-id");

            $.ajax({
                url: "<?= e(BASE_URL) ?>task/assign",
                type: "POST",
                data: {
                    csrf: "<?= e($token) ?>",
                    task_id: taskId,
                    assignee_id: assigneeId
                },
                dataType: "json",
                success: function(res) {
                    if (res.status) {
                        location.reload();
                    } else {
                        $btn.find(".assign-spinner").addClass("hidden");
                        if (window.Swal) {
                            Swal.fire("Error", res.message || "Assign failed", "error");
                        } else {
                            alert(res.message || "Assign failed");
                        }
                    }
                },
                error: function() {
                    $btn.find(".assign-spinner").addClass("hidden");
                    if (window.Swal) {
                        Swal.fire("Error", "Server error", "error");
                    } else {
                        alert("Server error");
                    }
                }
            });
        });

        $(document).on("click", ".status-item", function() {
            const $btn = $(this);
            $btn.find(".status-spinner").removeClass("hidden");

            const taskId = $(this).data("task-id");
            const statusId = $(this).data("status-id");

            $.ajax({
                url: "<?= e(BASE_URL) ?>task/status",
                type: "POST",
                data: {
                    csrf: "<?= e($token) ?>",
                    task_id: taskId,
                    status_id: statusId
                },
                dataType: "json",
                success: function(res) {
                    if (res.status) {
                        location.reload();
                    } else {
                        $btn.find(".status-spinner").addClass("hidden");
                        if (window.Swal) {
                            Swal.fire("Error", res.message || "Update failed", "error");
                        } else {
                            alert(res.message || "Update failed");
                        }
                    }
                },
                error: function() {
                    $btn.find(".status-spinner").addClass("hidden");
                    if (window.Swal) {
                        Swal.fire("Error", "Server error", "error");
                    } else {
                        alert("Server error");
                    }
                }
            });
        });

        $(document).on("click", function(e) {
            if (!$(e.target).closest(".taskActionMenu, .taskActionToggle").length) {
                $(".taskActionMenu").each(function() {
                    closeMenu($(this));
                });
            }
        });

    });
</script>