<?php
session_start(); // セッションを開始

// セッションに必要なデータが存在しない場合は、G2-2.phpに戻す
if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_mail']) || !isset($_SESSION['new_pass'])) {
    header("Location: G2-2.php");
    exit();
}

// ユーザー名とメールアドレス、新しいパスワードを取得
$user_name = $_SESSION['user_name'];
$user_mail = $_SESSION['user_mail'];
$new_pass = $_SESSION['new_pass'];

// データベース接続
$dsn = 'mysql:host=mysql310.phy.lolipop.lan;dbname=LAA1517478-3rd;charset=utf8';
$user = 'LAA1517478';
$password = '3rd1004';

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 新しいパスワードをハッシュ化
    $hashedPass = password_hash($new_pass, PASSWORD_DEFAULT);

    // ユーザーのパスワードを更新
    $stmt = $dbh->prepare("UPDATE Users SET user_pass = :user_pass WHERE user_name = :user_name AND user_mail = :user_mail");
    $stmt->bindParam(':user_pass', $hashedPass);
    $stmt->bindParam(':user_name', $user_name);
    $stmt->bindParam(':user_mail', $user_mail);
    $stmt->execute();

    // パスワード変更が完了した場合のメッセージ
    $message = "パスワードの変更が完了しました。";
} catch (PDOException $e) {
    $message = "エラーが発生しました: " . $e->getMessage();
}

// セッションのクリア
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/G2-3.css"> <!-- スタイルシート -->
    <title>パスワード再設定完了</title>
</head>
<body>
    <div class="container">
        <h1>PASSWORD<br>RESET<br>COMPLETION</h1>
        <p><?php echo htmlspecialchars($message); ?></p>

        <button type="button" class="btn" onclick="location.href='G1-1.php'">RETURN TO LOGIN</button><!-- RETURN TO LOGINボタン -->
        </form>
    </div>
</body>
</html>
