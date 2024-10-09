<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カレンダー</title>
    <link rel="stylesheet" href="./css/G4-1.css">
    

</head>
<body>
    <header>
       
        <div class="icon">
        <img src="img/Ciel logo.png">
        </div>
        <nav>
            <button class="menu-icon">≡</button>
        </nav>
    </header>

    <div class="calendar">
        <div class="month">
            <h2>2024年10月</h2>
        </div>
        <button id="prevMonth">前の月</button>
        <button id="nextMonth">次の月</button>
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
                    <td>7</td><br>
                    <td>8</td>
                    <td>9</td>
                    <td>10</td>
                    <td>11</td>
                    <td>12</td>
                    <td>13</td>
                    <td>14</td><br>
                    <td>15</td>
                    <td>16</td>
                    <td>17</td>
                    <td>18</td>
                    <td>19</td>
                    <td>20</td>
                    <td>21</td><br>
                    <td>22</td>
                    <td>23</td>
                    <td>24</td>
                    <td>25</td>
                    <td>26</td>
                    <td>27</td>
                    <td>28</td><br>
                    <td>29</td>
                    <td>730</td>
                </tr>
                <!-- 他の週も同様に追加 -->
            </tbody>
        </table>
        <div id="yearCalendar"></div>
        <input type="text" id="todoInput" placeholder="ToDoを追加">
        <ul id="todoList"></ul>
        <button class="add-btn">＋</button>
    </div>

    <script src="G4-1.js"></script>
</body>
</html>
