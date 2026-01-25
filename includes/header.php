<?php

declare(strict_types=1);
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e(APP_NAME) ?></title>
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>assets/css/app.css">
  <link rel="icon" href="<?= e(BASE_URL) ?>public/Image/KRUSolutionLogo.png">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<style>
  .sidebar-item {
    transition: background-color 0.2s;
  }

  .sidebar-item:hover {
    background-color: rgba(255, 255, 255, 0.1);
  }

  .sidebar-item.active {
    background-color: #0E5A8A;
  }
</style>

<body class="bg-white font-sans">
  <nav class="bg-blue-900 text-white px-4 py-3 flex items-center justify-between sticky top-0">
    <div class="flex items-center gap-4">
      <div class="flex items-center gap-2">
        <span class="text-sm font-bold">KRU Solution</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </div>
      <!-- <button class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center hover:bg-green-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </button> -->
    </div>
    <div class="flex items-center gap-4">
      <div class="relative">
        <input type="text" placeholder="Search ..." class="bg-blue-800 text-white px-4 py-2 rounded border border-blue-700 w-64 placeholder-blue-300 focus:outline-none focus:border-blue-500">
        <button class="absolute right-2 top-1/2 -translate-y-1/2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </button>
      </div>
      <?php
      $user = $_SESSION['user'] ?? null;
      foreach ($user['roles'] as $role) {
        if ($role != "member") {
          echo ' <a href="' . e(BASE_URL) . 'create-project" class="p-2 bg-green-600 rounded-full hover:bg-green-700 cursor-pointer" title="create project">
         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
    </svg>
      </a>';
        }
      }

      ?>

      <div class="relative">
        <button class="p-2 hover:bg-blue-800 rounded">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
        </button>
        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">27</span>
      </div>
      <button class="p-2 hover:bg-blue-800 rounded">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </button>
      <div class="w-10 h-10 bg-green-400 rounded-full flex items-center justify-center font-bold text-white">
        NS
      </div>
    </div>
  </nav>
  <div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-60 bg-gray-800 text-white overflow-y-hidden h-[91vh] fixed top-[9vh]">
      <div class="p-4 space-y-1">
        <a href="<?= e(BASE_URL) ?>dashboard" class="sidebar-item flex items-center gap-3 px-3 py-2 rounded cursor-pointer">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>Overview</span>
        </a>

        <a href="#" class="sidebar-item flex items-center gap-3 px-3 py-2 rounded cursor-pointer">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>Activity</span>
        </a>

        <a href="#" class="sidebar-item flex items-center justify-between px-3 py-2 rounded cursor-pointer">
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <span>Work packages</span>
          </div>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </a>
        <a href="#" class="sidebar-item active flex items-center justify-between px-3 py-2 rounded cursor-pointer">
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
            </svg>
            <span>Boards</span>
          </div>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </a>
        <a href="#" class="sidebar-item flex items-center gap-3 px-3 py-2 rounded cursor-pointer">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
          </svg>
          <span>Backlogs</span>
        </a>
        <a href="#" class="sidebar-item flex items-center justify-between px-3 py-2 rounded cursor-pointer">
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Time and costs</span>
          </div>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </a>
        <?php
        $user = $_SESSION['user'] ?? null;
        foreach ($user['roles'] as $role) {
          if ($role != "member") {
            echo '<a href="' . e(BASE_URL) . 'member" class="sidebar-item flex items-center gap-3 px-3 py-2 rounded cursor-pointer">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <span>Members</span>
        </a>';
          }
        }

        ?>

      </div>
    </aside>
    <!-- <header class="topbar">
    <div class="container">
      <div class="brand"><?= e(APP_NAME) ?></div>
      <nav class="nav">
        <a href="<?= e(BASE_URL) ?>dashboard">Dashboard</a>
        <a href="<?= e(BASE_URL) ?>tasks">Tasks</a>
        <a href="<?= e(BASE_URL) ?>logout">Logout</a>
      </nav>
    </div>
  </header> -->
    <main class="flex-1 overflow-y-auto bg-white ms-60">