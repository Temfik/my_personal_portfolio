<?php
session_start();
require_once 'db_connection.php';
require_once 'csrf_handler.php';

// Check if user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF Token Validation
    if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
        $_SESSION['error'] = "CSRF token validation failed. Action aborted.";
        header('Location: admin_dashboard.php');
        exit;
    }

    $id = intval($_POST['id'] ?? 0);

    if ($id > 0) {
        // First, get the file path to delete the actual file
        $stmt = $conn->prepare("SELECT file_path FROM documents WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            if (file_exists($row['file_path'])) {
                unlink($row['file_path']); // Delete the file from the server
            }
        }
        $stmt->close();

        // Now, delete the record from the database
        $stmt = $conn->prepare("DELETE FROM documents WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Document deleted successfully.";
        } else {
            $_SESSION['error'] = "Error deleting document.";
        }
        $stmt->close();
    }
}
$conn->close();
header('Location: admin_dashboard.php');
exit;
?>