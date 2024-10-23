<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>デバッグ用タブ管理アプリ</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #E1DBFF;
        }

        .tab-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
            background-color: #fff;
            overflow: hidden;
            position: relative;
            width: 100%;
        }

        .tab {
            padding: 10px 20px;
            background-color: #f5deb3;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            white-space: nowrap;
        }

        .tab.active {
            background-color: #E1DBFF;
            transform: scale(1.1);
        }

        .tab-list {
            display: flex;
            transition: transform 0.3s ease;
            position: relative;
        }

        .todo-list {
            padding: 20px;
            background-color: #e1dbff;
        }

        .todo-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .todo-item input[type="checkbox"] {
            margin-right: 10px;
        }
    </style>
</head>
<body>

    <div class="tab-container">
        <div class="tab-list" id="tab-list">
            <div class="tab" id="tab-yesterday"></div>
            <div class="tab active" id="tab-today"></div>
            <div class="tab" id="tab-tomorrow"></div>
        </div>
    </div>

    <script>
        const tabYesterday = document.getElementById('tab-yesterday');
        const tabToday = document.getElementById('tab-today');
        const tabTomorrow = document.getElementById('tab-tomorrow');
        const tabList = document.getElementById('tab-list');

        let today = new Date();
        let tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);
        let yesterday = new Date(today);
        yesterday.setDate(today.getDate() - 1);

        function formatDate(date) {
            const month = date.getMonth() + 1;
            const day = date.getDate();
            return `${month}/${day}`;
        }

        tabYesterday.innerText = formatDate(yesterday);
        tabToday.innerText = formatDate(today);
        tabTomorrow.innerText = formatDate(tomorrow);

        let futureDateCount = 0;
        let pastDateCount = 0;

        function handleTabClick(event) {
            const clickedTab = event.target;
            const allTabs = document.querySelectorAll('.tab');
            allTabs.forEach(tab => tab.classList.remove('active'));
            clickedTab.classList.add('active');

            // デバッグ用のログを追加
            console.log(`タブがクリックされました: ${clickedTab.innerText}`);

            // クリックされたタブが明日以降の場合
            if (clickedTab.id === 'tab-tomorrow' || clickedTab.id.startsWith('tab-future-')) {
                futureDateCount++;
                const newFutureDate = new Date();
                newFutureDate.setDate(today.getDate() + futureDateCount + 1);

                // 新しいタブを右側に追加
                const newTab = document.createElement('div');
                newTab.classList.add('tab');
                newTab.id = `tab-future-${futureDateCount}`;
                newTab.innerText = formatDate(newFutureDate);
                tabList.appendChild(newTab);
                attachTabClickEvent(newTab);
            }

            // クリックされたタブが昨日以前の場合
            if (clickedTab.id === 'tab-yesterday' || clickedTab.id.startsWith('tab-past-')) {
                pastDateCount++;
                const newPastDate = new Date();
                newPastDate.setDate(today.getDate() - pastDateCount - 1);

                // 新しいタブを左側に追加
                const newTab = document.createElement('div');
                newTab.classList.add('tab');
                newTab.id = `tab-past-${pastDateCount}`;
                newTab.innerText = formatDate(newPastDate);
                tabList.insertBefore(newTab, tabList.firstChild); // 左側に追加
                attachTabClickEvent(newTab);
            }
        }

        const tabs = document.querySelectorAll('.tab');
        tabs.forEach(tab => attachTabClickEvent(tab));

        function attachTabClickEvent(tab) {
            tab.addEventListener('click', handleTabClick);
        }
    </script>

</body>
</html>
