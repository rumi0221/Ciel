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


let selectedYear = 2024;
let selectedMonth = 11;
// function toggleMonthSelector(year, month) {
//     let selectedYear = year;
// let selectedMonth = month;
function toggleMonthSelector() {
    
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

function selectMonth(month) {
    selectedMonth = month;
    document.getElementById("current-month").innerText = `${selectedYear}年${selectedMonth}月`;
    document.querySelectorAll(".month").forEach(m => m.classList.remove("selected"));
    document.querySelectorAll(".month")[month - 1].classList.add("selected");
    generateCalendar(selectedYear,selectedMonth);
    toggleMonthSelector();
}

//ここから

// // let events = json_encode($events);

// // console.log(events);

// // 一時的な配列でデータを格納
// let tempEvents = [];

// // 日付を1日進める関数
// function incrementDate(date) {
//   const newDate = new Date(date);
//   newDate.setDate(newDate.getDate() + 1);
//   return newDate;
// }

// // メインの変換処理
// events.forEach(event => {
//   const startDate = new Date(event.start_date);
//   const finalDate = new Date(event.final_date);

//   for (let date = new Date(startDate); date <= finalDate; date = incrementDate(date)) {
//     const dateKey = date.toISOString().split('T')[0]; // YYYY-MM-DD形式に変換

//     // 一時的な配列にオブジェクトとして追加
//     tempEvents.push({
//       "date": dateKey,       // 日付
//       "content": event.plan, // タイトル
//       "color": event.color,   // 色
//       "memo": event.memo,  //メモ
//       "tag_id": event.tag_id　//タグID
//     });
//   }
// });

// // 日付順にソート
// tempEvents.sort((a, b) => new Date(a.date) - new Date(b.date));

// // ソート済みのデータを連番キーを使って変換後のオブジェクトに再格納
// const transformedEvents = {};
// tempEvents.forEach((event, index) => {
//   transformedEvents[index] = event;
// });

// console.log(transformedEvents);

// //ここまで

// function generateCalendar(year, month) {
//     const calendarTable = document.getElementById("calendar-table");
//     calendarTable.innerHTML = "";

//     const firstDay = new Date(year, month - 1, 1).getDay();
//     const daysInMonth = new Date(year, month, 0).getDate();

//     const daysOfWeek = ['日', '月', '火', '水', '木', '金', '土'];
//     let table = "<table><tr>";
//     daysOfWeek.forEach(day => {
//         table += `<th>${day}</th>`;
//     });
//     table += "</tr><tr>";

//     // 空のセルを追加
//     for (let i = 0; i < firstDay; i++) {
//         table += "<td></td>";
//     }

//     //日付
//     let day = 1;
//     for (let i = firstDay; i < 7; i++) {
//         table += `<td>${renderDayCell(day)}</td>`;
//         day++;
//     }
//     table += "</tr>";

//     while (day <= daysInMonth) {
//         table += "<tr>";
//         for (let i = 0; i < 7 && day <= daysInMonth; i++) {
//             table += `<td>${renderDayCell(day)}</td>`;
//             day++;
//         }
//         table += "</tr>";
//     }
//     table += "</table>";

//     calendarTable.innerHTML = table;
// }

// function renderDayCell(day) {
//     const date = new Date(selectedYear, selectedMonth - 1, day+1);

//     const eventList = events.filter(event => {
//         const startDate = new Date(event.start_date);
//         const finalDate = new Date(event.final_date);
//         return date >= startDate && date <= finalDate;
//     });

//     let cellContent = `<div class="day-number">${day}</div>`;
//     //最終てきに予定出してる
//     eventList.forEach(event => {
//         cellContent += `<div class="event" style="background-color:#${event.color};">${event.plan}</div>`;
//     });

//     return cellContent;
// }

// // 初期のカレンダー生成
// generateCalendar(selectedYear, selectedMonth);

//------------------------------------------------------------
// メニューの表示状態を切り替える関数
const clickBtn = document.getElementById('menu');
const popupWrapper = document.getElementById('popup-wrapper');
const close = document.getElementById('close');

// ボタンをクリックしたときにポップアップを表示させる
clickBtn.addEventListener('click', () => {
    popupWrapper.style.display = "flex";
});

// ポップアップの外側または「x」のマークをクリックしたときにポップアップを閉じる
popupWrapper.addEventListener('click', e => {
    if (e.target.id === 'popup-wrapper' || e.target.id === 'close') {
        popupWrapper.style.display = 'none';
    }
});


//-------------------------------------------------



// 4-1
function goToNextPage() {
    window.location.href = "G4-2.php";
}

// 4-2
function NextPage() {
    window.location.href = "G4-1.php";
}