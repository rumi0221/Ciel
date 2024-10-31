function selectTag(element) {
    document.querySelectorAll('.tag-item').forEach(item => {
        item.classList.remove('selected');
    });
    element.classList.add('selected');
}

// 全てのタグ項目を取得
const tagItems = document.querySelectorAll('.tag-item');

// 項目をクリックしたときの処理
tagItems.forEach(item => {
    item.addEventListener('click', () => {
        // 他の選択を解除
        tagItems.forEach(i => i.classList.remove('selected'));
        
        // クリックされた項目に selected クラスを追加
        item.classList.add('selected');
    });
});
