<?php    
    const SERVER = 'mysql310.phy.lolipop.lan';
    const DBNAME = 'LAA1517478-3rd';
    const USER = 'LAA1517478';
    const PASS = '3rd1004';
  
    $connect = 'mysql:host='.SERVER.';dbname='.DBNAME.';charset=utf8';
    $db = new PDO($connect, USER, PASS);

	  $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //   -------------------------------------------------------------------
    // 特定の期間に該当する予定を取得する関数
function getPlansByDateRange($start_date, $end_date) {
    $connect = 'mysql:host='.SERVER.';dbname='.DBNAME.';charset=utf8';
    $db = new PDO($connect, USER, PASS);

    $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user_id = 8;

    $stmt = $db->prepare("
        SELECT 
            Plans.plan AS plan, 
            Plans.start_date, 
            Plans.final_date, 
            Plans.memo AS memo,
            Tags.color AS color ,
            Tags.tag_id AS tag_id
        FROM 
            Plans 
        INNER JOIN 
            Tags ON Plans.usertag_id = Tags.tag_id 
        WHERE  
            Plans.user_id = :user_id
       
    ");

    // and
    // (Plans.start_date <= :end_date AND Plans.final_date >= :start_date)

        // $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
        // $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // カレンダーで表示している期間
    $selectedYear = date("Y");
    // $selectedMonth = $selectCalenderMonth;
    $selectedMonth = date("n");
    $start_date = "$selectedYear-$selectedMonth-01 00:00:00";
    $end_date = date("Y-m-t 23:59:59", strtotime($start_date)); 

    // $selectedYear = 2024;
    // $selectedMonth = 11;
    //  $start_date = "$selectedYear-$selectedMonth-01";
    //  $end_date = date("Y-m-t", strtotime($start_date)); // 月の最終日を取得
    
    $start_date = date('Y-m-d H:i:s', strtotime($start_date));
    $end_date = date('Y-m-d H:i:s', strtotime($end_date));


    $events = getPlansByDateRange($start_date, $end_date);
    
// --------------------------------------------------------------
  
$colorsql = 'SELECT * FROM Tags';
$colorstmt = $db->prepare($colorsql);
$colorstmt->execute();
$colorresults = $colorstmt->fetchAll(PDO::FETCH_ASSOC); 

$tagsql = 'SELECT * FROM Usertags';
$tagstmt = $db->prepare($tagsql);
$tagstmt->execute();
$tags = $tagstmt->fetchAll(PDO::FETCH_ASSOC); 

    $i = 0;
    foreach($tags as $tag){
        $tag_name[$i] =  $tag["tag_name"];
        $i++;
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
      <!-- <a href="#" class="show-all">全てのタグを表示</a> -->
        <div id="popup-wrapper">
            <div id="popup-inside">
                <div class="close" id="close" onclick="toggleMenu()">×</div>
            <form action="G4-1.php" method="post">
            <ul class="tag-list">
        <?php
        $i = 0;
            foreach($colorresults as $colorresult){
                echo "<li>
                <span class='tag-color' style='background-color: #".$colorresult['color']."';>
                    <input type='radio' class='radio-none-dsplay' name='tag' value=".$colorresult['tag_id']."></span>".$tag_name[$i].
                "</li>";
                    $i++;
                    if($i == 13){
                        break;
                    }
            }
        ?>
      </ul>
      <input type="submit" class="confirm-button" value="決定">
            </form>
            </div>
        </div>
    </div>
</header>

<footer><?php include 'menu.php';?></footer>
<div id="calendar">
          <h2 id="current-month" onclick="toggleMonthSelector()"><?php echo date("Y") ?>年<?php echo date("n") ?>月</h2>

    <!-- プラスボタン -->
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
  <script src="script/G4-1.js"></script>

  <!-- ------------------------------------------------------------------------------------------------ -->
<script>
let events = <?php echo json_encode($events); ?>;

console.log(events);

//ここから
// 一時的な配列でデータを格納
let tempEvents = [];

// 日付を1日進める関数
function incrementDate(date) {
  const newDate = new Date(date);
  newDate.setDate(newDate.getDate() + 1);
  return newDate;
}

// メインの変換処理
events.forEach(event => {
  const startDate = new Date(event.start_date);
  const finalDate = new Date(event.final_date);

  for (let date = new Date(startDate); date <= finalDate; date = incrementDate(date)) {
    const dateKey = date.toISOString().split('T')[0]; // YYYY-MM-DD形式に変換

    // 一時的な配列にオブジェクトとして追加
    tempEvents.push({
      "date": dateKey,       // 日付
      "content": event.plan, // タイトル
      "color": event.color,   // 色
      "memo": event.memo,  //メモ
      "tag_id": event.tag_id //タグID
    });
  }
});

// 日付順にソート
tempEvents.sort((a, b) => new Date(a.date) - new Date(b.date));

// ソート済みのデータを連番キーを使って変換後のオブジェクトに再格納
const transformedEvents = {};
tempEvents.forEach((event, index) => {
  transformedEvents[index] = event;
});

console.log(transformedEvents);

//ここまで
function generateCalendar(year, month) {
    const calendarTable = document.getElementById("calendar-table");
    calendarTable.innerHTML = "";

    const firstDay = new Date(year, month - 1, 1).getDay();
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

    //日付
    let day = 1;
    for (let i = firstDay; i < 7; i++) {
        table += `<td>${renderDayCell(day)}</td>`;
        day++;
    }
    table += "</tr>";

    while (day <= daysInMonth) {
        table += "<tr>";
        for (let i = 0; i < 7 && day <= daysInMonth; i++) {
            table += `<td>${renderDayCell(day)}</td>`;
            day++;
        }
        table += "</tr>";
    }
    table += "</table>";

    calendarTable.innerHTML = table;
}

function renderDayCell(day) {

    //イベント取得用日付
    const date = new Date(selectedYear, selectedMonth - 1, day);
    const eventList = events.filter(event => {
        const startDate = new Date(event.start_date);
        const finalDate = new Date(event.final_date);
// console.log(date);
// console.log(startDate);
// console.log(finalDate);
// console.log(date >= startDate);
// console.log(date <= finalDate);
        return date >= startDate && date <= finalDate; 
    });

console.log(eventList);

    let cellContent = `<div class="day-number">${day}</div>`;
    //最終てきに予定出してる
    eventList.forEach(event => {
        cellContent += `<div class="event" style="background-color:#${event.color};">${event.plan}</div>`;
    });

    return cellContent;
}

// 初期のカレンダー生成
generateCalendar(selectedYear, selectedMonth);
</script>