<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
.term-container {
    width: 300px;
    background-color: #D7B8F5;
    border-radius: 10px;
    padding: 10px;
    position: relative;
}

.term-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    background-color: #D7B8F5;
    padding: 10px;
    border-radius: 5px;
    position: relative;
}

.term-title {
    transition: all 0.5s ease;
    position: relative;
}

.term-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.5s ease, padding 0.5s ease;
    padding: 0;
}

.term-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 10px 0;
}

.due-date {
    color: red;
}

.open {
    max-height: 200px;
    padding: 10px 0;
}

#arrow {
    transition: transform 0.5s ease;
}

.rotated {
    transform: rotate(180deg);
}

.term-title.move-to-top-left {
    position: absolute;
    top: -15px;
    left: 10px;
    font-size: 16px;
}


    </style>
</head>
<body>
    <br>
    <br>
    <div class="term-container">
    <div class="term-header" onclick="toggleTerm()">
        <span id="term-title" class="term-title">term(2)</span>
        <span id="arrow">▼</span>
    </div>
    <div id="term-content" class="term-content">
        <div class="term-item">
            <input type="checkbox">
            <label>レポート課題</label>
            <span class="due-date">1/6まで</span>
        </div>
        <div class="term-item">
            <input type="checkbox">
            <label>新刊「○○」</label>
            <span class="due-date">1/20まで</span>
        </div>
    </div>
</div>


<script>
function toggleTerm() {
    const content = document.getElementById('term-content');
    const arrow = document.getElementById('arrow');
    const title = document.getElementById('term-title');

    if (content.classList.contains('open')) {
        content.classList.remove('open');
        arrow.classList.remove('rotated');
        title.classList.remove('move-to-top');
        title.textContent = "term(2)";
    } else {
        content.classList.add('open');
        arrow.classList.add('rotated');
        title.classList.add('move-to-top');
        title.textContent = "term";
    }
}


</script>
</body>
</html>