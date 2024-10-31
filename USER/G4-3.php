<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タグ選択画面</title>
    <link rel="stylesheet" href="./css/G4-3.css">
</head>
<body>
<img src="./img/Ciel logo.png">
<button class="back-button" onclick="history.back()">←</button>
<a href="#" class="confirm-button">決定</a>

<ul class="tag-list">
    <li class="tag-item" onclick="selectTag(this)">
        <span class="color-circle" style="background-color: #FF6B6B;"></span>
        <span class="checkmark">&#10003;</span>
        <span>タグ名</span>
    </li>
    <li class="tag-item" onclick="selectTag(this)">
        <span class="color-circle" style="background-color: #FFD93D;"></span>
        <span class="checkmark">&#10003;</span>
        <span>タグ名</span>
    </li>
    <li class="tag-item" onclick="selectTag(this)">
        <span class="color-circle" style="background-color: #BCE784;"></span>
        <span class="checkmark">&#10003;</span>
        <span>タグ名</span>
    </li>
    <li class="tag-item" onclick="selectTag(this)">
        <span class="color-circle" style="background-color: #5DD39E;"></span>
        <span class="checkmark">&#10003;</span>
        <span>タグ名</span>
    </li>
    <li class="tag-item" onclick="selectTag(this)">
        <span class="color-circle" style="background-color: #A2D2FF;"></span>
        <span class="checkmark">&#10003;</span>
        <span>タグ名</span>
    </li>
    <li class="tag-item" onclick="selectTag(this)">
        <span class="color-circle" style="background-color: #9D4EDD;"></span>
        <span class="checkmark">&#10003;</span>
        <span>タグ名</span>
    </li>
    <li class="tag-item" onclick="selectTag(this)">
        <span class="color-circle" style="background-color: #FF92C2;"></span>
        <span class="checkmark">&#10003;</span>
        <span>タグ名</span>
    </li>
</ul>
<script src="script/G4-3.js"></script>
</body>
</html>