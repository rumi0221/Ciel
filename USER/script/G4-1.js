
// function toggleMonthSelector() {
function toggleMonthSelector(year, month,tag) {
    selectedYear = year;
    selectedMonth = month;
    selectedTag = tag;
    
    let monthSelector = document.getElementById("month-selector");
    if (monthSelector.style.display === "block") {
        monthSelector.style.display = "none";
    } else {
        monthSelector.style.display = "block";
    }
}

function changeYear(change) {
    selectedYear += change;
    document.getElementById("selected-year").innerText = selectedYear + "年";
}

// function selectMonth(month) {
//     selectedMonth = month;
//     document.getElementById("current-month").innerText = `${selectedYear}年${selectedMonth}月`;

//     document.querySelectorAll(".month").forEach(m => m.classList.remove("selected"));
//     document.querySelectorAll(".month")[month - 1].classList.add("selected");
    
//     generateCalendar(selectedYear,selectedMonth);
//     toggleMonthSelector();
// }

//------------------------------------------------------------
function selectMonth(month) {
    selectedMonth = month;
    document.getElementById("current-month").innerText = `${selectedYear}年${selectedMonth}月`;
    
    // 月ボタンの選択状態を更新
    document.querySelectorAll(".month").forEach(m => m.classList.remove("selected"));
    document.querySelectorAll(".month")[month - 1].classList.add("selected");

    // 月と年を送信するフォームを作成して送信
    const form = document.createElement("form");
    form.method = "GET";
    form.action = "G4-1.php";  // 現在のページを再読み込み

    // 年と月をフォームに追加
    form.innerHTML = `
        <input type="hidden" name="year" value="${selectedYear}">
        <input type="hidden" name="month" value="${selectedMonth}">
        <input type="hidden" name="tag" value="${selectedTag}">
    `;
    
    // フォームを送信
    document.body.appendChild(form);
    form.submit();
    
    generateCalendar(selectedYear,selectedMonth);

    // 月選択を閉じる
    toggleMonthSelector();
}

//-------------------------------------------------

// メニューの表示・非表示を切り替える関数
function toggleMenu() {
    // メニューの要素を取得
    var menu = document.getElementById('menu');

    // メニューが表示されているかをチェックし、開閉を切り替える
    if (menu.classList.contains('open')) {
        menu.classList.remove('open'); // 表示されていれば非表示にする
    } else {
        menu.classList.add('open'); // 非表示なら表示する
    }
}

// メニュー全体の外側クリックや「×」ボタンで閉じる処理
const popupWrapper = document.getElementById('popup-wrapper');

// ポップアップの外側または「×」マークをクリックしたときにポップアップを閉じる
popupWrapper.addEventListener('click', (e) => {
    // 「×」または外側クリックの場合に閉じる
    if (e.target.id === 'popup-wrapper' || e.target.id === 'close') {
        var menu = document.getElementById('menu');
        menu.classList.remove('open'); // メニューを閉じる
    }
});

// ラジオボタン選択の視覚的な反応を追加
const radioButtons = document.querySelectorAll('input[type="radio"]');

// ラジオボタンが選択されたときにスタイルを適用
radioButtons.forEach((radio) => {
    radio.addEventListener('change', () => {
        // すべてのラベルから「checked」クラスを削除
        document.querySelectorAll('.tag-color').forEach((label) => {
            label.classList.remove('checked');
        });

        // 選択されたラジオボタンのラベルに「checked」クラスを追加
        if (radio.checked) {
            radio.parentElement.classList.add('checked');
        }
    });
});
