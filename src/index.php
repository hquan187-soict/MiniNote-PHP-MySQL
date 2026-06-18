<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$notes = [];
try {
    $stmt = $pdo->prepare("SELECT id, title, content, created_at FROM notes WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Lỗi fetch danh sách notes: " . $e->getMessage());
    $error_msg = "Không thể tải dữ liệu lúc này. Vui lòng thử lại sau.";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Mini Note</title>
    <style>
        .header-container { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ccc; padding-bottom: 10px; margin-bottom: 20px;}
        .note-list { list-style-type: none; padding: 0; }
        .note-item { background: #f9f9f9; padding: 15px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #eee;}
        .note-meta { color: #666; font-size: 0.85em; margin: 5px 0 10px 0; }
        .error-text { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header-container">
        <h1>Xin chào, <?= htmlspecialchars($_SESSION['username'] ?? 'Bạn') ?>!</h1>
        <a href="logout.php">Đăng xuất</a>
    </div>

    <div class="actions">
        <a href="create.php"><button type="button">+ Tạo ghi chú mới</button></a>
    </div>

    <h3>Danh sách ghi chú của bạn:</h3>

    <?php if (isset($error_msg)): ?>
        <p class="error-text"><?= htmlspecialchars($error_msg) ?></p>
    <?php elseif (!empty($notes)): ?>
        <ul class="note-list">
            <?php foreach ($notes as $note): ?>
                <li style="margin-bottom:15px;border-bottom:1px solid #ccc;padding-bottom:10px;">
                    <strong><a href="detail.php?id=<?= $note['id'] ?>" style="text-decoration:none;color:black;font-size:18px;"><?= htmlspecialchars($note['title']) ?></a></strong>
                    <br>
                        <small>Tạo lúc: <?= $note['created_at'] ?></small>
                    <br>
                        <a href="edit.php?id=<?= $note['id'] ?>" style="margin-right:10px;font-weight:bold;">Sửa</a> 
                        <a href="delete.php?id=<?= $note['id'] ?>" style="font-weight:bold;" onclick="
                            return confirm('Chắc chắn xóa chứ?');">Xóa</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Bạn chưa có ghi chú nào. Hãy tạo mới!</p>
    <?php endif; ?>
</body>
</html>