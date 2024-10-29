<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/menu.css">
    <title>常に3つのタブが表示されるアプリ</title>
    <style>
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
            justify-content: center;
            transition: transform 0.3s ease;
            position: relative;
        }

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            padding: 8px;
            margin-bottom: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            text-align: left;
        }

        .background {
            background-color: #E1DBFF;
            width: 100%;
            height: 100%;
            overflow: auto;
        }

        /* term */
        .term-container {
            display: block;
            margin: auto;
            width: 300px;
            background-color: #D7B8F5;
            border-radius: 10px;
            padding: 10px;
            position: relative;
        }

        .term-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            background-color: #D7B8F5;
            padding: 10px;
            border-radius: 5px;
            position: relative;
        }

        .term-title {
            transition: all 0.5s ease;
            position: relative;
        }

        .term-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease, padding 0.5s ease;
            padding: 0;
        }

        .term-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
        }

        .due-date {
            color: red;
        }

        .open {
            max-height: 200px;
            padding: 10px 0;
        }

        #arrow {
            transition: transform 0.5s ease;
        }

        .rotated {
            transform: rotate(180deg);
        }

        .term-title.move-to-top-left {
            position: absolute;
            top: -15px;
            left: 10px;
            font-size: 16px;
        }

        /* .termblock {
            margin: 20px auto;
            padding: 10px;
            width: 80%;
            background-color: #DBB5FF;
            border: none;
            border-radius: 10px;
        } */

        .tododiv {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            position: fixed;
            bottom: 100px;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 10px;
        }

        .todo-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .todo-inp {
            height: 3em;
            width: 20em;
            border-radius: 5px;
        }

        .todo-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            background-color: #FFF;
            border-radius: 10px;
        }

        .normal-mode {
            background-color: #f0f0f0;
        }

        .edit-mode {
            background-color: #e0e0e0;
            border: 1px dashed #000;
            justify-content: space-between;
        }

        .hide-checkbox {
            display: inline-block;
        }

        .delete-button {
            background-color: transparent;
            border: none;
            margin-left: 10px;
            display: none;
        }
    </style>
