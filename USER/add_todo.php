<?php
require 'db-connect.php';

// エラーメッセージをJSON以外に出力しないよう設定
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 入力データを取得
        $todo = trim($_POST['todo'] ?? '');
        $Date = $_POST['formattedDate'] ?? date('Y-m-d'); // `$Date` の値
        $user_id = 8; // 仮のユーザーID。実際はセッションなどから取得
        $completionFlag = 0; // 新規TODOは未完了状態

        if (empty($todo)) {
            echo json_encode(['status' => 'error', 'message' => 'TODOは空ではいけません']);
            exit;
        }

        // Todosテーブルで該当の日付の行数をカウントして +1
        $sql = $pdo->prepare("SELECT COUNT(*) AS row_count FROM Todos WHERE input_date = ?");
        $sql->execute([$Date]);
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        $rowCount = $result['row_count'] ?? 0;
        $sortsum = $rowCount + 1;

        // TODOをデータベースに挿入
        $stmt = $pdo->prepare("
            INSERT INTO Todos (`user_id`, `sort_id`, `todo`, `completion_flg`, `input_date`)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$user_id, $sortsum, $todo, $completionFlag, $Date]);

        echo json_encode(['status' => 'success', 'message' => 'TODOが追加されました']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => '無効なリクエスト']);
}
