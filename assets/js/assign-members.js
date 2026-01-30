$(function () { 
    // click row toggle checkbox
    $(document).on("click", "#memberList tr.memberItem", function(e) {
        if (e.target.type !== "checkbox") {
            const cb = $(this).find("input[type='checkbox']");
            cb.prop("checked", !cb.prop("checked")).trigger("change");
        }
    });

    // header select all (visible page)
    $("#selectAllHeader").on("change", function() {
        const checked = this.checked;

        $("#memberList tr.memberItem:visible input[type='checkbox']")
            .prop("checked", checked);

        $("#toggleSelectAll").text(checked ? "Unselect all" : "Select all");
    });

    // footer toggle select all (visible page)
    $("#toggleSelectAll").on("click", function() {
        const $visibleCheckboxes = $("#memberList tr.memberItem:visible input[type='checkbox']");

        const allChecked = $visibleCheckboxes.length > 0 &&
            $visibleCheckboxes.filter(":checked").length === $visibleCheckboxes.length;

        $visibleCheckboxes.prop("checked", !allChecked);

        $(this).text(allChecked ? "Select all" : "Unselect all");
        $("#selectAllHeader").prop("checked", !allChecked);
    });

    // clear all
    $("#clearAll").on("click", function() {
        $("#memberList input[type='checkbox']").prop("checked", false);
        $("#selectAllHeader").prop("checked", false);
        $("#toggleSelectAll").text("Select all");
    });

    // AJAX SAVE (your code stays same)
    $("#assignForm").on("submit", function(e) {
        e.preventDefault();

        const checkedCount = $("#memberList input[name='member_ids[]']:checked").length;
        const $form = $(this);

        const proceed = () => {
            const $btnSave = $form.find("button[type='submit']");
            $btnSave.prop("disabled", true).text("Saving...");
            $("#toggleSelectAll, #clearAll, #selectAllHeader").prop("disabled", true);

            $.ajax({
                url: $form.attr("action"),
                method: "POST",
                data: $form.serialize(),
                dataType: "json",
                success: function(res) {
                    const ok = res.status === true || res.success === true;

                    Swal.fire({
                        icon: ok ? "success" : "error",
                        title: ok ? "Success" : "Failed",
                        text: res.message || (ok ? "Saved successfully" : "Save failed"),
                        timer: ok ? 2000 : undefined,
                        showConfirmButton: !ok
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: "error",
                        title: "Server error",
                        text: "Something went wrong. Please try again."
                    });
                },
                complete: function() {
                    $btnSave.prop("disabled", false).text("Save");
                    $("#toggleSelectAll, #clearAll, #selectAllHeader").prop("disabled", false);
                }
            });
        };

        if (checkedCount === 0) {
            Swal.fire({
                icon: "warning",
                title: "Remove all members?",
                text: "This will remove all members from this project.",
                showCancelButton: true,
                confirmButtonText: "Yes, remove",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#16a34a",
                cancelButtonColor: "#6b7280"
            }).then((result) => {
                if (result.isConfirmed) proceed();
            });
        } else {
            proceed();
        }
    });
});