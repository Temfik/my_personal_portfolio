<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Login Debug Tool</h1>";

// Test database connection
echo "<h2>1. Database Connection Test</h2>";
try {
    $conn = new mysqli('localhost', 'root', '', 'temesgen_portfolio');
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    echo "<p style='color: green;'>✓ Database connected successfully</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ " . $e->getMessage() . "</p>";
    exit;
}

// Check if admin_users table exists
echo "<h2>2. Admin Users Table Check</h2>";
 $result = $conn->query("SHOW TABLES LIKE 'admin_users'");
if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✓ admin_users table exists</p>";
} else {
    echo "<p style='color: red;'>✗ admin_users table doesn't exist</p>";
    exit;
}

// Check if admin user exists
echo "<h2>3. Admin User Check</h2>";
 $stmt = $conn->prepare("SELECT * FROM admin_users");
 $stmt->execute();
 $result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✓ Found " . $result->num_rows . " admin user(s)</p>";
    
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Password Hash</th></tr>";
    
    while ($user = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['username'] . "</td>";
        echo "<td>" . $user['email'] . "</td>";
        echo "<td style='font-size: 10px;'>" . substr($user['password'], 0, 30) . "...</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>✗ No admin users found</p>";
}

// Test password verification
echo "<h2>4. Password Verification Test</h2>";
 $test_password = "Temesgen@1.com";
 $test_username = "temesgen001@gmail.com";

 $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
 $stmt->bind_param("s", $test_username);
 $stmt->execute();
 $result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    echo "<p>Testing password for user: " . $user['username'] . "</p>";
    
    if (password_verify($test_password, $user['password'])) {
        echo "<p style='color: green;'>✓ Password verification SUCCESS</p>";
    } else {
        echo "<p style='color: red;'>✗ Password verification FAILED</p>";
        echo "<p>Expected password: " . $test_password . "</p>";
        echo "<p>Stored hash: " . $user['password'] . "</p>";
        
        // Generate correct hash
        $correct_hash = password_hash($test_password, PASSWORD_DEFAULT);
        echo "<p>Correct hash should be: " . $correct_hash . "</p>";
    }
} else {
    echo "<p style='color: red;'>✗ User not found: " . $test_username . "</p>";
}

// Fix credentials if needed
echo "<h2>5. Fix Credentials (if needed)</h2>";
if (isset($_GET['fix']) && $_GET['fix'] == 'yes') {
    $new_password = "Temesgen@1.com";
    $new_username = "temesgen001@gmail.com";
    $new_email = "temesgen001@gmail.com";
    
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Delete existing users and insert new one
    $conn->query("DELETE FROM admin_users");
    $stmt = $conn->prepare("INSERT INTO admin_users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $new_username, $hashed_password, $new_email);
    
    if ($stmt->execute()) {
        echo "<p style='color: green; font-weight: bold;'>✓ Credentials fixed successfully!</p>";
        echo "<p><strong>New Username:</strong> " . $new_username . "</p>";
        echo "<p><strong>New Password:</strong> " . $new_password . "</p>";
    } else {
        echo "<p style='color: red;'>✗ Error fixing credentials: " . $stmt->error . "</p>";
    }
} else {
    echo "<p><a href='debug_login.php?fix=yes' style='background: #007bff; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Click here to fix credentials</a></p>";
}

 $conn->close();
?>

<p><a href="admin_login.php">Go to Login Page</a></p>