<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/menu.css">
    <title>ToDo画面</title>
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
            border-radius: 5px;
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
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            margin-left: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <img class="logo" src="img/Ciel logo.png">

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
                <button class="delete-button">削除</button>
            </li>
            <li class="normal-mode" data-id="2">
                <input type="checkbox" class="hide-checkbox"> 文2
                <button class="delete-button">削除</button>
            </li>
            <li class="normal-mode" data-id="3">
                <input type="checkbox" class="hide-checkbox"> 文3
                <button class="delete-button">削除</button>
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
                    li.querySelector('.delete-button').style.display = 'block';
                } else {
                    li.classList.add('normal-mode');
                    li.classList.remove('edit-mode');
                    li.querySelector('.delete-button').style.display = 'none';
                }
            });

            if (isEditMode) {
                enableDragAndDrop();
            }
        });

        function enableDragAndDrop() {
            let draggedItem = null;

            sortableList.addEventListener('dragstart', function (e) {
                draggedItem = e.target;
                setTimeout(function () {
                    e.target.style.display = 'none';
                }, 0);
            });

            sortableList.addEventListener('dragend', function (e) {
                setTimeout(function () {
                    draggedItem.style.display = 'block';
                    draggedItem = null;
                }, 0);
            });

            sortableList.addEventListener('dragover', function (e) {
                e.preventDefault();
            });

            sortableList.addEventListener('drop', function (e) {
                if (e.target.tagName === 'LI') {
                    sortableList.insertBefore(draggedItem, e.target.nextSibling);
                }
            });

            sortableList.querySelectorAll('li').forEach(li => {
                li.addEventListener('click', function (e) {
                    if (isEditMode && e.target.tagName !== 'BUTTON') {
                        const checkbox = li.querySelector('.hide-checkbox');
                        let text = li.childNodes[1].textContent.trim();
                        const input = document.createElement('input');
                        input.type = 'text';
                        input.value = text;
                        li.innerHTML = '';
                        li.appendChild(checkbox);
                        li.appendChild(input);
                        input.focus();

                        input.addEventListener('blur', function () {
                            li.innerHTML = `${checkbox.outerHTML} ${input.value} <button class="delete-button">削除</button>`;
                            attachDeleteHandler(li.querySelector('.delete-button'));
                        });

                        input.addEventListener('keydown', function (e) {
                            if (e.key === 'Enter') {
                                li.innerHTML = `${checkbox.outerHTML} ${input.value} <button class="delete-button">削除</button>`;
                                attachDeleteHandler(li.querySelector('.delete-button'));
                            }
                        });
                    }
                });
            });

            attachDeleteHandler();
        }

        function attachDeleteHandler(button = null) {
            const deleteButtons = document.querySelectorAll('.delete-button');
            deleteButtons.forEach((btn) => {
                btn.addEventListener('click', function () {
                    const li = btn.parentElement;
                    sortableList.removeChild(li);
                });
            });

            if (button) {
                button.addEventListener('click', function () {
                    const li = button.parentElement;
                    sortableList.removeChild(li);
                });
            }
        }

        // TODO追加処理
        addTodoButton.addEventListener('click', function (event) {
            event.preventDefault();  // ページリロードを防ぐ
            const newItemText = todoInput.value.trim();
            if (newItemText !== "") {
                const newItem = document.createElement('li');
                newItem.classList.add('normal-mode');  // 通常モードのスタイルを適用
                newItem.innerHTML = `<input type="checkbox" class="hide-checkbox"> ${newItemText} <button class="delete-button">削除</button>`;
                sortableList.appendChild(newItem);
                todoInput.value = "";  // 入力フォームをクリア

                attachDeleteHandler(newItem.querySelector('.delete-button'));

                if (isEditMode) {
                    enableDragAndDrop();  // 編集モード時に追加されたTODOのドラッグ&ドロップ機能を有効化
                }
            }
        });

    </script>
</body>
</html>
