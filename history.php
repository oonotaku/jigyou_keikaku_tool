<?php
require_once 'load_env.php';
loadEnv();
require_once 'header.php';
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
$pdo = new PDO('mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME') . ';charset=utf8', getenv('DB_USER'), getenv('DB_PASS'));
$stmt = $pdo->prepare("SELECT id, title, created_at FROM compositions WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION["user_id"]]);
$rows = $stmt->fetchAll();
?>
<h1>作成履歴</h1>
<ul>
<?php foreach ($rows as $row): ?>
  <li>
    <a href="view.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a> (<?= $row["created_at"] ?>)
  </li>
<?php endforeach; ?>
</ul>
<a href="dashboard.php">戻る</a>
