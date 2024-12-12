<?php
require 'db-connect.php';

if (isset($_POST['todo_id'])) {
    $todoId = $_POST['todo_id'];

    try {
        $pdo = new PDO($connect, USER, PASS);
        $sql = $pdo->prepare('DELETE FROM Todos WHERE todo_id = ?');
        $sql->execute([$todoId]);

        echo 'success';
    } catch (Exception $e) {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
