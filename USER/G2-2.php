<?php
session_start(); // セッションを開始

// セッションにユーザー名とメールアドレスが存在しない場合は、G2-1.phpに戻す
if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_mail'])) {
    header("Location: G2-1.php");
    exit();
}

// エラーメッセージ用の変数
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_pass = $_POST['new_pass'];
    $new_pass_confirm = $_POST['new_pass_confirm'];

    // パスワードの文字数チェック
    if (strlen($new_pass) < 6) {
        $error_message = "パスワードは6文字以上である必要があります。";
    } elseif ($new_pass !== $new_pass_confirm) {
        $error_message = "パスワードが一致しません。";
    } else {
        // セッションにパスワードを保存し、G2-3.phpに遷移
        $_SESSION['new_pass'] = $new_pass;
        header("Location: G2-3.php");
        exit();
    }
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
                <label for="new_pass">新しいパスワード<span class="required">*</span></label><br>
                <input type="password" id="new_pass" name="new_pass" placeholder="新しいパスワードを入力してください" maxlength="8" required>
                <img src="img/eye.png" alt="表示切替" class="toggle-password" onclick="togglePasswordVisibility('new_pass')">
            </div>

            <div class="input-group">
                <label for="new_pass_confirm">新しいパスワード(確認)<span class="required">*</span></label><br>
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
