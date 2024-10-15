<?php
// Start session to store login data
session_start();

$dsn = "LAA1517478-3rd; dbname=mysql310.phy.lolipop.lan; charset=utf8";
// $username = "LAA1517478";
// $password = "3rd1004";

// Dummy user credentials for testing (replace with a database in real cases)
$valid_username = 'LAA1517478';
$valid_password = '3rd1004';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['user'];
    $password = $_POST['password'];

    // Simple user validation (replace with a database query)
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: G1-2.php"); // Redirect to user management page after successful login
        exit;
    } else {
        $error_message = "ユーザー名、またはパスワードが間違っています。";
    }
}
?>

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
    <div class="text">
        <!-- Display error message if credentials are incorrect -->
        <?php if (!empty($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- Login form -->
        <form method="post" action="G1-2.php">
            <input type="text" class="input" name="user" placeholder=" user" required><br>
            <input type="password" class="input" name="password" placeholder=" password" required><br><br>
            <button type="submit" class="login-button">LOGIN</button>
        </form>
    </div>
</body>
</html>

<h1><?php echo $msg; ?></h1>
<?php echo $link; ?>


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
    <div class="text">
        <input type="text" class="input" name="user" placeholder=" user"><br>
        <input type="password" class="input" name="password" placeholder=" password"><br><br>
    <button class="login-button">LOGIN</button>
    </div>
</body>
</html>