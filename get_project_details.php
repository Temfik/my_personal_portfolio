<?php
require_once 'db_connection.php';

header('Content-Type: application/json');

 $project_id = isset($_GET['id']) ? $_GET['id'] : 0;

try {
    $query = "SELECT * FROM projects WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Project not found");
    }
    
    $project = $result->fetch_assoc();
    
    // Get project images
    $images_query = "SELECT * FROM project_images WHERE project_id = ? ORDER BY sort_order ASC";
    $images_stmt = $conn->prepare($images_query);
    $images_stmt->bind_param("i", $project_id);
    $images_stmt->execute();
    $images_result = $images_stmt->get_result();
    
    $images = [];
    while ($image_row = $images_result->fetch_assoc()) {
        $images[] = $image_row;
    }
    
    $project['images'] = $images;
    
    echo json_encode([
        'success' => true,
        'project' => $project
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}

 $conn->close();
?>