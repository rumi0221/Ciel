<?php
//プロフィールのupdateとG5-1に遷移
    require 'db-connect.php';
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
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">

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
        
        <div class="profile">
          <table>
          <form action="G5-2.php" method="POST" id="update">
            <?php
               echo '<input type="hidden" name="user_flg" value="true">';
              echo '<input type="hidden" name="user_id" value="' , $user_id ,'">';
              echo '<tr>','<td>user 　　：</td>','<td>','<input type="text" name="user_name" value="', htmlspecialchars($result['user_name']) ,'" required>','</td>','</tr>';
              echo '<tr>','<td>email　　：</td>','<td>','<input type="email"  name="user_mail" value="', htmlspecialchars($result['user_mail']) ,'" required>','</td>','</tr>';
              echo '<tr>','<td>password：</td>','<td>','<input type="password" name="user_pass" id="passwordInput" required>','</td>';
              
              //echo '<td><button id="showPasswordButton"><i class="fa-solid fa-eye"></i></button></td>','</tr>'
            ?>
            </form>
            <td><button id="showPasswordButton" class="fa fa-eye"></button></td></tr>
          </table> 
      </div> 
    

    <!-- footer挿入 -->
        <footer class="footer">
          <?php
            require 'menu.php';
          ?>
        </footer>

        <script>
              var showPasswordButton = document.getElementById("showPasswordButton");
                showPasswordButton.addEventListener("click", togglePasswordVisibility);

                function togglePasswordVisibility() {
                  var passwordInput = document.getElementById("passwordInput");
                    if (passwordInput.type === "password") {
                      passwordInput.type = "text";
                      showPasswordButton.className = "fa fa-eye-slash";
                    } else {
                      passwordInput.type = "password";
                      showPasswordButton.className = "fa fa-eye";
                    } 
                }
            </script>
    
</body>
</html>