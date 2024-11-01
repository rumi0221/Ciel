<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start(); // セッションを開始

// エラーメッセージ用の変数
$error_message = '';

// POSTメソッドで送信された場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'];
    $user_mail = $_POST['user_mail'];

    // データベース接続
    $dsn = 'mysql:host=mysql310.phy.lolipop.lan;dbname=LAA1517478-3rd;charset=utf8';
    $user = 'LAA1517478';
    $password = '3rd1004';
    try {
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // ユーザー名とメールアドレスが登録されているか確認
        $stmt = $dbh->prepare("SELECT COUNT(*) FROM Users WHERE user_name = :user_name AND user_mail = :user_mail");
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':user_mail', $user_mail);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // セッションにユーザー情報を保存
            $_SESSION['user_name'] = $user_name;
            $_SESSION['user_mail'] = $user_mail;

            // ユーザー名とメールアドレスが一致した場合、G2-2.phpに遷移
            header("Location: G2-2.php");
            exit();
        } else {
            // エラーメッセージを設定
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
