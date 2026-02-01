<?php
declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/layouts/app.php';

$token = csrf_token();
?>

<div class="max-w-4xl mx-auto p-6">
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-800">Create Team</h1>

    <button id="btnOpenCreateTeam"
      class="rounded-lg bg-blue-600 px-5 py-2.5 text-white font-semibold hover:bg-blue-700">
      + New Team
    </button>
  </div>
</div>
<div class="mt-6 bg-white rounded-xl shadow overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-gray-100 text-gray-700">
      <tr>
        <th class="px-4 py-3 text-left">Team</th>
        <th class="px-4 py-3 text-left">Type</th>
        <th class="px-4 py-3 text-left">Schedule</th>
        <th class="px-4 py-3">Action</th>
      </tr>
    </thead>

    <tbody>
      <?php if (empty($teams)): ?>
        <tr>
          <td colspan="4" class="px-4 py-6 text-center text-gray-500">
            No teams found
          </td>
        </tr>
      <?php endif; ?>

      <?php foreach ($teams as $team): ?>
        <tr class="border-t">
          <td class="px-4 py-3 font-semibold">
            <?= e($team['name']) ?>
          </td>

          <td class="px-4 py-3 capitalize">
            <?= e($team['team_type']) ?>
          </td>

          <td class="px-4 py-3">
            <?php if (empty($team['sessions'])): ?>
              <span class="text-gray-400">No schedule</span>
            <?php else: ?>
              <?php foreach ($team['sessions'] as $s): ?>
                <div class="text-xs bg-blue-50 text-blue-700 inline-block px-2 py-1 rounded mb-1">
                  <?= strtoupper(e($s['day'])) ?> ·
                  <?= e(substr($s['start'], 0, 5)) ?> –
                  <?= e(substr($s['end'], 0, 5)) ?>
                </div><br>
              <?php endforeach; ?>
            <?php endif; ?>
          </td>

          <td class="px-4 py-3 text-center">
            <button
              class="px-3 py-1 text-xs bg-gray-200 rounded hover:bg-gray-300">
              Edit
            </button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- ✅ Create Team Modal -->
<div id="createTeamModal" class="fixed inset-0 hidden items-center justify-center z-[9999] bg-black/40 p-4">
  <div id="createTeamModalContent"
    class="bg-white rounded-2xl shadow-2xl w-full max-w-xl transform transition-all duration-300 scale-95 opacity-0">

    <div class="flex items-center justify-between p-5 border-b">
      <h2 class="text-lg font-bold text-gray-800">Create Team</h2>
      <button id="btnCloseCreateTeam"
        class="rounded-lg border border-gray-300 px-3 py-2 text-gray-700 hover:bg-gray-50">✕</button>
    </div>

    <form id="createTeamForm" class="p-5">
      <input type="hidden" name="csrf" value="<?= e($token) ?>">

      <div class="space-y-5">
        <!-- Team name -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Team name</label>
          <input type="text" name="name" id="team_name" required
            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900
                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Enter team name..." />
        </div>

        <!-- Team type -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Team type</label>
          <select name="team_type" id="team_type"
            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900
                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="backend">Backend</option>
            <option value="frontend">Frontend</option>
            <option value="mobile">Mobile</option>
            <option value="other">Other</option>
          </select>
        </div>

        <!-- ✅ Schedule (ONLY ONE) -->
        <div>
          <label class="block text-sm font-semibold text-gray-800 mb-2">Schedule</label>

          <div class="flex flex-wrap items-center gap-3">
            <select name="day" id="day"
              class="min-w-[160px] rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900
                     focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
              <option value="">Select day</option>
              <option value="sat">Saturday</option>
              <option value="sun">Sunday</option>
              <option value="mon">Monday</option>
              <option value="tue">Tuesday</option>
              <option value="wed">Wednesday</option>
              <option value="thu">Thursday</option>
              <option value="fri">Friday</option>
            </select>

            <div class="flex items-center gap-2">
              <span class="text-gray-500">🕒</span>
              <input type="time" name="start_time" id="start_time"
                class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
            </div>

            <div class="flex items-center gap-2">
              <span class="text-gray-500">🕒</span>
              <input type="time" name="end_time" id="end_time"
                class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
            </div>
          </div>

          <p class="mt-2 text-xs text-gray-500">Select one day and one time range.</p>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2">
          <button type="button" id="btnCancelCreateTeam"
            class="rounded-lg border border-gray-300 px-4 py-2.5 text-gray-700 hover:bg-gray-50">
            Cancel
          </button>

          <button type="submit" id="btnSubmitCreateTeam"
            class="rounded-lg bg-blue-600 px-5 py-2.5 text-white font-semibold hover:bg-blue-700">
            Create
          </button>
        </div>
      </div>
    </form>

  </div>
</div>

<script>
$(document).ready(function () {
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

  $("#btnOpenCreateTeam").on("click", openModal);
  $("#btnCloseCreateTeam, #btnCancelCreateTeam").on("click", closeModal);

  $("#createTeamForm").on("submit", function (e) {
    e.preventDefault();

    const name  = $("#team_name").val().trim();
    const day   = $("#day").val();
    const start = $("#start_time").val();
    const end   = $("#end_time").val();

    if (!name) {
      Swal.fire({ icon: "warning", title: "Team name is required" });
      return;
    }

    if (!day || !start || !end) {
      Swal.fire({ icon: "warning", title: "Please select day, start time and end time" });
      return;
    }

    if (start >= end) {
      Swal.fire({ icon: "warning", title: "Start time must be less than end time" });
      return;
    }

    $("#btnSubmitCreateTeam").prop("disabled", true).text("Creating...");

    // show loading popup
    Swal.fire({
      title: "Creating team...",
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => Swal.showLoading(),
    });

    $.ajax({
      url: API_TEAM + "create-team",
      method: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function (res) {
        $("#btnSubmitCreateTeam").prop("disabled", false).text("Create");

        if (res.status) {
          Swal.fire({
            icon: "success",
            title: "Success",
            text: res.message || "Team created!",
            timer: 1500,
            showConfirmButton: false
          });

          closeModal();
        } else {
          Swal.fire({
            icon: "error",
            title: "Failed",
            text: res.message || "Create failed"
          });
        }
      },
      error: function (xhr) {
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