<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タブ管理アプリ</title>
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
            overflow: hidden; /* 追加 */
            position: relative; /* 追加 */
            width: 100%; /* 追加 */
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
            position: absolute; /* 追加 */
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

    <div class="todo-list">
        <div class="todo-item">
            <input type="checkbox"> おつかい
        </div>
        <div class="todo-item">
            <input type="checkbox"> お菓子の補充
        </div>
        <div class="todo-item">
            <input type="checkbox"> ATMからお金をおろす
        </div>
        <div class="todo-item">
            <input type="checkbox"> 新刊「○○」
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

        function handleTabClick(event) {
            const clickedTab = event.target;
            const allTabs = document.querySelectorAll('.tab');
            allTabs.forEach(tab => tab.classList.remove('active'));
            clickedTab.classList.add('active');

            // クリックされたタブを中央に配置
            centerTab(clickedTab);

            // 次の日のタブを生成
            if (clickedTab.id === 'tab-tomorrow' || clickedTab.id.startsWith('tab-future-')) {
                futureDateCount++;
                const newFutureDate = new Date();
                newFutureDate.setDate(today.getDate() + futureDateCount + 1); // 明日の次の日

                const newTab = document.createElement('div');
                newTab.classList.add('tab');
                newTab.id = `tab-future-${futureDateCount}`;
                newTab.innerText = formatDate(newFutureDate);
                tabList.appendChild(newTab);
                attachTabClickEvent(newTab);
            }

            // 3つ以上のタブがある場合に左側か右側を削除
            const totalTabs = tabList.children.length;
            if (totalTabs > 3) {
                tabList.removeChild(tabList.firstChild); // 左側のタブを削除
            }
        }

        function centerTab(clickedTab) {
            const clickedTabIndex = Array.from(tabList.children).indexOf(clickedTab);
            const targetIndex = 1; // 中央に表示するタブのインデックス

            let offsetIndex = clickedTabIndex - targetIndex; // オフセット計算
            tabList.style.transform = `translateX(${-offsetIndex * (clickedTab.offsetWidth + 10)}px)`; // スライド計算
        }

        const tabs = document.querySelectorAll('.tab');
        tabs.forEach(tab => attachTabClickEvent(tab));

        function attachTabClickEvent(tab) {
            tab.addEventListener('click', handleTabClick);
        }

        centerTab(tabToday); // 初期状態で今日のタブを中央に
    </script>

</body>
</html>
