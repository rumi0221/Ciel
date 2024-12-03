<?php
ob_start(); // 出力バッファリングを開始

require 'db-connect.php';
$pdo = new PDO($connect, USER, PASS);

// // エラーメッセージをJSON以外に出力しないよう設定
// header('Content-Type: application/json; charset=utf-8');

if (!isset($pdo)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'データベース接続が確立されていません'
    ]);
    exit;
}

try {
    // データベース処理などを実行
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    // JSONレスポンス用変数
    $response = [
        'status' => 'success',
        'message' => 'TODOが追加されました'
    ];

    try {
        // データベース処理
        $todo = trim($_POST['todo'] ?? '');
        $Date = $_POST['formattedDate'] ?? date('Y-m-d');
        $userId = 8;

        if (empty($todo)) {
            $response = [
                'status' => 'error',
                'message' => 'TODOは空ではいけません'
            ];
        } else {
            // sort_idを計算: 同じuser_idとinput_dateの行数
            $sortSql = 'SELECT COUNT(*) AS row_count FROM Todos WHERE user_id = ? AND input_date = ?';
            $sortStmt = $pdo->prepare($sortSql);
            $sortStmt->execute([$userId, $Date]);
            $sortResult = $sortStmt->fetch();
            $sortId = $sortResult['row_count'];

            $stmt = $pdo->prepare("
                INSERT INTO Todos (user_id, sort_id, todo, input_date)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$userId, $sortId, $todo, $Date]);
        }
    } catch (PDOException $e) {
        $response = [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }

    // 最後に1回だけレスポンスを出力
    echo json_encode($response);
    // レスポンス内容をログファイルに出力
    file_put_contents('/path/to/debug.log', json_encode($response));
    exit;
}

ob_end_clean(); // バッファ内容をクリア
echo json_encode($response); // 必要なJSONレスポンスのみ出力



// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     try {
//         // 入力データを取得
//         $todo = trim($_POST['todo'] ?? '');
//         $Date = $_POST['formattedDate'] ?? date('Y-m-d'); // `$Date` の値
//         $user_id = 8; // 仮のユーザーID。実際はセッションなどから取得
//         $completionFlag = 0; // 新規TODOは未完了状態

//         if (empty($todo)) {
//             echo json_encode(['status' => 'error', 'message' => 'TODOは空ではいけません']);
//             exit;
//         }

//         // Todosテーブルで該当の日付の行数をカウントして +1
//         $sql = $pdo->prepare("SELECT COUNT(*) AS row_count FROM Todos WHERE input_date = ?");
//         $sql->execute([$Date]);
//         $result = $sql->fetch(PDO::FETCH_ASSOC);
//         $rowCount = $result['row_count'] ?? 0;
//         $sortsum = $rowCount + 1;

//         // TODOをデータベースに挿入
//         $stmt = $pdo->prepare("
//             INSERT INTO Todos (`user_id`, `sort_id`, `todo`, `completion_flg`, `input_date`)
//             VALUES (?, ?, ?, ?, ?)
//         ");
//         $stmt->execute([$user_id, $sortsum, $todo, $completionFlag, $Date]);

//         echo json_encode(['status' => 'success', 'message' => 'TODOが追加されました']);
//     } catch (PDOException $e) {
//         echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
//     }
// } else {
//     echo json_encode(['status' => 'error', 'message' => '無効なリクエスト']);
// }
