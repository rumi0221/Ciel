<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3つまでのタブ管理アプリ</title>
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

        // 初期タブの日付設定
        function resetInitialTabs() {
            tabYesterday.innerText = formatDate(yesterday);
            tabToday.innerText = formatDate(today);
            tabTomorrow.innerText = formatDate(tomorrow);
        }

        resetInitialTabs();

        let futureDateCount = 0;
        let pastDateCount = 0;

        function handleTabClick(event) {
            const clickedTab = event.target;
            const allTabs = document.querySelectorAll('.tab');

            // クリックされたタブがすでにアクティブなら何もしない
            if (clickedTab.classList.contains('active')) {
                return;
            }

            // 全てのタブから active クラスを削除
            allTabs.forEach(tab => tab.classList.remove('active'));

            // クリックされたタブに active クラスを追加
            clickedTab.classList.add('active');

            // 未来のタブ（「明日」やそれ以降）がクリックされた場合
            if (clickedTab.id.startsWith('tab-future-') || clickedTab.id === 'tab-tomorrow') {
                futureDateCount++;
                const newFutureDate = new Date();
                newFutureDate.setDate(today.getDate() + futureDateCount + 1);

                // 新しい未来の日付のタブを右側に追加
                const newTab = document.createElement('div');
                newTab.classList.add('tab');
                newTab.id = `tab-future-${futureDateCount}`;
                newTab.innerText = formatDate(newFutureDate);
                tabList.appendChild(newTab);
                attachTabClickEvent(newTab);

                // タブが4つ以上にならないように調整
                while (tabList.childElementCount > 3) {
                    tabList.removeChild(tabList.firstChild); // 左側のタブを削除
                }
            }

            // 過去のタブ（「昨日」やそれ以前）がクリックされた場合
            if (clickedTab.id.startsWith('tab-past-') || clickedTab.id === 'tab-yesterday') {
                pastDateCount++;
                const newPastDate = new Date();
                newPastDate.setDate(today.getDate() - pastDateCount - 1);

                // 新しい過去の日付のタブを左側に追加
                const newTab = document.createElement('div');
                newTab.classList.add('tab');
                newTab.id = `tab-past-${pastDateCount}`;
                newTab.innerText = formatDate(newPastDate);
                tabList.insertBefore(newTab, tabList.firstChild); // 左側に追加
                attachTabClickEvent(newTab);

                // タブが4つ以上にならないように調整
                while (tabList.childElementCount > 3) {
                    tabList.removeChild(tabList.lastChild); // 右側のタブを削除
                }
            }

            // 今日のタブがクリックされたときは初期状態に戻す
            if (clickedTab.id === 'tab-today') {
                resetTabsToInitialState();
            }
        }

        // 初期状態に戻す関数
        function resetTabsToInitialState() {
            tabList.innerHTML = ''; // すべてのタブを削除
            tabList.appendChild(tabYesterday);  // 昨日のタブ
            tabList.appendChild(tabToday);      // 今日のタブ
            tabList.appendChild(tabTomorrow);   // 明日のタブ

            resetInitialTabs();  // タブの日付を再設定
            tabToday.classList.add('active');  // 今日のタブをアクティブに
            futureDateCount = 0;
            pastDateCount = 0;
        }

        const tabs = document.querySelectorAll('.tab');
        tabs.forEach(tab => attachTabClickEvent(tab));

        function attachTabClickEvent(tab) {
            tab.addEventListener('click', handleTabClick);
        }
    </script>

</body>
</html>
