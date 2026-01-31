<?php require_once __DIR__ . '/../pages/components/project-modal.php'; ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="<?= e(BASE_URL) ?>assets/js/pagination.js"></script>
<script src="<?= e(BASE_URL) ?>assets/js/app.js"></script>
<script src="<?= e(BASE_URL) ?>assets/js/assign-members.js"></script>
<script src="<?= e(BASE_URL) ?>assets/js/alert.js"></script>
<script src="<?= e(BASE_URL) ?>assets/js/confirm-delete-modal.js"></script>
<script>
    window.BASE_URL = "<?= e(BASE_URL) ?>";
    window.ROLE_MEMBER = "<?= (int)User::ROLE_MEMBER ?>";
</script>

<script src="<?= e(BASE_URL) ?>assets/js/member-modal.js"></script>