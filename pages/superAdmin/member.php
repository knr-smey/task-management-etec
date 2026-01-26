<?php

declare(strict_types=1);
$token = csrf_token();
// require __DIR__ . '/../../includes/helpers.php';
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
        <button class="bg-green-500 text-white px-4 py-2 rounded flex items-center cursor-pointer gap-2 hover:bg-green-600 transition" onclick="openModal()" id="addMember">
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
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700 uppercase">Type</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700 uppercase">Role</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700 uppercase">Status</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700 uppercase">Created At</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($members as $member): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-700"><?= $member['id'] ?></td>
                        <td class="px-6 py-4 text-gray-700">
                            <?= $member['name'] ?>
                        </td>
                        <td class="px-6 py-4 text-gray-700"><?= $member['email'] ?></td>
                        <td class="px-6 py-4 text-gray-700"><?= $member['course'] ?></td>
                        <td class="px-6 py-4 text-gray-700"><?= $member['roles'] ?></td>
                        <td class="px-6 py-4 text-gray-700" data-status="<?= $member['is_active'] ?>"> <?= User::statusLabel((int)$member['is_active']) ?></td>
                        <td class="px-6 py-4 text-gray-700"><?= $member['created_at'] ?></td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-green-500 hover:text-green-700 cursor-pointer" data-id="<?= $member['id'] ?>" onclick="openModal()" id="editMember">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5h2m-1 0v14m-7 0h14M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z" />
                                </svg>
                            </button>
                            <button class="text-red-500 hover:text-red-700 cursor-pointer" data-id="<?= $member['id'] ?>" onclick="openModalDelete()" id="openDeleteModal">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
<div id="modal" class="fixed bg-[#727272b6] inset-0 hidden items-center justify-center z-50 p-4">
    <!-- Modal Container -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800" id="title">Add New User</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="userForm" class="p-6 space-y-4">
            <input type="hidden" name="csrf" value="<?= e($token) ?>">
            <input type="hidden" name="hide_id" id="hide_id">
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 outline-none"
                        placeholder="John Doe">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 outline-none"
                        placeholder="john@example.com">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 outline-none"
                        placeholder="••••••••">
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        User Type <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="type"
                        name="type"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 outline-none bg-white">
                        <option value="" disabled selected>Select Type</option>
                        <option value="<?= User::TYPE_FRONTEND ?>">Frontend</option>
                        <option value="<?= User::TYPE_BACKEND ?>">Backend</option>
                    </select>
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="role"
                        name="role"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 outline-none bg-white">
                        <option value="" disabled selected>Select Role</option>
                        <option value="<?= User::ROLE_SUPERADMIN ?>">Super Admin</option>
                        <option value="<?= User::ROLE_ADMIN ?>">Admin</option>
                        <option value="<?= User::ROLE_INSTRUCTOR ?>">Instructor</option>
                        <option value="<?= User::ROLE_MEMBER ?>">Member</option>

                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="status"
                        name="status"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 outline-none bg-white">
                        <option value="" disabled selected>Select Status</option>
                        <option value="<?= User::STATUS_APPROVE ?>">Aprrove</option>
                        <option value="<?= User::STATUS_REJECT ?>">Reject</option>
                        <option value="<?= User::STATUS_PENDING ?>">Pending</option>
                    </select>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-3 pt-4">
                <button
                    type="button"
                    onclick="closeModal()"
                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition duration-200">
                    Cancel
                </button>
                <button
                    id="btnSubmit"
                    type="submit"
                    class="flex-1 bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transform hover:scale-105 transition duration-200 shadow-lg">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../../pages/components/deleteModal.php'; ?>
<script src="<?= e(BASE_URL) ?>assets/js/app.js"></script>
<script src="<?= e(BASE_URL) ?>assets/js/confirm-delete-modal.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    const modal = document.getElementById('modal');
    const modalContent = document.getElementById('modalContent');
    const modalDelete = document.getElementById('modalDelete');
    const modalContentDelete = document.getElementById('modalContentDelete');

    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        // Trigger animation
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function openModalDelete() {
        modalDelete.classList.remove('hidden');
        modalDelete.classList.add('flex');

        // Trigger animation
        setTimeout(() => {
            modalContentDelete.classList.remove('scale-95', 'opacity-0');
            modalContentDelete.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModal() {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        modalContentDelete.classList.remove('scale-100', 'opacity-100');
        modalContentDelete.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modalDelete.classList.add('hidden');
            modalDelete.classList.remove('flex');
            document.getElementById('userForm').reset();
        }, 300);
    }
    const BASE_URL = "<?= e(BASE_URL) ?>";
    const $form = $("#userForm");
    const $formDelete = $("#deleteForm");
    $('#addMember').click(function() {
        $('#title').text('Add Member');
        $('#btnSubmit').text('Create Member');
        $('#btnSubmit').removeClass('bg-green-600');
        $('#btnSubmit').removeClass('hover:bg-green-700');
        $('#btnSubmit').addClass('bg-blue-600');
        $('#btnSubmit').removeClass('hover:bg-blue-700');
    });
    $(document).on('click', '#editMember', function() {
        $('#hide_id').val($(this).attr('data-id'));
        const tr = $(this).parents('tr');
        $('#name').val(tr.find('td').eq(1).text().trim());
        $('#email').val(tr.find('td').eq(2).text().trim());
        $('#type').val(tr.find('td').eq(3).text().trim());
        const role = tr.find('td').eq(4).text().trim();
        $('#role').val((role == 'super_admin') ? 1 : (role == 'admin') ? 2 : (role == 'instructor') ? 3 : 4);
        const statusValue = tr.find('td').eq(5).data('status');
        $('#status').val(statusValue);
        $('#title').text('Edit Member');
        $('#btnSubmit').text('Edit Member');
        $('#btnSubmit').removeClass('bg-blue-600');
        $('#btnSubmit').removeClass('hover:bg-blue-700');
        $('#btnSubmit').addClass('bg-green-600');
        $('#btnSubmit').addClass('hover:bg-green-700');
        $('#password').removeAttr('required')

    });
    $form.on("submit", function(e) {
        e.preventDefault();
        $.ajax({
            url: BASE_URL + "create-member",
            method: "POST",
            data: $form.serialize(),
            dataType: "json",
            xhrFields: {
                withCredentials: true
            },
            success(res) {
                if (res.status) {
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                }

            },

            error(xhr) {
                console.error(xhr.responseText);
            }
        });
    });
    $formDelete.on("submit", function(e) {
        e.preventDefault();
        $.ajax({
            url: BASE_URL + "delete-member",
            method: "POST",
            data: $formDelete.serialize(),
            dataType: "json",
            xhrFields: {
                withCredentials: true
            },
            success(res) {
                if (res.status) {
                    console.log(res);

                    setTimeout(() => {
                        location.reload();
                    }, 500);
                }

            },

            error(xhr) {
                console.error(xhr.responseText);
            }
        });
    });
</script>