<?php
session_start();
require_once 'db_connection.php';
require_once 'csrf_handler.php';

header('Content-Type: application/json');

// Check if user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['csrf_token']) || !validate_csrf_token($data['csrf_token'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'CSRF token validation failed.']);
        exit;
    }

    $id = intval($data['id'] ?? 0);
    $is_featured = intval($data['is_featured'] ?? 0);

    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE projects SET is_featured = ? WHERE id = ?");
        $stmt->bind_param("ii", $is_featured, $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Project feature status updated.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update status.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid project ID.']);
    }
    $conn->close();
}
?>