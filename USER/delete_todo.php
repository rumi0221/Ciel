<?php
require 'db-connect.php';

if (isset($_POST['todo_id'])) {
    $todoId = $_POST['todo_id'];

    $sql = $pdo->prepare('DELETE FROM Todos WHERE todo_id = ?');
    $sql->execute([$todoId]);
}
?>
