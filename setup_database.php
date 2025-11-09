<?php
// =========================
// Developer: Temesgen
// Purpose: Portfolio System Database Setup Script
// Updated: November 2025
// =========================

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'temesgen_portfolio';

try {
    // Connect using MySQLi
    $conn = new mysqli($host, $user, $pass);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create database if it doesn't exist
    $conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
    $conn->select_db($dbname);

    // Drop existing tables to start fresh (optional)
    $conn->query("DROP TABLE IF EXISTS project_images");
    $conn->query("DROP TABLE IF EXISTS projects");
    $conn->query("DROP TABLE IF EXISTS documents");
    $conn->query("DROP TABLE IF EXISTS contact_messages");
    $conn->query("DROP TABLE IF EXISTS admin_users");
    $conn->query("DROP TABLE IF EXISTS visitor_logs");

    // =========================
    // Create admin_users table
    // =========================
    $conn->query("
        CREATE TABLE admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Insert default admin user
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $conn->query("INSERT INTO admin_users (username, password, email) VALUES ('admin', '$password', 'temesgen001@gmail.com')");

    // =========================
    // Create contact_messages table
    // =========================
    $conn->query("
        CREATE TABLE contact_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            subject VARCHAR(200) NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // =========================
    // Create documents table
    // =========================
    $conn->query("
        CREATE TABLE documents (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(200) NOT NULL,
            description TEXT,
            category VARCHAR(50) NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            file_type VARCHAR(50) NOT NULL,
            file_size INT NOT NULL,
            upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            is_active BOOLEAN DEFAULT 1
        )
    ");

    // =========================
    // Create projects table
    // =========================
    $conn->query("
        CREATE TABLE projects (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(200) NOT NULL,
            description TEXT NOT NULL,
            technologies VARCHAR(255) NOT NULL,
            role VARCHAR(200) NOT NULL,
            project_url VARCHAR(255),
            github_url VARCHAR(255),
            featured_image VARCHAR(255),
            status ENUM('active', 'inactive') DEFAULT 'active',
            is_featured BOOLEAN NOT NULL DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");

    // =========================
    // Create project_images table
    // =========================
    $conn->query("
        CREATE TABLE project_images (
            id INT AUTO_INCREMENT PRIMARY KEY,
            project_id INT NOT NULL,
            image_path VARCHAR(255) NOT NULL,
            image_description TEXT,
            sort_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
        )
    ");

    // =========================
    // Create visitor_logs table
    // =========================
    $conn->query("
        CREATE TABLE visitor_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ip_address VARCHAR(45) NOT NULL,
            user_agent TEXT,
            page_visited VARCHAR(255),
            referrer VARCHAR(255),
            visit_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // =========================
    // Insert sample projects
    // =========================
    $stmt = $conn->prepare("
        INSERT INTO projects (title, description, technologies, role, project_url, github_url, featured_image, is_featured)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $projects = [
        ['Connect_Project', 'Work monitoring system for Awash Bank\'s regional and branch offices.', 'PHP, MySQL, Bootstrap', 'Lead Developer & Regional Facilitator', 'https://demo.connect-project.com', 'https://github.com/temesgen-connect-project', 'uploads/projects/connect_featured.jpg', TRUE],
        ['Performance Mirror', 'Task and performance analytics web app.', 'PHP, Laravel, MySQL', 'System Architect & Team Leader', null, 'https://github.com/temesgen-performance-mirror', 'uploads/projects/performance_featured.jpg', TRUE],
        ['IT Management System', 'Task and performance tracking system for regional IT officers.', 'PHP (PDO), MySQL, Bootstrap', 'Lead Developer & Supervisor', null, null, 'uploads/projects/itmanagement_featured.jpg', FALSE],
        ['Sophos Antivirus Follow-Up Management System', 'Security follow-up system for regional protection.', 'PHP, MySQL', 'Initiator & Manager', null, null, 'uploads/projects/sophos_featured.jpg', FALSE],
        ['Awash Bank Performance Tracking Solution System', 'A nationwide branch performance system.', 'Node.js, PHP, MySQL', 'Initiator, Regional Facilitator, Developer', null, 'https://github.com/temesgen-tracking-system', 'uploads/projects/tracking_featured.jpg', FALSE],
        ['SACCO Financial System', 'Withdrawal, deposit, and member registration management.', 'Node.js & PHP (PDO)', 'Developer & IT Consultant', null, 'https://github.com/temesgen-sacco-system', 'uploads/projects/sacco_featured.jpg', FALSE],
        ['Dental Clinic Management System', 'Dental record system in two languages (English & Amharic).', 'Node.js & PHP (PDO)', 'Developer & System Designer', null, 'https://github.com/temesgen-dental-system', 'uploads/projects/dental_featured.jpg', FALSE]
    ];

    foreach ($projects as $p) {
        $stmt->bind_param("sssssssi", $p[0], $p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7]);
        $stmt->execute();
    }
    $stmt->close();

    // =========================
    // Insert project images for Connect_Project
    // =========================
    $imageStmt = $conn->prepare("
        INSERT INTO project_images (project_id, image_path, image_description, sort_order)
        VALUES (1, ?, ?, ?)
    ");
    $images = [
        ['uploads/projects/connect_1.jpg', 'Dashboard view showing real-time work monitoring', 1],
        ['uploads/projects/connect_2.jpg', 'Team collaboration interface with real-time updates', 2],
        ['uploads/projects/connect_3.jpg', 'Analytics dashboard with KPI tracking', 3],
        ['uploads/projects/connect_4.jpg', 'Mobile-responsive design for field technicians', 4],
        ['uploads/projects/connect_5.jpg', 'Cross-platform compatibility', 5],
    ];
    foreach ($images as $img) {
        $imageStmt->bind_param("ssi", $img[0], $img[1], $img[2]);
        $imageStmt->execute();
    }
    $imageStmt->close();

    $success_message = "✅ Database setup completed successfully!";
} catch (Exception $e) {
    $error_message = "❌ Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Setup Complete</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 800px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin: auto;
            padding: 30px;
        }
        h1 { color: #000; }
        .success, .error {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .btn {
            background: #FFD700;
            color: #000;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            transition: 0.3s;
        }
        .btn:hover {
            background: #000;
            color: #FFD700;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Setup Result</h1>
        <?php if (isset($success_message)): ?>
            <div class="success"><?= $success_message ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="error"><?= $error_message ?></div>
        <?php endif; ?>
        <p>Next Steps:</p>
        <ol>
            <li>Create an <b>uploads</b> folder in your project root.</li>
            <li>Set folder permissions to <code>755</code> or <code>777</code>.</li>
            <li>Upload images to <code>uploads/projects/</code> as listed above.</li>
            <li>Open <a href="index.php" class="btn">Portfolio Homepage</a> to test your setup.</li>
        </ol>
    </div>
</body>
</html>
