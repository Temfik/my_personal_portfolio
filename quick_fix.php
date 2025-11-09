<?php
// Quick fix for login issues
require_once 'db_connection.php';

echo "<h1>Quick Login Fix</h1>";

// 1. Clear and recreate admin_users table
echo "<p>Step 1: Recreating admin_users table...</p>";
 $conn->query("DROP TABLE IF EXISTS admin_users");
 $conn->query("
    CREATE TABLE admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

// 2. Insert correct credentials
echo "<p>Step 2: Inserting correct credentials...</p>";
 $username = "temesgen001@gmail.com";
 $password = "Temesgen@1.com";
 $email = "temesgen001@gmail.com";
 $hashed_password = password_hash($password, PASSWORD_DEFAULT);

 $stmt = $conn->prepare("INSERT INTO admin_users (username, password, email) VALUES (?, ?, ?)");
 $stmt->bind_param("sss", $username, $hashed_password, $email);

if ($stmt->execute()) {
    echo "<p style='color: green; font-weight: bold;'>✓ SUCCESS: Admin user created!</p>";
    echo "<p><strong>Username:</strong> " . $username . "</p>";
    echo "<p><strong>Password:</strong> " . $password . "</p>";
    
    // Test the login
    echo "<p>Step 3: Testing login...</p>";
    $test_stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
    $test_stmt->bind_param("s", $username);
    $test_stmt->execute();
    $result = $test_stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            echo "<p style='color: green;'>✓ Login test PASSED!</p>";
        } else {
            echo "<p style='color: red;'>✗ Login test FAILED!</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ User not found!</p>";
    }
    $test_stmt->close();
} else {
    echo "<p style='color: red;'>✗ Error creating admin user: " . $stmt->error . "</p>";
}

 $stmt->close();
 $conn->close();

echo "<hr>";
echo "<p><a href='admin_login.php' style='background: #007bff; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Try Login Now</a></p>";
echo "<p><a href='index.php' style='background: #28a745; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Go to Portfolio</a></p>";
?>