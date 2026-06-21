<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

$id_sua = $_GET['id'] ?? 0;
$loi_hienthi = '';
$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ? AND user_id = ?");
$stmt->execute([$id_sua, $_SESSION['user_id']]);
$note_cu = $stmt->fetch();

if(!$note_cu) die("Không tìm thấy ghi chú hoặc không có quyền truy cập!");

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tieude_moi = trim($_POST['title']);
    $noidung_update = trim($_POST['content']);
    
    if(empty($tieude_moi)) {
        $loi_hienthi = "Không được để trống tiêu đề!";
    } else {
        $update = $pdo->prepare("UPDATE notes SET title=?, content=? WHERE id=? AND user_id=?");
        $update->execute([$tieude_moi, $noidung_update, $id_sua, $_SESSION['user_id']]);
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<body style="background:#f4f4f4;font-family:sans-serif;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;">
    
    <div style="background:#fff;padding:30px;border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,0.1);width:100%;max-width:400px;">
        <h2 style="text-align:center;margin-top:0;">Sửa ghi chú</h2>
        
        <?php if ($loi_hienthi): ?>
            <p style="color:red;font-weight:bold;text-align:center;"><?= htmlspecialchars($loi_hienthi) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div style="margin-bottom:15px;">
                <label style="font-weight:bold;">Tiêu đề:</label><br>
                <input type="text" name="title" value="<?= htmlspecialchars($note_cu['title']) ?>" style="width:100%;padding:10px;box-sizing:border-box;border:1px solid #ccc;border-radius:4px;margin-top:5px;" required>
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="font-weight:bold;">Nội dung:</label><br>
                <textarea name="content" rows="6" style="width:100%;padding:10px;box-sizing:border-box;border:1px solid #ccc;border-radius:4px;margin-top:5px;"><?= htmlspecialchars($note_cu['content']) ?></textarea>
            </div>
            
            <div style="display:flex;gap:10px;justify-content:center;margin-top:20px;">
                <button type="submit" style="background:#007bff;color:white;border:none;padding:10px 15px;border-radius:4px;cursor:pointer;">Cập nhật</button>
                <a href="index.php"><button type="button" style="background:#6c757d;color:white;border:none;padding:10px 15px;border-radius:4px;cursor:pointer;">Hủy</button></a>
            </div>
        </form>
    </div>
</body>
</html>