<?php 
ob_start();

session_start(); ?>

<?php
// データベース接続
$servername = "mysql310.phy.lolipop.lan";
$username = "LAA1517478";
$password = "3rd1004";
$dbname = "LAA1517478-3rd";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

// ユーザーデータの取得
$sql = "SELECT user_id, user_name, user_mail, user_pass, last_history, delete_flg FROM Users";
$result = $conn->query($sql);
?>
<?php
    // 削除処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        if (!empty($_POST['user_ids'])) {
            $ids = implode(',', array_map('intval', $_POST['user_ids']));
            // $sql = "DELETE FROM Users WHERE user_id IN ($ids)";
            // デリーとフラグ更新
            $sql = "UPDATE Users SET delete_flg = true WHERE user_id IN ($ids)";
            // $conn->query($sql);
            // クエリの実行とエラーチェック
            if ($conn->query($sql) === TRUE) {
                $_SESSION["message"] = "選択されたユーザーが削除されました。";
            } else {
                $_SESSION["message"] = "エラーが発生しました: " . $conn->error;
            }
        } else {
            $_SESSION["message"] = "削除するユーザーを選択してください。";
        }
        header("location:dashboard.php");
        exit;
    }
    ?>
</div>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <title>ユーザー管理画面</title>
</head>
<body>

<div class="header">
    <p><img src="./img/Ciel logo.png" width="217.5" height="120" alt="Ciel"></p>
</div>

<div class="background-gradient">
<div class="table-container">
    <h2>USER</h2>
    <div class="error-message">
        <?php if (!empty($_SESSION['message'])):?>
        <p style="color:red;">
            <?php echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8')?></p>
            <?php unset($_SESSION['message']); //表示後にメッセージを削除
                endif;
            ?>
    </div>
    <form method="POST" action="">
    <button type="submit" name="delete" class="delete-btn">🗑delete</button>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>ユーザーID</th>
                    <th>ユーザー名</th>
                    <th>メールアドレス</th>
                    <!-- <th>パスワード</th> -->
                    <th>最終履歴</th>
                    <th>削除フラグ</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><input type="checkbox" name="user_ids[]" value="<?= $row['user_id'] ?>"></td>
                            <td><?= $row['user_id'] ?></td>
                            <td><?= htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['user_mail'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= $row['last_history'] ?></td>
                            <td><?= htmlspecialchars($row['delete_flg'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">データがありません</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </form>
</div>
</div>

</body>
</html>

<?php
$conn->close();
?>
