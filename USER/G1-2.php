<?php
session_start(); // セッションを開始
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/G1-2.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" rel="stylesheet">
    <title>会員登録</title>
    <script>
        function validatePassword() {
            const password = document.getElementById('user_pass').value;
            const confirmPassword = document.getElementById('user_pass_confirm').value;
            const errorMessageElement = document.getElementById('error-message'); // エラーメッセージを表示する要素

            // エラーメッセージをクリア
            errorMessageElement.textContent = '';

             // パスワードの長さを確認
        if (password.length < 6) {
            errorMessageElement.textContent = 'パスワードは6文字以上で入力してください。';
            return false; // フォーム送信を阻止
        }

            if (password !== confirmPassword) {
                // エラーメッセージを表示
                errorMessageElement.textContent = 'パスワードが一致しません。再度確認してください。';
                return false; // フォーム送信を阻止
            }
            return true; // フォーム送信を許可
        }
    </script>
</head>
<body onload="iconBehavior()">
    <!-- header挿入 -->
    <header class="header">
            <img src="img/Ciel logo.png" alt="Ciel" class="logo"></a>
</header>
    <div class="main">
    <h1>REGISTER</h1>
        <form action="G1-3.php" method="post" enctype="multipart/form-data" onsubmit="return validatePassword();">
            <!-- エラーメッセージを表示するための要素 -->
            <p id="error-message" style="color: red;"></p>

            <p>
            <div class="input-group">
                <label for="user_name">user<span class="required">*</span></label><br>

                    <!-- セッションに保存されている場合はその値を表示 -->
                    <input type="text" id="user_name" name="user_name" placeholder="ユーザ名を入力してください"
                           value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" required>
              
            </p>
            <p>
                <label for="user_mail">email<span class="required">*</span></label><br>
              
                    <input type="email" id="user_mail" name="user_mail" placeholder="メールアドレスを入力してください"
                           value="<?php echo htmlspecialchars($_SESSION['user_mail'] ?? ''); ?>" required>
               
            </p>
            <p>
    <div class="input-group">
        <label for="user_pass">password<span class="required">*</span></label><br>
        <input type="password" id="user_pass" name="user_pass" placeholder="パスワードを入力してください" maxlength="8" required>
        <img src="img/eye.png" alt="表示切替" class="toggle-password" onclick="togglePasswordVisibility('user_pass')">
        </div>
</p>
            <p>
                <label for="user_pass_confirm">password(確認)<span class="required">*</span></label><br>
                    <input type="password" id="user_pass_confirm" name="user_pass_confirm" placeholder="パスワードをもう一度入力してください" maxlength="8" required>
                    <img src="img/eye.png" alt="表示切替" class="toggle-password" onclick="togglePasswordVisibility('user_pass_confirm')">
                    
            </p>
        <div class="button-container">
            <button type="submit" class="button is-btn">CONFIRMATION</button>
            
                <button class="button is-medium" type="button" onclick="location.href='G1-1.php'">RETURN</button>
            </div>
           </div> 
        </form>
    </div>

    <script>
        function togglePasswordVisibility(fieldId) {
            const passwordField = document.getElementById(fieldId);
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
        }
    </script>

</body>
</html>
