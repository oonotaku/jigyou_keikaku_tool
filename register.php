<?php
require_once 'load_env.php';
loadEnv();
session_start();

$pdo = new PDO('mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME') . ';charset=utf8', getenv('DB_USER'), getenv('DB_PASS'));

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->execute([$email, $password]);
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>新規登録 - 事業計画作文生成ツール</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .register-wrapper {
      max-width: 400px;
      margin: 80px auto;
      padding: 30px;
      background: #f9f9f9;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      text-align: center;
    }
    .register-wrapper h1 {
      color: #1f3b57;
    }
  </style>
</head>
<body>
  <div class="register-wrapper">
    <h1>新規登録</h1>
    <form method="post">
      <input name="email" type="email" placeholder="メールアドレス" required><br>
      <input name="password" type="password" placeholder="パスワード" required><br>
      <button type="submit">登録する</button>
    </form>
    <p style="margin-top: 20px;">すでにアカウントをお持ちの方は <a href="login.php">ログイン</a></p>
  </div>
</body>
</html>
