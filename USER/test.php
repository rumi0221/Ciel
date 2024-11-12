<?php require 'db-connect.php'; ?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
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
    </div>

    <?php
        $pdo = new PDO($connect, USER, PASS);
        //仮で入れている
        $user_name = 'Test2';
        $user_pass = '';

        $user_id = 8;
    ?>

    <div class="background">
    <br>
    <?php
        //条件の中にこの画面の日付がplanの日付の中に含まれているのかを書く
        //とりあえず日付を10/23にしてtermが機能するのか試す　今日の日付にする場合（CURDATE()）
        $sql=$pdo->prepare('SELECT * FROM Plans WHERE user_id = ? AND start_date <= "2024-10-23 23:59:59" AND final_date >= "2024-10-23 00:00:00" AND todo_flg = 1');
        $sql->execute([$user_id]);
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
                $sql2=$pdo->prepare('select * from Todos where user_id = ?');
                $sql2->execute([$user_id]);
                // foreach($sql2 as $row2){
                //     $todo_id = $row2['todo_id'];
                //     $sort = $row2['sort_id'];
                //     $todo = $row2['todo'];
                //     $completion = $row2['completion_flg'];
                //     $check = ($completion == 1) ? 'checked' : '';
                //     echo '
                //         <li class="normal-mode">
                //             <input type="checkbox" data-id="', $todo_id, '" class="hide-checkbox"', $check, '> ', $todo, '
                //             <button class="delete-button"><img src="img/dustbox.png" style="height: 23px; width: auto;"></button>
                //         </li>
                //         ';
                // }

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


        //名前変更・並び替え
        toggleModeButton.addEventListener('click', (event) => {
        event.preventDefault();
        isEditMode = !isEditMode;

        const todoInput = document.getElementById('todo-input');

        if (isEditMode) {
            todoInput.style.display = 'none';  // 編集モード時は非表示
        } else {
            todoInput.style.display = 'block';  // 通常モード時は表示
        }

        const todoItems = sortableList.querySelectorAll('li');
        todoItems.forEach(item => {
            const todoText = item.querySelector('.todo-text');
            const editInput = item.querySelector('.edit-todo-input');
            const deleteButton = item.querySelector('.delete-button');
            const checkbox = item.querySelector('.hide-checkbox'); // チェックボックスの取得
            const editModeIcon = item.querySelector('.edit-mode-icon'); //画像の取得

            if (isEditMode) {
                item.classList.add('edit-mode');
                todoText.style.display = 'none';
                editInput.style.display = 'inline-block';
                deleteButton.style.display = 'block';
                checkbox.style.display = 'none'; // 編集モードではチェックボックスを非表示
                editModeIcon.style.display = 'inline-block'; //画像を表示
                item.setAttribute('draggable', 'true');
            } else {
                const newTodoText = editInput.value.trim();
                if (newTodoText !== todoText.textContent.trim()) {
                    todoText.textContent = newTodoText;
                    // Ajaxで名前を更新
                    const todoId = item.dataset.id;
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'update_todo.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.send('todo_id=' + todoId + '&todo=' + encodeURIComponent(newTodoText));
                }

                todoText.style.display = 'inline-block';
                editInput.style.display = 'none';
                deleteButton.style.display = 'none';
                checkbox.style.display = 'inline-block'; // 通常モードではチェックボックスを表示
                editModeIcon.style.display = 'none'; //画像を非表示
                item.classList.remove('edit-mode');
                item.removeAttribute('draggable');
            }
        });

        if (isEditMode) {
            enableDragAndDrop();
        }
    });


    //編集モードの並び替え
    function enableDragAndDrop() {
        let draggedItem = null;


        sortableList.addEventListener('dragstart', function (e) {
            if (e.target.tagName === 'LI') {
                draggedItem = e.target;
                draggedItem.classList.add('dragging');  // ドラッグ開始時にクラスを追加
                // setTimeout(() => {
                //     e.target.style.display = 'none';
                // }, 0);
            }
        });

        sortableList.addEventListener('dragend', function (e) {
            if (draggedItem) {
                draggedItem.classList.remove('dragging');  // ドラッグ終了時にクラスを削除
                // draggedItem.style.display = 'block';
                draggedItem = null;
            }
        });


        sortableList.addEventListener('dragstart', function (e) {
            if (e.target.closest('.edit-mode-icon')) {  // 画像部分をドラッグ対象に
                draggedItem = e.target.closest('li');
                // setTimeout(() => {
                //     draggedItem.style.display = 'none';  // ドラッグ中は非表示
                // }, 0);
            }
        });

        sortableList.addEventListener('dragend', function (e) {
            setTimeout(() => {
                // draggedItem.style.display = 'block';  // ドラッグ終了後に表示
                draggedItem = null;
            }, 0);
        });

        sortableList.addEventListener('dragover', function (e) {
            e.preventDefault();  // ドラッグ中に他の要素の上に移動できるようにする
            const closestItem = getClosestListItem(e.clientY);
            if (closestItem && closestItem !== draggedItem) {
                sortableList.insertBefore(draggedItem, closestItem.nextElementSibling);
            }
        });

        sortableList.addEventListener('drop', function (e) {
            e.preventDefault();
            if (draggedItem !== null) {
                // draggedItem.style.display = 'block';  // ドロップ時に再表示(こいつが戦犯)
                updateSortOrder();
            }
        });
    }

    // ドラッグ位置に一番近いリスト項目を取得する関数
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



    //削除ボタンをクリックした場合
    sortableList.addEventListener('click', (e) => {
        if (e.target.closest('.delete-button')) {
            const todoItem = e.target.closest('li');
            const todoId = todoItem.dataset.id;

            // Ajaxリクエストで削除
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_todo.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('todo_id=' + todoId);

            // DOMから削除
            todoItem.remove();
        }
    });




    function updateSortOrder() {
        const todoItems = sortableList.querySelectorAll('li');
        todoItems.forEach((item, index) => {
            const todoId = item.dataset.id;
            // Ajaxで並び順を更新
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_sort.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('todo_id=' + todoId + '&sort_id=' + index);
        });
    }


        //チェックボックス
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const planId = this.dataset.planId;  // 各チェックボックスに対応するプランのIDを取得
                const isChecked = this.checked ? 1 : 0;  // チェックされているかどうか

                // Ajaxリクエストを送信
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'checkbox_update.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        console.log('Completion flag updated successfully');
                    }
                };
                xhr.send('plan_id=' + planId + '&completion_flg=' + isChecked);
            });
        });

        



    </script>

</body>
</html>
