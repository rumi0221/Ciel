<?php
require 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $plan_id = $_POST['plan_id'] ?? null;
        $todo_flg = $_POST['todo_flg'] ?? null;

        if ($plan_id !== null && ($todo_flg === '0' || $todo_flg === '1')) {
            $pdo = new PDO($connect, USER, PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare('UPDATE Plans SET todo_flg = ? WHERE plan_id = ?');
            $stmt->execute([$todo_flg, $plan_id]);

            echo json_encode(['status' => 'success', 'message' => '更新が成功しました']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => '不正な入力値です']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'エラーが発生しました: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => '無効なリクエストです']);
}
?>
