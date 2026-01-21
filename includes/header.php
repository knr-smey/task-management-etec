<?php
declare(strict_types=1);
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e(APP_NAME) ?></title>
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>assets/css/app.css">
</head>
<body>
<header class="topbar">
  <div class="container">
    <div class="brand"><?= e(APP_NAME) ?></div>
    <nav class="nav">
      <a href="<?= e(BASE_URL) ?>dashboard">Dashboard</a>
      <a href="<?= e(BASE_URL) ?>tasks">Tasks</a>
      <a href="<?= e(BASE_URL) ?>logout">Logout</a>
    </nav>
  </div>
</header>
<main class="container">
