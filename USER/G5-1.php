<?php session_start();?>
<?php
    require_once 'db-connect.php';
    $db = new PDO($connect, USER, PASS);

	$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if(!isset($_SESSION['user'])){
        header("Location: G1-1.php");
        exit;
    }

    
  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout_flg']) == 'true'){
        session_destroy();
        header("Location: G5-1.php");
        exit;
    }


    // 初期化
    $error = false; 
    $errorMessage = ""; 

    try{
        // idの取得
        $user = $_SESSION['user'];
        $user_id = $user['user_id'];

        $sql='select * from Users where user_id = :user_id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // データが見つからない場合、新しいSQL実行
            $colorsql = 'SELECT * FROM Tags LIMIT 12';
            $colorstmt = $db->prepare($colorsql);
            $colorstmt->execute();
            $colorresult = $colorstmt->fetchAll(PDO::FETCH_ASSOC); // 複数の行を取得する場合

            if ($colorresult === false) {
            // 新しいクエリも失敗した場合のエラーハンドリング
                echo "データ取得に失敗しました。";
            }

    }catch(PDOException $e){
        $error = true;
        $errorMessage = "エラーが発生しました: " . $e->getMessage();
    }
    if ($error) {
        echo "<p>" . $errorMessage . "</p>";
        header("Location: G3-1-1.php");
        exit;

    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/G5-1.css">
    <title>設定画面</title>
</head>
<body>
    <!-- header挿入 -->
        <header class="header">
            <img src="img/Ciel logo.png" alt="Ciel" class="logo"></a>
        </header>
    <div class="main">
    <!-- profile -->
        <div class="profile">
            <form action="G5-2.php" method="POST">
                <table>
                <?php    
                       echo '<input type="hidden" name="user_id" value="' , $user_id ,'">';
                        echo '<tr>','<td>user ：</td>','<td>',$result['user_name'],'</td>','</tr>';
                        echo '<tr>','<td>email：</td>','<td>',$result['user_mail'],'</td>','</tr>';            
                    ?>
                </table> 
                <button type="submit">profile update-></button>
                </form>
        </div>
    <!-- tag -->
        <div class="tag">
          <form action="G5-3.php" method="POST">  
            <?php
            echo '<input type="hidden" name="user_id" value="' , $user_id ,'">';
            echo "<div style='display: flex; flex-wrap: wrap;'>";
            foreach ($colorresult as $colorresult) {
                    
                    if($colorresult === false){
                        echo 'データなし';
                    }else{
                        echo "<span style='display: inline-block; background-color: #" . htmlspecialchars($colorresult["color"])."; width: 30px; height: 30px; border-radius: 50%; margin: 5px;'></span>";
                    }
                }
                echo "</div>";
            ?>                   
            <button type="submit">rename a tag-></button>
            </form>
        </div>

        <!-- logout -->
        <div class="logout">
          <button id="click-btn">logout</button>
          <div id="popup-wrapper">
              <div id="popup-inside">
                  <div id="message">
                    <div id="close">x</div>
                      <h2>ログアウトしますか？</h2>
                      <form action="G5-1.php" method="POST">
                          <input type="submit" value="ログアウト" class="logoutsubmit">
                          <input type="hidden" name="logout_flg" value="true">
                      </form>
                  </div>
              </div>
          </div>

    </div>

    <!-- footer挿入 -->
        <footer class="footer">
            <?php
                require 'menu.php';
            ?>
        </footer>

        <!-- JavaScriptファイルの読み込み -->
    <script src="script/G5-1.js"></script>
    
</body>
</html>