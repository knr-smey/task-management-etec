<?php

declare(strict_types=1);

$token = csrf_token();
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/auth.php';

$user = $_SESSION['user'];
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>

<main class="ms-60 mt-[9vh] p-8 min-h-[91vh] bg-slate-50">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Members</h1>
        <button class="bg-green-500 text-white px-4 py-2 rounded flex items-center cursor-pointer gap-2 hover:bg-green-600 transition"
            onclick="openModalCreate()" id="addMember">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Member
        </button>
    </div>

    <div class="bg-white rounded-lg shadow">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700 uppercase">ID</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700 uppercase">Name</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700 uppercase">Email</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700 uppercase">Course</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700 uppercase">Role</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700 uppercase">Status</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700 uppercase">Created At</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700 uppercase">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                <?php foreach ($members as $member): ?>
                    <tr class="hover:bg-gray-50 transition"
                        data-id="<?= (int)$member['id'] ?>"
                        data-name="<?= e($member['name'] ?? '') ?>"
                        data-email="<?= e($member['email'] ?? '') ?>"
                        data-course="<?= e($member['course'] ?? '') ?>"
                        data-role="<?= e($member['roles'] ?? '') ?>"
                        data-active="<?= (int)($member['is_active'] ?? 1) ?>">
                        <td class="px-6 py-4 text-gray-700"><?= (int)$member['id'] ?></td>
                        <td class="px-6 py-4 text-gray-700"><?= e($member['name'] ?? '') ?></td>
                        <td class="px-6 py-4 text-gray-700"><?= e($member['email'] ?? '') ?></td>

                        <!-- ✅ course can be NULL -->
                        <td class="px-6 py-4 text-gray-700"><?= !empty($member['course']) ? e($member['course']) : '-' ?></td>

                        <td class="px-6 py-4 text-gray-700"><?= e($member['roles'] ?? '') ?></td>

                        <!-- ✅ is_active: 1 active / 0 inactive -->
                        <td class="px-6 py-4 text-gray-700">
                            <?= User::activeLabel((int)($member['is_active'] ?? 1)) ?>
                        </td>

                        <td class="px-6 py-4 text-gray-700"><?= e($member['created_at'] ?? '') ?></td>

                        <td class="px-6 py-4 text-right">
                            <button class="text-green-500 hover:text-green-700 cursor-pointer editBtn">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5h2m-1 0v14m-7 0h14M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z" />
                                </svg>
                            </button>

                            <button onclick="openDeleteModal(<?= (int)$member['id'] ?>, '<?= e(BASE_URL) ?>delete-member')" class="text-red-500 hover:text-red-700 cursor-pointer deleteBtn">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

<!-- ✅ Modal -->
<div id="modal" class="fixed bg-[#727272b6] inset-0 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform transition-all duration-300 scale-95 opacity-0"
        id="modalContent">

        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800" id="title">Add Member</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="userForm" class="p-6 space-y-4">
            <input type="hidden" name="csrf" value="<?= e($token) ?>">
            <input type="hidden" name="hide_id" id="hide_id">

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="John Doe">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="john@example.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password <span class="text-red-500" id="pwStar">*</span>
                    </label>
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="••••••••">
                    <p class="text-xs text-gray-500 mt-1 hidden" id="pwHint">Leave blank to keep old password.</p>
                </div>

                <!-- ✅ course dropdown (Frontend/Backend) -->
                <div id="courseWrap">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Course (Member only) <span class="text-red-500" id="courseStar">*</span>
                    </label>
                    <select id="type" name="type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                        <option value="" disabled selected>Select Course</option>
                        <option value="<?= User::TYPE_FRONTEND ?>">Frontend</option>
                        <option value="<?= User::TYPE_BACKEND ?>">Backend</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select id="role" name="role" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                        <option value="" disabled selected>Select Role</option>
                        <option value="<?= User::ROLE_SUPERADMIN ?>">Super Admin</option>
                        <option value="<?= User::ROLE_ADMIN ?>">Admin</option>
                        <option value="<?= User::ROLE_INSTRUCTOR ?>">Instructor</option>
                        <option value="<?= User::ROLE_MEMBER ?>">Member</option>
                    </select>
                </div>

                <!-- ✅ is_active dropdown -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal()"
                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50">
                    Cancel
                </button>

                <button id="btnSubmit" type="submit"
                    class="flex-1 bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 shadow-lg">
                    Create Member
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../../pages/components/deleteModal.php'; ?>

