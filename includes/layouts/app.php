<!DOCTYPE html>
<html lang="en">

<head>
    <?php require __DIR__ . '/../header.php'; ?>
</head>

<body class="bg-slate-50">

    <div id="pageLoader" class="page-loader hidden" aria-live="polite" aria-busy="true">
        <div class="page-loader-card">
            <div class="page-loader-spinner" aria-hidden="true"></div>
            <div class="page-loader-text">
                <p class="page-loader-title">Loading</p>
                <p class="page-loader-subtitle">Please wait a moment...</p>
            </div>
        </div>
    </div>

    <?php require __DIR__ . '/../navbar.php'; ?>
    <?php require __DIR__ . '/../sidebar.php'; ?>

    <!-- PAGE CONTENT START -->
    <main class="pt-23 ml-64 min-h-screen bg-slate-50 p-8">