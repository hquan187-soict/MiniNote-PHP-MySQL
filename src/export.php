<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}
$id_export = $_GET['id'] ?? 0;
//lấy data ra ghi vào file
$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ? AND user_id = ?");
$stmt->execute([$id_export, $_SESSION['user_id']]);
$data_note = $stmt->fetch();

if(!$data_note) die("Lỗi: Không tìm thấy file để xuất!");

// nối chuỗi rồi cho tải về
$ten_file = "MiniNote_" . $data_note['id'] . ".txt";
$noi_dung_file = "TIÊU ĐỀ: " . $data_note['title'] . "\r\n";
$noi_dung_file .= "NGÀY TẠO: " . $data_note['created_at'] . "\r\n";
$noi_dung_file .= "====================================\r\n\r\n";
$noi_dung_file .= $data_note['content'];

// Ép trình duyệt tải file về thay vì hiển thị
header('Content-Type: text/plain; charset=utf-8');
header('Content-Disposition: attachment; filename="'.$ten_file.'"');
echo $noi_dung_file;
exit;
?>