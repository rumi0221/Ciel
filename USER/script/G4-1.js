function createCalendar(year, month) {
    const calendarBody = document.querySelector("#calendar tbody");
    const yearMonth = document.getElementById("yearMonth");

    yearMonth.textContent = `${year}年${month + 1}月`;

    // 今月の最初の日を取得
    const firstDay = new Date(year, month, 1);
    // 今月の日数を取得
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    // 最初の日の曜日
    const startDay = firstDay.getDay();

    // カレンダーの行を初期化
    let row = document.createElement("tr");

    // 最初の日の前を空白にする
    for (let i = 0; i < startDay; i++) {
        const cell = document.createElement("td");
        row.appendChild(cell);
    }

    // 各日をカレンダーに挿入
    for (let day = 1; day <= daysInMonth; day++) {
        const cell = document.createElement("td");
        cell.textContent = day;

        // 日曜日のクラスを追加
        if ((startDay + day - 1) % 7 === 0) {
            cell.classList.add("sunday");
        }

        // 土曜日のクラスを追加
        if ((startDay + day) % 7 === 6) {
            cell.classList.add("saturday");
        }

        row.appendChild(cell);

        // 週が終わったら新しい行を追加
        if ((startDay + day) % 7 === 0) {
            calendarBody.appendChild(row);
            row = document.createElement("tr");
        }
    }

    // 残った日を空白で埋める
    if (row.children.length > 0) {
        while (row.children.length < 7) {
            const cell = document.createElement("td");
            row.appendChild(cell);
        }
        calendarBody.appendChild(row);
    }
}

const today = new Date();
createCalendar(today.getFullYear(), today.getMonth());