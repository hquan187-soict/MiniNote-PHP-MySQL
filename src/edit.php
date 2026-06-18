<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])) { 
header("Location: login.php"); 
    exit; 
}

$id_sua = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ? AND user_id = ?");
$stmt->execute([$id_sua, $_SESSION['user_id']]);
$note_cu = $stmt->fetch();

if(!$note_cu) die("Không tìm thấy ghi chú hoặc không có quyền truy cập!");

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tieude_moi = $_POST['title'];
    $noidung_update = $_POST['content'];
    $update = $pdo->prepare("UPDATE notes SET title=?, content=? WHERE id=? AND user_id=?");
    $update->execute([$tieude_moi, $noidung_update, $id_sua, $_SESSION['user_id']]);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<body>
    <h2>Sửa ghi chú</h2>
    <form method="POST" action="">
    <label>Tiêu đề:</label>
    <br>
    <input type="text" name="title" value="<?= htmlspecialchars($note_cu['title']) ?>" style="width:300px;margin-bottom:15px;padding:5px;">
    <br>
    <label>Nội dung:</label>
    <br>
    <textarea name="content" style="width:300px;height:120px;padding:5px;"><?= htmlspecialchars($note_cu['content']) ?></textarea><br><br>
    
    <button type="submit" style="background:blue;color:white;padding:8px 15px;border:none;cursor:pointer;">
        Cập nhật </button>
    <a href="index.php" style="text-decoration:none;margin-left:10px;">
        Hủy</a>
    </form>
    
    <style> form { border:2px dashed #999; padding:20px; max-width: 350px; } </style>
</body>
</html>