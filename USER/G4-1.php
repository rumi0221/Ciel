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
      <li><span class="tag-color checked" style="background-color: #ff7fbf;"></span>タグ名</li>
      <li><span class="tag-color" style="background-color: #7fafff;"></span>タグ名</li>
      <li><span class="tag-color" style="background-color: #d47fff;"></span>...</li>
      </ul>
      <button class="confirm-button">決定</button>
    </div>
</header>

<footer><?php include 'menu.php';?>
<img src="./img/Ciel logo.png">
<div id="calendar">
          <h2 id="current-month" onclick="toggleMonthSelector()">2024年10月</h2>
    <table>
      <thead>
        <tr>
          <th>日</th>
          <th>月</th>
          <th>火</th>
          <th>水</th>
          <th>木</th>
          <th>金</th>
          <th>土</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>2</td>
          <td>3</td>
          <td>4</td>
          <td>5</td>
          <td>6</td>
          <td>7</td>
        </tr>
        <tr>
          <td>8</td>
          <td>9</td>
          <td>10</td>
          <td>11</td>
          <td>12</td>
          <td>13</td>
          <td>14</td>
        </tr>
        <tr>
          <td>15</td>
          <td>16</td>
          <td>17</td>
          <td>18</td>
          <td>18</td>
          <td>20</td>
          <td>21</td>
        </tr>
        <tr>
          <td>22</td>
          <td>23</td>
          <td>24</td>
          <td>25</td>
          <td>26</td>
          <td>27</td>
          <td>28</td>
        </tr>
        <tr>
          <td>29</td>
          <td>30</td>
          <td>31</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <!-- 他の週も同様に追加 -->
      </tbody>
    </table>

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
            <span class="month selected" onclick="selectMonth(10)">10月</span>
            <span class="month" onclick="selectMonth(11)">11月</span>
            <span class="month" onclick="selectMonth(12)">12月</span>
        </div>
        <span class="close-selector" onclick="toggleMonthSelector()">×</span>
  </div>
</div>




    <button class="add-btn">＋</button>
  </div>

  <script src="script/G4-1.js"></script>
</body>
</html>
