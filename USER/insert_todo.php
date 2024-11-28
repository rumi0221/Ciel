<?php
require 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 入力データを取得
    $todo = $_POST['todo'] ?? '';
    $inputDate = $_POST['formattedDate'] ?? date('Y-m-d');
    $userId = 8; // 仮の固定値（適宜変更）
    
    if (!empty($todo)) {
        try {
            $pdo = new PDO($connect, USER, PASS);

            // sort_idを計算: 同じuser_idとinput_dateの行数
            $sortSql = 'SELECT COUNT(*) AS row_count FROM Todos WHERE user_id = ? AND input_date = ?';
            $sortStmt = $pdo->prepare($sortSql);
            $sortStmt->execute([$userId, $inputDate]);
            $sortResult = $sortStmt->fetch();
            $sortId = $sortResult['row_count'];

            // INSERT文を実行
            $insertSql = 'INSERT INTO Todos (user_id, sort_id, todo, input_date) VALUES (?, ?, ?, ?)';
            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->execute([$userId, $sortId, $todo, $inputDate]);

            echo json_encode(['status' => 'success', 'message' => 'TODOが正常に追加されました！']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'TODOを空にはできません！']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => '無効なリクエストメソッドです！']);
}
?>
