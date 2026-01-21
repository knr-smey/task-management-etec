<?php
declare(strict_types=1);

require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/auth.php';

$token = csrf_token();
require __DIR__ . '/../../includes/header.php';
?>
<div class="card">
  <h2>Create Task</h2>
  <form method="post" action="<?= e(BASE_URL) ?>api/task/create">
    <input type="hidden" name="csrf" value="<?= e($token) ?>">
    <label>Title</label>
    <input name="title" required>

    <div class="row" style="margin-top:10px;">
      <div>
        <label>Status</label>
        <select name="status">
          <option value="todo">todo</option>
          <option value="in_progress">in_progress</option>
          <option value="done">done</option>
        </select>
      </div>
      <div>
        <label>Assignee</label>
        <input name="assignee" placeholder="e.g. member1">
      </div>
    </div>

    <div style="margin-top:12px; display:flex; gap:10px;">
      <button type="submit">Save</button>
      <a href="<?= e(BASE_URL) ?>tasks"><button class="secondary" type="button">Cancel</button></a>
    </div>
  </form>
</div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
