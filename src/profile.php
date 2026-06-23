<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_hientai = $_SESSION['user_id'];
$thongbao_loi = '';
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id_hientai]);
$ds_user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mk_cu = $_POST['old_password'];
    $mk_moi = $_POST['new_password'];

    if (!empty($mk_cu) && !empty($mk_moi)) {
        if (password_verify($mk_cu, $ds_user['password'])) {
            $hash_moi = password_hash($mk_moi, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->execute([$hash_moi, $id_hientai]);
            
            $thongbao_loi = "<span style='color:green;font-weight:bold;'>Đổi pass ngon lành rồi nhé!</span>";
        } else {
            $thongbao_loi = "<span style='color:red;font-weight:bold;'>Pass cũ sai rồi!</span>";
        }
    } else {
        $thongbao_loi = "<span style='color:red;font-weight:bold;'>Nhập đủ thông tin vào!</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tài khoản của tôi - Mini Note</title>
</head>
<body style="font-family:sans-serif; background:#f4f4f4; padding:20px; display:flex; justify-content:center; align-items:center; height:100vh; margin:0;">
    
    <div style="background:#fff; padding:30px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1); width:100%; max-width:400px;">
        <h2 style="text-align:center; margin-top:0;">Trang cá nhân</h2>
        
        <div style="text-align:center; margin-bottom:15px;">
            <?php if ($thongbao_loi) echo $thongbao_loi; ?>
        </div>

        <div style="margin-bottom:15px;">
            <label style="font-weight:bold;">Tên đăng nhập:</label><br>
            <input type="text" value="<?= htmlspecialchars($ds_user['username']) ?>" disabled style="width:100%; padding:10px; box-sizing:border-box; border:1px solid #ccc; border-radius:4px; margin-top:5px; background:#e9ecef;">
        </div>

        <hr style="margin:20px 0;">
        <h4 style="margin-top:0;">Đổi mật khẩu</h4>
        
        <form method="POST" action="">
            <div style="margin-bottom:15px;">
                <input type="password" name="old_password" placeholder="Mật khẩu cũ" required style="width:100%; padding:10px; box-sizing:border-box; border:1px solid #ccc; border-radius:4px;">
            </div>
            <div style="margin-bottom:15px;">
                <input type="password" name="new_password" placeholder="Mật khẩu mới" required style="width:100%; padding:10px; box-sizing:border-box; border:1px solid #ccc; border-radius:4px;">
            </div>
            
            <div style="display:flex; gap:10px; justify-content:center; margin-top:20px;">
                <button type="submit" style="background:#007bff; color:white; border:none; padding:10px 15px; border-radius:4px; cursor:pointer; font-weight:bold;">Cập nhật Pass</button>
                <a href="index.php" style="background:#6c757d; color:white; border:none; padding:10px 15px; border-radius:4px; cursor:pointer; text-decoration:none; font-weight:bold;">Quay lại</a>
            </div>
        </form>
    </div>

</body>
</html>