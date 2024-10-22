<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo App</title>
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
            gap: 10px;
            padding: 10px;
            background-color: #fff;
        }

        .tab {
            padding: 10px 20px;
            background-color: #f5deb3;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.3s;
            white-space: nowrap;
        }

        .tab.active {
            background-color: #E1DBFF;
            transform: scale(1.1);
        }

        .tab-wrapper {
            display: flex;
            overflow: hidden;
            justify-content: center;
            width: 100%;
        }

        .tab-list {
            display: flex;
            transition: transform 0.3s ease;
        }

        .card {
            background-color: #f0eaff;
            padding: 10px;
            border-radius: 10px;
            margin: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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

    <div class="tab-wrapper">
        <div class="tab-list" id="tab-list">
            <div class="tab" id="tab-yesterday"></div>
            <div class="tab active" id="tab-today"></div>
            <div class="tab" id="tab-tomorrow"></div>
            <div class="tab" id="tab-day-after-tomorrow"></div>
        </div>
    </div>

    <div class="todo-list">
        <div class="card">
            <p>レポート課題</p>
            <small>冬季課題 | 1/6まで</small>
        </div>
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
        const tabDayAfterTomorrow = document.getElementById('tab-day-after-tomorrow');
        const tabList = document.getElementById('tab-list');

        let today = new Date();
        let tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);
        let dayAfterTomorrow = new Date(today);
        dayAfterTomorrow.setDate(today.getDate() + 2);

        let futureDateCount = 2;

        function formatDate(date) {
            const month = date.getMonth() + 1;
            const day = date.getDate();
            return `${month}/${day}`;
        }

        tabYesterday.innerText = formatDate(new Date(today.setDate(today.getDate() - 1)));
        tabToday.innerText = formatDate(new Date());
        tabTomorrow.innerText = formatDate(new Date(tomorrow));
        tabDayAfterTomorrow.innerText = formatDate(new Date(dayAfterTomorrow));

        function handleTabClick(event) {
            const clickedTab = event.target;

            const allTabs = document.querySelectorAll('.tab');
            allTabs.forEach(tab => tab.classList.remove('active'));

            clickedTab.classList.add('active');

            centerTab(clickedTab);

            if (clickedTab.id === 'tab-tomorrow' || clickedTab.id.startsWith('tab-future-')) {
                futureDateCount++;
                const newFutureDate = new Date();
                newFutureDate.setDate(new Date().getDate() + futureDateCount);

                const newTab = document.createElement('div');
                newTab.classList.add('tab');
                newTab.id = `tab-future-${futureDateCount}`;
                newTab.innerText = formatDate(newFutureDate);
                tabList.appendChild(newTab);

                attachTabClickEvent(newTab);
            }
        }

        function centerTab(clickedTab) {
            const tabListWidth = tabList.offsetWidth;
            const clickedTabIndex = Array.from(tabList.children).indexOf(clickedTab);

            const totalTabs = tabList.children.length;
            const targetIndex = 2;

            let offsetIndex = Math.max(clickedTabIndex - targetIndex, 0);

            let scrollPosition = -(clickedTab.offsetWidth + 20) * offsetIndex;
            tabList.style.transform = `translateX(${scrollPosition}px)`;
        }

        const tabs = document.querySelectorAll('.tab');
        tabs.forEach(tab => attachTabClickEvent(tab));

        function attachTabClickEvent(tab) {
            tab.addEventListener('click', handleTabClick);
        }

        centerTab(document.getElementById('tab-today'));
    </script>

</body>
</html>
