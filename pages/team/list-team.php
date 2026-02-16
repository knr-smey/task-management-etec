<?php

declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php';

require_once __DIR__ . '/../../includes/layouts/app.php';

$teamId = (int)($team['id'] ?? 0);
$ownerId = (int)($team['created_by'] ?? 0);
$members = $members ?? [];
?>

<style>
    .team-list-page .team-list-table>thead,
    .team-list-page .team-list-table>thead>tr,
    .team-list-page .team-list-table>thead>tr>th {
        background: #274193 !important;
        background-image: none !important;
        color: #ffffff !important;
    }
</style>

<section class="team-list-page">
    <div class="team-list-wrap">
        <div class="team-list-header">
            <h1 class="team-list-title">Team Members</h1>
            <p class="team-list-subtitle">
                <?= e($team['name'] ?? 'Team') ?> · <?= (int)($memberCount ?? 0) ?> members
            </p>
            <div class="team-list-manager">
                <strong>Manager:</strong> <?= e($team['owner_name'] ?? '-') ?>
            </div>
        </div>

        <div class="team-list-action-bar">
            <a class="team-list-btn team-list-btn-secondary" href="<?= e(BASE_URL) ?>team/detail?id=<?= $teamId ?>">
                Back to Team
            </a>
            <?php if (!empty($canEditUsers)): ?>
                <a class="team-list-btn team-list-btn-primary" href="<?= e(BASE_URL) ?>member?team_id=<?= $teamId ?>">
                    Open Member Manager
                </a>
            <?php endif; ?>
        </div>

        <div class="team-list-card">
            <div class="team-list-table-wrap">
                <table class="team-list-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Course</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($members)): ?>
                            <tr>
                                <td colspan="7" class="team-list-empty">No members in this team.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($members as $index => $m): ?>
                                <?php
                                $isOwnerRow = ((int)($m['id'] ?? 0) === $ownerId);
                                $displayRole = (string)($m['roles'] ?? 'member');
                                $joinedAt = (string)($m['joined_at'] ?? '');
                                ?>
                                <tr>
                                    <td><?= (int)($index + 1) ?></td>
                                    <td><?= e($m['name'] ?? '-') ?></td>
                                    <td><?= e($m['email'] ?? '-') ?></td>
                                    <td><?= e($m['course'] ?? '-') ?></td>
                                    <td>
                                        <?php if ($isOwnerRow): ?>
                                            <span class="team-list-badge team-list-badge-owner">Owner</span>
                                        <?php else: ?>
                                            <span class="team-list-badge team-list-badge-role"><?= e($displayRole) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= e($joinedAt !== '' ? $joinedAt : '-') ?></td>
                                    <td>
                                        <div class="team-list-actions">
                                            <?php if (!empty($canManageMembers) && !$isOwnerRow): ?>
                                                <button
                                                    type="button"
                                                    class="team-list-btn-mini team-list-btn-danger js-kick-member"
                                                    data-member-id="<?= (int)($m['id'] ?? 0) ?>"
                                                    data-member-name="<?= e($m['name'] ?? 'Member') ?>">
                                                    Kick
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="team-list-btn-mini team-list-btn-disabled" disabled>No action</button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
(() => {
    const teamId = <?= $teamId ?>;
    const csrf = "<?= e($token ?? '') ?>";
    const removeUrl = "<?= e(BASE_URL) ?>team/remove-member";

    document.addEventListener("click", async (event) => {
        const button = event.target.closest(".js-kick-member");
        if (!button) return;

        const memberId = button.getAttribute("data-member-id");
        const memberName = button.getAttribute("data-member-name") || "this member";

        if (!memberId) return;

        if (!window.confirm(`Kick ${memberName} from this team?`)) {
            return;
        }

        button.disabled = true;

        try {
            const payload = new URLSearchParams();
            payload.append("csrf", csrf);
            payload.append("team_id", String(teamId));
            payload.append("member_id", String(memberId));

            const response = await fetch(removeUrl, {
                method: "POST",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
                },
                body: payload.toString()
            });

            const data = await response.json();
            if (data.status) {
                window.location.reload();
                return;
            }

            alert(data.message || "Failed to remove member.");
        } catch (error) {
            alert("Server error while removing member.");
        } finally {
            button.disabled = false;
        }
    });
})();
</script>

<?php require_once __DIR__ . '/../../includes/layouts/app-footer.php'; ?>