</head>
<body>

    <img class="logo" src="img/Ciel logo.png">

    <div class="tab-container">
        <div class="tab-list" id="tab-list">
            <div class="tab" id="tab-left"></div>
            <div class="tab active" id="tab-center"></div>
            <div class="tab" id="tab-right"></div>
        </div>
    </div>

    <div class="background">
    <br>
    <div class="term-container">
        <div class="term-header" onclick="toggleTerm()">
            <span id="term-title" class="term-title">term(2)</span>
            <span id="arrow">▼</span>
        </div>
        <div id="term-content" class="term-content">
            <div class="term-item">
                <input type="checkbox">
                <label>レポート課題</label>
                <span class="due-date">1/6まで</span>
            </div>
            <div class="term-item">
                <input type="checkbox">
                <label>新刊「○○」</label>
                <span class="due-date">1/20まで</span>
            </div>
        </div>
    </div>

        <ul id="sortable-list">
            <li class="normal-mode" data-id="1">
                <input type="checkbox" class="hide-checkbox"> 文1
                <button class="delete-button"><img src="img/dustbox.png" style="height: 23px; width: auto;"></button>
            </li>
            <li class="normal-mode" data-id="2">
                <input type="checkbox" class="hide-checkbox"> 文2
                <button class="delete-button"><img src="img/dustbox.png" style="height: 23px; width: auto;"></button>
            </li>
            <li class="normal-mode" data-id="3">
                <input type="checkbox" class="hide-checkbox"> 文3
                <button class="delete-button"><img src="img/dustbox.png" style="height: 23px; width: auto;"></button>
            </li>
        </ul>

        <div class="tododiv">
            <form id="todo-form" class="todo-form">
                <input type="text" class="todo-inp" id="todo-input" placeholder=" TODOを追加する">
                <button class="todo-btn" id="addTodo" style="display: none;">
                    <img src="img/add.png" style="height:30px; width:30px;">
                </button>
                <button class="todo-btn" id="toggleMode">
                    <img src="img/edit.png" style="height:30px; width:30px;">
                </button>
            </form>
        </div>

        <div id="output"></div>
    </div>

    <footer><?php include 'menu.php'; ?></footer>

    <script>

        //term
        function toggleTerm() {
            const content = document.getElementById('term-content');
            const arrow = document.getElementById('arrow');
            const title = document.getElementById('term-title');

            if (content.classList.contains('open')) {
                content.classList.remove('open');
                arrow.classList.remove('rotated');
                title.classList.remove('move-to-top');
                title.textContent = "term(2)";
            } else {
                content.classList.add('open');
                arrow.classList.add('rotated');
                title.classList.add('move-to-top');
                title.textContent = "term";
            }
        }

        // tab
        const tabLeft = document.getElementById('tab-left');
        const tabCenter = document.getElementById('tab-center');
        const tabRight = document.getElementById('tab-right');
        const tabList = document.getElementById('tab-list');

        let today = new Date();
        let currentDay = new Date(today);

        function formatDate(date) {
            const month = date.getMonth() + 1;
            const day = date.getDate();
            return `${month}/${day}`;
        }

        function updateTabs() {
            let yesterday = new Date(currentDay);
            yesterday.setDate(currentDay.getDate() - 1);
            let tomorrow = new Date(currentDay);
            tomorrow.setDate(currentDay.getDate() + 1);

            tabLeft.innerText = formatDate(yesterday);
            tabCenter.innerText = formatDate(currentDay);
            tabRight.innerText = formatDate(tomorrow);
        }

        function handleTabClick(event) {
            const clickedTab = event.target;

            if (clickedTab.id === 'tab-left') {
                currentDay.setDate(currentDay.getDate() - 1);
            } else if (clickedTab.id === 'tab-right') {
                currentDay.setDate(currentDay.getDate() + 1);
            }

            updateTabs();
        }

        tabLeft.addEventListener('click', handleTabClick);
        tabRight.addEventListener('click', handleTabClick);

        updateTabs();

        const toggleModeButton = document.getElementById('toggleMode');
        const addTodoButton = document.getElementById('addTodo');
        const sortableList = document.getElementById('sortable-list');
        const todoForm = document.getElementById('todo-form');
        const todoInput = document.getElementById('todo-input');
        let isEditMode = false;

        toggleModeButton.addEventListener('click', (event) => {
            event.preventDefault();
            isEditMode = !isEditMode;
            const checkboxes = document.querySelectorAll('.hide-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.style.display = isEditMode ? 'none' : 'inline-block';
            });

            addTodoButton.style.display = 'none';
            todoInput.style.display = isEditMode ? 'none' : 'inline-block';
            const buttonIcon = toggleModeButton.querySelector('img');
            buttonIcon.src = isEditMode ? 'img/edit.png' : 'img/edit.png';

            sortableList.querySelectorAll('li').forEach(li => {
                if (isEditMode) {
                    li.classList.add('edit-mode');
                    li.classList.remove('normal-mode');
                    li.querySelector('.delete-button').style.display = 'block';
                    li.setAttribute('draggable', 'true');
                } else {
                    li.classList.add('normal-mode');
                    li.classList.remove('edit-mode');
                    li.querySelector('.delete-button').style.display = 'none';
                    li.removeAttribute('draggable');
                }
            });

            sortableList.querySelectorAll('.delete-button').forEach(button => {
                attachDeleteHandler(button);
            });

            if (isEditMode) {
                enableDragAndDrop();
            }
        });

        function enableDragAndDrop() {
            let draggedItem = null;
            let overItem = null;

            sortableList.addEventListener('dragstart', function (e) {
                if (e.target.tagName === 'LI') {
                    draggedItem = e.target;
                    setTimeout(() => {
                        e.target.style.display = 'none';
                    }, 0);
                }
            });

            sortableList.addEventListener('dragend', function (e) {
                setTimeout(() => {
                    draggedItem.style.display = 'block';
                    draggedItem = null;
                }, 0);
            });

            sortableList.addEventListener('dragover', function (e) {
                e.preventDefault();
                if (e.target.tagName === 'LI') {
                    overItem = e.target;
                }
            });

            sortableList.addEventListener('drop', function (e) {
                e.preventDefault();
                if (draggedItem !== null && overItem !== null && draggedItem !== overItem) {
                    const draggedId = draggedItem.dataset.id;
                    const overId = overItem.dataset.id;

                    if (draggedId && overId) {
                        swapListItems(draggedItem, overItem);
                    }
                }
            });
        }

        function swapListItems(item1, item2) {
            const parent = item1.parentElement;
            const nextSibling = item1.nextElementSibling === item2 ? item1 : item1.nextElementSibling;
            parent.insertBefore(item1, item2);
            parent.insertBefore(item2, nextSibling);
        }

        function attachDeleteHandler(button) {
            button.addEventListener('click', function () {
                button.parentElement.remove();
            });
        }
    </script>

</body>
</html>
