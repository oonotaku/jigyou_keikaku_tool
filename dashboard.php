<?php
require_once 'load_env.php';
loadEnv();
require_once 'header.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$prompt_template = file_exists('prompt.txt') ? file_get_contents('prompt.txt') : 'プロンプトが未設定です。';
$result = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $user_input = $_POST["user_input"];
    $prompt = $prompt_template . "\n\n入力: " . $user_input;

    $data = [
        "model" => "gpt-3.5-turbo",
        "messages" => [
            ["role" => "system", "content" => "あなたはプロのビジネスコンサルタントです。"],
            ["role" => "user", "content" => $prompt]
        ],
    ];

    $ch = curl_init("https://api.openai.com/v1/chat/completions");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . getenv("OPENAI_API_KEY")
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response, true)["choices"][0]["message"]["content"];

    $pdo = new PDO(
        'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME') . ';charset=utf8',
        getenv('DB_USER'),
        getenv('DB_PASS')
    );
    $stmt = $pdo->prepare("INSERT INTO compositions (user_id, title, input_text, result_text) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION["user_id"], $title, $user_input, $result]);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>事業計画作文生成</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="form-wrapper">
    <h1>事業計画アドバイス作成</h1>
    <form method="post">
      <input type="text" name="title" placeholder="表題を入力" required><br>
      <textarea name="user_input" rows="4" cols="50" placeholder="悩みや質問を入力してください..." required></textarea><br>
      <button type="submit">アドバイス生成</button>
    </form>
    <p style="text-align:center; margin-top: 20px;">
      <a href="history.php">作成履歴を見る</a>
    </p>
  </div>

  <?php if ($result): ?>
    <div class="result-box">
      <h2>アドバイス</h2>
      <p><?php echo nl2br(htmlspecialchars($result)); ?></p>
    </div>
  <?php endif; ?>
</body>
</html>
