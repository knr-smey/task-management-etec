<?php

declare(strict_types=1); ?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e(APP_NAME) ?></title>

  <!-- CSS -->
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>assets/css/app.css">
  <link rel="icon" href="<?= e(BASE_URL) ?>public/Image/KRUSolutionLogo.png">

  <!-- Tailwind -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

  <!-- jQuery -->
  <script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous">
  </script>

  <!-- Sidebar style -->
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
</head>

<body class="bg-white font-sans">