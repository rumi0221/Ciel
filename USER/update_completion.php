<?php
require 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $todo_id = $_POST['todo_id'] ?? null;
    $completion_flg = $_POST['completion_flg'] ?? null;

    if ($todo_id !== null && $completion_flg !== null) {
        try {
            $pdo = new PDO($connect, USER, PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // データベース更新クエリ
            $stmt = $pdo->prepare('UPDATE Todos SET completion_flg = ? WHERE todo_id = ?');
            $stmt->execute([$completion_flg, $todo_id]);

            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => '無効なデータ']);
    }
}
