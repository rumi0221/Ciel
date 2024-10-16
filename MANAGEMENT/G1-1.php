

<!-- <!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <title>ログイン画面</title>
</head>
<body class="login">
    <h1>LOGIN</h1>
    <div class="text">
        <input type="text" class="input" name="user" placeholder=" user"><br>
        <input type="password" class="input" name="password" placeholder=" password"><br><br>
    <button class="login-button">LOGIN</button>
    </div>
</body>
</html> -->

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
    <title>ログイン画面</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            width: 400px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .login-container h1 {
            margin-bottom: 30px;
            font-size: 24px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        .login-btn {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            color: white;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-btn:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>ログイン</h1>
    <?php if (isset($error)) { echo '<p class="error">'.$error.'</p>'; } ?>
    <form method="POST" action="">
        <input type="text" name="user" placeholder="ユーザー名" required>
        <input type="password" name="password" placeholder="パスワード" required>
        <button type="submit" class="login-btn">ログイン</button>
    </form>
</div>

</body>
</html>