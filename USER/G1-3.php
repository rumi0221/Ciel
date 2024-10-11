<?php
session_start(); // セッションを開始

// フォームからのデータをセッションに保存
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['user_name'] = $_POST['user_name'];
    $_SESSION['user_mail'] = $_POST['user_mail'];
    $_SESSION['user_pass'] = $_POST['user_pass'];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/G1-3.css"/>
    <title>登録内容の確認</title>
</head>
<body>
<div class="main">
    <h1>REGISTER</h1>
    <table>
        <tr>
            <td>user　:　</td>
            <td><?php echo htmlspecialchars($_SESSION['user_name']); ?></td>
        </tr>
        <tr>
            <td>email　:　</td>
            <td><?php echo htmlspecialchars($_SESSION['user_mail']); ?></td>
        </tr>
        <tr>
            <td>password　:　</td>
            <td>********</td>
        </tr>
    </table>

    <div class="button-container">
        <form action="G1-4.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="user_name" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>">
            <input type="hidden" name="user_mail" value="<?php echo htmlspecialchars($_SESSION['user_mail']); ?>">
            <input type="hidden" name="user_pass" value="<?php echo htmlspecialchars($_SESSION['user_pass']); ?>">

            <button type="submit" class="btn-register">CREATE ACCOUNT</button>
        </form>
        <!-- RETURNボタンの修正 -->
        <button class="btn-back" onclick="location.href='G1-2.php'">RETURN</button>
    </div>
</div>

</body>
</html>
