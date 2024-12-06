<?php
session_start();
require 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $pdo = new PDO($connect, USER, PASS);
        $user_id = $_SESSION['user']['user_id'];

        $todo = trim($_POST['todo'] ?? '');
        $Date = $_POST['formattedDate'] ?? date('Y-m-d');
        $userId = $user_id;

        if (empty($todo)) {
            echo json_encode(['status' => 'error', 'message' => 'TODOは空ではいけません']);
            exit;
        }

        // sort_idを計算
        $sortSql = 'SELECT COUNT(*) AS row_count FROM Todos WHERE user_id = ? AND input_date = ?';
        $sortStmt = $pdo->prepare($sortSql);
        $sortStmt->execute([$userId, $Date]);
        $sortId = $sortStmt->fetchColumn();

        // データベースに挿入
        $stmt = $pdo->prepare("
            INSERT INTO Todos (user_id, sort_id, todo, input_date)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$userId, $sortId, $todo, $Date]);

        // 追加されたTODOの情報を取得
        $todoId = $pdo->lastInsertId();
        $response = [
            'status' => 'success',
            'todo' => [
                'todo_id' => $todoId,
                'sort_id' => $sortId,
                'todo' => htmlspecialchars($todo),
                'completion_flg' => 0,
                'input_date' => $Date,
            ],
        ];
        echo json_encode($response);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
} else {
    // 無効なリクエストメソッドへの対応
    echo json_encode(['status' => 'error', 'message' => '無効なリクエスト']);
    exit;
}




// session_start();
// ob_start(); // 出力バッファリングを開始

// require 'db-connect.php';
// $pdo = new PDO($connect, USER, PASS);
// $user_id =  $_SESSION['user']['user_id'];

// // // エラーメッセージをJSON以外に出力しないよう設定
// // header('Content-Type: application/json; charset=utf-8');

// if (!isset($pdo)) {
//     echo json_encode([
//         'status' => 'error',
//         'message' => 'データベース接続が確立されていません'
//     ]);
//     exit;
// }

// try {
//     // データベース処理などを実行
//     echo json_encode(['status' => 'success']);
// } catch (Exception $e) {
//     echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
// }

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     header('Content-Type: application/json; charset=utf-8');
//     try {
//         $todo = trim($_POST['todo'] ?? '');
//         $Date = $_POST['formattedDate'] ?? date('Y-m-d');
//         $userId = $user_id;

//         if (empty($todo)) {
//             echo json_encode(['status' => 'error', 'message' => 'TODOは空ではいけません']);
//             exit;
//         }

//         // sort_idを計算
//         $sortSql = 'SELECT COUNT(*) AS row_count FROM Todos WHERE user_id = ? AND input_date = ?';
//         $sortStmt = $pdo->prepare($sortSql);
//         $sortStmt->execute([$userId, $Date]);
//         $sortId = $sortStmt->fetchColumn();

//         // データベースに挿入
//         $stmt = $pdo->prepare("
//             INSERT INTO Todos (user_id, sort_id, todo, input_date)
//             VALUES (?, ?, ?, ?)
//         ");
//         $stmt->execute([$userId, $sortId, $todo, $Date]);

//         // 追加されたTODOの情報を取得
//         $todoId = $pdo->lastInsertId();
//         $response = [
//             'status' => 'success',
//             'todo' => [
//                 'todo_id' => $todoId,
//                 'sort_id' => $sortId,
//                 'todo' => htmlspecialchars($todo),
//                 'completion_flg' => 0,
//                 'input_date' => $Date,
//             ],
//         ];
//         echo json_encode($response);
//     } catch (PDOException $e) {
//         echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
//     }
//     exit;
// }

// ob_end_clean(); // バッファ内容をクリア
// echo json_encode($response); // 必要なJSONレスポンスのみ出力


?>