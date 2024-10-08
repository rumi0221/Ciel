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
        <h1>Ciel</h1>
        <nav>
            <button class="menu-icon">≡</button>
        </nav>
    </header>

    <div class="calendar">
        <div class="month">
            <h2>2024年10月</h2>
        </div>
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
            <tbody id="calendar-body">
                <!-- カレンダーの日付が入る -->
            </tbody>
        </table>
        <button class="add-btn">＋</button>
    </div>

    <script src="script.js"></script>
</body>
</html>
