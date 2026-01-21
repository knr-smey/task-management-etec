<?php
declare(strict_types=1);

require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/auth.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : -1;
$tasks = $_SESSION['tasks'] ?? [];
$task = $tasks[$id] ?? null;

if (!$task) {
    http_response_code(404);
    echo "Task not found";
    exit;
}

$token = csrf_token();
require __DIR__ . '/../../includes/header.php';
?>
<div class="card">
  <h2>Edit Task</h2>
  <form method="post" action="<?= e(BASE_URL) ?>api/task/update">
    <input type="hidden" name="csrf" value="<?= e($token) ?>">
    <input type="hidden" name="id" value="<?= (int)$id ?>">

    <label>Title</label>
    <input name="title" value="<?= e($task['title']) ?>" required>

    <div class="row" style="margin-top:10px;">
      <div>
        <label>Status</label>
        <select name="status">
          <?php foreach (['todo','in_progress','done'] as $s): ?>
            <option value="<?= e($s) ?>" <?= $task['status']===$s?'selected':'' ?>><?= e($s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label>Assignee</label>
        <input name="assignee" value="<?= e($task['assignee']) ?>">
      </div>
    </div>

    <div style="margin-top:12px; display:flex; gap:10px;">
      <button type="submit">Update</button>
      <form method="post" action="<?= e(BASE_URL) ?>api/task/delete" style="display:inline;">
        <input type="hidden" name="csrf" value="<?= e($token) ?>">
        <input type="hidden" name="id" value="<?= (int)$id ?>">
        <button class="secondary" type="submit" onclick="return confirm('Delete task?')">Delete</button>
      </form>
      <a href="<?= e(BASE_URL) ?>tasks"><button class="secondary" type="button">Back</button></a>
    </div>
  </form>
</div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
