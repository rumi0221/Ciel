<?php
//プロフィールのupdateとG5-1に遷移
  //require 'db-connect.php';
  const SERVER = 'mysql310.phy.lolipop.lan';
   const DBNAME = 'LAA1517478-3rd';
   const USER = 'LAA1517478';
   const PASS = '3rd1004';

   $connect = 'mysql:host='.SERVER.';dbname='.DBNAME.';charset=utf8';
    $db = new PDO($connect, USER, PASS);

	$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_flg'])){
    $user_id = $_POST['user_id'];
    $user_name = $_POST['user_name']; 
    $user_mail = $_POST['user_mail'];
    $user_pass = password_hash($_POST['user_pass'], PASSWORD_DEFAULT); 

    try{
        $sql = 'update Users set user_name=:name, user_mail=:mail, user_pass=:pass where user_id=:user_id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':name', $user_name, PDO::PARAM_STR);
        $stmt->bindParam(':mail', $user_mail, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $user_pass, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
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

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/G5-2.css">
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

    <?php
        $user_id = $_POST['user_id'];
        
            $sql='select * from Users where user_id = :user_id';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <!-- profile　 -->
        <form action="G5-2.php" method="POST" id="update">
        <div class="profile">
          <table>
            <?php
               echo '<input type="hidden" name="user_flg" value="true">';
              echo '<input type="hidden" name="user_id" value="' , $user_id ,'">';
              echo '<tr>','<td>user 　　：</td>','<td>','<input type="text" name="user_name" value="', htmlspecialchars($result['user_name']) ,'" required>','</td>','</tr>';
              echo '<tr>','<td>email　　：</td>','<td>','<input type="email"  name="user_mail" value="', htmlspecialchars($result['user_mail']) ,'" required>','</td>','</tr>';
              echo '<tr>','<td>password：</td>','<td>','<input type="password" name="user_pass" required>','</td>','</tr>';
            ?>
          </table> 
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