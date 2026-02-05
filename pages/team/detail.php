<?php

declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php';

require_once __DIR__ . '/../../includes/layouts/app.php';
?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
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