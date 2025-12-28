<?php
session_start();
require_once '../config/db.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL . "/modules/auth/login.php");
    exit();
}

include '../header.php';
?>

    <h2>Dashboard Quản Trị</h2>
    <p style="margin-bottom: 20px;">Xin chào, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong>!</p>
    
    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        <!-- Card chức năng: Quản lý Giao dịch -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #ddd; flex: 1; min-width: 250px;">
            <h3 style="margin-bottom: 15px; color: #333;">📊 Quản lý Giao dịch</h3>
            <p style="color: #666; font-size: 14px; margin-bottom: 20px;">Xem danh sách toàn bộ giao dịch, tìm kiếm, sửa hoặc xóa giao dịch của thành viên.</p>
            <a href="<?php echo BASE_URL; ?>/modules/transactions/admin_transactions/admin_report.php" 
               style="background: #0095f6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block;">
               Truy cập ngay &rarr;
            </a>
        </div>

        <!-- Placeholder cho chức năng khác -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #ddd; flex: 1; min-width: 250px;">
            <h3 style="margin-bottom: 15px; color: #333;">👥 Quản lý Thành viên</h3>
            <p style="color: #666; font-size: 14px; margin-bottom: 20px;">(Tính năng đang phát triển) Quản lý danh sách người dùng, khóa tài khoản...</p>
            <button disabled style="background: #ccc; color: white; padding: 10px 20px; border: none; border-radius: 4px; font-weight: bold; cursor: not-allowed;">Sắp ra mắt</button>
        </div>
    </div>

<?php
include '../footer.php';
?>