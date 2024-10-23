<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <title>ログイン画面</title>
</head>
<body class="login">
    <h1>LOGIN</h1>
    <?php if (isset($error)) { echo '<p class="error">'.$error.'</p>'; } ?>
    <div class="text">
    <form method="POST" action="G1-2.php">
        <input type="text" class="input" name="user" placeholder=" user" required><br>
        <input type="password" class="input" name="password" placeholder=" password" required><br><br>
        <button type="submit" class="login-button">LOGIN</button>
    </form>
    </div>
</body>
</html>