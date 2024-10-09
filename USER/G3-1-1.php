<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/menu.css">
    <title>Document</title>
</head>
<body>
    <img class="logo" src="img/Ciel logo.png" >

    <div>
        <input type="checkbox" name="term" value="#">#
        のこり　日
    </div>

    <input type="checkbox" name="todo" value="#">#
    <input type="checkbox" name="todo" value="#">#
    <input type="checkbox" name="todo" value="#">#
    <input type="checkbox" name="todo" value="#">#
    <input type="checkbox" name="todo" value="#">#
    <input type="checkbox" name="todo" id="output">
    
    <form action="#" id="todo-input">
        <input type="text" name="content" id="content" required>
        <input type="submit" value="submit">
    </form>
    <div id="output"></div>

<footer><?php include 'menu.php';?></footer>
</body>
<script>
    // submit時にイベント実行をする関数
    document.getElementById('todo-input').onsubmit = function (event) {
        // 再読み込み防止
        event.preventDefault();
        // 入力フォームの内容を取得
        let inputForm = document.getElementById('content').value;
		// 入力内容を画面に出力
        document.getElementById('output').textContent = `${inputForm}`;
        // フォームのリセット
        document.getElementById('todo-input').reset();
    }
</script> 
</html>