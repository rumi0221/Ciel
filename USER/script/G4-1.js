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
    generateCalender(selectedYear,selectedMonth);
    toggleMonthSelector();
}

function generateCalendar(year, month) {
    const calendarTable = document.getElementById("calendar-table");
    calendarTable.innerHTML = "";

    const firstDay = new Date(year, month - 1, 1).getDay();
    const daysInMonth = new Date(year, month, 0).getDate();

    const daysOfWeek = ['日', '月', '火', '水', '木', '金', '土'];
    let table = "<table><tr>";
    daysOfWeek.forEach(day => {
        table += `<th>${day}</th>`;
    });
    table += "</tr><tr>";

    for (let i = 0; i < firstDay; i++) {
        table += "<td></td>";
    }

    let day = 1;
    for (let i = firstDay; i < 7; i++) {
        table += `<td>${day}</td>`;
        day++;
    }
    table += "</tr>";

    while (day <= daysInMonth) {
        table += "<tr>";
        for (let i = 0; i < 7 && day <= daysInMonth; i++) {
            table += `<td>${day}</td>`;
            day++;
        }
        table += "</tr>";
    }
    table += "</table>";

    calendarTable.innerHTML = table;
}

generateCalendar(selectedYear, selectedMonth);





function goToNextPage() {
    window.location.href = "G4-2.html";
}

function NextPage() {
    window.location.href = "G4-1.php";
}


  

