(function () {
  const modal = document.getElementById("deleteModal");
  const content = document.getElementById("deleteModalContent");
  const form = document.getElementById("deleteForm");

  if (!modal || !content || !form) return;

  window.openDeleteModal = function ({ id, name = "-", url, title, message }) {
    if (!id || !url) {
      alert("Missing delete data");
      return;
    }

    document.getElementById("delete_id").value = id;
    form.action = url;

    const titleEl = document.getElementById("deleteTitle");
    const msgEl = document.getElementById("deleteMessage");

    if (titleEl) titleEl.textContent = title || "Confirm Delete";
    if (msgEl) msgEl.textContent = message || "Are you sure you want to delete this item?";

    modal.classList.remove("hidden");
    modal.classList.add("flex");

    setTimeout(() => {
      content.classList.remove("scale-95", "opacity-0");
      content.classList.add("scale-100", "opacity-100");
    }, 10);
  };

  window.closeDeleteModal = function () {
    content.classList.remove("scale-100", "opacity-100");
    content.classList.add("scale-95", "opacity-0");

    setTimeout(() => {
      modal.classList.add("hidden");
      modal.classList.remove("flex");
      document.getElementById("delete_id").value = "";
    }, 200);
  };

  // bind buttons
  $(document).on("click", ".deleteBtn", function () {
    openDeleteModal({
      id: $(this).data("id"),
      name: $(this).data("name"),
      url: $(this).data("url"),
    });
  });

  // submit
  $("#deleteForm").on("submit", function (e) {
    e.preventDefault();

    $.ajax({
      url: this.action,
      method: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success(res) {
        if (res.status) location.reload();
        else alert(res.message || "Delete failed");
      },
      error(xhr) {
        console.error(xhr.responseText);
        alert("Server error");
      },
    });
  });
})();
