<?php
require 'db-connect.php';

if (isset($_POST['todo_id']) && isset($_POST['sort_id'])) {
    $todo_id = $_POST['todo_id'];
    $sort_id = $_POST['sort_id'];

    $pdo = new PDO($connect, USER, PASS);
    $sql = $pdo->prepare('UPDATE Todos SET sort_id = ? WHERE todo_id = ?');
    $sql->execute([$sort_id, $todo_id]);

    echo 'success';
} else {
    echo 'error';
}
?>
