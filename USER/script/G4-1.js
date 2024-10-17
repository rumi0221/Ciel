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



  

