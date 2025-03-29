<?php
require_once 'load_env.php';
loadEnv();
session_start();

$pdo = new PDO('mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME') . ';charset=utf8', getenv('DB_USER'), getenv('DB_PASS'));

$error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "メールアドレスまたはパスワードが正しくありません。";
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ログイン - 事業計画作文生成ツール</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .login-wrapper {
      max-width: 400px;
      margin: 80px auto;
      padding: 30px;
      background: #f9f9f9;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      text-align: center;
    }
    .login-wrapper h1 {
      color: #1f3b57;
    }
    .login-wrapper p.error {
      color: #b30000;
    }
  </style>
</head>
<body>
  <div class="login-wrapper">
    <h1>ログイン</h1>
    <?php if ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">
      <input name="email" type="email" placeholder="メールアドレス" required><br>
      <input name="password" type="password" placeholder="パスワード" required><br>
      <button type="submit">ログイン</button>
    </form>
    <p style="margin-top: 20px;">アカウントがない方は <a href="register.php">新規登録</a></p>
  </div>
</body>
</html>
