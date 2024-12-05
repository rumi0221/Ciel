<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start(); // セッションを開始

// エラーメッセージをセッションから取得
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']); // 一度表示したら削除

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'];
    $user_mail = $_POST['user_mail'];

    $dsn = 'mysql:host=mysql310.phy.lolipop.lan;dbname=LAA1517478-3rd;charset=utf8';
    $user = 'LAA1517478';
    $password = '3rd1004';

    try {
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $dbh->prepare("SELECT COUNT(*) FROM Users WHERE user_name = :user_name AND user_mail = :user_mail");
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':user_mail', $user_mail);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $token = bin2hex(random_bytes(16));
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $stmt = $dbh->prepare("UPDATE Users SET token = :token, expires_at = :expires_at WHERE user_name = :user_name AND user_mail = :user_mail");
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':expires_at', $expires_at);
            $stmt->bindParam(':user_name', $user_name);
            $stmt->bindParam(':user_mail', $user_mail);
            $stmt->execute();

            $reset_link = "http://aso2201127.fem.jp/Ciel/USER/G2-2.php?token=$token";
            $subject = "パスワード再設定リンク";
            $message = "以下のリンクをクリックしてパスワードを再設定してください:\n\n$reset_link";
            $headers = "From: no-reply@yourdomain.com";

            if (mail($user_mail, $subject, $message, $headers)) {
                $error_message = "再設定リンクを送信しました。メールをご確認ください。";
            } else {
                $error_message = "メール送信に失敗しました。";
            }
        } else {
            $error_message = "ユーザー名またはメールアドレスが一致しません。";
        }
    } catch (PDOException $e) {
        $error_message = "エラーが発生しました: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/G2-1.css"/> <!-- スタイルシート -->
    <link href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" rel="stylesheet">
    <title>パスワード再設定</title>
</head>
<body>
    <!-- header挿入 -->
    <header class="header">
            <img src="img/Ciel logo.png" alt="Ciel" class="logo"></a>
</header>
    <div class="main">
        <h1>PASSWORD<br>RESET</h1>
        <form action="G2-1.php" method="POST">
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div> <!-- エラーメッセージ表示 -->
            <?php endif; ?>
                <div class="input-group">
                    <label for="user_name">user</label><br>
                    <input type="text" id="user_name" name="user_name" maxlength="32" required>
                </div>
                <div class="input-group">
                    <label for="user_mail">email</label><br>
                    <input type="email" id="user_mail" name="user_mail" maxlength="50" required>
                </div>
                <div class="button-container">
                    <button class="button is-btn" type="submit">NEXT</button> <!-- NEXTボタン -->
                <div class="link-container">
                    <button class="button is-medium" type="button" onclick="location.href='G1-1.php'">RETURN</button> <!-- RETURNボタン -->
                </div>
        </form>
            </div>
    </div>
</body>
</html>
