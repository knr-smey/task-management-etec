<?php
declare(strict_types=1);

require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/auth.php';

$tasks = $_SESSION['tasks'] ?? [];
require __DIR__ . '/../../includes/header.php';
?>
<div class="card">
  <div style="display:flex; justify-content:space-between; align-items:center;">
    <h2 style="margin:0;">Tasks</h2>
    <a href="<?= e(BASE_URL) ?>tasks/create"><button type="button">+ New Task</button></a>
  </div>

  <?php if (empty($tasks)): ?>
    <p class="small">No tasks yet. Create one.</p>
  <?php else: ?>
    <table class="table" style="margin-top:10px;">
      <thead>
        <tr><th>Title</th><th>Status</th><th>Assignee</th><th>Action</th></tr>
      </thead>
      <tbody>
      <?php foreach ($tasks as $id => $t): ?>
        <tr>
          <td><?= e($t['title']) ?></td>
          <td><?= e($t['status']) ?></td>
          <td><?= e($t['assignee']) ?></td>
          <td>
            <a href="<?= e(BASE_URL) ?>tasks/edit?id=<?= (int)$id ?>">Edit</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
