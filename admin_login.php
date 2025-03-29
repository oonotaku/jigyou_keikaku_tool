<?php
require_once 'load_env.php';
loadEnv();
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_user = $_POST['username'];
    $input_pass = $_POST['password'];

    $admin_user = getenv('ADMIN_USER');
    $admin_pass = getenv('ADMIN_PASS');

    if ($input_user === $admin_user && $input_pass === $admin_pass) {
        $_SESSION['is_admin'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = 'ユーザー名またはパスワードが間違っています';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>管理者ログイン</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>管理者ログイン</h1>
  <?php if ($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
  <?php endif; ?>
  <form method="post">
    <input name="username" placeholder="ユーザー名"><br>
    <input type="password" name="password" placeholder="パスワード"><br>
    <button type="submit">ログイン</button>
  </form>
</body>
</html>
