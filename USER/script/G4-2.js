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