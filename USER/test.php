<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/menu.css">
    <title>ToDo画面</title>
    <style>
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
        }
        /* todo追加のテキストボックスと編集ボタンの配置固定 */
        .tododiv {
            display: flex;
            justify-content: center; /* 横方向に中央揃え */
            align-items: center;     /* 縦方向に中央揃え */
            gap: 10px;               /* 要素間に余白を設定 */
            height: 150px;           /* 明示的に高さを設定 */
            margin-top: 50px;        /* 上部にマージンを追加 */
        }
        form {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .todoinput {
            height: 3em;
            width: 20em;
            border-radius: 5px;
        }
        .todobtn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            background-color: #FFF;
            border-radius: 10px;
            cursor: pointer;
        }
        .todobtn img {
            height: 40px;
            width: 40px;
        }
    </style>
</head>
<body>
    <img class="logo" src="img/Ciel logo.png" alt="Logo">

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

    <!-- テキストボックスと追加ボタンのフォーム -->
    <div class="tododiv">
        <form id="todo-form">
            <input type="text" id="todo-input" class="todoinput" placeholder="TODOを追加する">
            <button id="toggleMode" class="todobtn">
                <img src="img/edit.png" alt="Edit Icon">
            </button>
        </form>
    </div>

    <div id="output"></div>

    <footer><?php include 'menu.php'; ?></footer>

</body>
<script>
    const toggleModeButton = document.getElementById('toggleMode');
    const sortableList = document.getElementById('sortable-list');
    const todoForm = document.getElementById('todo-form');
    const todoInput = document.getElementById('todo-input');
    let isEditMode = false;

    toggleModeButton.addEventListener('click', () => {
        isEditMode = !isEditMode;
        const checkboxes = document.querySelectorAll('.hide-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.style.display = isEditMode ? 'none' : 'inline-block';
        });

        todoForm.style.display = isEditMode ? 'none' : 'flex';
        toggleModeButton.textContent = isEditMode ? '完了' : '並び替えモード';

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

        sortableList.addEventListener('touchstart', function (e) {
            draggedItem = e.target.closest('li');
            draggedItem.style.opacity = '0.5';
        });

        sortableList.addEventListener('touchend', function (e) {
            draggedItem.style.opacity = '1';
            draggedItem = null;
        });

        sortableList.addEventListener('touchmove', function (e) {
            e.preventDefault();
            const touchLocation = e.targetTouches[0];
            const hoverElement = document.elementFromPoint(touchLocation.pageX, touchLocation.pageY);
            if (hoverElement && hoverElement.tagName === 'LI' && hoverElement !== draggedItem) {
                sortableList.insertBefore(draggedItem, hoverElement);
            }
        });
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

    todoForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const newItemText = todoInput.value.trim();
        if (newItemText !== "") {
            const newItem = document.createElement('li');
            newItem.classList.add('normal-mode');
            newItem.innerHTML = `<input type="checkbox" class="hide-checkbox"> ${newItemText} <button class="delete-button">削除</button>`;
            sortableList.appendChild(newItem);
            todoInput.value = "";

            attachDeleteHandler(newItem.querySelector('.delete-button'));

            if (isEditMode) {
                enableDragAndDrop();
            }
        }
    });

    enableDragAndDrop();
</script>
</html>
