<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_can_xoa = $_GET['id'] ?? 0;
if($id_can_xoa) {
    $xoa_query = $pdo->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
    $xoa_query->execute([$id_can_xoa, $_SESSION['user_id']]);
}

header("Location: index.php");
exit;
?>