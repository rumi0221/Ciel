<?php
require 'db-connect.php';

if (isset($_POST['plan_id']) && isset($_POST['completion_flg'])) {
    $plan_id = $_POST['plan_id'];
    $completion_flg = $_POST['completion_flg'];

    $pdo = new PDO($connect, USER, PASS);
    $sql = $pdo->prepare('UPDATE Plans SET completion_flg = ? WHERE plan_id = ?');
    $sql->execute([$completion_flg, $plan_id]);

    if ($sql->rowCount() > 0) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
