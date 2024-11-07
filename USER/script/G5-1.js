const clickBtn = document.getElementById('click-btn');
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