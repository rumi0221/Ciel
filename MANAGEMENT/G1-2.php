<?php 
ob_start();

session_start(); ?><!--  // セッションの開始 -->

<?php
// データベース接続情報
$host = "mysql310.phy.lolipop.lan"; // データベースのホスト名
$dbname = "LAA1517478-3rd"; // データベース名
$username = "LAA1517478"; // データベースのユーザ名
$password = "3rd1004"; // データベースのパスワード

// MySQLデータベースに接続
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベース接続エラー: " . $e->getMessage());
}

// ログインフォームが送信されたか確認
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUser = $_POST['user'];
    $inputPassword = $_POST['password'];

    // データベースからユーザー情報を取得
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE user_name = :user AND user_mail = :mail");
    $stmt->execute(['user' => 'Admin', 'mail' => 'admin@s.ac.jp']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 入力されたパスワードとデータベースのパスワードを比較
    if ($user && password_verify($inputPassword, password_hash('Nimda', PASSWORD_DEFAULT))) {
        $_SESSION['loggedin'] = true;
        $_SESSION['user_name'] = $user['user_name'];
        header("Location:dashboard.php"); // ログイン成功時のリダイレクト先
        exit;
    } else {
        $error = "ユーザー名またはパスワードが間違っています。";
        include('G1-1.php');
    }
}
ob_end_flush();
?>