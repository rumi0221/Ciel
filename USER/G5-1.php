<?php session_start();?>
<?php
   const SERVER = 'mysql310.phy.lolipop.lan';
   const DBNAME = 'LAA1517478-3rd';
   const USER = 'LAA1517478';
   const PASS = '3rd1004';

   $connect = 'mysql:host='.SERVER.';dbname='.DBNAME.';charset=utf8';
    $db = new PDO($connect, USER, PASS);

	$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // if(!isset($_SESSION['user'])){
    //     header("Location: G3-1-1.php");
    //     exit;
    // }

    // // 初期化
    // $error = false; 
    // $errorMessage = ""; 

    try{
        // idの取得
        // $user = $_SESSION['user'];
        // $user_id = $user['user_id'];
        $user_id = 1;
        $sql='select * from Users where user_id = :user_id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

    }catch(PDOException $e){
        $error = true;
        $errorMessage = "エラーが発生しました: " . $e->getMessage();
    }
    // if ($error) {
    //     echo "<p>" . $errorMessage . "</p>";
    //     header("Location: G1-1.php");
    //     exit;

    // }
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
            tag<br>                          
            <button type="submit">rename a tag-></button>
            </form>
        </div>
    </div>
    <!-- footer挿入 -->
        <footer class="footer">
            <?php
                require 'menu.php';
            ?>
        </footer>
    
</body>
</html>