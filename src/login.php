<?php
session_start();
require 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
$loi_hienthi = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user_info = $stmt->fetch();
        if ($user_info && password_verify($password, $user_info['password'])) {
            $_SESSION['user_id'] = $user_info['id'];
            $_SESSION['username'] = $user_info['username'];
            header("Location: index.php");
            exit;
        } else {
            $loi_hienthi = "Sai tài khoản hoặc mật khẩu!";
        }
    } else {
        $loi_hienthi = "Vui lòng nhập đủ thông tin!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<body style="background:#f4f4f4;font-family:sans-serif;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;">
    <div style="background:#fff;padding:30px;border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,0.1);width:300px;text-align:center;">
        <h2>Đăng nhập Mini Note</h2>
        <?php if ($loi_hienthi): ?><p style="color:red;font-weight:bold;"><?= $loi_hienthi ?></p><?php endif; ?>
        
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Tên đăng nhập" required style="width:90%;padding:10px;margin-bottom:15px;border:1px solid #ccc;border-radius:4px;"><br>
            <input type="password" name="password" placeholder="Mật khẩu" required style="width:90%;padding:10px;margin-bottom:15px;border:1px solid #ccc;border-radius:4px;"><br>
            <button type="submit" style="background:#333;color:white;border:none;padding:10px;border-radius:4px;cursor:pointer;width:100%;font-size:16px;">Vào trong</button>
        </form>
        <p style="margin-top:15px;font-size:14px;">Chưa có chưa có tài khoản? <a href="register.php" style="color:#007bff;text-decoration:none;">Tạo ngay</a></p>
    </div>
</body>
</html>