<?php
require_once '../config/config.php';

// Bắt buộc đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = htmlspecialchars($_SESSION['username'] ?? 'Người dùng');
$email = htmlspecialchars($_SESSION['email'] ?? '');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng điều khiển - Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container dashboard-container">
        <div class="dashboard-header">
            <h2>Hệ thống Dashboard</h2>
            <a href="logout.php" class="custom-logout-btn">Đăng xuất</a>
        </div>
        
        <div class="welcome-card">
            <h3>Hi, <?php echo $username; ?>! 🎉</h3>
            <p style="color: var(--text-muted); line-height: 1.6;">
                Email: <strong style="color:var(--text-main)"><?php echo $email; ?></strong>
            </p>
        </div>
    </div>
</body>
</html>
