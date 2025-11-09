<?php
session_start();
require_once 'db_connection.php';

// ✅ Check if user is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ✅ Check if file was uploaded
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {

        $file_name = $_FILES['document']['name'];
        $file_tmp = $_FILES['document']['tmp_name'];
        $file_size = $_FILES['document']['size'];
        $file_type = $_FILES['document']['type'];

        // ✅ Validate file extension
        $allowed_extensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            $_SESSION['error'] = "Invalid file type. Only PDF, Word, Excel, PowerPoint, and image files are allowed.";
            header('Location: admin_dashboard.php?error=' . urlencode($_SESSION['error']));
            exit;
        }

        // ✅ Check file size (10MB max)
        if ($file_size > 10485760) {
            $_SESSION['error'] = "File size too large. Maximum size is 10MB.";
            header('Location: admin_dashboard.php?error=' . urlencode($_SESSION['error']));
            exit;
        }

        // ✅ Create upload directory if not exists
        $upload_dir = 'uploads/documents/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // ✅ Generate unique file name
        $new_filename = time() . '_' . uniqid() . '.' . $file_extension;
        $file_path = $upload_dir . $new_filename;

        // ✅ Move file to upload folder
        if (move_uploaded_file($file_tmp, $file_path)) {
            // ✅ Get form data safely
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $category = trim($_POST['category'] ?? '');

            // ✅ Validate required fields
            if (empty($title) || empty($category)) {
                $_SESSION['error'] = "Title and Category are required.";
                header('Location: admin_dashboard.php?error=' . urlencode($_SESSION['error']));
                exit;
            }

            // ✅ Prepare insert statement
            $stmt = $conn->prepare("INSERT INTO documents (title, description, category, file_path, file_type, file_size, is_active) VALUES (?, ?, ?, ?, ?, ?, 1)");
            if (!$stmt) {
                $_SESSION['error'] = "Database error: " . $conn->error;
                header('Location: admin_dashboard.php?error=' . urlencode($_SESSION['error']));
                exit;
            }

            // The '1' for is_active is already in the query, so we don't need to bind it.
            $stmt->bind_param("sssssi", $title, $description, $category, $file_path, $file_type, $file_size);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Document uploaded successfully!";
                header('Location: admin_dashboard.php?success=' . urlencode($_SESSION['success']));
                exit;
            } else {
                $_SESSION['error'] = "Error saving file record to database.";
                header('Location: admin_dashboard.php?error=' . urlencode($_SESSION['error']));
                exit;
            }

            $stmt->close();
        } else {
            $_SESSION['error'] = "Error moving uploaded file. Please check folder permissions.";
            header('Location: admin_dashboard.php?error=' . urlencode($_SESSION['error']));
            exit;
        }
    } else {
        $_SESSION['error'] = "Please select a file to upload.";
        header('Location: admin_dashboard.php?error=' . urlencode($_SESSION['error']));
        exit;
    }
}

$conn->close();
?>
