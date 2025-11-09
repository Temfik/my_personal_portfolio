<?php
session_start();
require_once 'db_connection.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Fix Documents System</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Documents System Fix</h1>";

// Step 1: Check and create uploads directory
echo "<div class='section'>";
echo "<h2>Step 1: Check Uploads Directory</h2>";

 $uploads_dir = 'uploads/';
if (!file_exists($uploads_dir)) {
    if (mkdir($uploads_dir, 0777, true)) {
        echo "<p class='success'>✓ Created uploads directory</p>";
    } else {
        echo "<p class='error'>✗ Failed to create uploads directory</p>";
    }
} else {
    echo "<p class='success'>✓ Uploads directory exists</p>";
    
    if (!is_writable($uploads_dir)) {
        if (chmod($uploads_dir, 0777)) {
            echo "<p class='success'>✓ Fixed uploads directory permissions</p>";
        } else {
            echo "<p class='error'>✗ Uploads directory is not writable</p>";
        }
    } else {
        echo "<p class='success'>✓ Uploads directory is writable</p>";
    }
}
echo "</div>";

// Step 2: Check and create documents table
echo "<div class='section'>";
echo "<h2>Step 2: Check Documents Table</h2>";

try {
    $conn = new mysqli('localhost', 'root', '', 'temesgen_portfolio');
    
    // Check if table exists
    $table_check = $conn->query("SHOW TABLES LIKE 'documents'");
    if ($table_check->num_rows === 0) {
        echo "<p class='error'>✗ Documents table doesn't exist</p>";
        
        // Create table
        $create_table = "
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
        ";
        
        if ($conn->query($create_table)) {
            echo "<p class='success'>✓ Created documents table</p>";
        } else {
            echo "<p class='error'>✗ Failed to create documents table: " . $conn->error . "</p>";
        }
    } else {
        echo "<p class='success'>✓ Documents table exists</p>";
        
        // Check if table has data
        $count_query = "SELECT COUNT(*) as count FROM documents";
        $count_result = $conn->query($count_query);
        $count_row = $count_result->fetch_assoc();
        
        if ($count_row['count'] === 0) {
            echo "<p class='error'>✗ No documents found in table</p>";
            
            // Insert sample documents
            $sample_docs = [
                [
                    'title' => 'Professional CV - Temesgen Fikadu Baysa',
                    'description' => 'Comprehensive curriculum vitae highlighting professional experience and qualifications',
                    'category' => 'CV',
                    'file_path' => 'uploads/documents/Temesgen_CV.pdf',
                    'file_type' => 'application/pdf',
                    'file_size' => 1024000
                ],
                [
                    'title' => 'Job Application Letter - IT Supervisor',
                    'description' => 'Application letter for Regional IT Supervisor position',
                    'category' => 'Application Letter',
                    'file_path' => 'uploads/documents/Application_Letter.pdf',
                    'file_type' => 'application/pdf',
                    'file_size' => 512000
                ],
                [
                    'title' => 'MSc Certificate - Project Management',
                    'description' => 'Master of Science in Project Planning and Management certificate',
                    'category' => 'Education',
                    'file_path' => 'uploads/documents/MSc_Certificate.pdf',
                    'file_type' => 'application/pdf',
                    'file_size' => 768000
                ]
            ];
            
            foreach ($sample_docs as $doc) {
                $insert_query = "INSERT INTO documents (title, description, category, file_path, file_type, file_size) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("sssssi", $doc['title'], $doc['description'], $doc['category'], $doc['file_path'], $doc['file_type'], $doc['file_size']);
                $stmt->execute();
                $stmt->close();
            }
            
            echo "<p class='success'>✓ Added sample documents</p>";
        } else {
            echo "<p class='success'>✓ Found " . $count_row['count'] . " documents in table</p>";
        }
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "<p class='error'>✗ Database error: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Step 3: Test API
echo "<div class='section'>";
echo "<h2>Step 3: Test Documents API</h2>";

 $api_url = 'get_documents.php';
 $context = stream_context_create([
    'http' => [
        'timeout' => 5
    ]
]);

 $response = @file_get_contents($api_url, false, $context);

if ($response !== false) {
    $data = json_decode($response, true);
    
    if ($data) {
        if (isset($data['error']) && $data['error']) {
            echo "<p class='error'>✗ API error: " . $data['message'] . "</p>";
        } else {
            echo "<p class='success'>✓ API working correctly</p>";
            echo "<p class='success'>✓ Returned " . count($data) . " documents</p>";
        }
    } else {
        echo "<p class='error'>✗ Invalid JSON response from API</p>";
    }
} else {
    echo "<p class='error'>✗ API request failed</p>";
}
echo "</div>";

echo "<div class='section'>";
echo "<h2>Fix Complete!</h2>";
echo "<p>Your documents system should now be working correctly.</p>";
echo "<a href='admin_dashboard.php' class='btn'>Go to Admin Dashboard</a>";
echo "<a href='index.php' class='btn'>Go to Portfolio</a>";
echo "</div>";

echo "</body>
</html>";
?>