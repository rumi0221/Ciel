<?php 
session_start();

require_once 'db-connect.php';
$db = new PDO($connect, USER, PASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(!isset($_SESSION['user'])){
    header("Location: G1-1.php");
    exit;
}

// idの取得
$user = $_SESSION['user'];
$user_id = $user['user_id'];

// タグフィルター処理
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['tag']) && $_GET['tag'] !== 'null' && $_GET['tag'] > 0) {
    function getPlansByDateRange($start_date, $end_date) {
        global $db;
        global $user_id;
    
        $stmt = $db->prepare("
            SELECT 
                Plans.plan_id AS plan_id, 
                Plans.plan AS plan, 
                Plans.start_date, 
                Plans.final_date, 
                Plans.memo AS memo,
                Tags.color AS color,
                Tags.tag_id AS tag_id
            FROM 
                Plans 
            INNER JOIN 
                Tags ON Plans.usertag_id = Tags.tag_id 
            WHERE  
                Plans.user_id = :user_id
            AND (Plans.usertag_id = :tag_id)
        ");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':tag_id', $_GET['tag']);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    // 特定の期間の予定を取得する関数
    function getPlansByDateRange($start_date, $end_date) {
        global $db;
        global $user_id;

        $stmt = $db->prepare("
            SELECT
                Plans.plan_id AS plan_id, 
                Plans.plan AS plan, 
                Plans.start_date, 
                Plans.final_date, 
                Plans.memo AS memo,
                Tags.color AS color,
                Tags.tag_id AS tag_id
            FROM 
                Plans 
            INNER JOIN 
                Tags ON Plans.usertag_id = Tags.tag_id 
            WHERE  
                Plans.user_id = :user_id
        ");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// 年月の取得
$selectedYear = date("Y");
$selectedMonth = date("n");

if (isset($_GET['month']) && isset($_GET['year'])) {
    $selectedMonth = $_GET['month'];
    $selectedYear = $_GET['year'];
}

$start_date = "$selectedYear-$selectedMonth-01 00:00:00";
$end_date = date("Y-m-t 23:59:59", strtotime($start_date));

$events = getPlansByDateRange($start_date, $end_date);

// タグ情報を取得
$colorsql = 'SELECT * FROM Tags';
$colorstmt = $db->prepare($colorsql);
$colorstmt->execute();
$colorresults = $colorstmt->fetchAll(PDO::FETCH_ASSOC); 

// タグ名の準備
$tagsql = 'SELECT * FROM Usertags where user_id = :user_id';
$tagstmt = $db->prepare($tagsql);
$tagstmt->bindParam(':user_id',$user_id,PDO::PARAM_INT);
$tagstmt->execute();
$tags = $tagstmt->fetchAll(PDO::FETCH_ASSOC);

$i = 0;
foreach($tags as $tag){
    $tag_name[$i] =  $tag["tag_name"];
    $i++;
}

// 祝日判定ファイルを読み込む
require 'holidays.php';
// 祝日データの取得
$holidayData = getHoliday($selectedYear);

// イベントデータを整形
$eventData = [];
foreach ($events as $event) {
    $startDate = new DateTime($event['start_date']);
    $endDate = new DateTime($event['final_date']);
    $endDate->setTime(23, 59, 59);

    if($event['memo'] === null || $event['memo'] === "メモ"){
        $memo = "　";
    } else {
        $memo = $event['memo'].'<br><br>';
    }

    while ($startDate <= $endDate) {
        $dateKey = $startDate->format("Y-m-d");
        $eventData[] = [
            'date' => $dateKey,
            'id' => $event['plan_id'],
            'content' => $event['plan'],
            'starttime' => $startDate->format("H:i"),
            'endtime' => $endDate->format("H:i"),
            'color' => $event['color'],
            'memo' => $memo
        ];
        $startDate->modify('+1 day');
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カレンダー</title>
    <link rel="stylesheet" href="./css/G4-1.css">
    <link rel="stylesheet" href="./css/menu.css">
</head>
<body>
    <header>
        <img src="./img/Ciel logo.png">
        <div class="menu-icon" onclick="toggleMenu()">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
        <!-- タグ選択 -->
        <div id="menu" class="menu">
            <div id="popup-wrapper">
                <div id="popup-inside">
                    <div class="close" id="close" onclick="toggleMenu()">×</div>
                    <form action="G4-1.php" method="get">
                        <input type="hidden" name="year" value="<?= $selectedYear; ?>">
                        <input type="hidden" name="month" value="<?= $selectedMonth; ?>">
                        <ul class="tag-list">
                        <?php
                            $i = 0;
                            foreach($colorresults as $colorresult){
                                echo "<li>";
                                echo "<label class='tag-color' style='background-color: #".$colorresult['color']."';>";
                                echo "<input type='radio' name='tag' value=".$colorresult['tag_id']."></label>".$tag_name[$i].
                                "</li>";
                                $i++;
                                if($i == 13){
                                    break;
                                }
                            }
                        ?>
                        </ul>
                        <div class="button">
                            <input type="reset" class="reset" value="リセット">
                            <input type="submit" class="confirm-button" value="決定" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </header>
    <footer><?php include 'menu.php';?></footer>
    <div id="calendar">
        <h2 id="current-month" onclick="toggleMonthSelector(<?= $selectedYear; ?>, <?= $selectedMonth; ?>, <?= isset($_GET['tag']) ? $_GET['tag'] : 'null' ; ?>)">
            <?= $selectedYear; ?>年<?= $selectedMonth; ?>月
        </h2>
        
        <!-- 予定新規 -->
        <form action="G4-2.php" method="post">
            <button class="floating-button">+</button>
            <input type="hidden" name="crud" value="insert">
            <input type="hidden" name="user_flg" value="false">
        </form>
        <div id="calendar-table"></div>

        <!-- カレンダー移動 -->
        <div id="month-selector" class="month-selector">
            <div class="selector-header">
                <span id="prev-year" onclick="changeYear(-1)">← 前年</span>
                <span id="selected-year"><?php echo $selectedYear; ?>年</span>
                <span id="next-year" onclick="changeYear(1)">翌年 →</span>
            </div>
            
            <div class="month-grid">
            <?php 
            $selectCalenderMonth = 0;
            for($i = 1; $i <= 12; $i++){
                if ($i == $selectedMonth){
                    echo '<span class="month" onclick="selectMonth('.$i.')">'.$i.'月</span>';
                } else {
                    echo '<span class="month selected" onclick="selectMonth('.$i.')">'.$i.'月</span>';
                    $selectCalenderMonth = $i;
                }
            } 
            ?>
            </div>
            <span class="close-selector" onclick="toggleMonthSelector()">×</span>
        </div>

        <!-- 詳細表示 -->
        <div id="event-details" class="hidden">
            <h4 id="selected-date"></h4>
            <ul id="event-list"></ul>
        </div>
    </div>

    <script src="script/G4-1.js"></script>
    <script>
        const events = <?php echo json_encode($eventData); ?>;
        const holidays = <?php echo json_encode($holidayData); ?>;
        
        // 初期表示
        generateCalendar(<?= $selectedYear; ?>, <?= $selectedMonth; ?>);
    </script>
</body>
</html>
