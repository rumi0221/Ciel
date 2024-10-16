<?php session_start(); ?><!--  // セッションの開始 -->

<?php
// データベース接続情報
$host = "mysql310.phy.lolipop.lan"; // データベースのホスト名
$dbname = "LAA1517478-3rd"; // データベース名
$username = "LAA1517478"; // データベースのユーザ名
$password = "3rd1004"; // データベースのパスワード

// MySQLデータベースに接続
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベース接続エラー: " . $e->getMessage());
}

// ログインフォームが送信されたか確認
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUser = $_POST['user'];
    $inputPassword = $_POST['password'];

    // データベースからユーザー情報を取得
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_name = :user AND mail = :mail");
    $stmt->execute(['user' => 'Admin', 'mail' => 'admin@s.ac.jp']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 入力されたパスワードとデータベースのパスワードを比較
    if ($user && password_verify($inputPassword, password_hash('Nimda', PASSWORD_DEFAULT))) {
        $_SESSION['loggedin'] = true;
        $_SESSION['user_name'] = $user['user_name'];
        header("Location: dashboard.php"); // ログイン成功時のリダイレクト先
        exit;
    } else {
        $error = "ユーザー名またはパスワードが間違っています。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <title>ログイン画面</title>
</head>
<body class="login">
    <h1>LOGIN</h1>
    <?php if (isset($error)) { echo '<p class="error">'.$error.'</p>'; } ?>
    <div class="text">
    <form method="POST" action="G1-2.php">
        <input type="text" class="input" name="user" placeholder=" user" required><br>
        <input type="password" class="input" name="password" placeholder=" password" required><br><br>
        <button type="submit" class="login-button">LOGIN</button>
    </form>
    </div>
</body>
</html>
</body>
</html>