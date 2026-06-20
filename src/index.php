<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$tu_khoa = $_GET['keyword'] ?? '';
$id_hientai = $_SESSION['user_id'];
$ds_notes = [];

try {
    if ($tu_khoa != '') {
        $stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? AND (title LIKE ? OR content LIKE ?) ORDER BY created_at DESC");
        $stmt->execute([$id_hientai, "%$tu_khoa%", "%$tu_khoa%"]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$id_hientai]);
    }
    $ds_notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi kết nối"); 
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
    <form method="GET" action="" style="margin-top:15px;display:flex;gap:10px;">
        <input type="text" name="keyword" value="<?= htmlspecialchars($tu_khoa) ?>" placeholder="Nhập từ khóa..." style="padding:5px;width:250px;">
        <button type="submit" style="background:#333;color:#fff;border:none;padding:5px 10px;">Tìm kiếm</button>
        <a href="index.php" style="padding:5px 10px;background:#ddd;color:black;text-decoration:none;">Tất cả</a>
    </form>

    <h3>Danh sách ghi chú của bạn:</h3>

    <?php if (!empty($ds_notes)): ?>
        <ul class="note-list">
            <?php foreach ($ds_notes as $note): ?>
                <li style="margin-bottom:15px;border-bottom:1px solid #ccc;padding-bottom:10px;">
                    <strong><a href="detail.php?id=<?= $note['id'] ?>" style="text-decoration:none;color:black;font-size:18px;"><?= htmlspecialchars($note['title']) ?></a></strong>
                    <br>
                        <small>Tạo lúc: <?= $note['created_at'] ?></small>
                    <br>
                        <a href="edit.php?id=<?= $note['id'] ?>" style="margin-right:10px;font-weight:bold;color:orange;">Sửa</a> 
                        <a href="delete.php?id=<?= $note['id'] ?>" style="font-weight:bold;color:red;" onclick="
                            return confirm('Chắc chắn xóa chứ?');">Xóa</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p style="color:red;font-style:italic">Không có ghi chú nào match keyword!</p>
    <?php endif; ?>
</body>
</html>