<?php
session_start();
include '../../../config/db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Lấy tham số lọc
$filter_category = isset($_GET['filter_category']) ? intval($_GET['filter_category']) : 0;
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

// Lấy danh sách danh mục để hiển thị trong bộ lọc
$sql_cats = "SELECT id, name FROM categories ORDER BY name";
$result_cats = $conn->query($sql_cats);

// Lấy danh sách giao dịch từ database, sắp xếp mới nhất lên đầu
// KẾT NỐI (JOIN) với bảng categories để lấy tên danh mục và loại (thu/chi)
$sql = "SELECT t.*, c.name as category_name, c.type as category_type 
        FROM transactions t
        LEFT JOIN categories c ON t.category_id = c.id
        WHERE t.user_id = ?";

// Xây dựng câu query động và mảng tham số
$params = [$user_id];
$types = "i";

if (!empty($from_date)) {
    $sql .= " AND t.transaction_date >= ?";
    $params[] = $from_date;
    $types .= "s";
}

if (!empty($to_date)) {
    $sql .= " AND t.transaction_date <= ?";
    $params[] = $to_date;
    $types .= "s";
}

if ($filter_category > 0) {
    $sql .= " AND t.category_id = ?";
    $params[] = $filter_category;
    $types .= "i";
}

$sql .= " ORDER BY t.transaction_date DESC, t.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử giao dịch</title>
    <link rel="stylesheet" href="../../../assets/css/user_history.css">
</head>
<body>
    <div class="container">
    <h1>Lịch sử giao dịch</h1>

    <form method="GET" class="filter-form">
        <div class="filter-group">
            <label>Từ ngày:</label>
            <input type="date" name="from_date" value="<?php echo htmlspecialchars($from_date); ?>">
        </div>
        
        <div class="filter-group">
            <label>Đến ngày:</label>
            <input type="date" name="to_date" value="<?php echo htmlspecialchars($to_date); ?>">
        </div>

        <div class="filter-group">
            <label>Danh mục:</label>
            <select name="filter_category">
                <option value="0">-- Tất cả --</option>
                <?php 
                if ($result_cats && $result_cats->num_rows > 0) {
                    while($cat = $result_cats->fetch_assoc()) {
                        $selected = ($filter_category == $cat['id']) ? 'selected' : '';
                        echo "<option value='" . $cat['id'] . "' $selected>" . htmlspecialchars($cat['name']) . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <button type="submit">Tìm kiếm</button>
    </form>

    <?php if ($result->num_rows == 0): ?>
        <p class="empty-msg">Chưa có giao dịch nào phù hợp.</p>
        <?php if (!empty($from_date) || !empty($to_date) || $filter_category > 0): ?>
            <p style="text-align: center;"><a href="user_history.php" class="btn-back">Quay lại danh sách đầy đủ</a></p>
        <?php endif; ?>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Danh mục</th>
                    <th>Số tiền</th>
                    <th>Ngày</th>
                </tr>
            </thead>
            <tbody>
                <?php $stt = 1; // Biến đếm số thứ tự hiển thị ?>
                <?php while ($transaction = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $stt++; ?></td>
                        <td class="<?php echo $transaction['category_type']; ?>">
                            <!-- Hiển thị tên danh mục thay vì ID -->
                            <?php echo htmlspecialchars($transaction['category_name']); ?>
                        </td>
                        <td><?php echo number_format($transaction['amount'], 0, ',', '.'); ?> VNĐ</td>
                        <td><?php echo date('d/m/Y', strtotime($transaction['transaction_date'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 20px;">
        <a href="user_add.php" class="btn-back">← Quay lại thêm giao dịch</a>
    </div>
    </div>
</body>
</html>

    