<?php
require 'db-connect.php';

if (isset($_POST['todo_id']) && isset($_POST['todo'])) {
    $todoId = $_POST['todo_id'];
    $todo = $_POST['todo'];

    $pdo = new PDO($connect, USER, PASS);
    $sql = $pdo->prepare('UPDATE Todos SET todo = ? WHERE todo_id = ?');
    $sql->execute([$todo, $todoId]);

    echo 'success';
} else {
    echo 'error';
}
?>
