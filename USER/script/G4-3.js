function selectTag(element) {
    document.querySelectorAll('.tag-item').forEach(item => {
        item.classList.remove('selected');
    });
    element.classList.add('selected');

    // hidden要素の value 値を取得
    const hiddenInput = element.querySelector('input[type="hidden"]');
    const tagId = hiddenInput ? hiddenInput.value : null;

    // 取得した値を別の hidden 要素の value に設定
    const targetHiddenInput = document.getElementById('tag_color_no');
    if (tagId !== null && targetHiddenInput) {
        targetHiddenInput.value = tagId;
        // alert(`hidden要素 "tag_color_no" の値が設定されました: ${tagId}`);
    } else {
        // alert("hidden要素が見つからない、または設定できませんでした");
    }

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

//戻る
function goBackUpdate() {
    window.location.href = document.referrer;
}