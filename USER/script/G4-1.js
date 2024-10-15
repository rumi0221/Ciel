document.addEventListener('DOMContentLoaded', function() {
  const calendarBody = document.getElementById('calendar-body');
  const daysInMonth = 30; // 9月の日数（例）
  
  // カレンダーの日付を生成
  for (let i = 1; i <= daysInMonth; i++) {
      let td = document.createElement('td');
      td.innerHTML = i;
      
      // タグを表示する場合
      if (tags[i]) {
          let tag = document.createElement('div');
          tag.classList.add('tag');
          tag.textContent = tags[i];
          td.appendChild(tag);
      }

      // カレンダーに日付を追加
      let row;
      if (i % 7 === 1) {  // 新しい行を追加
          row = document.createElement('tr');
          calendarBody.appendChild(row);
      }
      row = calendarBody.querySelector('tr:last-child');
      row.appendChild(td);
  }
});

