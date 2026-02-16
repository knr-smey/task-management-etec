<?php

declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php';

require_once __DIR__ . '/../../includes/layouts/app.php';
?>

<style>
    :root {
        --team-accent: #274193;
        --team-accent-dark: #1f3478;
        --team-accent-soft: #e8eefb;
        --team-accent-soft-2: #d9e4fb;
    }

    .team-detail-page .text-blue-600,
    .team-detail-page .text-blue-700 {
        color: var(--team-accent) !important;
    }

    .team-detail-page .hover\:text-blue-700:hover {
        color: var(--team-accent-dark) !important;
    }

    .team-detail-page .bg-blue-50 {
        background-color: var(--team-accent-soft) !important;
    }

    .team-detail-page .bg-blue-100 {
        background-color: var(--team-accent-soft-2) !important;
    }

    .team-detail-page .bg-blue-600,
    .team-detail-page .bg-blue-700 {
        background-color: var(--team-accent) !important;
    }

    .team-detail-page .hover\:bg-blue-50:hover {
        background-color: var(--team-accent-soft) !important;
    }

    .team-detail-page .hover\:bg-blue-700:hover {
        background-color: var(--team-accent-dark) !important;
    }

    .team-detail-page .border-blue-600 {
        border-color: var(--team-accent) !important;
    }

    .team-detail-page .hover\:border-blue-400:hover {
        border-color: var(--team-accent) !important;
    }

    .team-detail-page .text-blue-200 {
        color: #b7c6eb !important;
    }

    .team-detail-page .shadow-blue-500\/30 {
        --tw-shadow-color: rgb(39 65 147 / 0.30) !important;
        --tw-shadow: var(--tw-shadow-colored) !important;
    }

    .team-detail-page .hover\:shadow-blue-500\/40:hover {
        --tw-shadow-color: rgb(39 65 147 / 0.40) !important;
        --tw-shadow: var(--tw-shadow-colored) !important;
    }

    .team-detail-page .bg-gradient-to-br.from-blue-500.to-blue-600,
    .team-detail-page .bg-gradient-to-br.from-blue-600.to-blue-700,
    .team-detail-page .bg-gradient-to-b.from-blue-600.to-blue-700,
    .team-detail-page .bg-gradient-to-r.from-blue-600.to-blue-700 {
        background-image: linear-gradient(135deg, var(--team-accent) 0%, var(--team-accent-dark) 100%) !important;
    }

    .team-detail-page .bg-gradient-to-r.from-blue-50.to-indigo-50 {
        background-image: linear-gradient(90deg, #edf2fc 0%, #e7ecf9 100%) !important;
    }

    #assignProjectModal .bg-gradient-to-r.from-blue-50.to-indigo-50 {
        background-image: linear-gradient(90deg, #edf2fc 0%, #e7ecf9 100%) !important;
    }

    #assignProjectModal button[type="submit"] {
        background-image: linear-gradient(90deg, var(--team-accent) 0%, var(--team-accent-dark) 100%) !important;
    }

    #assignProjectModal button[type="submit"]:hover {
        background-image: linear-gradient(90deg, var(--team-accent-dark) 0%, #182a61 100%) !important;
    }

    #assignProjectModal .focus\:ring-blue-500:focus {
        --tw-ring-color: rgb(39 65 147 / 0.35) !important;
    }

    #assignProjectModal .focus\:border-blue-500:focus {
        border-color: var(--team-accent) !important;
    }
</style>

<div class="team-detail-page min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div>

        <?php require_once __DIR__ . '/../components/team-header.php'; ?>

        <!-- Schedule & Members Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">


            <?php require_once __DIR__ . '/../components/team-schedule.php'; ?>

            <?php
                $groupedMembers = $groupedMembers ?? [];
                require_once __DIR__ . '/../components/team-members.php'; 
            ?>

        </div>

        <?php require_once __DIR__ . '/../components/team-projects.php'; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../components/assign-project-modal.php'; ?>

<?php require_once __DIR__ . '/../../includes/layouts/app-footer.php'; ?>

<!-- SCRIPT MUST BE AFTER FOOTER -->
<script>
    $(document).ready(function() {

        const API_PROJECT = "<?= e(BASE_URL) ?>api/project.php?url=";

        $("#btnOpenAssignProject").on("click", function() {
            $("#assignProjectModal").removeClass("hidden").addClass("flex");
        });

        $("#btnCloseAssignProject").on("click", function() {
            $("#assignProjectModal").addClass("hidden").removeClass("flex");
        });

        $("#assignProjectForm").on("submit", function(e) {
            e.preventDefault();

            $.ajax({
                url: API_PROJECT + "assign-project-team",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(res) {
                    if (res.status) {
                        $("#assignProjectModal").addClass("hidden").removeClass("flex");
                        Swal.fire("Success", "Project assigned", "success")
                            .then(() => location.reload());
                    } else {
                        Swal.fire("Error", res.message, "error");
                    }
                },
                error: function() {
                    Swal.fire("Error", "Server error", "error");
                }
            });
        });

    });
</script>
