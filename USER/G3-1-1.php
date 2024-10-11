<!-- todo画面のデフォルト画面です -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/menu.css">
    <title>ToDo画面</title>
    <style>
        html, body {
            margin: 0; /* デフォルトのマージンをリセット */
            padding: 0; /* デフォルトのパディングをリセット */
            height: 100%; /* 高さを100%に設定 */
        }

        ul {
            list-style-type: none;
            padding: 0; /* パディングをリセット */
        }
        
        li {
            padding: 8px;
            margin-bottom: 4px;
            cursor: pointer;
            display: flex;
            align-items: center; /* 縦中央揃え */
            text-align: left; /* テキストを左寄せ */
        }

        .background {
            background-color: #E1DBFF;
            width: 100%; /* 100%に設定 */
            height: 100%; /* 100%に設定 */
            overflow: auto; /* 必要に応じてスクロールを追加 */
        }

        /* todo追加のテキストボックスと編集ボタンの配置固定 */
        .tododiv {
            display: flex;
            justify-content: center; /* 横方向に中央揃え */
            align-items: center;     /* 縦方向に中央揃え */
            gap: 10px;               /* 要素間に余白を設定 */
            position: fixed;         /* 位置を固定 */
            bottom: 100px;            /* フッターのすぐ上に配置（必要に応じて調整） */
            left: 0;
            right: 0;
            z-index: 1000;           /* 他の要素より上に表示 */
            background-color: none; /* 背景色を設定 */
            padding: 10px;           /* 周囲に余白を追加 */
            
            
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
            /* display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 8px;
                background-color: #FFF;
                border-radius: 10px; */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            background-color: #FFF;
            border-radius: 10px;
        }
        /* 通常モードのスタイル */
        .normal-mode {
            background-color: #f0f0f0;
        }
        /* 並び替えモードのスタイル */
        .edit-mode {
            background-color: #e0e0e0;
            border: 1px dashed #000;
            justify-content: space-between; /* 並び替えモード時の左右配置 */
        }
        .hide-checkbox {
            display: inline-block; /* 最初から表示 */
        }
        .delete-button {
            background-color: #ff4d4d; /* 赤色 */
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            margin-left: 10px; /* ボタンとテキストの間にマージンを追加 */
            display: none; /* 初期状態で非表示 */
        }
    </style>
</head>
<body>
    <img class="logo" src="img/Ciel logo.png" >

    <div class="background">
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
        <form id="todo-form" class="todo-form">
            <input type="text" class="todo-inp" id="todo-input" placeholder="TODOを追加する">
            <button class="todo-btn" id="toggleMode"><img src="img/edit.png" style="height:30px; width:30px;"></button>
        </form>
        </div>

        <div id="output"></div>
    </div>

<footer><?php include 'menu.php';?></footer>
</body>
<script>
    const toggleModeButton = document.getElementById('toggleMode');
    const sortableList = document.getElementById('sortable-list');
    const todoForm = document.getElementById('todo-form');
    const todoInput = document.getElementById('todo-input');
    let isEditMode = false;

    // 並び替えモードのトグル
    toggleModeButton.addEventListener('click', () => {
    isEditMode = !isEditMode;
    const checkboxes = document.querySelectorAll('.hide-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.style.display = isEditMode ? 'none' : 'inline-block';
    });

    // テキストボックスのみ非表示にする
    todoInput.style.display = isEditMode ? 'none' : 'inline-block';  // テキストボックスを非表示にする

    // ボタンのアイコンを変更
    const buttonIcon = toggleModeButton.querySelector('img');
    buttonIcon.src = isEditMode ? 'img/edit.png' : 'img/edit.png';

    // リストのスタイル切り替え
    sortableList.querySelectorAll('li').forEach(li => {
        if (isEditMode) {
            li.classList.add('edit-mode'); // 並び替えモードのスタイルを追加
            li.classList.remove('normal-mode'); // 通常モードのスタイルを削除
            li.querySelector('.delete-button').style.display = 'block'; // 削除ボタンを表示
        } else {
            li.classList.add('normal-mode'); // 通常モードのスタイルを追加
            li.classList.remove('edit-mode'); // 並び替えモードのスタイルを削除
            li.querySelector('.delete-button').style.display = 'none'; // 削除ボタンを非表示
        }
    });

    if (isEditMode) {
        enableDragAndDrop();
    }
});



    // 並び替えの有効化（タッチ対応）
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

        // モバイルタッチ対応
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

        // 文をクリックして編集モードにする
        sortableList.querySelectorAll('li').forEach(li => {
            li.addEventListener('click', function (e) {
                if (isEditMode && e.target.tagName !== 'BUTTON') { // ボタン以外のクリックで編集
                    const checkbox = li.querySelector('.hide-checkbox');
                    let text = li.childNodes[1].textContent.trim(); // チェックボックスの次のノード
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = text;
                    li.innerHTML = ''; // 既存の内容をクリア
                    li.appendChild(checkbox); // チェックボックスを再追加
                    li.appendChild(input);
                    input.focus();

                    // 編集終了
                    input.addEventListener('blur', function () {
                        li.innerHTML = `${checkbox.outerHTML} ${input.value} <button class="delete-button">削除</button>`;
                        attachDeleteHandler(li.querySelector('.delete-button')); // 削除ボタンのハンドラーを再設定
                    });

                    // Enterキーで更新
                    input.addEventListener('keydown', function (e) {
                        if (e.key === 'Enter') {
                            li.innerHTML = `${checkbox.outerHTML} ${input.value} <button class="delete-button">削除</button>`;
                            attachDeleteHandler(li.querySelector('.delete-button')); // 削除ボタンのハンドラーを再設定
                        }
                    });
                }
            });
        });

        // 削除ボタンのイベントハンドラーを設定
        attachDeleteHandler();
    }

    // 削除ボタンのハンドラーを設定する関数
    function attachDeleteHandler(button = null) {
        const deleteButtons = document.querySelectorAll('.delete-button');
        deleteButtons.forEach((btn) => {
            btn.addEventListener('click', function () {
                const li = btn.parentElement; // ボタンの親要素（li）
                sortableList.removeChild(li); // リストから削除
            });
        });

        if (button) {
            button.addEventListener('click', function () {
                const li = button.parentElement; // ボタンの親要素（li）
                sortableList.removeChild(li); // リストから削除
            });
        }
    }

    // フォームから入力されたテキストをチェックボックスとしてリストに追加
    todoForm.addEventListener('submit', function (event) {
        event.preventDefault();  // ページリロードを防ぐ
        const newItemText = todoInput.value.trim();
        if (newItemText !== "") {
            const newItem = document.createElement('li');
            newItem.classList.add('normal-mode'); // 通常モードのスタイルを追加
            newItem.innerHTML = `<input type="checkbox" class="hide-checkbox"> ${newItemText} <button class="delete-button">削除</button>`;
            sortableList.appendChild(newItem);
            todoInput.value = "";  // フォームをクリア

            // 新しい項目の削除ボタンにハンドラーを設定
            attachDeleteHandler(newItem.querySelector('.delete-button'));

            if (isEditMode) {
                enableDragAndDrop(); // 新しい項目にもドラッグ機能を追加
            }
        }
    });

    // 初期状態の並び替え機能の有効化
    enableDragAndDrop();
</script>
</html>
