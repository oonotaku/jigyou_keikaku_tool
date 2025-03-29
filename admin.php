<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
  header('Location: admin_login.php');
  exit;
}
$filename = 'prompt.txt';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    file_put_contents($filename, $_POST['prompt']);
}
$current_prompt = file_exists($filename) ? file_get_contents($filename) : '';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>管理者プロンプト設定</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .admin-wrapper {
      max-width: 700px;
      margin: 60px auto;
      background: #f9f9f9;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .admin-wrapper h1 {
      color: #1f3b57;
      text-align: center;
    }
    .admin-wrapper textarea {
      width: 100%;
      height: 200px;
      padding: 10px;
      font-size: 16px;
      border-radius: 6px;
      border: 1px solid #ccc;
      margin-top: 15px;
    }
    .admin-wrapper .buttons {
      margin-top: 20px;
      text-align: right;
    }
    .admin-wrapper .logout-link {
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="admin-wrapper">
    <h1>プロンプト設定（管理者用）</h1>
    <form method="post">
      <label for="prompt">プロンプト内容を編集:</label>
      <textarea name="prompt" id="prompt"><?php echo htmlspecialchars($current_prompt); ?></textarea><br>
      <div class="buttons">
        <button type="submit">保存する</button>
      </div>
    </form>
    <div class="logout-link">
      <a href="admin_logout.php">← 管理者ログアウト</a>
    </div>
  </div>
</body>
</html>
