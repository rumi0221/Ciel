function toggleMonthSelector(year, month, tag) {
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
    
    generateCalendar(selectedYear, selectedMonth);

    // 月選択を閉じる
    toggleMonthSelector();
}

//-------------------------------------------------

function toggleMenu() {
    var menu = document.getElementById('menu');

    if (menu.classList.contains('open')) {
        menu.classList.remove('open');
    } else {
        menu.classList.add('open');
    }
}

const popupWrapper = document.getElementById('popup-wrapper');

popupWrapper.addEventListener('click', (e) => {
    if (e.target.id === 'popup-wrapper' || e.target.id === 'close') {
        var menu = document.getElementById('menu');
        menu.classList.remove('open');
    }
});

const radioButtons = document.querySelectorAll('input[type="radio"]');

radioButtons.forEach((radio) => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.tag-color').forEach((label) => {
            label.classList.remove('checked');
        });

        if (radio.checked) {
            radio.parentElement.classList.add('checked');
        }
    });
});

//カレンダー
function generateCalendar(year, month) {
    console.log(`Generating calendar for ${year}年${month}月`);

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

    for (let day = 1; day <= daysInMonth; day++) {
        const dateKey = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const holidayName = holidays[dateKey];
        const eventList = events.filter(event => event.date === dateKey);

        const currentDayOfWeek = (firstDay + day - 1) % 7; // 曜日を計算

        let classes = [];

        // 土曜日・日曜日判定
        if (currentDayOfWeek === 0) classes.push('sunday');
        if (currentDayOfWeek === 6) classes.push('saturday');

        //祝日
        if (holidayName) {
            console.log(`Holiday found on ${dateKey}: ${holidayName}`);
            classes.push('holidayColor');
        } else {
            console.log(`No holiday on ${dateKey}`);
        }

        let cellContent = `<div class="day-number">${day}</div>`;
        
        if (holidayName) {
            cellContent += `<div class="holiday-name">${holidayName}</div>`;
        }else{
            cellContent += `<div class="holiday-name-hidden"></div>`;
        }

        //予定表示数調整
        if (eventList.length > 0) {
            const limitedEventList = eventList.slice(0, 2);
            limitedEventList.forEach(event => {
                const shortContent = event.content.substring(0, 3);
                cellContent += `<div class="event" style="background-color:#${event.color};">${shortContent}</div>`;
            });

            if (eventList.length > 2) {
                cellContent += `<div class="event more-indicator">...</div>`;
            }
        }

        table += `<td class="${classes.join(' ')}" onclick="showEvents(${year}, ${month}, ${day})">${cellContent}</td>`;
        if ((day + firstDay) % 7 === 0) {
            table += "</tr><tr>";
        }
    }
    table += "</tr></table>";
    calendarTable.innerHTML = table;
}

//予定詳細
function showEvents(year, month, day) {
    const selectedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    console.log(`Showing events for ${selectedDate}`);

     // 祝日情報を取得
     const holidayName = holidays[selectedDate] || ""; // 祝日名を取得（ない場合は空文字）

    const eventList = events.filter(event => event.date === selectedDate);

    document.getElementById("event-details").classList.remove("hidden");
    document.getElementById("selected-date").innerText = `${year}年${month}月${day}日 ${holidayName}`;

    const eventListContainer = document.getElementById("event-list");
    eventListContainer.innerHTML = "";

    if (eventList.length > 0) {
        eventList.forEach(event => {
            console.log(`Event: ${event.content}, Time: ${event.starttime || "終日"} ～ ${event.endtime || "終日"}`);
            const listItem = document.createElement("li");
            listItem.innerHTML = `
                <span class="event-time">${event.starttime || "終日"} ～ ${event.endtime || "終日"}</span>
                <div class="schedule-column">
                    <form action="G4-2.php" method="post">
                        <input type="hidden" name="plan_id" value="${event.id}">
                        <input type="hidden" name="crud" value="update">
                        <input type="hidden" name="user_flg" value="false">
                        <button class="link-style-btn">
                            <span class="contentfont">${event.content}</span>
                        </button>
                    </form>
                    <span class="memofont">${event.memo}</span>
                </div>
            `;
            eventListContainer.appendChild(listItem);
        });
    } else {
        console.log("No events for this date.");
        eventListContainer.innerHTML = "<li>予定はありません</li>";
    }
}