<script src="<?= e(BASE_URL) ?>assets/js/app.js"></script>
<script src="<?= e(BASE_URL) ?>assets/js/confirm-delete-modal.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>

<script>
    const BASE_URL = "<?= e(BASE_URL) ?>";

    const modal = document.getElementById('modal');
    const modalContent = document.getElementById('modalContent');

    const $form = $("#userForm");
    const $formDelete = $("#deleteForm");

    function showModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModal() {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            // reset form
            document.getElementById('userForm').reset();
            $("#hide_id").val('');
            $("#pwHint").addClass('hidden');
            $("#password").prop('required', true);
            $("#pwStar").removeClass('hidden');
            $("#courseWrap").removeClass('hidden');
            $("#type").prop('required', true);
            $("#courseStar").removeClass('hidden');
        }, 250);
    }

    // ✅ course required ONLY for member
    function applyRoleRule() {
        const role = parseInt($("#role").val() || "0", 10);

        if (role === <?= (int)User::ROLE_MEMBER ?>) {
            $("#courseWrap").removeClass('hidden');
            $("#type").prop('required', true);
            $("#courseStar").removeClass('hidden');
        } else {
            $("#courseWrap").addClass('hidden');
            $("#type").prop('required', false);
            $("#type").val(""); // will be NULL in backend
        }
    }

    $("#role").on("change", applyRoleRule);

    // ✅ Create
    function openModalCreate() {
        $("#title").text("Add Member");
        $("#btnSubmit").text("Create Member").removeClass('bg-green-600').addClass('bg-blue-600');

        $("#hide_id").val("");

        $("#password").val("").prop('required', true);
        $("#pwHint").addClass('hidden');
        $("#pwStar").removeClass('hidden');

        // default role Member
        $("#role").val("<?= (int)User::ROLE_MEMBER ?>");
        applyRoleRule();

        $("#status").val("1"); // active
        showModal();
    }

    // ✅ Edit
    $(document).on("click", ".editBtn", function() {
        const tr = $(this).closest("tr");

        const id = tr.data("id");
        const name = tr.data("name");
        const email = tr.data("email");
        const course = tr.data("course");
        const roleText = (tr.data("role") || "").toString().trim();
        const isActive = tr.data("active");

        $("#title").text("Edit Member");
        $("#btnSubmit").text("Update").removeClass('bg-blue-600').addClass('bg-green-600');

        $("#hide_id").val(id);
        $("#name").val(name);
        $("#email").val(email);

        // password optional in edit
        $("#password").val("").prop('required', false);
        $("#pwHint").removeClass('hidden');
        $("#pwStar").addClass('hidden');

        // map role name -> id
        const roleId = (roleText === "super_admin") ? 1 :
                       (roleText === "admin") ? 2 :
                       (roleText === "instructor") ? 3 : 4;

        $("#role").val(roleId);
        applyRoleRule();

        // course
        if (roleId === 4) {
            $("#type").val(course);
        } else {
            $("#type").val("");
        }

        $("#status").val(String(isActive));
        showModal();
    });

    // ✅ submit
    $form.on("submit", function(e) {
        e.preventDefault();

        // frontend validation: member must select course
        const role = parseInt($("#role").val() || "0", 10);
        if (role === <?= (int)User::ROLE_MEMBER ?> && ($("#type").val() || "") === "") {
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

    // ✅ delete modal wiring (keep your current deleteModal.php fields)
    $(document).on("click", ".deleteBtn", function() {
        const tr = $(this).closest("tr");
        const id = tr.data("id");

        $("#delete_id").val(id);
        openModalDelete();
    });

    $formDelete.on("submit", function(e) {
        e.preventDefault();

        $.ajax({
            url: BASE_URL + "delete-member",
            method: "POST",
            data: $formDelete.serialize(),
            dataType: "json",
            xhrFields: { withCredentials: true },
            success(res) {
                if (res.status) location.reload();
                else alert(res.message || "Delete failed");
            },
            error(xhr) {
                console.error(xhr.responseText);
                alert("Server error: check console / php error log");
            }
        });
    });
</script>
