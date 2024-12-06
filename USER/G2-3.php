<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $new_pass = $_POST['new_pass'];
    $new_pass_confirm = $_POST['new_pass_confirm'];

    if ($new_pass !== $new_pass_confirm) {
        $_SESSION['error_message'] = "パスワードが一致しません。";
        header("Location: G2-2.php?token=" . urlencode($token));
        exit();
    }

    $dsn = 'mysql:host=mysql310.phy.lolipop.lan;dbname=LAA1517478-3rd;charset=utf8';
    $user = 'LAA1517478';
    $password = '3rd1004';

    try {
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $hashedPass = password_hash($new_pass, PASSWORD_DEFAULT);

        $stmt = $dbh->prepare("UPDATE Users SET user_pass = :user_pass, token = NULL, expires_at = NULL WHERE token = :token");
        $stmt->bindParam(':user_pass', $hashedPass);
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        $message = "パスワードの変更が完了しました。";
    } catch (PDOException $e) {
        $message = "エラーが発生しました: " . $e->getMessage();
    }
} else {
    $_SESSION['error_message'] = "不正なアクセスです。";
    header("Location: G2-1.php");
    exit();
}
session_destroy();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/G2-3.css">
    <title>パスワード再設定完了</title>
</head>
<body>
    <header class="header">
        <img src="img/Ciel logo.png" alt="Ciel" class="logo">
    </header>
    <div class="main">
        <h1>PASSWORD<br>RESET<br>COMPLETION</h1>
        <p><?php echo htmlspecialchars($message); ?></p>
        <button type="button" class="button is-medium" onclick="location.href='G1-1.php'">RETURN TO LOGIN</button>
    </div>
</body>
</html>
