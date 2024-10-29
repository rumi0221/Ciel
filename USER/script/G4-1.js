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
let selectedMonth = 10;

function toggleMonthSelector() {
    const monthSelector = document.getElementById("month-selector");
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
    toggleMonthSelector(); // Close the selector after choosing
}

function goToNextPage() {
    // 次のページ（例: event.html）に遷移する
    window.location.href = "G4-2.html";
}

function NextPage() {
    window.location.href = "G4-1.php";
}

 // 月ごとにカレンダーを表示する関数
 function generateCalendar(month) {
    const now = new Date();
    const year = now.getFullYear();
    const firstDay = new Date(year, month, 1); // その月の最初の日
    const lastDay = new Date(year, month + 1, 0); // その月の最後の日
    
    const daysInMonth = lastDay.getDate();
    const startDay = firstDay.getDay(); // その月の最初の日が何曜日か
    
    const calendarContainer = document.getElementById("calendar-container");
    calendarContainer.innerHTML = ''; // 既存のカレンダーをクリア

    // カレンダーのHTMLを生成
    let calendarHTML = "<table class='calendar'><tr><th>日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th>土</th></tr><tr>";

    // 空のセルを追加して、月の最初の日を揃える
    for (let i = 0; i < startDay; i++) {
        calendarHTML += "<td></td>";
    }

    // 各日のセルを追加
    for (let day = 1; day <= daysInMonth; day++) {
        calendarHTML += `<td>${day}</td>`;
        if ((day + startDay) % 7 === 0) {
            calendarHTML += "</tr><tr>"; // 行を終了し、新しい行を開始
        }
    }

    calendarHTML += "</tr></table>";
    calendarContainer.innerHTML = calendarHTML;
    
    // カレンダーを表示
    const calendar = document.querySelector(".calendar");
    calendar.style.display = "table";
}

// 各月のボタンにクリックイベントを追加
const monthButtons = document.querySelectorAll(".month-button");
monthButtons.forEach(button => {
    button.addEventListener("click", function() {
        const month = parseInt(this.getAttribute("data-month"));
        generateCalendar(month);
    });
});



  

