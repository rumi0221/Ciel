// function toggleMenu() {
//     // メニューの要素を取得
//     var menu = document.getElementById('menu');
    
//     // メニューが表示されているかをチェックし、開閉を切り替える
//     if (menu.classList.contains('open')) {
//         menu.classList.remove('open'); // 表示されていれば非表示にする
//     } else {
//         menu.classList.add('open'); // 非表示なら表示する
//     }
// }


// let selectedYear = 2024;
// let selectedMonth = 11;
// // function toggleMonthSelector(year, month) {
// //     let selectedYear = year;
// // let selectedMonth = month;
// function toggleMonthSelector() {
    
//     let monthSelector = document.getElementById("month-selector");
//     if (monthSelector.style.display === "block") {
//         monthSelector.style.display = "none";
//     } else {
//         monthSelector.style.display = "block";
//     }
// }

// function changeYear(change) {
//     selectedYear += change;
//     document.getElementById("selected-year").innerText = selectedYear + "年";
// }

// function selectMonth(month) {
//     selectedMonth = month;
//     document.getElementById("current-month").innerText = `${selectedYear}年${selectedMonth}月`;
//     document.querySelectorAll(".month").forEach(m => m.classList.remove("selected"));
//     document.querySelectorAll(".month")[month - 1].classList.add("selected");
//     generateCalendar(selectedYear,selectedMonth);
//     toggleMonthSelector();
// }

// //ここから


// //------------------------------------------------------------
// // メニューの表示状態を切り替える関数
// const clickBtn = document.getElementById('menu');
// const popupWrapper = document.getElementById('popup-wrapper');
// const close = document.getElementById('close');

// // ボタンをクリックしたときにポップアップを表示させる
// clickBtn.addEventListener('click', () => {
//     popupWrapper.style.display = "flex";
// });

// // ポップアップの外側または「x」のマークをクリックしたときにポップアップを閉じる
// popupWrapper.addEventListener('click', e => {
//     if (e.target.id === 'popup-wrapper' || e.target.id === 'close') {
//         popupWrapper.style.display = 'none';
//     }
// });

//-------------------------------------------------

// 4-2
function NextPage() {
    window.location.href = "G4-1.php";
}

function InputTagPage(plan, startDate, finalDate, memo, termName) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'G4-3.php';

    // const planIdValue = document.getElementById(planId).value;
    const title = document.getElementById(plan).value ?? null ;
    const start = document.getElementById(startDate).value ?? null;
    const final = document.getElementById(finalDate).value ?? null;
    const memoValue = document.getElementById(memo).value ?? null;
    const crud = "insert";

 // ラジオボタンの値を取得
 const todoFlg = getRadioValue(termName);

    const fields = { title, start, final, memoValue, todoFlg, crud };

       // フィールド内容をデバッグ
       console.log(fields);

    for (const [name, value] of Object.entries(fields)) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}

function UpdateTagPage(plan_id,plan, startDate, finalDate, memo, termName) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'G4-3.php';

    const planId = plan_id;
    const title = document.getElementById(plan).value ?? null ;
    const start = document.getElementById(startDate).value ?? null;
    const final = document.getElementById(finalDate).value ?? null;
    const memoValue = document.getElementById(memo).value ?? null;
    const crud = "update";

 // ラジオボタンの値を取得
 const todoFlg = getRadioValue(termName);

    const fields = {planId, title, start, final, memoValue, todoFlg, crud };

    for (const [name, value] of Object.entries(fields)) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}

function getRadioValue(name) {
    const radios = document.getElementsByName(name);
    for (const radio of radios) {
        if (radio.checked) {
            return radio.value;
        }
    }
    return null; // ラジオボタンが選択されていない場合
}