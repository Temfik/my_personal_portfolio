<?php
require_once 'db_connection.php';

header('Content-Type: application/json');

try {
    $category = isset($_GET['category']) ? $_GET['category'] : '';
    
    $params = [];
    $query = "SELECT * FROM documents WHERE is_active = 1"; // Use 1 for TRUE in SQL
    
    if (!empty($category)) {
        $query .= " AND category = ?";
        $params[] = $category;
    }
    
    $query .= " ORDER BY upload_date DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    
    $documents = [];
    while ($row = $stmt->fetch()) { // PDO uses fetch() in a loop
        $documents[] = $row;
    }
    
    echo json_encode($documents);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}

 // No need to close PDO connection manually when using persistent connections.
?>