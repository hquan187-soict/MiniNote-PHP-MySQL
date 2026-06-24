<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$stmt = $pdo->prepare("DELETE FROM notes WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);

header("Location: index.php");
exit;
?>