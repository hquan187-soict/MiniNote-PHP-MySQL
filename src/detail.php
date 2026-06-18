<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$id_note = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ? AND user_id = ?");
$stmt->execute([$id_note, $_SESSION['user_id']]);
$thong_tin_note = $stmt->fetch();
if(!$thong_tin_note) die("Lỗi: Không tìm thấy ghi chú hoặc không có quyền truy cập");

?>
<!DOCTYPE html>
<html lang="vi">
<body>
    <style> body { font-family: sans-serif; background: #f4f4f4; } </style>
    <h2>Chi tiết ghi chú</h2>
    
    <div style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,0.1);max-width:500px;">
        <h3 style="margin-top:0;color:#333;"><?= htmlspecialchars($thong_tin_note['title']) ?></h3>
        <small style="color:gray;">
            Được tạo vào: <?= $thong_tin_note['created_at'] ?>
        </small>
        <p style="white-space:pre-wrap;line-height:1.6;margin-top:15px;">
            <?= htmlspecialchars($thong_tin_note['content']) ?>
        </p>
    </div>
    
    <br>
    <a href="index.php" style="background:gray;color:white;padding:5px 10px;text-decoration:none;border-radius:3px;">
        Quay về
    </a>
</body>
</html>