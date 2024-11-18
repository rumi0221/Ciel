<?php
require 'db-connect.php';

$pdo = new PDO($connect, USER, PASS);
$date = $_POST['formattedDate'] ?? date('Y-m-d'); // POSTから日付を取得

$sql = $pdo->prepare('SELECT * FROM Todos WHERE user_id = ? AND input_date = ? ORDER BY sort_id ASC');
$sql->execute([8, $date]); // ユーザーIDと日付をもとにTODOを取得

$todos = [];
foreach ($sql as $row) {
    $todos[] = [
        'todo_id' => $row['todo_id'],
        'todo' => htmlspecialchars($row['todo']), // XSS防止
        'completion_flg' => $row['completion_flg']
    ];
}

header('Content-Type: application/json');
echo json_encode($todos);
