(function () {
  // same as: const BASE_URL = "<?= e(BASE_URL) ?>";
  const BASE_URL = window.BASE_URL || "";

  const ROLE_MEMBER = parseInt(window.ROLE_MEMBER || "4", 10);

  const modal = document.getElementById("modal");
  const modalContent = document.getElementById("modalContent");

  const $form = $("#userForm");
  const $formDelete = $("#deleteForm"); // keep same (even if not used)

  if (!modal || !modalContent || !$form.length) return;

  function showModal() {
    modal.classList.remove("hidden");
    modal.classList.add("flex");
    setTimeout(() => {
      modalContent.classList.remove("scale-95", "opacity-0");
      modalContent.classList.add("scale-100", "opacity-100");
    }, 10);
  }

  // keep same function name because your HTML uses onclick="closeModal()"
  window.closeModal = function () {
    modalContent.classList.remove("scale-100", "opacity-100");
    modalContent.classList.add("scale-95", "opacity-0");

    setTimeout(() => {
      modal.classList.add("hidden");
      modal.classList.remove("flex");

      // reset form
      document.getElementById("userForm").reset();
      $("#hide_id").val("");
      $("#pwHint").addClass("hidden");
      $("#password").prop("required", true);
      $("#pwStar").removeClass("hidden");
      $("#courseWrap").removeClass("hidden");
      $("#type").prop("required", true);
      $("#courseStar").removeClass("hidden");
    }, 250);
  };

  // course required ONLY for member (same)
  function applyRoleRule() {
    const role = parseInt($("#role").val() || "0", 10);

    if (role === ROLE_MEMBER) {
      $("#courseWrap").removeClass("hidden");
      $("#type").prop("required", true);
      $("#courseStar").removeClass("hidden");
    } else {
      $("#courseWrap").addClass("hidden");
      $("#type").prop("required", false);
      $("#type").val(""); // will be NULL in backend
    }
  }

  $("#role").on("change", applyRoleRule);

  // Create (same name: openModalCreate())
  window.openModalCreate = function () {
    $("#title").text("Add Member");
    $("#btnSubmit").text("Create Member").removeClass("bg-green-600").addClass("bg-blue-600");

    $("#hide_id").val("");

    $("#password").val("").prop("required", true);
    $("#pwHint").addClass("hidden");
    $("#pwStar").removeClass("hidden");

    // default role Member
    $("#role").val(String(ROLE_MEMBER));
    applyRoleRule();

    $("#status").val("1"); // active
    showModal();
  };

  // Edit (same)
  $(document).on("click", ".editBtn", function () {
    const tr = $(this).closest("tr");

    const id = tr.data("id");
    const name = tr.data("name");
    const email = tr.data("email");
    const course = tr.data("course");
    const roleText = (tr.data("role") || "").toString().trim();
    const isActive = tr.data("active");

    $("#title").text("Edit Member");
    $("#btnSubmit").text("Update").removeClass("bg-blue-600").addClass("bg-green-600");

    $("#hide_id").val(id);
    $("#name").val(name);
    $("#email").val(email);

    $("#password").val("").prop("required", false);
    $("#pwHint").removeClass("hidden");
    $("#pwStar").addClass("hidden");

    const roleId =
      roleText === "super_admin" ? 1 :
      roleText === "admin" ? 2 :
      roleText === "instructor" ? 3 : 4;

    $("#role").val(String(roleId));
    applyRoleRule();

    if (roleId === ROLE_MEMBER) {
      $("#type").val(course);
    } else {
      $("#type").val("");
    }

    $("#status").val(String(isActive));
    showModal();
  });

  // submit (same)
  $form.on("submit", function (e) {
    e.preventDefault();

    // frontend validation: member must select course
    const role = parseInt($("#role").val() || "0", 10);
    if (role === ROLE_MEMBER && ($("#type").val() || "") === "") {
      alert("Course is required for Member");
      return;
    }

    $.ajax({
      url: BASE_URL + "create-member",
      method: "POST",
      data: $form.serialize(),
      dataType: "json",
      xhrFields: { withCredentials: true },
      success(res) {
        if (res.status) {
          location.reload();
        } else {
          alert(res.message || "Something went wrong");
        }
      },
      error(xhr) {
        console.error(xhr.responseText);
        alert("Server error: check console / php error log");
      }
    });
  });
})();
