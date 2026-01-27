<?php

declare(strict_types=1);

$token = csrf_token();
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/auth.php';

$user = $_SESSION['user'];
require_once __DIR__ . '/../../includes/layouts/app.php';
?>

<!-- page header  -->
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
    <div>
        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight">Members</h1>
        <p class="text-sm text-gray-500 mt-1">Manage your team members and their roles</p>
    </div>

    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
        <!-- Filter by Role -->
        <div class="relative">
            <select id="filterRole"
                class="appearance-none bg-white border border-gray-300 text-gray-700 px-4 py-2.5 pr-10 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 cursor-pointer hover:border-gray-400 font-medium min-w-[140px]">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="teacher">Teacher</option>
                <option value="member">Member</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        <!-- Filter by Status -->
        <div class="relative">
            <select id="filterStatus"
                class="appearance-none bg-white border border-gray-300 text-gray-700 px-4 py-2.5 pr-10 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 cursor-pointer hover:border-gray-400 font-medium min-w-[140px]">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        <!-- Add Member Button -->
        <button class="cursor-pointer bg-gradient-to-r from-green-500 to-green-600 text-white px-5 py-2.5 rounded-lg flex items-center justify-center gap-2 hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-medium whitespace-nowrap"
            onclick="openModalCreate()"
            id="addMember">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Add Member</span>
        </button>
    </div>
</div>

<!-- table member -->
<?php require __DIR__ . '/../components/memberTable.php'; ?>

<!-- modal create and edit -->
<?php require __DIR__ . '/../components/member-modal.php'; ?>

<!-- modal delete -->
<?php require __DIR__ . '/../../pages/components/deleteModal.php'; ?>

<!-- footer -->
<?php require_once __DIR__ . '/../../includes/layouts/app-footer.php'; ?>

