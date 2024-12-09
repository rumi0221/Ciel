<?php session_start();?>
<?php // データベース接続設定
require_once 'db-connect.php';

$db = new PDO($connect, USER, PASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user_id = 8;

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update']) ){
    //update
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_flg'])){
        //G4-1からの遷移か？
        if(isset($_POST['id'])){

            if(isset($_POST['tag'])){
                $tag = $_POST["tag"];
            }else{
                $tag = 13;
            }
            // $user_id = $_POST[''];
            $user_id = 8;
            $plan_id = $_POST['id'];
            $title = $_POST['title'];
            $start_date = $_POST['start'];
            $final_date = $_POST['end'];
            $memo = $_POST["memo"];
            $todo_flg = $_POST["term"];
            $start = date('Y-m-d H:i:s', strtotime($start_date));
            $final = date('Y-m-d H:i:s', strtotime($final_date));
    
            //updateに変更
                $sql = 'update Plans set plan = :title, start_date = :start, final_date = :final, memo = :memo, todo_flg = :todo_flg, usertag_id = :tag
                        where plan_id=:plan_id && user_id=:user_id';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':plan_id', $plan_id);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':memo', $memo);
                $stmt->bindParam(':todo_flg', $todo_flg);
                $stmt->bindParam(':tag', $tag);
                $stmt->bindParam(':start', $start);
                $stmt->bindParam(':final', $final);
                $stmt->execute();
                header("Location: G4-1.php");
                exit;
        }
        ?>
    <?php
   }else{
        //G4-1からのselect
        $plan_id = $_POST['id'];
        global $db;
    
        $stmt = $db->prepare("
            SELECT 
                *
            FROM 
                Plans 
            WHERE  
                plan_id = :plan_id
        ");
        $stmt->bindParam(':plan_id', $plan_id);
        $stmt->execute();
    
        $plans = $stmt->fetch(PDO::FETCH_ASSOC);

    ?>
    <!DOCTYPE html>
    <html lang="ja">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="./css/G4-2.css">
            <title>予定更新</title>
        </head>
        <body>

        <div class="header">
                <div class="cancel-button" onclick="NextPage()">キャンセル</div>
                <img src="./img/Ciel logo.png" style="margin-left:-30px;">
                <input type="submit" value="更新" form="update" class="confirm-button">
        </div>
            <form action="G4-2.php" method="POST" id="update">

                <h2>予定更新</h2>
            <?php
            //echo入れる
                echo '<input type="text" value="' .$plans['plan'].'" id="title" name="title" required><br><br>';

                echo "<label for='tag'>タグ選択:</label>";
                
                if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tag_id'])){

                    $tag_id = $_POST['tag_id'];
                    $colorsql = 'SELECT color FROM Tags where tag_id = :tag_id';
                    $colorstmt = $db->prepare($colorsql);
                    $colorstmt->bindParam(':tag_id', $tag_id);
                    $colorstmt->execute();
                    $colorresults = $colorstmt->fetch(PDO::FETCH_ASSOC);
                    echo "<div style='display: flex; flex-wrap: wrap;'>";
                    echo "<div style='display: inline-block; background-color: #" . htmlspecialchars($colorresults["color"])."; width: 20px; height: 20px; border-radius: 50%; margin: 5px;'></div>";
                    echo "</div>";
                    echo '<input type="hidden" name="tag" value="' , $tag_id ,'">';
                }
                
                echo "<input type='button' onclick=\"location.href='G4-3.php'\" id='tag' name='tag' value='＋'><br><br>";
                // echo '<input type="button" onclick="location.href='G4-3.php'" id="tag" name="tag" value="＋"><br><br>';

                echo "<label>TERMに追加する:</label>";
                echo '<input type="radio" id="term_yes" name="term" value="1">YES';
                echo '<input type="radio" id="term_no" name="term" value="0" checked>NO<br><br>';

                echo '<label for="start">開始日時:</label>';
                echo '<input type="datetime-local" id="start" name="start" required value="' , $plans['start_date'] ,'" ><br><br>';

                echo '<label for="end">終了日時:</label>';
                echo '<input type="datetime-local" id="end" name="end" value="' , $plans['final_date'] ,'" required><br><br>';

                echo '<textarea name="memo">' .$plans['memo']. '</textarea><br><br>';
                echo '<input type="hidden" name="id" value="' .$plans['plan_id']. '">';
                ?>
                <input type="hidden" name="user_flg" value="true">
                <input type="hidden" name="update" value="true">
            </form>
            <script src="script/G4-1.js"></script>
            </body>
            </html>
<?php
    }
        
?>
<?php
}else{
    //insert
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_flg'])){
        if(isset($_POST['tag'])){
            $tag = $_POST["tag"];
        }else{
            $tag = 13;
        }
        // $user_id = $_POST[''];
        $user_id = 8;
        $title = $_POST['title'];
        $start_date = $_POST['start'];
        $final_date = $_POST['end'];
        $memo = $_POST["memo"];
        $todo_flg = $_POST["term"];
        $start = date('Y-m-d H:i:s', strtotime($start_date));
        $final = date('Y-m-d H:i:s', strtotime($final_date));

            $sql = 'INSERT INTO Plans (user_id, plan, start_date, final_date, memo, todo_flg, usertag_id) VALUES (:user_id, :title, :start,:final, :memo,:todo_flg,:tag)';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':memo', $memo);
            $stmt->bindParam(':todo_flg', $todo_flg);
            $stmt->bindParam(':tag', $tag);
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
                if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tag_id'])){

                    $tag_id = $_POST['tag_id'];
                    $colorsql = 'SELECT color FROM Tags where tag_id = :tag_id';
                    $colorstmt = $db->prepare($colorsql);
                    $colorstmt->bindParam(':tag_id', $tag_id);
                    $colorstmt->execute();
                    $colorresults = $colorstmt->fetch(PDO::FETCH_ASSOC);
                    echo "<div style='display: flex; flex-wrap: wrap;'>";
                    echo "<div style='display: inline-block; background-color: #" . htmlspecialchars($colorresults["color"])."; width: 20px; height: 20px; border-radius: 50%; margin: 5px;'></div>";
                    echo "</div>";
                    echo '<input type="hidden" name="tag" value="' , $tag_id ,'">';
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
<?php
}
?>