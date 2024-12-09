<?php
require 'db-connect.php';

if (isset($_POST['todo_id']) && isset($_POST['todo'])) {
    $todoId = $_POST['todo_id'];
    $todo = $_POST['todo'];

    $sql = $pdo->prepare('UPDATE Todos SET todo = ? WHERE todo_id = ?');
    $sql->execute([$todo, $todoId]);
}
?>
