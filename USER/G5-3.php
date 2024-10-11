<?php
   require 'db-connect.php';
    $db = new PDO($connect, USER, PASS);

	$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // if(!isset($_SESSION['user'])){
    //     header("Location: G3-1-1.php");
    //     exit;
    // }

    // // 初期化
    // $error = false; 
    // $errorMessage = ""; 

    //カラーのselect
    try{
        // idの取得
        // $user = $_SESSION['user'];
        // $user_id = $user['user_id'];
        $user_id = 2;
        $sql='select * from Users where user_id = :user_id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        //usertag取得
        $colorsql='select Tags.color from Tags inner join Usertags on Tags.tag_id = Usertags.tag_id inner join Users on Usertags.user_id = :user_id LIMIT 12';
        $colorstmt = $db->prepare($colorsql);
        $colorstmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $colorstmt->execute();
        $colorresult = $colorstmt->fetch(PDO::FETCH_ASSOC);
        
        if ($colorresult === false) {
        // データが見つからない場合、新しいSQLクエリを実行
            $colorsql = 'SELECT * FROM Tags LIMIT 12';
            $colorstmt = $db->prepare($colorsql);
            $colorstmt->execute();
            $colorresult = $colorstmt->fetchAll(PDO::FETCH_ASSOC);

            if ($colorresult === false) {
            // 新しいクエリも失敗した場合のエラーハンドリング
                echo "データ取得に失敗しました。";
            }

        } else {
        // 最初のクエリが成功した場合の処理
            echo "Usertagsと結合されたデータを取得しました。";
            print_r($colorresult); // デバッグ用にデータを表示
        }

    }catch(PDOException $e){
        $error = true;
        $errorMessage = "エラーが発生しました: " . $e->getMessage();
    }
    // if ($error) {
    //     echo "<p>" . $errorMessage . "</p>";
    //     header("Location: G3-1-1.php");
    //     exit;

    // }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/G5-3.css">
    <title>プロフィール設定画面</title>
</head>
<body>
    <!-- header挿入 -->
        <header class="header">
          <button type="button" onclick="history.back()" class="headerbutton">←</button>
            <img src="img/Ciel logo.png" alt="Ciel" class="logo"></a>
            <input type="submit" value="更新" form="update" class="headersubmit">
        </header>
    <div class="main">
    <!-- tag　 -->
        <form action="G5-3.php" method="POST" id="update"></form>
        <div class="tag">
        <?php
            echo '<input type="hidden" name="user_id" value="' , $user_id ,'">';
            echo "<div style='display: flex; flex-wrap: wrap;'>";
            foreach ($colorresult as $colorresult) {
                    if($colorresult === false){
                        echo 'データなし';
                    }else{
                        echo "<div style='display: inline-block; background-color: #" . htmlspecialchars($colorresult["color"])."; width: 30px; height: 30px; border-radius: 50%; margin: 5px;'></div>",'<input type="text" name="tag_name" value="', htmlspecialchars($result['tag_name']) ;
                    }
                }
                echo "</div>";
            ?>
          </div>
    </form>

    <!-- footer挿入 -->
        <footer class="footer">
        <?php
            require 'menu.php';
          ?>
        </footer>
    
</body>
</html>