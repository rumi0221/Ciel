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

<img class="logo" src="img/Ciel logo.png">

<div class="background">
    
<style>
        body {
            font-family: "Arial", sans-serif;
            /* background-color: #e8d9f0; */
            margin: 0;
            padding: 0;
        }

        header {
            /* text-align: center;
            font-family: "Cursive", sans-serif;
            font-size: 2rem;
            padding: 10px; */
            position: fixed; /* ヘッダーを固定 */
            top: 0;          /* 画面上部に配置 */
            left: 0;
            width: 100%;     /* 幅を全体に広げる */
            z-index: 1000;   /* 他の要素の上に表示 */
            background-color: #e8d9f0; /* 背景色を設定 */
            padding: 10px 0; /* 上下の余白を確保 */
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2); /* 見やすくするための影 */
        }

        .date-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 10px;
        }

        .date-buttons button {
            background-color: #f9c892;
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 1rem;
            cursor: pointer;
        }

        .date-buttons button:hover {
            background-color: #f7b173;
        }

        .calendar-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 10px 0;
            scroll-behavior: smooth; /* スムーズなスクロール */
            overflow-y: visible; /* 一時的にスクロール制限を解除 */
            max-height: unset;      /* 高さ制限を解除 */
            padding: 10px;
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
            gap: 5px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }

        .calendar div {
            text-align: center;
            padding: 5px;
            font-size: 0.9rem;
        }

        .calendar .header {
            font-weight: bold;
        }

        .calendar .today {
            background-color: #a87db8;
            color: white;
            border-radius: 50%;
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
        // カレンダーを生成する関数
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

            // 曜日ヘッダーの生成（赤と青の色付け）
            daysInWeek.forEach((day, index) => {
                const header = document.createElement("div");
                header.textContent = day;
                header.className = "header";
                if (index === 0) header.style.color = "red"; // 日曜日
                if (index === 6) header.style.color = "blue"; // 土曜日
                calendar.appendChild(header);
            });

            // その月の最初の日と日数を取得
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            // 空白部分を追加
            for (let i = 0; i < firstDay; i++) {
                const empty = document.createElement("div");
                calendar.appendChild(empty);
            }

            // 日付を追加
            const today = new Date();
            for (let day = 1; day <= daysInMonth; day++) {
                const date = document.createElement("div");
                const currentDate = new Date(year, month, day);
                const weekDay = currentDate.getDay(); // 曜日（0:日曜日, 6:土曜日）
                date.textContent = day;

                // 今日の日付をハイライト
                if (year === today.getFullYear() && month === today.getMonth() && day === today.getDate()) {
                    date.className = "today";
                }

                // 曜日ごとに色付け
                if (weekDay === 0) date.style.color = "red"; // 日曜日
                if (weekDay === 6) date.style.color = "blue"; // 土曜日

                calendar.appendChild(date);
            }

            container.appendChild(calendar);
            return container;
        }

        // 前後12か月分のカレンダーを生成
        function generateAllCalendars() {
            const container = document.getElementById("calendar-container");
            const today = new Date();
            const currentYear = today.getFullYear();
            const currentMonth = today.getMonth();

            let todayElement = null; // 初期化

            // 前12か月
            for (let i = 12; i > 0; i--) {
                const date = new Date(currentYear, currentMonth - i);
                const calendar = generateCalendar(date.getFullYear(), date.getMonth());
                container.appendChild(calendar);
            }

            // 今月
            const currentCalendar = generateCalendar(currentYear, currentMonth);
            container.appendChild(currentCalendar);
            todayElement = currentCalendar.querySelector(".today"); // 今日の日付要素を取得

            // 次12か月
            for (let i = 1; i <= 12; i++) {
                const date = new Date(currentYear, currentMonth + i);
                const calendar = generateCalendar(date.getFullYear(), date.getMonth());
                container.appendChild(calendar); // 今月の後に追加
            }

            // 今日の日付が真ん中に表示されるようにスクロール
            if (todayElement) {
                setTimeout(() => {
                    // const headerHeight = document.querySelector("header").offsetHeight;
                    // const scrollY = todayElement.getBoundingClientRect().top + window.scrollY - (window.innerHeight / 2) + (todayElement.offsetHeight / 2);
                    // window.scrollTo({ top: scrollY, behavior: "smooth" });
                    todayElement.scrollIntoView({ behavior: "smooth", block: "center" });
                }, 200); // レイアウト確定後にスクロールする
            }
        }

        // カレンダーを生成
        generateAllCalendars();
    </script>

</div>

<footer><?php include 'menu.php'; ?></footer>
</body>
</html>