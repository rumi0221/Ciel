<!-- <!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <title>ユーザー管理画面</title>
</head>
<body class="user">
    <h2>USER</h2>
        <input type="button" class="delete" value="delete">
    <table>
        
    </table>
</body>
</html> -->

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

// 削除処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    if (!empty($_POST['user_ids'])) {
        $ids = implode(',', array_map('intval', $_POST['user_ids']));
        $sql = "DELETE FROM users WHERE id IN ($ids)";
        $conn->query($sql);
    }
}

// ユーザーデータの取得
$sql = "SELECT user_id, user_name, user_email, user_pass, last_history FROM Users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー管理画面</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            background-color: #d5e0ff;
            padding: 20px;
            text-align: center;
        }
        .table-container {
            margin: 20px auto;
            width: 80%;
            border-collapse: collapse;
        }
        table {
            width: 100%;
            border: 1px solid #ddd;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .delete-btn {
            color: blue;
            cursor: pointer;
            text-decoration: underline;
        }
        .delete-btn:hover {
            color: darkblue;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Ciel</h1>
</div>

<div class="table-container">
    <h2>USER</h2>
    <form method="POST" action="">
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>ユーザーID</th>
                    <th>ユーザー名</th>
                    <th>メールアドレス</th>
                    <th>パスワード</th>
                    <th>最終履歴</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><input type="checkbox" name="user_ids[]" value="<?= $row['id'] ?>"></td>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['user_email'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['user_pass'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= $row['last_history'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">データがありません</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <button type="submit" name="delete" class="delete-btn">delete</button>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>
