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


        // タブをクリックした際の動作
        function handleTabClick(event) {
            const clickedTab = event.target;

            if (clickedTab.id === 'tab-left') {
                currentDay.setDate(currentDay.getDate() - 1);
            } else if (clickedTab.id === 'tab-right') {
                currentDay.setDate(currentDay.getDate() + 1);
            }

            updateTabs();        //タブの日付を更新
            setFormattedDate();  //中央タブの日付をhiddenフィールドにセット

            // 中央タブの日付に基づいてTODOリストを再取得して表示
            const date = getCenterTabDate(); // 中央タブのYYYY-MM-DD形式の日付を取得
            loadTodos(date);                // サーバーからTODOを再取得して更新

            // // 新しい日付に基づいてTODOリストを取得して表示
            // loadTodos(getCenterTabDate());  // 中央のタブの日付に基づいてTODOを再取得
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
                    sortableList.innerHTML = '';  // 現在のリストをクリア

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
        window.onload = function() {
            setFormattedDate();  // hiddenフィールドに現在の日付をセット
            const date = getCenterTabDate(); // 中央タブの日付
            loadTodos(date);                // TODOリストを取得して表示
        };
        

        tabLeft.addEventListener('click', handleTabClick);
        tabRight.addEventListener('click', handleTabClick);

        updateTabs();


        const toggleModeButton = document.getElementById('toggleMode');
        const addTodoButton = document.getElementById('addTodo');
        const sortableList = document.getElementById('sortable-list');
        const todoForm = document.getElementById('todo-form');
        const todoInput = document.getElementById('todo-input');

        //編集モードの状態を管理
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

        // 編集モードに応じてterm-containerの表示切り替え
        const termContainer = document.querySelector('.term-container');
        if (termContainer) {
            termContainer.style.display = isEditMode ? 'none' : 'block';
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


        // // 現在の日付を取得して YYYY-MM-DD フォーマットにする関数
        // function getFormattedDate(date) {
        //     const year = date.getFullYear();
        //     const month = ("0" + (date.getMonth() + 1)).slice(-2); // 月を2桁に
        //     const day = ("0" + date.getDate()).slice(-2); // 日を2桁に
        //     return `${year}-${month}-${day}`;
        // }

        // // 中央のタブの日付を取得してフォーマットする関数
        // function getCenterTabDate() {
        //     const tabCenterText = document.getElementById('tab-center').innerText;
            
        //     // タブに表示されている日付 (例: "10/23") を変換
        //     const [month, day] = tabCenterText.split('/');
        //     const today = new Date();
        //     const year = today.getFullYear();

        //     const formattedDate = `${year}-${("0" + month).slice(-2)}-${("0" + day).slice(-2)}`;
        //     return formattedDate;
        // }


        // タブの中央にある日付 (例: "10/23") を取得して、フォーマットを変換する関数
        function getCenterTabDate() {
            const tabCenterText = document.getElementById('tab-center').innerText;

            // タブに表示されている日付 (例: "10/23") を [月, 日] の配列に分割
            const [month, day] = tabCenterText.split('/');

            // 今日の日付から年を取得
            const today = new Date();
            const year = today.getFullYear();

            // 年、月、日を組み合わせて、YYYY-MM-DDの形式に変換
            const formattedDate = `${year}-${("0" + month).slice(-2)}-${("0" + day).slice(-2)}`;
            return formattedDate;
        }

        // 取得した日付をフォームのhiddenフィールドにセットする
        function setFormattedDate() {
            const formattedDate = getCenterTabDate();  // 中央のタブの日付を取得してフォーマット
            document.getElementById('formatted-date').value = formattedDate;  // hiddenフィールドにセット
        }

        // ページ読み込み時に`formattedDate`を設定する
        window.onload = function() {
            setFormattedDate();  // ページが読み込まれたら自動で日付をセット
        };

        



    </script>

</body>
</html>
