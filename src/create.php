<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title)) {
        $error = "Tiêu đề không được để trống!";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO notes (user_id, title, content) VALUES (?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $title, $content]);
            
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            // Log lại lỗi hệ thống để dễ debug nếu sập DB
            error_log("Database Error: " . $e->getMessage());
            $error = "Có lỗi xảy ra trong quá trình lưu. Vui lòng thử lại sau!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tạo ghi chú - Mini Note</title>
    <style>
        .form-group { margin-bottom: 15px}
        .form-control { width: 100%; max-width: 100%; padding: 10px; box-sizing: border-box; border:1px solid #ccc; border-radius:4px; margin-top:5px; }
    </style>
</head>
<body style="background:#f4f4f4;font-family:sans-serif;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;">
    
    <div style="background:#fff;padding:30px;border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,0.1);width:100%;max-width:400px;">
        <h2 style="text-align:center;margin-top:0;">Tạo ghi chú mới</h2>
        
        <?php if (!empty($error)): ?>
            <p style="color: red; font-weight: bold; text-align:center;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="title" style="font-weight:bold;">Tiêu đề:</label><br>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="content" style="font-weight:bold;">Nội dung:</label><br>
                <textarea name="content" id="content" rows="6" class="form-control"></textarea>
            </div>
            
            <div style="display:flex;gap:10px;justify-content:center;margin-top:20px;">
                <button type="submit" style="background:#28a745;color:white;border:none;padding:10px 15px;border-radius:4px;cursor:pointer;">Lưu ghi chú</button>
                <a href="index.php"><button type="button" style="background:#6c757d;color:white;border:none;padding:10px 15px;border-radius:4px;cursor:pointer;">Hủy</button></a>
            </div>
        </form>
    </div>
</body>
</html>