<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start(); // セッションを開始

$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];
    $_SESSION['token'] = $token; // トークンをセッションに保存

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
    <link rel="stylesheet" href="css/G2-2.css">
    <title>パスワード再設定</title>
</head>
<body>
    <header class="header">
        <img src="img/Ciel logo.png" alt="Ciel" class="logo">
    </header>
    <div class="main">
        <h1>PASSWORD<br>RESET</h1>
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form action="G2-3.php" method="post">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token']); ?>">
            <div class="input-group">
                <label for="new_pass">new password<span class="required">*</span></label> 
                <input type="password" id="new_pass" name="new_pass" maxlength="8" required>
            </div>
            <div class="input-group">
                <label for="new_pass_confirm">new password (確認)<span class="required">*</span></label> 
                <input type="password" id="new_pass_confirm" name="new_pass_confirm" maxlength="8" required>
            </div>
            <div class="button-container">
                <button type="submit" class="button is-btn">RESET PASSWORD</button>
                <button type="button" class="button is-medium" onclick="location.href='G2-1.php'">RETURN</button>
            </div>
        </form>
    </div>
</body>
</html>
