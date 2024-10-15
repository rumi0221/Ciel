<?php

session_start(); 
// 登録完了時にセッションデータをクリア
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    unset($_SESSION['user_name']);
    unset($_SESSION['user_mail']);
    unset($_SESSION['user_pass']);
}

$dsn = 'mysql:host=mysql310.phy.lolipop.lan;dbname=LAA1517478-3rd;charset=utf8';
$user = 'LAA1517478';
$password = '3rd1004';

$user_name = $_POST['user_name'];
$user_mail = $_POST['user_mail'];
$user_pass = $_POST['user_pass'];
$hashedPass = password_hash($user_pass, PASSWORD_DEFAULT);

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // メールアドレスの存在チェック
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM Users WHERE user_mail = :user_mail");
    $stmt->bindParam(':user_mail', $user_mail);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $message = "このメールアドレスは既に登録されています。";
    } else {
        // データベースにユーザー情報を挿入
        $stmt = $dbh->prepare("INSERT INTO Users (user_name, user_mail, user_pass) VALUES (:user_name, :user_mail, :user_pass)");
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':user_mail', $user_mail);
        $stmt->bindParam(':user_pass', $hashedPass);
        $stmt->execute();

        // 登録が完了したらユーザー情報を取得してセッションに保存
        $stmt = $dbh->prepare("SELECT * FROM Users WHERE user_mail = :user_mail");
        $stmt->bindParam(':user_mail', $user_mail);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $_SESSION['user'] = $user;  // 既に開始されたセッションにユーザー情報を保存
        $user_id = $user['user_id'];  // 新規登録されたユーザーのIDを取得

        // Tagsテーブルから全てのタグを取得
        $stmt = $dbh->prepare("SELECT * FROM Tags LIMIT 12");
        $stmt->execute();
        $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Usertagsテーブルに挿入
        foreach ($tags as $tag) {
            // 既に同じ user_id と tag_id のペアが存在するかチェック
            $stmt = $dbh->prepare("SELECT COUNT(*) FROM Usertags WHERE user_id = :user_id AND tag_id = :tag_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':tag_id', $tag['tag_id']);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            // 存在しない場合のみ INSERT を実行
            if ($count == 0) {
                $stmt = $dbh->prepare("INSERT INTO Usertags (user_id, tag_id, tag_name) VALUES (:user_id, :tag_id, :tag_name)");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':tag_id', $tag['tag_id']);
                $stmt->bindParam(':tag_name', $tag['tag_name']);
                $stmt->execute();
            }
        }

        $message = "登録が完了しました。";
    }
} catch (PDOException $e) {
    $message = "エラーが発生しました: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/G1-4.css">
    <title>登録完了</title>
</head>
<body>
    <div class="container">
    <h1>REGISTER<br>COMPLETION</h1>
        <p><?php echo $message; ?></p>
        
        <button class="button is-medium" type="button" onclick="location.href='G1-1.php'">RETURN TO LOGIN</button>
    </div>
</body>
</html>
