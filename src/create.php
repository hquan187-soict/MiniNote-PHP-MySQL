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
        .form-control { width: 100%; max-width: 400px; padding: 8px; box-sizing: border-box; }
    </style>
</head>
<body>
    <h2>Tạo ghi chú mới</h2>
    
    <?php if (!empty($error)): ?>
        <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="title">Tiêu đề:</label><br>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="content">Nội dung:</label><br>
            <textarea name="content" id="content" rows="6" class="form-control"></textarea>
        </div>
        <button type="submit">Lưu ghi chú</button>
        <a href="index.php"><button type="button">Hủy</button></a>
    </form>
</body>
</html>