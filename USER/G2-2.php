<?php
session_start();
$error_message = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $dsn = 'mysql:host=mysql310.phy.lolipop.lan;dbname=LAA1517478-3rd;charset=utf8';
    $user = 'LAA1517478';
    $password = '3rd1004';

    try {
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $dbh->prepare("SELECT user_name FROM Users WHERE token = :token AND expires_at > NOW()");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $_SESSION['error_message'] = "無効なトークン、またはトークンの有効期限が切れています。";
            header("Location: G2-1.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "エラーが発生しました: " . $e->getMessage();
        header("Location: G2-1.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "トークンが見つかりません。";
    header("Location: G2-1.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/G2-2.css"/> <!-- スタイルシート -->
    <link href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" rel="stylesheet">
    <title>パスワード再設定</title>
</head>
<body>
    <!-- header挿入 -->
    <header class="header">
        <img src="img/Ciel logo.png" alt="Ciel" class="logo">
    </header>
    <div class="main">
        <h1>PASSWORD<br>RESET</h1>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div> <!-- エラーメッセージ表示 -->
        <?php endif; ?>

        <form action="G2-2.php" method="post"> <!-- 現在のページにPOSTメソッドで送信 -->
            <div class="input-group">
                <label for="new_pass">new password<span class="required">*</span></label><br>
                <input type="password" id="new_pass" name="new_pass" placeholder="新しいパスワードを入力してください" maxlength="8" required>
                <img src="img/eye.png" alt="表示切替" class="toggle-password" onclick="togglePasswordVisibility('new_pass')">
            </div>

            <div class="input-group">
                <label for="new_pass_confirm">new password (確認)<span class="required">*</span></label><br>
                <input type="password" id="new_pass_confirm" name="new_pass_confirm" placeholder="新しいパスワードをもう一度入力してください" maxlength="8" required>
                <img src="img/eye.png" alt="表示切替" class="toggle-password" onclick="togglePasswordVisibility('new_pass_confirm')">
            </div>

            <div class="button-container">
                <button type="submit" class="button is-btn">RESET PASSWORD</button> <!-- RESET PASSWORDボタン -->
                <button type="button" class="button is-medium" onclick="location.href='G2-1.php'">RETURN</button> <!-- RETURNボタン -->
            </div>
        </form>
    </div>

    <script>
        function togglePasswordVisibility(fieldId) {
            const passwordField = document.getElementById(fieldId);
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
        }
    </script>

</body>
</html>
