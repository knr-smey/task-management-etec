<?php

declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/layouts/app.php';

$token = csrf_token();
?>

<!-- page header -->
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">

  <!-- Title -->
  <div>
    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight">
      Teams
    </h1>
    <p class="text-sm text-gray-500 mt-1">
      Manage your teams and their schedules
    </p>
  </div>

  <!-- Actions -->
  <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">

    <!-- Filter by Team Type -->
    <div class="relative">
      <select id="filterTeamType"
        class="appearance-none bg-white border border-gray-300 text-gray-700 px-4 py-2.5 pr-10 rounded-lg
               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
               transition-all duration-200 cursor-pointer hover:border-gray-400 font-medium min-w-[160px]">
        <option value="">All Types</option>
        <option value="frontend">Frontend</option>
        <option value="backend">Backend</option>
        <option value="mobile">Mobile</option>
        <option value="other">Other</option>
      </select>

      <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M19 9l-7 7-7-7" />
        </svg>
      </div>
    </div>

    <!-- Add Team Button -->
    <button
      id="btnOpenCreateTeam"
      class="cursor-pointer bg-gradient-to-r from-blue-600 to-blue-700 text-white
             px-5 py-2.5 rounded-lg flex items-center justify-center gap-2
             hover:from-blue-700 hover:to-blue-800 transition-all duration-200
             shadow-md hover:shadow-lg transform hover:-translate-y-0.5
             font-medium whitespace-nowrap">

      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 4v16m8-8H4" />
      </svg>

      <span>New Team</span>
    </button>

  </div>
</div>

<div class="mt-6 rounded-md" id="teamCardsWrap">
  <?php require_once __DIR__ . '/../components/team-cards.php'; ?>
</div>

<?php require_once __DIR__ . '/../components/team-modal.php'; ?>

<script>
  $(document).ready(function() {
    const BASE_URL = window.BASE_URL || "<?= e(BASE_URL) ?>";
    const API_TEAM = BASE_URL + "api/team.php?url=";

    function openModal() {
      $("#createTeamModal").removeClass("hidden").addClass("flex");
      setTimeout(() => {
        $("#createTeamModalContent").removeClass("scale-95 opacity-0").addClass("scale-100 opacity-100");
      }, 10);
    }

    function closeModal() {
      $("#createTeamModalContent").removeClass("scale-100 opacity-100").addClass("scale-95 opacity-0");
      setTimeout(() => {
        $("#createTeamModal").addClass("hidden").removeClass("flex");
        $("#createTeamForm")[0].reset();
      }, 200);
    }

    function reloadTeamCards() {
      $.ajax({
        url: API_TEAM + "team-cards",
        method: "GET",
        dataType: "json",
        success: function(res) {
          if (res.status) {
            // depends on ResponseService structure
            const html = res.data?.html || res.html || "";
            if (html) $("#teamCardsWrap").html(html);
          }
        }
      });
    }
    $("#btnOpenCreateTeam").on("click", openModal);
    $("#btnCloseCreateTeam, #btnCancelCreateTeam").on("click", closeModal);

    $("#createTeamForm").on("submit", function(e) {
      e.preventDefault();

      const name = $("#team_name").val().trim();
      const day = $("#day").val();
      const start = $("#start_time").val();
      const end = $("#end_time").val();

      if (!name) {
        Swal.fire({
          icon: "warning",
          title: "Team name is required"
        });
        return;
      }

      if (!day || !start || !end) {
        Swal.fire({
          icon: "warning",
          title: "Please select day, start time and end time"
        });
        return;
      }

      if (start >= end) {
        Swal.fire({
          icon: "warning",
          title: "Start time must be less than end time"
        });
        return;
      }

      $("#btnSubmitCreateTeam").prop("disabled", true).text("Creating...");


      $.ajax({
        url: API_TEAM + "create-team",
        method: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function(res) {
          $("#btnSubmitCreateTeam").prop("disabled", false).text("Create");

          if (res.status) {
            closeModal();
            reloadTeamCards();
          } else {
            Swal.fire({
              icon: "error",
              title: "Failed",
              text: res.message || "Create failed"
            });
          }
        },
        error: function(xhr) {
          $("#btnSubmitCreateTeam").prop("disabled", false).text("Create");

          Swal.fire({
            icon: "error",
            title: "Server error",
            text: "Error (" + xhr.status + ")"
          });
        }
      });
    });
  });
</script>


<?php require_once __DIR__ . '/../../includes/layouts/app-footer.php'; ?>