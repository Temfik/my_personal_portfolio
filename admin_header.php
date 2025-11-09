<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_connection.php';
require_once 'csrf_handler.php';

// Check if user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

// Generate CSRF token for forms
generate_csrf_token();

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Admin Panel'; ?> - Temesgen Fikadu Baysa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php require_once 'admin_styles.php'; ?>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Admin Panel</h2>
                <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></p>
            </div>
            <ul class="sidebar-menu">
                <li><a href="admin_dashboard.php" class="<?php echo $current_page == 'admin_dashboard.php' ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="admin_projects.php" class="<?php echo in_array($current_page, ['admin_projects.php', 'admin_project_images.php']) ? 'active' : ''; ?>"><i class="fas fa-project-diagram"></i> Projects</a></li>
                <li><a href="admin_visitor_logs.php" class="<?php echo $current_page == 'admin_visitor_logs.php' ? 'active' : ''; ?>"><i class="fas fa-users"></i> Visitor Logs</a></li>
                <li><a href="admin_messages.php" class="<?php echo $current_page == 'admin_messages.php' ? 'active' : ''; ?>"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <div class="main-content">