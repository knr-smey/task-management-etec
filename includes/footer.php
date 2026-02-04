<?php
declare(strict_types=1);

// REQUIRED because footer uses User::ROLE_MEMBER
require_once __DIR__ . '/../app/Models/User.php';

// existing code...
require_once __DIR__ . '/../pages/components/project-modal.php';
?>


<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="<?= e(BASE_URL) ?>assets/js/confirm-delete-modal.js"></script>
<script src="<?= e(BASE_URL) ?>assets/js/project.js"></script>
<script src="<?= e(BASE_URL) ?>assets/js/pagination.js"></script>
<script src="<?= e(BASE_URL) ?>assets/js/assign-members.js"></script>
<script src="<?= e(BASE_URL) ?>assets/js/alert.js"></script>


<script>
    window.BASE_URL = "<?= e(BASE_URL) ?>";
    window.ROLE_MEMBER = "<?= (int)User::ROLE_MEMBER ?>";
</script>

<script>
    (function () {
        const loader = document.getElementById('pageLoader');
        if (!loader) return;

        const showLoader = () => {
            loader.classList.remove('hidden');
            loader.style.opacity = '1';
        };

        const hideLoader = () => {
            loader.classList.add('hidden');
            loader.style.opacity = '0';
        };

        window.addEventListener('load', hideLoader);

        document.addEventListener('click', (event) => {
            const link = event.target.closest('a');
            if (!link) return;

            const href = link.getAttribute('href') || '';
            if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
            if (link.target === '_blank' || link.hasAttribute('download')) return;

            showLoader();
        });

        window.addEventListener('beforeunload', showLoader);
    })();
</script>

<script>
    (function () {
        const btn = document.getElementById('logoutBtn');
        const modal = document.getElementById('logoutModal');
        const closeBtn = document.getElementById('closeLogoutModal');
        const cancelBtn = document.getElementById('cancelLogout');

        if (!btn || !modal) return;

        const openModal = () => {
            modal.classList.remove('hidden');
            modal.setAttribute('aria-hidden', 'false');
        };

        const closeModal = () => {
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
        };

        btn.addEventListener('click', openModal);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

        modal.addEventListener('click', (event) => {
            if (event.target === modal) closeModal();
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') closeModal();
        });
    })();
</script>

<script src="<?= e(BASE_URL) ?>assets/js/member-modal.js"></script>