<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$tu_khoa = $_GET['keyword'] ?? '';
$thu_tu = $_GET['sort'] ?? 'desc';
$id_hientai = $_SESSION['user_id'];
$ds_notes = [];
$sql_order = $thu_tu === 'asc' ? 'ASC' : 'DESC';

try {
    if ($tu_khoa != '') {
        $stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? AND (title LIKE ? OR content LIKE ?) ORDER BY created_at $sql_order");
        $stmt->execute([$id_hientai, "%$tu_khoa%", "%$tu_khoa%"]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY created_at $sql_order");
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
    <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #ccc; padding-bottom:10px; margin-bottom:20px;">
        <h1 style="margin:0;">Xin chào, <a href="profile.php" style="color:#007bff; text-decoration:none;"><?= htmlspecialchars($_SESSION['username'] ?? 'Bạn') ?></a>!</h1>
        <a href="logout.php" style="color:red; font-weight:bold; text-decoration:none;">Đăng xuất</a>
    </div>

    <div class="actions" style="margin-bottom: 20px; display:flex; gap:10px;">
        <a href="create.php" style="background:#28a745; color:white; padding:8px 15px; text-decoration:none; border-radius:4px; font-weight:bold;">+ Tạo ghi chú mới</a>
        <a href="delete_all.php" onclick="return confirm('Mất sạch ráng chịu nha. Xóa hết?');" style="background:#dc3545; color:white; padding:8px 15px; text-decoration:none; border-radius:4px; font-weight:bold;">Xóa tất cả</a>
    </div>
    <form method="GET" action="" style="margin-top:15px;display:flex;gap:10px;align-items:center;">
        <input type="text" name="keyword" value="<?= htmlspecialchars($tu_khoa) ?>" placeholder="Nhập từ khóa..." style="padding:5px;width:250px;">
        
        <select name="sort" style="padding:5px;height:31px;">
            <option value="desc" <?= $thu_tu == 'desc' ? 'selected' : '' ?>>Mới nhất</option>
            <option value="asc" <?= $thu_tu == 'asc' ? 'selected' : '' ?>>Cũ nhất</option>
        </select>

        <button type="submit" style="background:#333;color:#fff;border:none;padding:5px 10px;">Tìm kiếm</button>
        <a href="index.php" style="padding:5px 10px;background:#ddd;color:black;text-decoration:none;">Tất cả</a>
    </form>

    <h3>Danh sách ghi chú của bạn (<?= count($ds_notes) ?>):</h3>

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