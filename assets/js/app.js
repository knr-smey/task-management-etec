console.log("app.js loaded ✅");

$(document).ready(function () {
  const BASE_URL = window.BASE_URL || ""; // you already set in footer
  const API_PROJECT = BASE_URL + "api/project.php?url=";

  // ===== modal helpers =====
  function openProjectModal() {
    $("#createProjectModal").removeClass("hidden").addClass("flex");
    // animation
    setTimeout(() => {
      $("#createProjectModalContent").removeClass("scale-95 opacity-0").addClass("scale-100 opacity-100");
    }, 10);
  }

  function closeProjectModal() {
    $("#createProjectModalContent").removeClass("scale-100 opacity-100").addClass("scale-95 opacity-0");
    setTimeout(() => {
      $("#createProjectModal").addClass("hidden").removeClass("flex");
      $("#createProjectForm")[0].reset();
      $("#project_id").val("");
      $("input[name='action']").val("create-project");
      $("#createProjectTitle").text("Create Project");
      $("#btnProjectSubmit").text("Create Project");
    }, 200);
  }

  // ===== open create modal =====
  $(document).on("click", "#openCreateProjectBtn", function () {
    $("#createProjectForm")[0].reset();
    $("#project_id").val("");
    $("input[name='action']").val("create-project");
    $("#createProjectTitle").text("Create Project");
    $("#btnProjectSubmit").text("Create Project");
    openProjectModal();
  });

  // ===== close modal =====
  $(document).on("click", "#closeCreateProjectModal, #cancelCreateProject", function () {
    closeProjectModal();
  });

  // close by clicking backdrop
  $(document).on("click", "#createProjectModal", function (e) {
    if (e.target === this) closeProjectModal();
  });

  // ===== EDIT button (you need .editProjectBtn in table) =====
  // Put data on <tr> like member table:
  // <tr data-id="1" data-name=".." data-description=".." data-status="active" data-start_date="2026-01-01" data-end_date="2026-01-05">
  $(document).on("click", ".editProjectBtn", function () {
    const tr = $(this).closest("tr");

    $("#project_id").val(tr.data("id") || "");
    $("#project_name").val(tr.data("name") || "");
    $("#project_description").val(tr.data("description") || "");
    $("#project_status").val(tr.data("status") || "active");
    $("#project_start_date").val(tr.data("start_date") || "");
    $("#project_end_date").val(tr.data("end_date") || "");

    $("input[name='action']").val("update-project");
    $("#createProjectTitle").text("Edit Project");
    $("#btnProjectSubmit").text("Update Project");

    openProjectModal();
  });

  // ===== SUBMIT (CREATE or UPDATE) =====
  $("#createProjectForm").on("submit", function (e) {
    e.preventDefault();

    const action = $("input[name='action']").val() || "create-project";

    // simple validation
    if (($("#project_name").val() || "").trim() === "") {
      alert("Project Name is required");
      return;
    }

    // choose endpoint by action
    const url = API_PROJECT + action;

    $.ajax({
      url: url,
      method: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function (res) {
        if (res.status) {
          closeProjectModal();
          location.reload(); // easiest (later we can update table without reload)
        } else {
          alert(res.message || "Something went wrong");
        }
      },
      error: function (xhr) {
        console.log(xhr.responseText);
        alert("Server error. Check console / php error log");
      }
    });
  });
});
