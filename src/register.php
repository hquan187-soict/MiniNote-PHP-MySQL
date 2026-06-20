<?php
session_start();
require 'db.php';

$loi_hienthi = '';
$is_dangky_thanhcong = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashed_password]);
            $is_dangky_thanhcong = true;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $loi_hienthi = "Tên đăng nhập đã tồn tại!";
            } else {
                die("Lỗi kết nối");
            }
        }
    } else {
        $loi_hienthi = "Vui lòng nhập đầy đủ!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<body style="background:#f4f4f4;font-family:sans-serif;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;">
    <div style="background:#fff;padding:30px;border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,0.1);width:300px;text-align:center;">
        <h2>Tạo tài khoản</h2>
        <?php if ($loi_hienthi): ?><p style="color:red;font-weight:bold;"><?= $loi_hienthi ?></p><?php endif; ?>
        <?php if ($is_dangky_thanhcong): ?><p style="color:green;font-weight:bold;">Đăng ký thành công!</p><?php endif; ?>
        
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Tên đăng nhập" required style="width:90%;padding:10px;margin-bottom:15px;border:1px solid #ccc;border-radius:4px;"><br>
            <input type="password" name="password" placeholder="Mật khẩu" required style="width:90%;padding:10px;margin-bottom:15px;border:1px solid #ccc;border-radius:4px;"><br>
            <button type="submit" style="background:#28a745;color:white;border:none;padding:10px;border-radius:4px;cursor:pointer;width:100%;font-size:16px;">Đăng ký</button>
        </form>
        <p style="margin-top:15px;font-size:14px;">Đã có tài khoản <a href="login.php" style="color:#007bff;text-decoration:none;">Đăng nhập</a></p>
    </div>
</body>
</html>