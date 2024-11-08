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
    <div class="menu-icon" onclick="toggleMenu()">
      <div class="bar"></div>
      <div class="bar"></div>
      <div class="bar"></div>
    </div>
    <div id="menu" class="menu">
      <a href="#" class="show-all">全てのタグを表示</a>
      <ul class="tag-list">
      <li><span class="tag-color" style="background-color: #d4ff7f;"></span>タグ名</li>
      <li><span class="tag-color" style="background-color: #ffeb7f;"></span>タグ名</li>
      <li><span class="tag-color" style="background-color: #ff7fbf;"></span>タグ名</li>
      <li><span class="tag-color" style="background-color: #7fafff;"></span>タグ名</li>
      <li><span class="tag-color" style="background-color: #d47fff;"></span>タグ名</li>
      </ul>
      <a href="#" class="confirm-button">決定</a>
    </div>
</header>

<footer><?php include 'menu.php';?>
<img src="./img/Ciel logo.png">
<div id="calendar">
          <h2 id="current-month" onclick="toggleMonthSelector()">2024年11月</h2>



    <!-- プラスボタン -->
    <div class="floating-button">+</div>
    <!-- プラスボタン -->
    <div class="floating-button" onclick="goToNextPage()">+</div>
    
    <div id="calendar-table"></div>

    <div id="month-selector" class="month-selector">
        <div class="selector-header">
            <span id="prev-year" onclick="changeYear(-1)">← 前年</span>
            <span id="selected-year">2024年</span>
            <span id="next-year" onclick="changeYear(1)">翌年 →</span>
        </div>
        <div class="month-grid">
            <span class="month" onclick="selectMonth(1)">1月</span>
            <span class="month" onclick="selectMonth(2)">2月</span>
            <span class="month" onclick="selectMonth(3)">3月</span>
            <span class="month" onclick="selectMonth(4)">4月</span>
            <span class="month" onclick="selectMonth(5)">5月</span>
            <span class="month" onclick="selectMonth(6)">6月</span>
            <span class="month" onclick="selectMonth(7)">7月</span>
            <span class="month" onclick="selectMonth(8)">8月</span>
            <span class="month" onclick="selectMonth(9)">9月</span>
            <span class="month" onclick="selectMonth(10)">10月</span>
            <span class="month selected" onclick="selectMonth(11)">11月</span>
            <span class="month" onclick="selectMonth(12)">12月</span>
        </div>

        <span class="close-selector" onclick="toggleMonthSelector()">×</span>
</div>
  <script src="script/G4-1.js"></script>
</body>
</html>
