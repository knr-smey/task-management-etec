<?php

declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/layouts/app.php';

$token = csrf_token();
?>

<div class="team-index-page">
<!-- page header -->
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">

    <!-- Title -->
    <div>
      <div class="flex items-center gap-3 mb-2">
        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center shadow-lg">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
        </div>
        <h1 class="text-3xl lg:text-4xl font-bold title-gradient tracking-tight">
          Teams
        </h1>
      </div>
      <p class="text-sm text-gray-600 mt-1 ml-1 flex items-center gap-2">
        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Manage your teams and their schedules
      </p>
    </div>

    <?php if (!empty($canCreateTeam)): ?>
      <!-- Actions -->
      <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">

        <!-- Filter by Team Type -->
        <div class="relative group">
          <select id="filterTeamType"
            class="filter-select appearance-none bg-white border-2 border-gray-200 text-gray-700 px-4 py-2.5 pr-10 rounded-xl
                focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600
                transition-all duration-200 cursor-pointer hover:border-purple-300 font-medium min-w-[160px] shadow-sm">
            <option value="">All Types</option>
            <option value="frontend">Frontend</option>
            <option value="backend">Backend</option>
            <option value="mobile">Mobile</option>
            <option value="other">Other</option>
          </select>

          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 group-hover:text-blue-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
            </svg>
          </div>
        </div>

        <!-- Add Team Button -->
        <button
          id="btnOpenCreateTeam"
          class="btn-new-team cursor-pointer text-white
              px-6 py-2.5 rounded-xl flex items-center justify-center gap-2
              font-semibold whitespace-nowrap shadow-lg">

          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 4v16m8-8H4" />
          </svg>

          <span>New Team</span>
        </button>

      </div>
    <?php endif; ?>
    
</div>

<!-- Teams Cards Container -->
<div class="mt-6">
  <div id="teamCardsWrap" class="transition-all duration-300">
    <?php require_once __DIR__ . '/../components/team-cards.php'; ?>
  </div>
</div>
</div>

<?php require_once __DIR__ . '/../components/team-modal.php'; ?>
<?php require_once __DIR__ . '/../components/deleteModal.php'; ?>

