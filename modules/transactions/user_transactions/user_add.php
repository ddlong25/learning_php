<?php
session_start();
include '../../../config/db.php';

// Khởi tạo mảng giao dịch nếu chưa có
if (!isset($_SESSION['transactions'])) {
    $_SESSION['transactions'] = [];
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Thu Chi - Giao dịch</title>
    <link rel="stylesheet" href="../../../assets/css/user_add.css">
</head>
<body>
    <div class="container">
    <a href="../../../index.php" style="text-decoration: none; color: #333; font-weight: bold; display: inline-block; margin-bottom: 15px;">← Quay lại</a>
    <h1>Thêm Giao dịch</h1>

    <?php
    // Xử lý form khi submit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = intval($_SESSION['user_id']);
        
        $wallet_id = isset($_POST['wallet_id']) ? intval($_POST['wallet_id']) : 0;
        $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
        $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
        $transaction_date = isset($_POST['transaction_date']) ? $_POST['transaction_date'] : date('Y-m-d');

        if ($user_id > 0 && $wallet_id > 0 && $category_id > 0 && $amount > 0) {
            // 1. Lấy loại danh mục (Thu/Chi) để biết cần cộng hay trừ tiền
            $sql_type = "SELECT type FROM categories WHERE id = ?";
            $stmt_type = $conn->prepare($sql_type);
            $stmt_type->bind_param("i", $category_id);
            $stmt_type->execute();
            $res_type = $stmt_type->get_result();
            $row_type = $res_type->fetch_assoc();
            $type = $row_type['type']; // 'income' hoặc 'expense'
            $stmt_type->close();

            $sql = "INSERT INTO transactions (user_id, wallet_id, category_id, amount, transaction_date) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiids", $user_id, $wallet_id, $category_id, $amount, $transaction_date);

            if ($stmt->execute()) {
                // 2. Cập nhật số dư ví (Balance)
                if ($type == 'income') {
                    $sql_update = "UPDATE wallets SET balance = balance + ? WHERE id = ?";
                } else {
                    $sql_update = "UPDATE wallets SET balance = balance - ? WHERE id = ?";
                }
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("di", $amount, $wallet_id);
                $stmt_update->execute();
                $stmt_update->close();

                echo '<p style="color: green;">Giao dịch đã được thêm thành công</p>';
            } else {
                echo '<p style="color: red;">Lỗi: ' . $stmt->error . '</p>';
            }
            $stmt->close();
        } else {
            echo '<p style="color: red;">Vui lòng điền đầy đủ thông tin hợp lệ.</p>';
        }
    }

    // Lấy danh sách danh mục và ví (Lấy SAU khi xử lý POST để hiển thị số dư mới nhất)
    $sql_cats = "SELECT id, name, type FROM categories ORDER BY type, name";
    $result_cats = $conn->query($sql_cats);

    // Chỉ lấy ví của người dùng hiện tại để hiển thị đúng số dư
    $user_id = intval($_SESSION['user_id']);
    $sql_wallets = "SELECT id, name, balance FROM wallets WHERE user_id = ?";
    $stmt_wallets = $conn->prepare($sql_wallets);
    $stmt_wallets->bind_param("i", $user_id);
    $stmt_wallets->execute();
    $result_wallets = $stmt_wallets->get_result();
    ?>

    <form method="post" action="">
        <label for="wallet_id">Chọn Ví thanh toán:</label>
        <select id="wallet_id" name="wallet_id" required>
            <option value="">-- Chọn ví --</option>
            <?php 
            if ($result_wallets->num_rows > 0) {
                while($wallet = $result_wallets->fetch_assoc()) {
                    echo "<option value='" . $wallet['id'] . "'>" . htmlspecialchars($wallet['name']) . " (Số dư: " . number_format($wallet['balance']) . ")</option>";
                }
            }
            ?>
        </select>

        <label for="category_id">Danh mục:</label>
        <select id="category_id" name="category_id" required>
            <option value="">-- Chọn danh mục --</option>
            <?php 
            if ($result_cats->num_rows > 0) {
                while($cat = $result_cats->fetch_assoc()) {
                    $typeLabel = ($cat['type'] == 'income') ? '(Thu)' : '(Chi)';
                    echo "<option value='" . $cat['id'] . "'>" . htmlspecialchars($cat['name']) . " " . $typeLabel . "</option>";
                }
            }
            ?>
        </select>

        <label for="amount">Số tiền (VNĐ):</label>
        <input type="number" id="amount" name="amount" min="0" step="0.01" required>

        <label for="transaction_date">Ngày giao dịch:</label>
        <input type="date" id="transaction_date" name="transaction_date" value="<?php echo date('Y-m-d'); ?>" required>

        <button type="submit">Thêm giao dịch</button>
    </form>

    <!-- Thay danh sách bằng button chuyển sang form khác -->
    <a href="user_history.php" class="btn-secondary">Xem lịch sử giao dịch</a>
    </div>
</body>
</html>
