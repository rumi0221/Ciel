<?php require 'db-connect.php'; ?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/G3-1.css">
    <link rel="stylesheet" href="css/menu.css">
    <title>アプリ</title>
</head>
<body>

    <img class="logo" src="img/Ciel logo.png">

    <div class="tab-container">
        <div class="tab-list" id="tab-list">
            <div class="tab" id="tab-left"></div>
            <div class="tab active" id="tab-center"></div>
            <div class="tab" id="tab-right"></div>
        </div>        
        <div id="todocalendar">
            <form action="G3-2" method="POST">
                <button type="submit" class="formbtng3-2">
                <img src="img/calendar.png" style="height:30px; width:30px;"><br>
                ToDo
                </button>
            </form>
        </div>
    </div>
    

    <?php
        $pdo = new PDO($connect, USER, PASS);
        //仮で入れている
        $user_name = 'Test2';
        $user_pass = '';

        $Date = $_POST['formattedDate'] ?? date('Y-m-d');
        $user_id = 8;
    ?>

    <div class="background">
    <br>
    <?php
        //条件の中にこの画面の日付がplanの日付の中に含まれているのかを書く
        //とりあえず日付を10/23にしてtermが機能するのか試す　今日の日付にする場合（CURDATE()）
        $sql=$pdo->prepare('SELECT * FROM Plans WHERE user_id = ? AND start_date <= ? AND final_date >= ? AND todo_flg = 1');
        $sql->execute([$user_id, $Date, $Date]);
        echo '
            <div class="term-container">
                <div class="term-header" onclick="toggleTerm()">
                    <span id="term-title" class="term-title">term</span>
                    <span id="arrow">▼</span>
                </div>
            <div id="term-content" class="term-content">
        ';
        foreach($sql as $row){
            $plan_id = $row['plan_id']; // plan_idを取得
            $plan = $row['plan'];
            $fdate = $row['final_date'];
            $date = new DateTime($fdate);    // DateTimeオブジェクトに変換
            $formattedDate = $date->format('m/d'); // 月/日 の形式に変換
            $completion = $row['completion_flg'];
            $check = ($completion == 1) ? 'checked' : '';
            echo '
                <div class="term-item">
                    <input type="checkbox" data-id="', $plan_id, '" ', $check, ' style="transform: scale(1.4);">
                    <label class="term-list">　', $plan, '</label>
                    <span class="due-date">　', $formattedDate, 'まで</span>
                </div>
                ';
        }

        echo '</div></div>';

    ?>

        <ul id="sortable-list">
            <?php
                //日付の条件をつけて → sortで昇順にする
                $sql2=$pdo->prepare('select * from Todos where user_id = ? and input_date = ?');
                $sql2->execute([$user_id, $Date]);
                foreach($sql2 as $row2){
                    $todo_id = $row2['todo_id'];
                    $sort = $row2['sort_id'];
                    $todo = htmlspecialchars($row2['todo']); // XSS防止
                    $completion = $row2['completion_flg'];
                    $check = ($completion == 1) ? 'checked' : '';
                    echo '
                        <li class="normal-mode" data-id="', $todo_id, '">
                            <input type="checkbox" class="hide-checkbox" data-id="', $todo_id, '" ', $check, '>
                            <img src="img/grip-lines.png" class="edit-mode-icon" style="display: none;">
                            <span class="todo-text">', $todo, '</span>
                            <input type="text" class="edit-todo-input" value="', $todo, '" style="display: none; margin-left:5px;">
                            <button class="delete-button" style="display: none;"><img src="img/dustbox.png" style="height: 23px; width: auto;"></button>
                        </li>
                    ';
                }
            ?>
        </ul>

        <div class="tododiv">
            <form id="todo-form" class="todo-form">
                <input type="text" class="todo-inp" id="todo-input" placeholder=" TODOを追加する">
                <input type="hidden" id="formatted-date" name="formattedDate"> <!-- タブの日付をここにセット -->
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
    document.addEventListener('DOMContentLoaded', function () {
        // Termの既存コード
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

        // タブ関連のコード
        const tabLeft = document.getElementById('tab-left');
        const tabCenter = document.getElementById('tab-center');
        const tabRight = document.getElementById('tab-right');
        const sortableList = document.getElementById('sortable-list');

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

        // タブをクリックした際の動作
        function handleTabClick(event) {
            const clickedTab = event.target;

            if (clickedTab.id === 'tab-left') {
                currentDay.setDate(currentDay.getDate() - 1);
            } else if (clickedTab.id === 'tab-right') {
                currentDay.setDate(currentDay.getDate() + 1);
            }

            updateTabs();        // タブの日付を更新
            setFormattedDate();  // 中央タブの日付をhiddenフィールドにセット

            // 中央タブの日付に基づいてTODOリストを再取得して表示
            const date = getCenterTabDate();
            loadTodos(date);
        }

        // TODOリストを取得して更新する関数
        function loadTodos(date) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'get_todos.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // サーバーに日付を送信してTODOを取得
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const todos = JSON.parse(xhr.responseText);
                    const sortableList = document.getElementById('sortable-list');
                    sortableList.innerHTML = ''; // 現在のリストをクリア

                    // 新しいTODOリストを表示
                    todos.forEach(todo => {
                        const todoItem = `
                            <li class="normal-mode" data-id="${todo.todo_id}">
                                <input type="checkbox" class="hide-checkbox" data-id="${todo.todo_id}" ${todo.completion_flg == 1 ? 'checked' : ''}>
                                <img src="img/grip-lines.png" class="edit-mode-icon" style="display: none;">
                                <span class="todo-text">${todo.todo}</span>
                                <input type="text" class="edit-todo-input" value="${todo.todo}" style="display: none; margin-left:5px;">
                                <button class="delete-button" style="display: none;"><img src="img/dustbox.png" style="height: 23px; width: auto;"></button>
                            </li>
                        `;
                        sortableList.innerHTML += todoItem;
                    });
                }
            };

            xhr.send('formattedDate=' + date);
        }

        // ページ初期化時に中央タブの日付のTODOを表示
        window.onload = function () {
            setFormattedDate();
            const date = getCenterTabDate();
            loadTodos(date);
        };

        tabLeft.addEventListener('click', handleTabClick);
        tabRight.addEventListener('click', handleTabClick);

        updateTabs();

        // 編集モードの切り替え処理
        const toggleModeButton = document.getElementById('toggleMode');

        // 編集モード時に非表示にする要素を取得
        const termContainer = document.querySelector('.term-container');
        const todoInput = document.getElementById('todo-input');
        const todoCalendar = document.getElementById('todocalendar');

        let isEditMode = false;

        toggleModeButton.addEventListener('click', function (event) {
            event.preventDefault();
            isEditMode = !isEditMode;

            if (isEditMode) {
                tabLeft.style.display = 'none';  // 編集モードでは非表示
                tabRight.style.display = 'none'; // 編集モードでは非表示
                termContainer.style.display = 'none';
                todoInput.style.display = 'none';
                todoCalendar.style.display= 'none';
                // toggleModeButton.textContent = '通常モードに戻る';
            } else {
                tabLeft.style.display = '';  // 通常モードでは表示
                tabRight.style.display = ''; // 通常モードでは表示
                termContainer.style.display = '';
                todoInput.style.display = '';
                todoCalendar.style.display= '';
                // toggleModeButton.textContent = '編集モードへ移行する';
            }

            const todoItems = sortableList.querySelectorAll('li');
            todoItems.forEach(item => {
                const todoText = item.querySelector('.todo-text');
                const editInput = item.querySelector('.edit-todo-input');
                const deleteButton = item.querySelector('.delete-button');
                const checkbox = item.querySelector('.hide-checkbox');
                const editModeIcon = item.querySelector('.edit-mode-icon');

                if (isEditMode) {
                    item.classList.add('edit-mode');
                    todoText.style.display = 'none';
                    editInput.style.display = 'inline-block';
                    deleteButton.style.display = 'block';
                    checkbox.style.display = 'none';
                    editModeIcon.style.display = 'inline-block';
                    item.setAttribute('draggable', 'true');
                } else {
                    const newTodoText = editInput.value.trim();
                    if (newTodoText !== todoText.textContent.trim()) {
                        todoText.textContent = newTodoText;

                        const todoId = item.dataset.id;
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', 'update_todo.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.send('todo_id=' + todoId + '&todo=' + encodeURIComponent(newTodoText));
                    }

                    todoText.style.display = 'inline-block';
                    editInput.style.display = 'none';
                    deleteButton.style.display = 'none';
                    checkbox.style.display = 'inline-block';
                    editModeIcon.style.display = 'none';
                    item.classList.remove('edit-mode');
                    item.removeAttribute('draggable');
                }
            });

            if (isEditMode) {
                enableDragAndDrop();
            }
        });

        // ドラッグ＆ドロップ処理
        function enableDragAndDrop() {
            let draggedItem = null;

            sortableList.addEventListener('dragstart', function (e) {
                if (e.target.tagName === 'LI') {
                    draggedItem = e.target;
                    draggedItem.classList.add('dragging');
                }
            });

            sortableList.addEventListener('dragend', function () {
                if (draggedItem) {
                    draggedItem.classList.remove('dragging');
                    draggedItem = null;
                }
            });

            sortableList.addEventListener('dragover', function (e) {
                e.preventDefault();
                const closestItem = getClosestListItem(e.clientY);
                if (closestItem && closestItem !== draggedItem) {
                    sortableList.insertBefore(draggedItem, closestItem.nextElementSibling);
                }
            });

            sortableList.addEventListener('drop', function (e) {
                e.preventDefault();
                if (draggedItem !== null) {
                    updateSortOrder();
                }
            });
        }

        function getClosestListItem(y) {
            const items = [...sortableList.querySelectorAll('li:not(.dragging)')];
            return items.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                } else {
                    return closest;
                }
            }, { offset: Number.NEGATIVE_INFINITY }).element;
        }

        function updateSortOrder() {
            const todoItems = sortableList.querySelectorAll('li');
            todoItems.forEach((item, index) => {
                const todoId = item.dataset.id;
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_sort.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send('todo_id=' + todoId + '&sort_id=' + index);
            });
        }

        function getCenterTabDate() {
            const tabCenterText = tabCenter.innerText;
            const [month, day] = tabCenterText.split('/');
            const today = new Date();
            const year = today.getFullYear();
            return `${year}-${("0" + month).slice(-2)}-${("0" + day).slice(-2)}`;
        }

        function setFormattedDate() {
            const formattedDate = getCenterTabDate();
            document.getElementById('formatted-date').value = formattedDate;
        }
    });
</script>
</body>
</html>