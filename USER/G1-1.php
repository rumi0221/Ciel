<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    unset($_SESSION['user_name']);
    unset($_SESSION['user_mail']);
    unset($_SESSION['user_pass']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dsn = 'mysql:host=mysql310.phy.lolipop.lan;dbname=LAA1517478-3rd;charset=utf8';
    $user = 'LAA1517478';
    $password = '3rd1004';

    $user_name = $_POST['user_name'];
    $user_pass = $_POST['user_pass'];

    try {
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $dbh->prepare("SELECT * FROM Users WHERE user_name = :user_name");
        $stmt->bindParam(':user_name', $user_name);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($user_pass, $user['user_pass'])) {
            $_SESSION['user'] = $user;
            header("Location: G3-1-1.php");
            exit();
        } else {
            $error_message = "ユーザー名、またはパスワードが間違っています。";
        }
    } catch (PDOException $e) {
        $error_message = "エラー: " . $e->getMessage();
    }

    $dbh = null;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/G1-1.css"/>
    <title>ログイン</title>
</head>
<body>
    <!-- header挿入 -->
    <header class="header">
            <img src="img/Ciel logo.png" alt="Ciel" class="logo"></a>
</header>
    <div class="form-container">
    <h1>LOGIN</h1>
        <form action="G1-1.php" method="POST">
            <?php if(isset($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <div class="input-group">
                <label for="user_name">user</label><br>
                <input type="text" id="user_name" name="user_name" required>
            </div>
            <div class="input-group">
                <label for="user_pass">password</label><br>
                <input type="password" id="user_pass" name="user_pass" maxlength="8" required>
                <img src="img/eye.png" alt="表示切替" class="toggle-password" onclick="togglePasswordVisibility('user_pass')">
            </div>

            <!-- SIGN UPボタンをLOGINボタンの上に配置 -->
            <div class="button-container">
                <button class="button is-signup" type="button" onclick="location.href='G1-2.php'">SIGN UP</button>
            
            <!-- LOGINボタン（フォーム送信） -->
         
                <button class="button is-login" type="submit">LOGIN</button>
            </div>
            <div class="link-container">
                <a href="G2-1.php">Forgot your password?</a>
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
