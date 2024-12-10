<?php session_start(); ?>
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
        $user_id =  $_SESSION['user']['user_id'];
        $user_name =  $_SESSION['user']['user_name'];

        $Date = $_POST['formattedDate'] ?? date('Y-m-d');

        // G3-2から受け取る日付(Y-m-d)
        $selectedDate = $_POST['selected_date'] ?? $Date;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['selected_date'])){
                $Date = $_POST['selected_date'];
            }else{
                try {
                    // $pdo = new PDO($dsn, $user, $password);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
                    $action = $_POST['action'] ?? '';
                    $todo = $_POST['todo'] ?? '';
                    $Ddate = $_POST['formattedDate'] ?? date('Y-m-d');
        
                    if ($action === 'add_todo' && $todo && $Ddate) {
                        // 現在の日付とユーザーに基づいてsort_idを計算
                        $stmt = $pdo->prepare('SELECT COUNT(*) AS count FROM Todos WHERE user_id = ? AND input_date = ?');
                        $stmt->execute([$user_id, $Ddate]);
                        $sortsum = $stmt->fetchColumn();
        
                        // 新しいTODOを追加
                        $insertStmt = $pdo->prepare('INSERT INTO Todos (`user_id`, `sort_id`, `todo`, `completion_flg`, `input_date`) VALUES (?, ?, ?, DEFAULT, ?)');
                        $insertStmt->execute([$user_id, $sortsum, $todo, $Ddate]);
        
                        // 最新のTODOリストを取得してHTML生成
                        $listStmt = $pdo->prepare('SELECT * FROM Todos WHERE user_id = ? AND input_date = ? ORDER BY sort_id ASC');
                        $listStmt->execute([$user_id, $Ddate]);
                        foreach ($listStmt as $row2) {
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
                    } else {
                        http_response_code(400);
                        echo '無効なリクエスト';
                    }
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo 'エラー: ' . htmlspecialchars($e->getMessage());
                }
                exit;
            }
        }
    ?>


    




    <div class="background">
    <br>
    <?php
        // term
        $sql=$pdo->prepare("SELECT *, date_format(start_date, '%Y-%m-%d') as a, date_format(final_date, '%Y-%m-%d') as b FROM Plans WHERE user_id = ? AND DATE_FORMAT(start_date, '%Y-%m-%d') <= ? AND DATE_FORMAT(final_date, '%Y-%m-%d') >= ? AND todo_flg = 0");
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
            $fdate = DATE($row['final_date']);
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
                $sql2=$pdo->prepare('select * from Todos where user_id = ? and input_date = ? ORDER BY sort_id ASC' );
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
                <button class="todo-btn" id="addTodo" style="display: none;"></button>
                <button class="todo-btn" id="toggleMode">
                    <img src="img/edit.png" style="height:30px; width:30px;">
                </button>
            </form>
        </div>

        <div id="output"></div>
    </div>

    <footer><?php include 'menu.php'; ?></footer>

    <script>
        const selectedDate = <?php echo json_encode($selectedDate); ?>; // PHP変数をJSに渡す
        function setFormattedDate() {
            const formattedDateElement = document.getElementById('formatted-date');
            if (formattedDateElement) {
                const formattedDate = getCenterTabDate();
                formattedDateElement.value = formattedDate;
            } else {
                console.error('Formatted date input element not found');
            }
        }
    document.addEventListener('DOMContentLoaded', function () {
        // Termの既存コード
        function toggleTerm() {
            console.log('toggleTerm called');
            const content = document.getElementById('term-content');
            const arrow = document.getElementById('arrow');
            const title = document.getElementById('term-title');

            if (content.classList.contains('open')) {
                content.classList.remove('open');
                title.classList.remove('move-to-top');
                title.textContent = "term";
                arrow.textContent = "▼"; 
            } else {
                content.classList.add('open');
                title.classList.add('move-to-top');
                title.textContent = "term";
                arrow.textContent = "▲";
            }
        }

        // termのチェックボックス更新処理
        document.querySelectorAll('.term-container input[type="checkbox"]').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const planId = this.dataset.id; // チェックボックスに紐づくplan_id
                const isChecked = this.checked ? 1 : 0; // チェック状態を数値に変換

                // AJAXリクエストで更新を送信
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_plan.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        console.log(xhr.responseText); // 成功時のレスポンスを確認
                    } else if (xhr.readyState === 4) {
                        console.error('更新に失敗しました: ', xhr.status, xhr.statusText);
                    }
                };
                xhr.send(`plan_id=${planId}&todo_flg=${isChecked}`);
            });
        });


        // グローバルに登録
        window.toggleTerm = toggleTerm;

        // タブ関連のコード
        const tabLeft = document.getElementById('tab-left');
        const tabCenter = document.getElementById('tab-center');
        const tabRight = document.getElementById('tab-right');
        const sortableList = document.getElementById('sortable-list');

        let today = new Date();
        let currentDay = selectedDate ? new Date(selectedDate) : new Date(today);

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

        updateTabs();

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


        // チェックボックスのクリックイベント
        sortableList.addEventListener('change', function (event) {
            const target = event.target;
            if (target.classList.contains('hide-checkbox')) {
                const todoId = target.dataset.id;
                const isChecked = target.checked ? 1 : 0;

                // チェック状態をサーバーに送信
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_completion.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        console.log('更新成功:', xhr.responseText);
                    } else if (xhr.readyState === 4) {
                        console.error('更新失敗:', xhr.status, xhr.statusText);
                    }
                };
                xhr.send(`todo_id=${todoId}&completion_flg=${isChecked}`);
            }
        });


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

                // 入力欄にフォーカスが外れたときに変更を保存
                editInput.addEventListener('blur', function () {
                    const newTodoText = editInput.value.trim();

                    if (newTodoText !== todoText.textContent.trim()) {
                        // TODO文を更新
                        todoText.textContent = newTodoText;

                        // サーバーに更新リクエストを送信
                        const todoId = item.dataset.id;
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', 'update_todo.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                        xhr.onload = function () {
                            if (xhr.status === 200 && xhr.responseText.trim() === 'success') {
                                console.log('TODO updated successfully');
                            } else {
                                console.error('Failed to update TODO');
                            }
                        };

                        xhr.send('todo_id=' + todoId + '&todo=' + encodeURIComponent(newTodoText));
                    }

                    // 入力欄を非表示にして元の状態に戻す
                    editInput.style.display = 'inline-block';
                    todoText.style.display = 'none';
                });

                // TODO文をクリックすると編集モードに
                todoText.addEventListener('click', function () {
                    todoText.style.display = 'none';
                    editInput.style.display = 'inline-block';
                    editInput.focus();
                });
            



                deleteButton.addEventListener('click', function () {
                    const todoId = item.dataset.id;

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'delete_todo.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    
                    xhr.onload = function () {
                        if (xhr.status === 200 && xhr.responseText.trim() === 'success') {
                            item.remove(); // リストから削除
                        }
                    };
                    xhr.send('todo_id=' + todoId); // todoを削除
                });


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
            console.log(sortableList); // null の場合、正しい要素が取得できていません

            sortableList.addEventListener('dragstart', function (e) {
                console.log('dragstart event triggered');
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
                const closestItem = getClosestListItem(e.clientY); // ドロップ位置を決定
                if (closestItem && closestItem !== draggedItem) {
                    sortableList.insertBefore(draggedItem, closestItem.nextElementSibling);
                }
            });

            sortableList.addEventListener('drop', function (e) {
                e.preventDefault();
                if (draggedItem !== null) {
                    updateSortOrder();  // 並び順をデータベースに送信する
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
                xhr.send('todo_id=' + todoId + '&sort_id=' + index);    // 並び順を更新
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



        const todoList = document.getElementById('todo-list');
        const dateInput = document.getElementById('formatted-date'); // 隠しフィールドに設定される日付

        // const currentDate = new Date().toISOString().split('T')[0];

        // エンターキー押下時の処理
        todoInput.addEventListener('keypress', function (event) {
            if (event.key === 'Enter') { // エンターキーが押下された場合
                event.preventDefault(); // フォーム送信を防止

                const todoText = todoInput.value.trim();
                if (!todoText) {
                    alert('TODOを入力してください');
                    return;
                }

                // const formattedDate = new Date().toISOString().split('T')[0]; // 現在の日付を取得 (YYYY-MM-DD)
                const currentDate = dateInput.value; // 画面上の日付を取得

                // AJAXでPHPにデータを送信
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'add_todo.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        try{
                            const response = JSON.parse(xhr.responseText);
                            if (response.status === 'success') {
                                const newTodo = response.todo;
                                // TODOを画面に追加
                                const li = document.createElement('li');
                                li.className = 'normal-mode';
                                li.setAttribute('data-id', newTodo.todo_id);
                                li.innerHTML = `
                                    <input type="checkbox" class="hide-checkbox" data-id="${newTodo.todo_id}">
                                    <img src="img/grip-lines.png" class="edit-mode-icon" style="display: none;">
                                    <span class="todo-text">${newTodo.todo}</span>
                                    <input type="text" class="edit-todo-input" value="${newTodo.todo}" style="display: none; margin-left:5px;">
                                    <button class="delete-button" style="display: none;">
                                        <img src="img/dustbox.png" style="height: 23px; width: auto;">
                                    </button>
                                `;
                                todoList.appendChild(li);

                                todoInput.value = ''; // 入力フィールドをクリア
                            } else {
                                alert('エラーが発生しました: ' + response.message);
                            }
                        } catch(error){
                            console.error('JSONのパースエラー:', error);
                            console.error('サーバーからのレスポンス:', xhr.responseText);
                        }
                    }else if(xhr.readyState === 4){
                        console.error('HTTPリクエストエラー:', xhr.status, xhr.statusText);
                    }
                };

                // xhr.send(`todo=${encodeURIComponent(todoText)}&formattedDate=${encodeURIComponent(formattedDate)}`);
                // データ送信
                xhr.send(`action=add_todo&todo=${encodeURIComponent(todoText)}&formattedDate=${encodeURIComponent(currentDate)}`);
            
            }
        });


        
    });
</script>
</body>
</html>
