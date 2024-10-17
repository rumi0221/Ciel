<?php session_start();?>
<?php
   require 'db-connect.php';
    $db = new PDO($connect, USER, PASS);

	$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if(!isset($_SESSION['user'])){
        header("Location: G3-1-1.php");
        exit;
    }

    // 初期化
    $error = false; 
    $errorMessage = ""; 

    //update(新規登録時insertされる)
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_flg'])){

        for ($i = 1; $i <= 12; $i++){
            if (isset($_POST['tag_id_' . $i]) && isset($_POST['tag_name_' . $i])) {
        
        $user_id = $_POST['user_id'];
        $tag_id = $_POST['tag_id_'.$i];
        $tag_name = $_POST['tag_name_'.$i]; 
    
        try{
            $sql = 'update Usertags set tag_name=:tag_name where user_id=:user_id and tag_id=:tag_id';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':tag_name', $tag_name, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':tag_id', $tag_id, PDO::PARAM_INT);
            $stmt->execute();
            header("Location: G5-1.php");
            exit;
        } catch(PDOException $e) {
            $error = true;
            $errorMessage = "エラーが発生しました: " . $e->getMessage();
        } catch(IconException $e) {
            $error = true;
            $errorMessage = "エラーが発生しました: " . $e->getMessage();
        }
        }
    }
    header("Location: G5-3.php");
    }
    //select
    try{
        // idの取得
        $user = $_SESSION['user'];
        $user_id = $user['user_id'];

        $sql='select * from Users where user_id = :user_id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        //usertag取得
        $colorsql='select * from Usertags where user_id = :user_id';
        $usertag = $db->prepare($colorsql);
        $usertag->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $usertag->execute();
        $usertags = $usertag->fetchAll(PDO::FETCH_ASSOC);
        
        if ($usertags === false) {
        // データが見つからない場合、新しいSQL実行
            $colorsql = 'SELECT * FROM Tags LIMIT 12';
            $colorstmt = $db->prepare($colorsql);
            $colorstmt->execute();
            $colorresults = $colorstmt->fetchAll(PDO::FETCH_ASSOC);

            if ($colorresults === false) {
            // 失敗した場合のエラーハンドリング
                echo "データ取得に失敗しました。";
            }

        } else {
        // 最初のクエリが成功した場合の処理
            $colorsql = 'SELECT * FROM Tags LIMIT 12';
            $colorstmt = $db->prepare($colorsql);
            $colorstmt->execute();
            $colorresults = $colorstmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $i = 0;
        foreach($usertags as  $usertag){
            $tag_id[$i] =  $usertag["tag_id"];
            $tag_name[$i] =  $usertag["tag_name"];
            $i++;
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
    <title>tag設定画面</title>
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
        <form action="G5-3.php" method="POST" id="update">
        <div class="tag">
        <?php
            echo '<input type="hidden" name="user_id" value="' , $user_id ,'">';
            echo '<input type="hidden" name="user_flg" value="true">';

            if($tag_name){
                //usertagテーブル出力
                $i = 0;
                foreach ($colorresults as $colorresult) {
                    // echo '<input type="hidden" name="tag_id" value="' , $tag_id ,'">';    
                    echo "<div style='display: flex; flex-wrap: wrap;'>";
                    echo "<div style='display: inline-block; background-color: #" . htmlspecialchars($colorresult["color"])."; width: 20px; height: 20px; border-radius: 50%; margin: 5px;'></div>";
                    // echo "<div style='display: inline-block; background-color: #" . htmlspecialchars($color[$i])."; width: 20px; height: 20px; border-radius: 50%; margin: 5px;'></div>";
                    // echo '<input type="text" name="tag_name_"'.$j. 'value="', htmlspecialchars($usertag['tag_name']),'">';
                    // echo "</div>";
                    // echo '<input type="hidden" name="tag_id_"'.$j. ' value="',($usertag['tag_id']),'">';
                    echo '<input type="text" name="tag_name_' . $i+1 . '"value="'. htmlspecialchars($tag_name[$i]).'">';
                    echo '<input type="hidden" name="tag_id_' . $i+1 . '"value="'. htmlspecialchars($tag_id[$i]).'">';
                    echo "</div>";
                    $i++;
                    if($i == 13){
                        break;
                    }
                }

                //tagsテーブル出力
            }else{
                foreach ($colorresults as $colorresult) {
                    echo '<input type="hidden" name="tag_id" value="' , $tag_id ,'">';    
                    echo "<div style='display: flex; flex-wrap: wrap;'>";
                    echo "<div style='display: inline-block; background-color: #" . htmlspecialchars($colorresult["color"])."; width: 20px; height: 20px; border-radius: 50%; margin: 5px;'></div>";
                    // echo '<input type="text" name="tag_name" value="', htmlspecialchars($colorresult['tag_name']),'">';
                    echo "usertag取れてない";
                    echo "</div>";
                }
            }  
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