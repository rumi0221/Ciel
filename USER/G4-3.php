<?php session_start();?>
<?php
    require_once 'db-connect.php';
    $db = new PDO($connect, USER, PASS);

	$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $user = $_SESSION['user'];
    $user_id = $user['user_id'];

    // POSTデータをセッションに保存
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['plan_data'] = [
        'plan_id' => $_POST['planId'] ?? null,
        'plan' => $_POST['title'] ?? null,
        'start_date' => $_POST['start'] ?? null,
        'final_date' => $_POST['final'] ?? null,
        'memo' => $_POST['memoValue'] ?? null,
        'todo_flg' => $_POST['todoFlg'] ?? null,
        'crud' => $_POST['crud'] ?? null,
    ];

    //sessionデータ検証用
// echo '<pre>'; print_r($_SESSION['plan_data']); echo '</pre>';

    }

    $colorsql='select * from Usertags where user_id = :user_id';
        $usertag = $db->prepare($colorsql);
        $usertag->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $usertag->execute();
        $usertags = $usertag->fetchAll(PDO::FETCH_ASSOC);
 
        $colorsql = 'SELECT * FROM Tags limit 12';
            $colorstmt = $db->prepare($colorsql);
            $colorstmt->execute();
            $colorresults = $colorstmt->fetchAll(PDO::FETCH_ASSOC);
 
        $i = 0;
        foreach($usertags as  $usertag){
            $tag_id[$i] =  $usertag["tag_id"];
            $tag_name[$i] =  $usertag["tag_name"];
            $i++;
        }

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['plan_id']) !== null && $_SESSION['plan_data']['crud'] === "update"){
//update
$plan_id = $_SESSION['plan_data']['plan_id'];
?>
    <!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タグ選択更新画面</title>
    <link rel="stylesheet" href="./css/G4-3.css">
    <style>
    .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 20px;
        background-color: #fff;
        border-bottom: 3px solid #f8cce2; /* Pink underline */
        margin: 0 auto;
    }
 
    .headerbutton {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
    }
 
    /* .logo { */
        height: 40px; /* Adjust height as needed */
    /* } */
 
    .headersubmit {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
    }
        .back-button {
            font-size: 20px;
            text-decoration: none;
            color: #333;
            margin-right: 15px;
        }
 
        .title {
            flex-grow: 1;
            font-size: 18px;
            font-weight: normal;
        }
 
        .confirm-button {
            padding: 5px 15px;
            background: none;
            border: none;
            color: #007AFF;
            font-size: 16px;
        }
 
        .color-selector {
            padding: 20px;
            background-color: #fff;
        }
 
        .color-option {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
 
        .color-option:last-child {
            border-bottom: none;
        }
 
        .color-radio {
            display: none;
        }
 
        .color-circle {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            margin-right: 15px;
            position: relative;
        }
 
        .color-label {
            display: flex;
            align-items: center;
            width: 100%;
            cursor: pointer;
        }
 
        .color-text {
            color: #333;
            font-size: 16px;
        }
 
        .check-icon {
            display: none;
            position: absolute;
            right: -5px;
            top: -5px;
            width: 16px;
            height: 16px;
            background-color: #4CD964;
            border-radius: 50%;
            border: 2px solid #fff;
        }
 
        .color-radio:checked + .color-label .check-icon {
            display: block;
        }
 
        /* カラーバリエーション */
        .color-1 .color-circle { background-color: #FF6B6B; }
        .color-2 .color-circle { background-color: #FFD93D; }
        .color-3 .color-circle { background-color: #95DE64; }
        .color-4 .color-circle { background-color: #95DE64; }
        .color-5 .color-circle { background-color: #4CAF50; }
        .color-6 .color-circle { background-color: #40E0D0; }
        .color-7 .color-circle { background-color: #87CEEB; }
        .color-8 .color-circle { background-color: #6495ED; }
        .color-9 .color-circle { background-color: #9370DB; }
        .color-10 .color-circle { background-color: #FF69B4; }
        .color-11 .color-circle { background-color: #FF1493; }
        .color-12 .color-circle { background-color: #FF4081; }
    </style>
</head>
<body>
 
<header class="header">
    <!-- <button type="button" onclick="history.back()" class="headerbutton">←</button> -->
    <img src="img/Ciel logo.png" alt="Ciel" class="logo">
    <input type="submit" value="決定" form="select" class="headersubmit">
</header>
 
<form action = "G4-2.php" method="post" id="select">
<ul class="tag-list">
    <?php
    $i = 0;
    echo"<div class='color-selector'>";
    foreach ($colorresults as $colorresult) {
        echo"<li class='tag-item' onclick='selectTag(this)'>";
        echo"<span class='color-circle' style='background-color: #". htmlspecialchars($colorresult["color"]).";'></span>";
        echo"<span class='checkmark'>&#10003;</span>";
        echo"<span>".  htmlspecialchars($tag_name[$i])."</span>";
        echo '<input type="hidden" name="tag_id_" value="'. htmlspecialchars($tag_id[$i]).'">';
        echo"</li>";
        $i++;
        if($i == 13){
            break;
        }
    }
    echo "</div>";
    echo '<input type="hidden" name="crud" value="update">';
    echo '<input type="hidden" name="user_flg" value="false">';
    echo '<input type="hidden" name="tag_returnflg" value="true">';
    // echo '<input type="hidden" name="plan_id" value="'.$plan_id.'">';
    
    echo '<input type="hidden" name="plan_id" value="'.$_SESSION['plan_data']['plan_id'].'">';
    echo '<input type="hidden" name="plan" value="'.$_SESSION['plan_data']['plan'].'">';
    echo '<input type="hidden" name="start_date" value="'.$_SESSION['plan_data']['start_date'].'">';
    echo '<input type="hidden" name="final_date" value="'.$_SESSION['plan_data']['final_date'].'">';
    echo '<input type="hidden" name="memo" value="'.$_SESSION['plan_data']['memo'].'">';
    echo '<input type="hidden" name="todo_flg" value="'.$_SESSION['plan_data']['todo_flg'].'">';
    ?>
</ul>
<!-- echo '<input id="tag_color_no" type="hidden" name="tag_id" value="">'; -->
<input id="tag_color_no" type="hidden" name="tag_id" value="">
</form>
<script src="script/G4-3.js"></script>
</body>
</html>
 
<?php
}else if($_POST['crud'] == "insert"){
    // insert遷移
?>
    <!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タグ選択画面</title>
    <link rel="stylesheet" href="./css/G4-3.css">
    <style>
    .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 20px;
        background-color: #fff;
        margin: 0 auto;
    }
 
    .headerbutton {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
    }
 
    .headersubmit {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
    }
        .back-button {
            font-size: 20px;
            text-decoration: none;
            color: #333;
            margin-right: 15px;
        }
 
        .title {
            flex-grow: 1;
            font-size: 18px;
            font-weight: normal;
        }
 
        .confirm-button {
            padding: 5px 15px;
            background: none;
            border: none;
            color: #007AFF;
            font-size: 16px;
        }
 
        .color-selector {
            padding: 20px;
            background-color: #fff;
        }
 
        .color-option {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
 
        .color-option:last-child {
            border-bottom: none;
        }
 
        .color-radio {
            display: none;
        }
 
        .color-circle {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            margin-right: 15px;
            position: relative;
        }
 
        .color-label {
            display: flex;
            align-items: center;
            width: 100%;
            cursor: pointer;
        }
 
        .color-text {
            color: #333;
            font-size: 16px;
        }
 
        .check-icon {
            display: none;
            position: absolute;
            right: -5px;
            top: -5px;
            width: 16px;
            height: 16px;
            background-color: #4CD964;
            border-radius: 50%;
            border: 2px solid #fff;
        }
 
        .color-radio:checked + .color-label .check-icon {
            display: block;
        }
 
        /* カラーバリエーション */
        .color-1 .color-circle { background-color: #FF6B6B; }
        .color-2 .color-circle { background-color: #FFD93D; }
        .color-3 .color-circle { background-color: #95DE64; }
        .color-4 .color-circle { background-color: #95DE64; }
        .color-5 .color-circle { background-color: #4CAF50; }
        .color-6 .color-circle { background-color: #40E0D0; }
        .color-7 .color-circle { background-color: #87CEEB; }
        .color-8 .color-circle { background-color: #6495ED; }
        .color-9 .color-circle { background-color: #9370DB; }
        .color-10 .color-circle { background-color: #FF69B4; }
        .color-11 .color-circle { background-color: #FF1493; }
        .color-12 .color-circle { background-color: #FF4081; }
    </style>
</head>
<body>
 
<header class="header">
    <!-- <button type="button" onclick="goBackUpdate()" class="headerbutton">←</button> -->
    <img src="img/Ciel logo.png" alt="Ciel" class="logo">
    <input type="submit" value="決定" form="select" class="headersubmit">
</header>
 
<form action = "G4-2.php" method="post" id="select">
<ul class="tag-list">
    <?php
    $i = 0;
    echo"<div class='color-selector'>";
    foreach ($colorresults as $colorresult) {
        echo"<li class='tag-item' onclick='selectTag(this)'>";
        echo"<span class='color-circle' style='background-color: #". htmlspecialchars($colorresult["color"]).";'></span>";
        echo"<span class='checkmark'>&#10003;</span>";
        echo"<span>".  htmlspecialchars($tag_name[$i])."</span>";
        echo '<input type="hidden" name="tag_id_" value="'. htmlspecialchars($tag_id[$i]).'">';
        echo"</li>";
        $i++;
        if($i == 13){
            break;
        }
 
    }
    echo"</div>";
    echo '<input type="hidden" name="crud" value="insert">';
    echo '<input type="hidden" name="user_flg" value="false">';
    echo '<input type="hidden" name="tag_returnflg" value="true">';

    echo '<input type="hidden" name="plan" value="'.$_SESSION['plan_data']['plan'].'">';
    echo '<input type="hidden" name="start_date" value="'.$_SESSION['plan_data']['start_date'].'">';
    echo '<input type="hidden" name="final_date" value="'.$_SESSION['plan_data']['final_date'].'">';
    echo '<input type="hidden" name="memo" value="'.$_SESSION['plan_data']['memo'].'">';
    echo '<input type="hidden" name="todo_flg" value="'.$_SESSION['plan_data']['todo_flg'].'">';
    ?>
</ul>
<!-- echo '<input id="tag_color_no" type="hidden" name="tag_id" value="">'; -->
<input id="tag_color_no" type="hidden" name="tag_id" value="">
</form>
<script src="script/G4-3.js"></script>
</body>
</html>
 
<?php
    }
?>
 