<script>
  $(document).ready(function() {
    const BASE_URL = window.BASE_URL || "<?= e(BASE_URL) ?>";
    const API_TEAM = BASE_URL + "api/team.php?url=";

    function openModal(isEdit = false) {
      if (!isEdit) {
        $("#teamModalTitle").text("Create Team");
        $("#teamModalSubtitle").text("Fill in the information below");
        $("#btnSubmitCreateTeam").text("Create Team");
        $("#team_id").val("");
      }
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
        $("#team_id").val("");
      }, 200);
    }

    function reloadTeamCards() {
      // Add loading state
      $("#teamCardsWrap").css("opacity", "0.5");
      
      $.ajax({
        url: API_TEAM + "team-cards",
        method: "GET",
        dataType: "json",
        success: function(res) {
          if (res.status) {
            // depends on ResponseService structure
            const html = res.data?.html || res.html || "";
            if (html) {
              $("#teamCardsWrap").html(html);
              $("#teamCardsWrap").css("opacity", "1");
            }
          }
        },
        error: function() {
          $("#teamCardsWrap").css("opacity", "1");
        }
      });
    }
    
    $("#btnOpenCreateTeam").on("click", () => openModal(false));
    $("#btnCloseCreateTeam, #btnCancelCreateTeam").on("click", closeModal);

    // Team menu dropdown handlers (must live here for AJAX reloads)
    function closeAllMenus() {
      $(".teamMenu").addClass("hidden");
      $(".teamMenuBtn").attr("aria-expanded", "false");
    }

    const teamCardIgnoreSelector = "a, button, input, select, textarea, label, .teamMenu, .teamMenuBtn";

    $(document).on("click", ".teamCardLink", function(e) {
      if ($(e.target).closest(teamCardIgnoreSelector).length) {
        return;
      }

      const href = $(this).data("href");
      if (href) {
        window.location.href = href;
      }
    });

    $(document).on("keydown", ".teamCardLink", function(e) {
      if (e.key !== "Enter" && e.key !== " ") {
        return;
      }

      if ($(e.target).closest(teamCardIgnoreSelector).length && e.target !== this) {
        return;
      }

      e.preventDefault();

      const href = $(this).data("href");
      if (href) {
        window.location.href = href;
      }
    });

    $(document).on("click", ".teamMenuBtn", function(e) {
      e.stopPropagation();

      const $btn = $(this);
      const $menu = $btn.closest(".relative").find(".teamMenu");
      const isHidden = $menu.hasClass("hidden");

      closeAllMenus();

      if (isHidden) {
        $menu.removeClass("hidden");
        $btn.attr("aria-expanded", "true");
      }
    });

    $(document).on("click", function() {
      closeAllMenus();
    });

    $(document).on("click", ".teamMenu", function(e) {
      e.stopPropagation();
    });

    $(document).on("click", ".teamMenu a, .teamMenu button", function() {
      closeAllMenus();
    });

    $(document).on("click", ".btnInviteTeam", function() {
      const teamId = $(this).data("id");
      const csrf = $("input[name='csrf']").val();

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

    $(document).on("click", ".btnEditTeam", function() {
      const $btn = $(this);
      $("#teamModalTitle").text("Edit Team");
      $("#teamModalSubtitle").text("Update team information below");
      $("#btnSubmitCreateTeam").text("Update Team");

      $("#team_id").val($btn.data("id") || "");
      $("#team_name").val($btn.data("name") || "");
      $("#team_type").val($btn.data("type") || "");
      $("#day").val($btn.data("day") || "");
      $("#start_time").val(($btn.data("start") || "").toString().substring(0, 5));
      $("#end_time").val(($btn.data("end") || "").toString().substring(0, 5));

      openModal(true);
    });

    $("#createTeamForm").on("submit", function(e) {
      e.preventDefault();

      const name = $("#team_name").val().trim();
      const day = $("#day").val();
      const start = $("#start_time").val();
      const end = $("#end_time").val();

      if (!name) {
        Swal.fire({
          icon: "warning",
          title: "Team name is required",
          confirmButtonColor: "#667eea"
        });
        return;
      }

      if (!day || !start || !end) {
        Swal.fire({
          icon: "warning",
          title: "Please select day, start time and end time",
          confirmButtonColor: "#667eea"
        });
        return;
      }

      if (start >= end) {
        Swal.fire({
          icon: "warning",
          title: "Start time must be less than end time",
          confirmButtonColor: "#667eea"
        });
        return;
      }

      const isEdit = $("#team_id").val() !== "";
      $("#btnSubmitCreateTeam")
        .prop("disabled", true)
        .text(isEdit ? "Updating..." : "Creating...");


      $.ajax({
        url: API_TEAM + (isEdit ? "update-team" : "create-team"),
        method: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function(res) {
          $("#btnSubmitCreateTeam")
            .prop("disabled", false)
            .text(isEdit ? "Update Team" : "Create Team");

          if (res.status) {
            Swal.fire({
              icon: "success",
              title: "Success!",
              text: isEdit ? "Team updated successfully" : "Team created successfully",
              confirmButtonColor: "#667eea",
              timer: 2000
            });
            closeModal();
            reloadTeamCards();
          } else {
            Swal.fire({
              icon: "error",
              title: "Failed",
              text: res.message || "Create failed",
              confirmButtonColor: "#667eea"
            });
          }
        },
        error: function(xhr) {
          $("#btnSubmitCreateTeam")
            .prop("disabled", false)
            .text(isEdit ? "Update Team" : "Create Team");

          Swal.fire({
            icon: "error",
            title: "Server error",
            text: "Error (" + xhr.status + ")",
            confirmButtonColor: "#667eea"
          });
        }
      });
    });
  });
</script>


<?php require_once __DIR__ . '/../../includes/layouts/app-footer.php'; ?>
