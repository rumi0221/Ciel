<?php session_start();?>
<?php
    require_once 'db-connect.php';
    $db = new PDO($connect, USER, PASS);

	$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $user = $_SESSION['user'];
    $user_id = $user['user_id'];

    //crud分け
if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['crud'] == "update" ){
    $user = $_SESSION['user'];
    $user_id = $user['user_id'];
    //update処理 OR updatehtml
    if($_SERVER['REQUEST_METHOD'] == 'POST' && ($_POST['user_flg'] == 'true' && $_POST['tag_returnflg'] == 'false')){

           //update処理
        if(isset($_POST['tag'])){
            $tag = $_POST["tag"];
        }else{
            $tag = 13;
        }

        $plan_id = $_POST['id'];
        $title = $_POST['title'];
        $start_date = $_POST['start'];
        $final_date = $_POST['end'];
        $memo = $_POST["memo"];
        $todo_flg = $_POST["term"];
        $start = date('Y-m-d H:i:s', strtotime($start_date));
        $final = date('Y-m-d H:i:s', strtotime($final_date));

            $sql = 'update Plans set 
                    plan = :title, start_date = :start, final_date = :final, memo = :memo, todo_flg = :todo_flg, usertag_id = :tag
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
        
        }else{
        //G4-1からのselect

        $plan_id = $_POST['plan_id'];
        global $db;
    
        $stmt = $db->prepare("
            SELECT * FROM Plans 
            WHERE plan_id = :plan_id
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
                <img src="./img/Ciel logo.png" style="margin-left:-20px;">
                <input type="submit" value="更新" form="update" class="confirm-button">
        </div>
            <form action="G4-2.php" method="POST" id="update">

                <h2>予定更新</h2>
            <?php
            
                echo '<input type="text" value="' .$plans['plan'].'" id="title" name="title" required><br><br>';

                echo '<div class="border"></div>';

                echo '<div class = "tag_button">';
                echo "<span class='tag'>タグ選択:</span>";
                
                if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tag_id'])){
                    //更新後
                    $tag_id = $_POST['tag_id'];
                    $colorsql = 'SELECT color FROM Tags where tag_id = :tag_id';
                    $colorstmt = $db->prepare($colorsql);
                    $colorstmt->bindParam(':tag_id', $tag_id);
                    $colorstmt->execute();
                    $colorresults = $colorstmt->fetch(PDO::FETCH_ASSOC);
                    
                    echo "<span style='display: inline-block; background-color: #" . htmlspecialchars($colorresults["color"])."; width: 20px; height: 20px; border-radius: 50%; margin: 5px 0% 0% 50%;'></span>";
                    echo '<input type="button" onclick="location.href=\'G4-3.php?plan_id=' . $plan_id . '\'" id="tag" name="tag" value="＋"><br>';
                    echo '</div>';
                    echo '<input type="hidden" name="tag" value="' , $tag_id ,'">';
                }else{
                    //更新前
                    $color_id = $plans['usertag_id'];
                    $colorsql = 'SELECT color FROM Tags where tag_id = :tag_id';
                    $colorstmt = $db->prepare($colorsql);
                    $colorstmt->bindParam(':tag_id', $color_id);
                    $colorstmt->execute();
                    $colorresults = $colorstmt->fetch(PDO::FETCH_ASSOC);
                    // echo '<div class = "tag_button">';
                    echo "<span style='display: inline-block; background-color: #" . htmlspecialchars($colorresults["color"])."; width: 20px; height: 20px; border-radius: 50%; margin: 5px 0% -1% 50%;'></span>";
                    echo '<input type="button" onclick="location.href=\'G4-3.php?plan_id=' . $plan_id . '\'" id="tag" name="tag" value="＋"><br>';
                    echo '</div>';
                }

                echo '<div class="border"></div>';
                    
                echo "<label>TERMに追加する:</label>";
                echo '<input type="radio" id="term_yes" name="term" value="1">YES';
                echo '<input type="radio" id="term_no" name="term" value="0" checked>NO<br><br>';

                echo '<div class="border"></div>';

                echo '<label for="start">開始日時:</label>';
                echo '<input type="datetime-local" id="start" name="start" required value="' , $plans['start_date'] ,'" ><br>';

                echo '<label for="end">終了日時:</label>';
                echo '<input type="datetime-local" id="end" name="end" value="' , $plans['final_date'] ,'" required><br><br>';

                echo '<div class="border"></div>';

                echo '<textarea name="memo">' .$plans['memo']. '</textarea><br><br>';
                echo '<input type="hidden" name="id" value="' .$plans['plan_id']. '">';
                ?>
                <input type="hidden" name="user_flg" value="true">
                <input type="hidden" name="crud" value="update">
                <input type="hidden" name="tag_returnflg" value="false">
            </form>

            <!-- 削除 -->

            <div class = "footer">
            <form action="G4-2.php" method = "POST">
                <input type = "submit" value = "削除" class="DeleteBtn">
                <?php echo '<input type="hidden" name="plan_id" value="' .$plans['plan_id']. '">';
                      echo '<input type="hidden" name="user_id" value="' .$user_id. '">';
                ?>
                <input type="hidden" name="crud" value="delete">
            </form>
            </div>

            <script src="script/G4-2.js"></script>
            </body>
            </html>
<?php
    }

}else if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['crud'] == "insert"){
    //insert処理
    if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['user_flg'] == 'true'){

        if(isset($_POST['tag'])){
            $tag = $_POST["tag"];
        }else{
            $tag = 13;
        }

        $user = $_SESSION['user'];
        
        $user_id = $user['user_id'];
        $title = $_POST['title'];
        $start_date = $_POST['start'];
        $final_date = $_POST['end'];
        $memo = $_POST["memo"];
        $todo_flg = $_POST["term"];
        $start = date('Y-m-d H:i:s', strtotime($start_date));
        $final = date('Y-m-d H:i:s', strtotime($final_date));

            $sql = 'INSERT INTO Plans 
                    (user_id, plan, start_date, final_date, memo, todo_flg, usertag_id) 
                    VALUES (:user_id, :title, :start,:final, :memo,:todo_flg,:tag)';
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

    }else if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // inserthtml
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
            <img src="./img/Ciel logo.png" style="margin-left:-20px;">
            <input type="submit" value="決定" form="insert" class="confirm-button">
    </div>
        <form action="G4-2.php" method="POST" id="insert">

            <h2>新しい予定</h2>
            <input type="text" placeholder="タイトルを入力" id="title" name="title" required><br><br>

            <div class="border"></div>

            <div class = "tag_button">
            <span class="tag">タグ選択:</span>
            <?php 
                if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tag_id'])){

                    $tag_id = $_POST['tag_id'];
                    $colorsql = 'SELECT color FROM Tags where tag_id = :tag_id';
                    $colorstmt = $db->prepare($colorsql);
                    $colorstmt->bindParam(':tag_id', $tag_id);
                    $colorstmt->execute();
                    $colorresults = $colorstmt->fetch(PDO::FETCH_ASSOC);

                    $Usertagsql = 'SELECT tag_name FROM Usertags where tag_id = :tag_id and user_id = :user_id';
                    $Usertagstmt = $db->prepare($Usertagsql);
                    $Usertagstmt->bindParam(':tag_id', $tag_id);
                    $Usertagstmt->bindParam(':user_id', $user_id);
                    $Usertagstmt->execute();
                    $Usertagresults = $Usertagstmt->fetch(PDO::FETCH_ASSOC);

                    // echo '<div class = "tag_button">';
                    ?>
                    <input class="button" onclick="location.href='G4-3.php'" id="tag" name="tag" value="＋">
                    <?php
                    echo "<span style='display: inline-block; background-color: #" . htmlspecialchars($colorresults["color"])."; width: 20px; height: 20px; border-radius: 50%;margin: 5px 0% -1% -15%;'></span>";
                    echo "<span class='tagname'>".$Usertagresults["tag_name"]."</span><br><br>";
                    echo '<input type="hidden" name="tag" value="' , $tag_id ,'">';
                }else{
                    ?>
                    <input class="button" onclick="location.href='G4-3.php'" id="tag" name="tag" value="＋"><br><br>
                    <?php
                }
            
            ?>
            
            </div>

            <div class="border"></div>

            <label>TERMに追加する:</label>
            <input type="radio" id="term_yes" name="term" value="1">YES
            <input type="radio" id="term_no" name="term" value="0" checked>NO<br><br>

            <div class="border"></div>

            <label for="start">開始日時:</label>
            <input type="datetime-local" id="start" name="start" required><br>

            <label for="end">終了日時:</label>
            <input type="datetime-local" id="end" name="end" required><br><br>

            <div class="border"></div>

            <textarea name="memo" placeholder="メモ"></textarea><br><br>
            <input type="hidden" name="user_flg" value="true">
            <input type="hidden" name="crud" value="insert">
            <input type="hidden" name="tag_returnflg" value="false">
        </form>
        <script src="script/G4-2.js"></script>
        </body>
        </html>
<?php
    }
}else if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['crud'] == "delete"){
    //delete処理
        $plan_id = $_POST['plan_id'];
        $sql = 'Delete FROM Plans where plan_id = :plan_id';
        $Deletestmt = $db->prepare($sql);
        $Deletestmt->bindParam(':plan_id', $plan_id);
        $Deletestmt->execute();
        header("Location: G4-1.php");
        exit;
}else{
    echo 'error';
}
?>