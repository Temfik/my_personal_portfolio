<?php
 $password = 'Temesgen@1.com';
 $hashed = password_hash($password, PASSWORD_DEFAULT);
echo "Password: " . $password . "<br>";
echo "Hashed: " . $hashed . "<br>";
echo "Verify: " . (password_verify($password, $hashed) ? "✓ Correct" : "✗ Incorrect") . "<br>";
?>