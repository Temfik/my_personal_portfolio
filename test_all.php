<?php
echo "<h1>Complete System Test</h1>";

// Test 1: Database
try {
    $conn = new mysqli('localhost', 'root', '', 'temesgen_portfolio');
    echo "<p>✓ Database: OK</p>";
} catch (Exception $e) {
    echo "<p>✗ Database: " . $e->getMessage() . "</p>";
}

// Test 2: Admin user
 $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
 $stmt->bind_param("s", "temesgen001@gmail.com");
 $stmt->execute();
 $result = $stmt->get_result();
echo $result->num_rows === 1 ? "<p>✓ Admin User: OK</p>" : "<p>✗ Admin User: Missing</p>";

// Test 3: Password
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    echo password_verify("Temesgen@1.com", $user['password']) ? "<p>✓ Password: OK</p>" : "<p>✗ Password: Failed</p>";
}

// Test 4: Files
 $files = ['admin_login.php', 'db_connection.php', 'admin_dashboard.php'];
foreach ($files as $file) {
    echo file_exists($file) ? "<p>✓ $file: Exists</p>" : "<p>✗ $file: Missing</p>";
}

echo "<hr>";
echo "<a href='admin_login.php'>Go to Login</a>";
?>