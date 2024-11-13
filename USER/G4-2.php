<?php require_once 'db-connect.php';
    $db = new PDO($connect, USER, PASS);

	$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $user_id = 8;
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_flg'])){
        
        // $user_id = $_POST[''];
        $user_id = 8;
        $title = $_POST['title'];
        $start_date = $_POST['start'];
        $final_date = $_POST['end'];
        $memo = $_POST["memo"];
        $todo_flg = $_POST["term"];
        // $user_tag = $_POST["tag"];
        $user_tag = 7;
        $start = date('Y-m-d H:i:s', strtotime($start_date));
        $final = date('Y-m-d H:i:s', strtotime($final_date));

        $sql = 'INSERT INTO Plans (user_id, plan, start_date, final_date, memo, todo_flg, usertag_id) VALUES (:user_id, :title, :start,:final, :memo,:todo_flg,:user_tag)';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':memo', $memo);
        $stmt->bindParam(':todo_flg', $todo_flg);
        $stmt->bindParam(':user_tag', $user_tag);
        $stmt->bindParam(':start', $start);
        $stmt->bindParam(':final', $final);
        $stmt->execute();
        header("Location: G4-1.php");
        exit;
    }

   
    ?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/G4-2.css">
    <title>新しい予定</title>
</head>
<body>

<div class="header">
        <div class="cancel-button" onclick="NextPage()">キャンセル</div>
        <img src="./img/Ciel logo.png" style="margin-left:-30px;">
        <input type="submit" value="決定" form="insert" class="confirm-button">
</div>
    <form action="G4-2.php" method="POST" id="insert">

        <h2>新しい予定</h2>
        <input type="text" value="タイトルを入力" id="title" name="title" required><br><br>

        <label for="tag">タグ選択:</label>
        <?php 
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                echo $_POST['tag_id_'];
            }
           
        ?>
        <input type="button" onclick="location.href='G4-3.php'" id="tag" name="tag" value="＋"><br><br>

        <label>TERMに追加する:</label>
        <input type="radio" id="term_yes" name="term" value="1">YES
        <input type="radio" id="term_no" name="term" value="0" checked>NO<br><br>

        <label for="start">開始日時:</label>
        <input type="datetime-local" id="start" name="start" required><br><br>

        <label for="end">終了日時:</label>
        <input type="datetime-local" id="end" name="end" required><br><br>

        <textarea name="memo">メモ</textarea><br><br>
        <input type="hidden" name="user_flg" value="true">
    </form>
    <script src="script/G4-1.js"></script>
</body>
</html>
