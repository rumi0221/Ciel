<?php session_start();?>
<?php    
// データベース接続設定
const SERVER = 'mysql310.phy.lolipop.lan';
const DBNAME = 'LAA1517478-3rd';
const USER = 'LAA1517478';
const PASS = '3rd1004';

$connect = 'mysql:host='.SERVER.';dbname='.DBNAME.';charset=utf8';
$db = new PDO($connect, USER, PASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])){
//     echo 'OK!';
//     exit;
//   }

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tag']) != 0){
    function getPlansByDateRange($start_date, $end_date) {
        global $db;
        $user_id = 8;
    
        $stmt = $db->prepare("
            SELECT 
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
        $stmt->bindParam(':tag_id', $_POST['tag']);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}else{
    // 特定の期間の予定を取得する関数
    function getPlansByDateRange($start_date, $end_date) {
        global $db;
        $user_id = 8;

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

// カレンダーで表示している期間
$selectedYear = date("Y");
$selectedMonth = date("n");
$start_date = "$selectedYear-$selectedMonth-01 00:00:00";
$end_date = date("Y-m-t 23:59:59", strtotime($start_date));

$events = getPlansByDateRange($start_date, $end_date);

// タグ情報を取得
$colorsql = 'SELECT * FROM Tags';
$colorstmt = $db->prepare($colorsql);
$colorstmt->execute();
$colorresults = $colorstmt->fetchAll(PDO::FETCH_ASSOC); 

// タグ名の準備
$tagsql = 'SELECT * FROM Usertags';
$tagstmt = $db->prepare($tagsql);
$tagstmt->execute();
$tags = $tagstmt->fetchAll(PDO::FETCH_ASSOC);

$i = 0;
foreach($tags as $tag){
    $tag_name[$i] =  $tag["tag_name"];
    $i++;
}

// イベントデータを整形
$eventData = [];
foreach ($events as $event) {
    $startDate = new DateTime($event['start_date']);
    $endDate = new DateTime($event['final_date']);
    $endDate -> setTime(23, 59, 59); // 終了日を1日の終わりに設定

    while ($startDate <= $endDate) {
        $dateKey = $startDate->format("Y-m-d");
        $eventData[] = [
            'date' => $dateKey,
            'id' => $event['plan_id'],
            'content' => $event['plan'],
            'starttime' => $startDate->format("H:i"),
            'endtime' => $endDate->format("H:i"),
            'color' => $event['color'],
            'memo' => $event['memo']
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
        <div id="menu" class="menu">
            <div id="popup-wrapper">
                <div id="popup-inside">
                    <div class="close" id="close" onclick="toggleMenu()">×</div>
                    <form action="G4-1.php" method="post">
                        <ul class="tag-list">
                        <?php
                            $i = 0;
                            foreach($colorresults as $colorresult){
                                echo "<li>";
                                echo "<span class='tag-color' style='background-color: #".$colorresult['color']."';>";
                                echo "<input type='radio' name='tag' value=".$colorresult['tag_id']."></span>".$tag_name[$i].
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
                            <input type="submit" class="confirm-button" value="決定">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </header>
    <footer><?php include 'menu.php';?></footer>
    <div id="calendar">
        <h2 id="current-month" onclick="toggleMonthSelector()"><?= $selectedYear; ?>年<?= $selectedMonth; ?>月</h2>
        <div class="floating-button" onclick="goToNextPage()">+</div>
        <div id="calendar-table"></div>

        <!-- カレンダー移動 -->
        <div id="month-selector" class="month-selector">
            <div class="selector-header">
                <span id="prev-year" onclick="changeYear(-1)">← 前年</span>
                <span id="selected-year"><?php echo date("Y") ?>年</span>
                <span id="next-year" onclick="changeYear(1)">翌年 →</span>
            </div>
            <div class="month-grid">
            <?php 
            $selectCalenderMonth = 0;
            for($i = 1;$i <= 12;$i++){
                if($i == date("n")){
                    echo '<span class="month" onclick="selectMonth('.$i.')">'.$i.'月</span>';
                }else{
                    echo '<span class="month selected" onclick="selectMonth('.$i.')">'.$i.'月</span>';
                    $selectCalenderMonth = $i;
                }
            } 
            ?>
            </div>

        <span class="close-selector" onclick="toggleMonthSelector()">×</span>
    </div>

    <!-- 詳細出す -->
        <div id="event-details" class="hidden">
            <h4 id="selected-date"></h4>
            <ul id="event-list"></ul>
        </div>
    </div>

    <script src="script/G4-1.js"></script>
    <script>
        const events = <?php echo json_encode($eventData); ?>;

        // カレンダー生成関数
        function generateCalendar(year, month) {
            const calendarTable = document.getElementById("calendar-table");
            calendarTable.innerHTML = "";

            //曜日
            const firstDay = new Date(year, month - 1, 1).getDay();
            //月の日数
            const daysInMonth = new Date(year, month, 0).getDate();

            const daysOfWeek = ['日', '月', '火', '水', '木', '金', '土'];
            let table = "<table><tr>";
            daysOfWeek.forEach(day => {
                table += `<th>${day}</th>`;
            });
            table += "</tr><tr>";

            // 空のセルを追加
            for (let i = 0; i < firstDay; i++) {
                table += "<td></td>";
            }

            //日付セル作成
            for (let day = 1; day <= daysInMonth; day++) {
                const dateKey = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const eventList = events.filter(event => event.date === dateKey);

                let cellContent = `<div class="day-number">${day}</div>`;
                eventList.forEach(event => {
                    cellContent += `<div class="event" style="background-color:#${event.color};">${event.content}</div>`;
                });

                //showEvents：詳細
                table += `<td onclick="showEvents(${year}, ${month}, ${day})">${cellContent}</td>`;
                if ((day + firstDay) % 7 === 0) {
                    table += "</tr><tr>";
                }
            }
            table += "</tr></table>";
            calendarTable.innerHTML = table;
        }

        // イベント詳細表示
        function showEvents(year, month, day) {
            const selectedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const eventList = events.filter(event => event.date === selectedDate);

            document.getElementById("event-details").classList.remove("hidden");
            document.getElementById("selected-date").innerText = `${year}年${month}月${day}日`;

            const eventListContainer = document.getElementById("event-list");
            eventListContainer.innerHTML = "";

            if (eventList.length > 0) {
                eventList.forEach(event => {
                    const listItem = document.createElement("li");
                    //予定押下でG4-2に遷移
                    listItem.innerHTML = `
                        <span class="event-time">${event.starttime || "終日"} ～ ${event.endtime || "終日"}</span>
                        <form action="G4-2.php" method="post">
                            <input type="hidden" id="popup" name="id" value="${event.id}">
                            <input type="hidden" name="update" value="update"><br>
                            <button class="link-style-btn">
                                <span>${event.content}</span>
                            </button>
                        </form>
                            <span>${event.memo}</span>`;

                    eventListContainer.appendChild(listItem);
                });
            } else {
                eventListContainer.innerHTML = "<li>予定はありません</li>";
            }
        }

        // 初期表示
        generateCalendar(<?= $selectedYear; ?>, <?= $selectedMonth; ?>);
    </script>
</body>
</html>
