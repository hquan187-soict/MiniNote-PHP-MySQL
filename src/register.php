<?php
session_start();
require 'db.php';
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    if (!empty($username) && !empty($password)) {
        // Băm mật khẩu (Bcrypt)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashed_password]);
            $success = "Đăng ký thành công! Hãy đăng nhập.";
        } catch (PDOException $e) {
            // Lỗi 23000 thường là do trùng khóa UNIQUE (username đã tồn tại)
            if ($e->getCode() == 23000) {
                $error = "Tên đăng nhập đã tồn tại!";
            } else {
                $error = "Có lỗi xảy ra: " . $e->getMessage();
            }
        }
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký - Mini Note</title>
</head>
<body>
    <h2>Đăng ký tài khoản</h2>
    <?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>
    <?php if ($success): ?><p style="color:green;"><?= $success ?></p><?php endif; ?>
    <form method="POST" action="">
        <label>Tên đăng nhập:</label><br>
        <input type="text" name="username" required><br><br>
        <label>Mật khẩu:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Đăng ký</button>
    </form>
    <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
</body>
</html>