<!DOCTYPE html>
<html>
<head>
    <title>Database Connection Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
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
    <h1>Database Connection Test</h1>
    
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Test 1: Basic MySQL connection
    echo "<div class='test-section'>";
    echo "<h3>Test 1: Basic Connection</h3>";
    try {
        $conn = new mysqli('localhost', 'root', '');
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        echo "<p class='success'>✓ Basic MySQL connection successful</p>";
        $conn->close();
    } catch (Exception $e) {
        echo "<p class='error'>✗ Basic connection failed: " . $e->getMessage() . "</p>";
    }
    echo "</div>";

    // Test 2: Database selection
    echo "<div class='test-section'>";
    echo "<h3>Test 2: Database Selection</h3>";
    try {
        $conn = new mysqli('localhost', 'root', '', 'temesgen_portfolio');
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        echo "<p class='success'>✓ Database 'temesgen_portfolio' selected successfully</p>";
        
        // Test 3: Check tables
        echo "<h4>Test 3: Check Tables</h4>";
        $tables = ['projects', 'project_images', 'admin_users', 'contact_messages', 'documents'];
        foreach ($tables as $table) {
            $result = $conn->query("SHOW TABLES LIKE '$table'");
            if ($result->num_rows > 0) {
                echo "<p class='success'>✓ Table '$table' exists</p>";
            } else {
                echo "<p class='error'>✗ Table '$table' missing</p>";
            }
        }
        
        // Test 4: Check projects data
        echo "<h4>Test 4: Check Projects Data</h4>";
        $result = $conn->query("SELECT COUNT(*) as count FROM projects");
        $row = $result->fetch_assoc();
        echo "<p class='info'>Found " . $row['count'] . " projects in database</p>";
        
        if ($row['count'] > 0) {
            $result = $conn->query("SELECT * FROM projects LIMIT 1");
            $project = $result->fetch_assoc();
            echo "<p class='info'>Sample project: " . $project['title'] . "</p>";
        }
        
        $conn->close();
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Database test failed: " . $e->getMessage() . "</p>";
    }
    echo "</div>";

    // Test 5: PHP extensions
    echo "<div class='test-section'>";
    echo "<h3>Test 5: PHP Extensions</h3>";
    echo "<p>PHP Version: " . PHP_VERSION . "</p>";
    echo "<p>MySQLi extension: " . (extension_loaded('mysqli') ? "✓ Loaded" : "✗ Not loaded") . "</p>";
    echo "<p>JSON extension: " . (extension_loaded('json') ? "✓ Loaded" : "✗ Not loaded") . "</p>";
    echo "</div>";

    // Test 6: File permissions
    echo "<div class='test-section'>";
    echo "<h3>Test 6: File Permissions</h3>";
    $uploads_dir = 'uploads';
    $projects_dir = 'uploads/projects';

    if (!file_exists($uploads_dir)) {
        if (mkdir($uploads_dir, 0755, true)) {
            echo "<p class='success'>✓ Created uploads directory</p>";
        } else {
            echo "<p class='error'>✗ Failed to create uploads directory</p>";
        }
    } else {
        echo "<p class='success'>✓ Uploads directory exists</p>";
    }

    if (!file_exists($projects_dir)) {
        if (mkdir($projects_dir, 0755, true)) {
            echo "<p class='success'>✓ Created projects directory</p>";
        } else {
            echo "<p class='error'>✗ Failed to create projects directory</p>";
        }
    } else {
        echo "<p class='success'>✓ Projects directory exists</p>";
    }
    echo "</div>";

    // Test 7: API Test
    echo "<div class='test-section'>";
    echo "<h3>Test 7: API Test</h3>";
    $api_url = 'get_projects.php';
    $context = stream_context_create([
        'http' => [
            'timeout' => 5
        ]
    ]);
    $response = @file_get_contents($api_url, false, $context);
    
    if ($response !== false) {
        $data = json_decode($response, true);
        if ($data && isset($data['success'])) {
            if ($data['success']) {
                echo "<p class='success'>✓ API working correctly</p>";
                echo "<p class='info'>Found " . count($data['projects']) . " projects</p>";
            } else {
                echo "<p class='error'>✗ API error: " . $data['message'] . "</p>";
            }
        } else {
            echo "<p class='error'>✗ Invalid JSON response</p>";
            echo "<pre>" . htmlspecialchars($response) . "</pre>";
        }
    } else {
        echo "<p class='error'>✗ API request failed</p>";
    }
    echo "</div>";
    ?>

    <div class="links">
        <h3>Quick Links:</h3>
        <a href="setup_database.php">Setup Database</a>
        <a href="admin_login.php">Admin Panel</a>
        <a href="index.php">Portfolio</a>
        <a href="get_projects.php">Test API</a>
    </div>
</body>
</html>