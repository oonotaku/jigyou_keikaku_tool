<?php
require_once 'load_env.php';
loadEnv();
require_once 'header.php';
session_start

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$pdo = new PDO(
    'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME') . ';charset=utf8',
    getenv('DB_USER'),
    getenv('DB_PASS')
);

$stmt = $pdo->prepare("SELECT * FROM compositions WHERE id = ? AND user_id = ?");
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);
$row = $stmt->fetch();

if (!$row) {
    echo "表示できません。";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($row['title']) ?> - 作文詳細</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .view-container {
      max-width: 800px;
      margin: 50px auto;
      padding: 30px;
      background: #f9f9f9;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      font-size: 17px;
      line-height: 1.8;
    }
    .view-container h1 {
      color: #1f3b57;
      text-align: center;
    }
    .section-label {
      font-weight: bold;
      color: #1f3b57;
      margin-top: 30px;
      margin-bottom: 10px;
      font-size: 18px;
    }
    .view-container p {
      white-space: pre-wrap;
      margin: 0;
      color: #333;
    }
    .back-link {
      margin-top: 30px;
      text-align: center;
    }
    .back-link a {
      color: #1f3b57;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="view-container">
    <h1><?= htmlspecialchars($row['title']) ?></h1>
    <p><small>作成日時: <?= $row['created_at'] ?></small></p>

    <div class="section-label">入力内容：</div>
    <p><?= nl2br(htmlspecialchars($row['input_text'])) ?></p>

    <div class="section-label">生成結果：</div>
    <p><?= nl2br(htmlspecialchars($row['result_text'])) ?></p>

    <div class="back-link">
      <a href="history.php">← 作成履歴に戻る</a>
    </div>
  </div>
</body>
</html>
