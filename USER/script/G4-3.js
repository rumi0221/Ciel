function selectTag(element) {
    document.querySelectorAll('.tag-item').forEach(item => {
        item.classList.remove('selected');
    });
    element.classList.add('selected');
}