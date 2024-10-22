<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/menu.css">
    <style>
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
        .termblock {
            /* display: flex;
            justify-content: center; */
            margin: 20px auto;
            padding: 10px;
            width: 80%;
            background-color: #DBB5FF;
            border: none;
            border-radius: 10px;
        }
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
            display: none; /* 初期状態で非表示にする */
        }


        /* タブ部分 */
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
        }

        .tab.active {
            background-color: #d4b4ff;
            transform: scale(1.1);
        }

        /* スライド用の設定 */
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
    </style>
</head>
<body>
<img class="logo" src="img/Ciel logo.png">

<div class="tab-wrapper">
    <div class="tab-list">
        <div class="tab" id="tab-yesterday"></div>
        <div class="tab" id="tab-today"></div>
        <div class="tab" id="tab-tomorrow"></div>
    </div>
</div>
//ここまで

<div class="background">
    <div class="termblock">
        term
        <ul>
            <li class="normal-mode">
                <input type="checkbox"  class="hide-checkbox">term1
            </li>
        </ul>
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
            <button class="todo-btn" id="addTodo" style="display: none;"><img src="img/add.png" style="height:30px; width:30px;"></button> <!-- 非表示にする -->
            <button class="todo-btn" id="toggleMode"><img src="img/edit.png" style="height:30px; width:30px;"></button>
        </form>
    </div>

    <div id="output"></div>
</div>

<footer><?php include 'menu.php'; ?></footer>

    <script>
        const toggleModeButton = document.getElementById('toggleMode');
        const addTodoButton = document.getElementById('addTodo');
        const sortableList = document.getElementById('sortable-list');
        const todoForm = document.getElementById('todo-form');
        const todoInput = document.getElementById('todo-input');
        let isEditMode = false;



        const tabs = document.querySelectorAll('.tab');
        const tabList = document.querySelector('.tab-list');
        const tabWrapper = document.querySelector('.tab-wrapper');

        tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => {
                // すべてのタブからactiveクラスを外す
                tabs.forEach(t => t.classList.remove('active'));
                // クリックしたタブにactiveクラスを追加
                tab.classList.add('active');

                // 選択したタブを左から2番目に表示するためのスライド
                const tabWidth = tab.offsetWidth + 20; // タブの幅と隙間
                const scrollPosition = -tabWidth * (index - 1);
                tabList.style.transform = `translateX(${scrollPosition}px)`;
            });
        });



        // 編集モードのトグル機能
        toggleModeButton.addEventListener('click', (event) => {
            event.preventDefault();  // デフォルトのボタン動作を無効化
            isEditMode = !isEditMode;
            const checkboxes = document.querySelectorAll('.hide-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.style.display = isEditMode ? 'none' : 'inline-block';
            });

            addTodoButton.style.display = 'none'; // addTodoボタンは常に非表示
            todoInput.style.display = isEditMode ? 'none' : 'inline-block';  // 編集モードでテキストボックスを非表示
            const buttonIcon = toggleModeButton.querySelector('img');
            buttonIcon.src = isEditMode ? 'img/edit.png' : 'img/edit.png';

            sortableList.querySelectorAll('li').forEach(li => {
                if (isEditMode) {
                    li.classList.add('edit-mode');
                    li.classList.remove('normal-mode');
                    li.querySelector('.delete-button').style.display = 'block'; // 編集モードで表示
                    li.setAttribute('draggable', 'true'); // 編集モードでドラッグ可能にする
                } else {
                    li.classList.add('normal-mode');
                    li.classList.remove('edit-mode');
                    li.querySelector('.delete-button').style.display = 'none'; // 通常モードで非表示
                    li.removeAttribute('draggable'); // 通常モードでドラッグ無効
                }
            });

            // 各削除ボタンに削除イベントを追加
            sortableList.querySelectorAll('.delete-button').forEach(button => {
                attachDeleteHandler(button);
            });

            if (isEditMode) {
                enableDragAndDrop();
            }
        });

        // ドラッグ＆ドロップ機能
        function enableDragAndDrop() {
            let draggedItem = null;
            let overItem = null;

            // PC用ドラッグイベント
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
                if (e.target.tagName === 'LI' && e.target !== draggedItem) {
                    overItem = e.target;
                    const rect = overItem.getBoundingClientRect();
                    const offset = e.clientY - rect.top;
                    if (offset > rect.height / 2) {
                        sortableList.insertBefore(draggedItem, overItem.nextSibling);
                    } else {
                        sortableList.insertBefore(draggedItem, overItem);
                    }
                }
            });

            sortableList.addEventListener('drop', function (e) {
                e.preventDefault();
                if (e.target.tagName === 'LI' && e.target !== draggedItem) {
                    sortableList.insertBefore(draggedItem, overItem);
                }
            });

            // スマホ対応のタッチイベント
            sortableList.addEventListener('touchstart', function (e) {
                if (e.target.tagName === 'LI') {
                    draggedItem = e.target;
                    e.target.style.opacity = '0.5';  // ドラッグ中に視覚的に区別
                }
            });

            sortableList.addEventListener('touchmove', function (e) {
                e.preventDefault();
                const touch = e.touches[0];
                const elementUnderTouch = document.elementFromPoint(touch.clientX, touch.clientY);

                if (elementUnderTouch && elementUnderTouch.tagName === 'LI' && elementUnderTouch !== draggedItem) {
                    overItem = elementUnderTouch;
                    const rect = overItem.getBoundingClientRect();
                    const offset = touch.clientY - rect.top;
                    if (offset > rect.height / 2) {
                        sortableList.insertBefore(draggedItem, overItem.nextSibling);
                    } else {
                        sortableList.insertBefore(draggedItem, overItem);
                    }
                }
            });

            sortableList.addEventListener('touchend', function () {
                draggedItem.style.opacity = '1';  // 元の状態に戻す
                draggedItem = null;
            });

            // すべての <li> 要素をドラッグ可能にする
            sortableList.querySelectorAll('li').forEach(li => {
                li.setAttribute('draggable', 'true');  // PC向けのドラッグ＆ドロップ
            });
        }

        // TODO追加処理
        addTodoButton.addEventListener('click', function (event) {
            event.preventDefault();  // ページリロードを防ぐ
            const newItemText = todoInput.value.trim();
            if (newItemText !== "") {
                const newItem = document.createElement('li');
                newItem.classList.add('normal-mode');  // 通常モードのスタイルを適用
                newItem.innerHTML = `<input type="checkbox" class="hide-checkbox"> ${newItemText} <button class="delete-button" style="display: none;"><img src="img/dustbox.png" style="height: 23px; width: auto;"></button>`;
                newItem.setAttribute('draggable', isEditMode); // 編集モードならドラッグ可能に
                sortableList.appendChild(newItem);
                todoInput.value = "";  // 入力フォームをクリア

                // 新規追加した項目にも削除イベントを適用
                attachDeleteHandler(newItem.querySelector('.delete-button'));

                if (isEditMode) {
                    enableDragAndDrop();  // 編集モード時に追加されたTODOのドラッグ&ドロップ機能を有効化
                }
            }
        });

        // 削除ボタンの機能を追加する関数
        function attachDeleteHandler(deleteButton) {
            deleteButton.addEventListener('click', function (event) {
                event.preventDefault(); // ページリロード防止
                const li = deleteButton.closest('li');
                li.remove(); // 対応するリストアイテムを削除
            });
        }

    </script>
</body>
</html>
