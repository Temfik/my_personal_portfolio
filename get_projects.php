<?php
require_once 'db_connection.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    // Check if tables exist
    $tables_check = $conn->query("SHOW TABLES LIKE 'projects'");
    if ($tables_check->num_rows === 0) {
        throw new Exception("Projects table doesn't exist. Please run setup_database.php first.");
    }
    
    $query = "
        SELECT p.*, 
               (SELECT COUNT(*) FROM project_images WHERE project_id = p.id) as image_count
        FROM projects p 
        WHERE p.status = 'active'
        ORDER BY p.is_featured DESC, p.created_at DESC
    ";
    
    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    $projects = [];
    
    while ($row = $result->fetch_assoc()) {
        // Get project images
        $imageQuery = "SELECT * FROM project_images WHERE project_id = ? ORDER BY sort_order ASC";
        $imageStmt = $conn->prepare($imageQuery);
        $imageStmt->bind_param("i", $row['id']);
        $imageStmt->execute();
        $imageResult = $imageStmt->get_result();
        
        $images = [];
        while ($imageRow = $imageResult->fetch_assoc()) {
            $images[] = $imageRow;
        }
        
        $imageStmt->close();
        
        $row['images'] = $images;
        $projects[] = $row;
    }
    
    echo json_encode(['success' => true, 'projects' => $projects]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

 $conn->close();
?>