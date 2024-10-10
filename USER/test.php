<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drag & Drop with Editable Text</title>
    <style>
        ul {
            list-style-type: none;
        }
        li {
            padding: 8px;
            margin-bottom: 4px;
            background-color: #f0f0f0;
            cursor: pointer;
        }
        .draggable {
            background-color: #e0e0e0;
            padding: 8px;
            border: 1px dashed #000;
        }
    </style>
</head>
<body>

<button id="toggleMode">並び替えモード</button>
<ul id="sortable-list">
    <li data-id="1"><input type="checkbox" class="checkbox" style="display:none;"> 文1</li>
    <li data-id="2"><input type="checkbox" class="checkbox" style="display:none;"> 文2</li>
    <li data-id="3"><input type="checkbox" class="checkbox" style="display:none;"> 文3</li>
</ul>

<!-- テキストボックスと追加ボタンのフォーム -->
<form id="todo-form">
    <input type="text" id="todo-input" placeholder="新しい項目を追加">
    <button type="submit">追加</button>
</form>

<script>
    const toggleModeButton = document.getElementById('toggleMode');
    const sortableList = document.getElementById('sortable-list');
    const todoForm = document.getElementById('todo-form');
    const todoInput = document.getElementById('todo-input');
    let isEditMode = false;

    // 並び替えモードのトグル
    toggleModeButton.addEventListener('click', () => {
        isEditMode = !isEditMode;
        const checkboxes = document.querySelectorAll('.checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.style.display = isEditMode ? 'none' : 'inline-block';
        });
        toggleModeButton.textContent = isEditMode ? '完了' : '並び替えモード';

        if (isEditMode) {
            sortableList.querySelectorAll('li').forEach(li => {
                li.classList.add('draggable');
            });
            enableDragAndDrop();
        } else {
            sortableList.querySelectorAll('li').forEach(li => {
                li.classList.remove('draggable');
            });
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
                if (isEditMode) {
                    let text = li.childNodes[1].textContent.trim(); // テキストを取得
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = text;
                    li.innerHTML = ''; // liの内容をクリア
                    li.appendChild(input);
                    input.focus();
                    // チェックボックスを非表示にする
                    const checkbox = li.querySelector('.checkbox');
                    checkbox.style.display = 'none';

                    // 編集終了
                    input.addEventListener('blur', function () {
                        updateListItem(li, input.value);
                    });

                    // エンターキーで編集を確定
                    input.addEventListener('keydown', function (event) {
                        if (event.key === 'Enter') {
                            event.preventDefault(); // ページリロードを防ぐ
                            updateListItem(li, input.value);
                        }
                    });

                    // Escキーで編集をキャンセル
                    input.addEventListener('keydown', function (event) {
                        if (event.key === 'Escape') {
                            updateListItem(li, text); // 元のテキストに戻す
                        }
                    });
                }
            });
        });
    }

    // リストアイテムを更新する関数
    function updateListItem(li, newText) {
        li.innerHTML = `<input type="checkbox" class="checkbox" style="display:none;"> ${newText}`;
        // チェックボックスを非表示にする
        const checkbox = li.querySelector('.checkbox');
        checkbox.style.display = 'none';
    }

    // フォームから入力されたテキストをチェックボックスとしてリストに追加
    todoForm.addEventListener('submit', function (event) {
        event.preventDefault();  // ページリロードを防ぐ
        const newItemText = todoInput.value.trim();
        if (newItemText !== "") {
            const newItem = document.createElement('li');
            newItem.innerHTML = `<input type="checkbox" class="checkbox" style="display:none;"> ${newItemText}`;
            sortableList.appendChild(newItem);
            todoInput.value = "";  // フォームをクリア
            if (isEditMode) {
                enableDragAndDrop(); // 新しい項目にもドラッグ機能を追加
            }
        }
    });

    // 初期状態の並び替え機能の有効化
    enableDragAndDrop();
</script>

</body>
</html>
