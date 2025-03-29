<?php
session_start();

$username = '';
if (isset($_SESSION['user_id'])) {
    require_once 'load_env.php';
    loadEnv();
    $pdo = new PDO(
        'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME') . ';charset=utf8',
        getenv('DB_USER'),
        getenv('DB_PASS')
    );
    $stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    $username = $user ? $user['email'] : '';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>事業計画作文生成ツール</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
  <div><strong>事業計画作文生成ツール</strong></div>
  <div>
    <?php if ($username): ?>
      <?php echo htmlspecialchars($username); ?> |
      <a href="logout.php">ログアウト</a>
    <?php endif; ?>
  </div>
</div>
