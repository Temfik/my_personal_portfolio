<?php
require_once 'db_connection.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Documents Debug</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; border-color: #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border-color: #bee5eb; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 3px; overflow-x: auto; }
        .links { margin-top: 30px; }
        .links a { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; margin: 5px; border-radius: 5px; }
        .links a:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>Documents System Debug</h1>";

// Test 1: Database connection
echo "<div class='test-section'>";
echo "<h3>Test 1: Database Connection</h3>";
try {
    $conn = new mysqli('localhost', 'root', '', 'temesgen_portfolio');
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    echo "<p class='success'>✓ Database connected successfully</p>";
    $conn->close();
} catch (Exception $e) {
    echo "<p class='error'>✗ Database connection failed: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 2: Check documents table
echo "<div class='test-section'>";
echo "<h3>Test 2: Check Documents Table</h3>";
try {
    $conn = new mysqli('localhost', 'root', '', 'temesgen_portfolio');
    $table_check = $conn->query("SHOW TABLES LIKE 'documents'");
    
    if ($table_check->num_rows > 0) {
        echo "<p class='success'>✓ Documents table exists</p>";
        
        // Check if table has data
        $count_query = "SELECT COUNT(*) as count FROM documents";
        $count_result = $conn->query($count_query);
        $count_row = $count_result->fetch_assoc();
        
        echo "<p class='info'>Found " . $count_row['count'] . " documents in database</p>";
        
        if ($count_row['count'] > 0) {
            // Show sample data
            $sample_query = "SELECT * FROM documents LIMIT 3";
            $sample_result = $conn->query($sample_query);
            
            echo "<h4>Sample Documents:</h4>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Title</th><th>Category</th><th>File Path</th></tr>";
            
            while ($row = $sample_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['title'] . "</td>";
                echo "<td>" . $row['category'] . "</td>";
                echo "<td>" . $row['file_path'] . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
        } else {
            echo "<p class='info'>No documents found in database</p>";
        }
    } else {
        echo "<p class='error'>✗ Documents table doesn't exist</p>";
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "<p class='error'>✗ Error checking documents table: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 3: Check uploads directory
echo "<div class='test-section'>";
echo "<h3>Test 3: Check Uploads Directory</h3>";

 $uploads_dir = 'uploads/';
if (file_exists($uploads_dir)) {
    echo "<p class='success'>✓ Uploads directory exists</p>";
    
    if (is_writable($uploads_dir)) {
        echo "<p class='success'>✓ Uploads directory is writable</p>";
    } else {
        echo "<p class='error'>✗ Uploads directory is not writable</p>";
        echo "<p class='info'>Try running: chmod 755 uploads/</p>";
    }
    
    // List files in uploads directory
    $files = scandir($uploads_dir);
    $file_count = 0;
    
    echo "<h4>Files in uploads directory:</h4>";
    echo "<ul>";
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "<li>$file</li>";
            $file_count++;
        }
    }
    echo "</ul>";
    echo "<p class='info'>Found $file_count files</p>";
} else {
    echo "<p class='error'>✗ Uploads directory doesn't exist</p>";
    echo "<p class='info'>Try creating it: mkdir uploads</p>";
}
echo "</div>";

// Test 4: Test API directly
echo "<div class='test-section'>";
echo "<h3>Test 4: Test Documents API</h3>";

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
            echo "<p class='error'>✗ API returned error: " . $data['message'] . "</p>";
            if (isset($data['debug'])) {
                echo "<pre>" . json_encode($data['debug'], JSON_PRETTY_PRINT) . "</pre>";
            }
        } else {
            echo "<p class='success'>✓ API working correctly</p>";
            echo "<p class='info'>Returned " . count($data) . " documents</p>";
        }
    } else {
        echo "<p class='error'>✗ Invalid JSON response from API</p>";
        echo "<pre>" . htmlspecialchars($response) . "</pre>";
    }
} else {
    echo "<p class='error'>✗ API request failed</p>";
    echo "<p class='info'>Check if get_documents.php exists and is accessible</p>";
}
echo "</div>";

echo "<div class='links'>";
echo "<h3>Quick Links:</h3>";
echo "<a href='setup_database.php'>Setup Database</a>";
echo "<a href='admin_login.php'>Admin Panel</a>";
echo "<a href='index.php'>Portfolio</a>";
echo "<a href='get_documents.php'>Test Documents API</a>";
echo "</div>";

echo "</body>
</html>";
?>