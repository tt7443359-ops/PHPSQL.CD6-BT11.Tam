<?php
require_once '../config/config.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $reset_password = $_POST['reset_password'] ?? ''; 

    if (empty($username) || empty($email) || empty($password) || empty($reset_password)) {
        $error = "Vui lòng điền đầy đủ thông tin!";
    } elseif ($password !== $reset_password) {
        $error = "Mật khẩu xác nhận không khớp!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Định dạng email không hợp lệ!";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $error = "Email này đã được đăng ký! Vui lòng sử dụng email khác.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $insert_stmt = $pdo->prepare($insert_query);
            
            if ($insert_stmt->execute([$username, $email, $hashed_password])) {
                // Đăng ký thành công, tự động nhảy sang login
                header("Location: login.php");
                exit();
            } else {
                $error = "Có lỗi xảy ra trong quá trình đăng ký. Vui lòng thử lại!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Tạo Tài Khoản Mới</h2>
        <p class="subtitle">Tham gia cùng chúng tôi ngay hôm nay</p>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Tên người dùng (Username)</label>
                <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="email">Địa chỉ Email</label>
                <input type="text" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <label for="reset_password">Xác nhận mật khẩu</label>
                <input type="password" id="reset_password" name="reset_password" class="form-control">
            </div>
            <button type="submit" class="btn">Đăng Ký Ngay</button>
        </form>

        <?php if (!empty($error)): ?>
            <div class="alert error" style="margin-top: 1.5rem; margin-bottom: 0;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="links">
            Đã có tài khoản? <a href="login.php">Đăng nhập</a>
        </div>
    </div>
</body>
</html>
