const calendarEl = document.getElementById('calendar');
const date = new Date();
const currentYear = date.getFullYear();
const currentMonth = date.getMonth();
const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

// カレンダーのHTML構造を生成
let calendarHtml = '<table><thead><tr>';
for (let i = 0; i < 7; i++) {
  calendarHtml += `<th>${['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][i]}</th>`;
}
calendarHtml += '</tr></thead><tbody><tr>';

for (let i = 1; i <= daysInMonth; i++) {
  const dayOfWeek = new Date(currentYear, currentMonth, i).getDay();
  if (i === 1) {
    calendarHtml += '<tr>';
    for (let j = 0; j < dayOfWeek; j++) {
      calendarHtml += '<td></td>';
    }
  }
  calendarHtml += `<td>${i}</td>`;
  if (dayOfWeek === 6) {
    calendarHtml += '</tr>';
    if (i < daysInMonth) {
      calendarHtml += '<tr>';
    }
  } else if (i === daysInMonth) {
    for (let j = dayOfWeek + 1; j <= 6; j++) {
      calendarHtml += '<td></td>';
    }
    calendarHtml += '</tr>';
  }
}
calendarHtml += '</tbody></table>';
calendarEl.innerHTML = calendarHtml;

const prevMonthBtn = document.getElementById('prevMonth');
const nextMonthBtn = document.getElementById('nextMonth');
let currentDisplayedMonth = currentMonth;

function generateCalendar(year, month) {
  // カレンダー生成処理（サンプルコード1の内容を関数にまとめる）
}

prevMonthBtn.addEventListener('click', () => {
  currentDisplayedMonth--;
  if (currentDisplayedMonth < 0) {
    currentDisplayedMonth = 11;
    currentYear--;
  }
  generateCalendar(currentYear, currentDisplayedMonth);
});

nextMonthBtn.addEventListener('click', () => {
  currentDisplayedMonth++;
  if (currentDisplayedMonth > 11) {
    currentDisplayedMonth = 0;
    currentYear++;
  }
  generateCalendar(currentYear, currentDisplayedMonth);
});

function generateCalendar(date) {
    // 既存のコード
  
    for (let i = 0; i < 42; i++) {
      // 既存のコード
  
      if (day.getDay() === 0 || day.getDay() === 6) {
        dayCell.classList.add('weekend');
      }
  
      // 既存のコード
    }
  }

  const yearCalendarEl = document.getElementById('yearCalendar');

function generateYearCalendar(year) {
  yearCalendarEl.innerHTML = '';

  for (let i = 0; i < 12; i++) {
    const monthCalendar = document.createElement('div');
    const monthDate = new Date(year, i, 1);
    generateCalendar(monthDate, monthCalendar);
    yearCalendarEl.appendChild(monthCalendar);
  }
}

generateYearCalendar(new Date().getFullYear());

const todoInput = document.getElementById('todoInput');
const todoList = document.getElementById('todoList');

todoInput.addEventListener('keydown', (e) => {
  if (e.key === 'Enter' && e.target.value.trim() !== '') {
    const listItem = document.createElement('li');
    listItem.textContent = e.target.value;
    todoList.appendChild(listItem);
    e.target.value = '';
  }
});

