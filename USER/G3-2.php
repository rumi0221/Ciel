<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/G3-1.css">
    <link rel="stylesheet" href="css/menu.css">
    <title>カレンダー</title>
</head>
<body>
<header>
<img class="logo" src="img/Ciel logo.png">
</header>

<div class="background">
    
<style>
        body {
            font-family: "Arial", sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            /* background-color: #e8d9f0; */
            background-color: white;
            padding: 10px 0;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        }

        h2 {
            font-family: Serif;
        }

        .calendar-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 10px 0;
            padding: 5px;
            /* padding-top: 105px */
        }

        .calendar-section {
            text-align: center;
            margin: 20px 0;
            width: 90%;
            max-width: 400px;
        }

        .calendar-section h2 {
            margin-bottom: 10px;
        }

        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }

        .calendar div {
            text-align: center;
            padding: 5px;
            font-family: serif;
            font-size: 0.95rem;
            margin-top: 5px;  /* 上に5pxの間隔を追加 */
            margin-bottom: 5px; /* 下に5pxの間隔を追加 */
        }

        .calendar .header {
            font-weight: bold;
        }

        .calendar .today {
            background-color: #9CB8FF;
            color: white;
            border-radius: 50%;
            width: 25px; /* 幅を指定 */
            height: 25px; /* 高さを指定 */
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 5px auto; /* 中央に配置 */
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            display: flex;
            justify-content: space-around;
            background-color: #f0f0f0;
            padding: 10px 0;
            border-top: 1px solid #ccc;
        }

        footer button {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }

        footer button:hover {
            color: #a87db8;
        }

        hr {
            border: none;
            height: 1px;
            /* background-color: #ccc; */
            background-color: #000000;
            margin: 20px 0;
            width: 90%;
        }
    </style>
</head>
<body>
    <div class="calendar-container" id="calendar-container">
        <!-- JavaScriptでカレンダーを自動生成 -->
    </div>

    <footer>
        <button>📅</button>
        <button>✔️</button>
        <button>⚙️</button>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
        const header = document.querySelector("header");
        const calendarContainer = document.querySelector(".calendar-container");
        const headerHeight = header.offsetHeight; // ヘッダーの高さを取得
        calendarContainer.style.paddingTop = `${headerHeight}px`; // 余白を設定
        });  

    function generateCalendar(year, month) {
        const daysInWeek = ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"];
        const container = document.createElement("div");
        container.className = "calendar-section";

        // 月のタイトル
        const title = document.createElement("h2");
        title.textContent = `${year}年 ${month + 1}月`;
        container.appendChild(title);

        // カレンダー本体
        const calendar = document.createElement("div");
        calendar.className = "calendar";

        // 曜日ヘッダー
        daysInWeek.forEach((day, index) => {
            const header = document.createElement("div");
            header.textContent = day;
            header.className = "header";
            if (index === 0) header.style.color = "red"; // 日曜日
            if (index === 6) header.style.color = "blue"; // 土曜日
            calendar.appendChild(header);
        });

        // 日付部分
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        for (let i = 0; i < firstDay; i++) {
            const empty = document.createElement("div");
            calendar.appendChild(empty);
        }

        const today = new Date();
        for (let day = 1; day <= daysInMonth; day++) {
            const date = document.createElement("div");
            const currentDate = new Date(year, month, day);
            const weekDay = currentDate.getDay();
            date.textContent = day;

            // 正確な日付を取得する（タイムゾーンを考慮）
            const localDate = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
            date.dataset.date = localDate; // YYYY-MM-DD形式

            // 今日の日付を強調表示
            if (year === today.getFullYear() && month === today.getMonth() && day === today.getDate()) {
                date.className = "today";
            }

            // 日曜日・土曜日の色を設定
            if (weekDay === 0) date.style.color = "red"; // 日曜日
            if (weekDay === 6) date.style.color = "blue"; // 土曜日

            // 日付をクリックしたときのイベントを追加
            date.addEventListener("click", (e) => {
                    alert(`選択された日付: ${e.target.dataset.date}`);
                    // ここで取得した日付をさらに処理に渡すことが可能
            });

            calendar.appendChild(date);
        }

        container.appendChild(calendar);
        return container;
    }

    function generateAllCalendars() {
        const container = document.getElementById("calendar-container");
        const today = new Date();
        const currentYear = today.getFullYear();
        const currentMonth = today.getMonth();

        let previousYear = null;
        let todayElement = null;

        for (let offset = -12; offset <= 12; offset++) {
            const date = new Date(currentYear, currentMonth + offset);
            const year = date.getFullYear();
            const month = date.getMonth();

            if (year !== previousYear && previousYear !== null) {
                const yearDivider = document.createElement("hr");
                container.appendChild(yearDivider);
            }

            const calendar = generateCalendar(year, month);
            container.appendChild(calendar);

            if (year === today.getFullYear() && month === today.getMonth()) {
                todayElement = calendar.querySelector(".today");
            }

            previousYear = year;
        }

        // 今日のカレンダーが中央に表示されるようにスクロール
        if (todayElement) {
            setTimeout(() => {
                todayElement.scrollIntoView({
                    behavior: "smooth",
                    block: "center",
                });
            }, 100); // レイアウト確定後にスクロール処理を実行
        }
    }

    // カレンダー生成処理の実行
    generateAllCalendars();
</script>

</div>

<footer><?php include 'menu.php'; ?></footer>
</body>
</html>
