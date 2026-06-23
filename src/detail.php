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
<body style="font-family: sans-serif; background: #f4f4f4; display:flex; justify-content:center; align-items:center; height:100vh; margin:0;">
    
    <div style="background:#fff;padding:30px;border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,0.1);width:100%;max-width:500px;">
        <h2 style="text-align:center;margin-top:0;">Chi tiết ghi chú</h2>
        <h3 style="margin-top:10px;color:#333;border-bottom:1px solid #eee;padding-bottom:10px;"><?= htmlspecialchars($thong_tin_note['title']) ?></h3>
        <small style="color:gray;display:block;margin-bottom:10px;">
            Được tạo vào: <?= $thong_tin_note['created_at'] ?>
        </small>
        <?php if (!empty($thong_tin_note['image_path'])): ?>
            <div style="text-align:center; margin-top:10px;">
                <img src="<?= htmlspecialchars($thong_tin_note['image_path']) ?>" style="max-width:100%; border-radius:4px; border:1px solid #ccc;">
            </div>
        <?php endif; ?>
        <p style="white-space:pre-wrap;line-height:1.6;margin-top:15px;background:#f9f9f9;padding:15px;border-radius:4px;border:1px solid #ddd;">
            <?= htmlspecialchars($thong_tin_note['content']) ?>
        </p>
        
        <br>
        <div style="text-align:center;margin-top:10px;">
            <a href="index.php" style="background:#6c757d;color:white;padding:8px 20px;text-decoration:none;border-radius:4px;">
                Quay về
            </a>
        </div>
    </div>
</body>
</html>