<?php
// This script should be included at the top of pages you want to track.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Do not log visits from logged-in admins
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    return; // Exit the script and do not log the visit
}


// Avoid logging every single refresh by the same user in a short time
$log_timeout = 60 * 5; // Log the same visitor again after 5 minutes
$current_page = $_SERVER['REQUEST_URI'];

if (!isset($_SESSION['last_visit_time']) || !isset($_SESSION['last_page_visited']) || (time() - $_SESSION['last_visit_time'] > $log_timeout) || $_SESSION['last_page_visited'] != $current_page) {
    
    require_once 'db_connection.php';

    // Gracefully handle if the table doesn't exist to prevent fatal errors.
    $table_check = $conn->query("SHOW TABLES LIKE 'visitor_logs'");
    if ($table_check->num_rows == 0) {
        // The table doesn't exist, so we can't log anything. Exit silently.
        return;
    }

    // Use filter_input for safer access to server variables
    $ip_address = filter_input(INPUT_SERVER, 'REMOTE_ADDR');
    $user_agent = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');
    $referrer = filter_input(INPUT_SERVER, 'HTTP_REFERER');

    // Prepare and execute the insert statement
    $stmt = $conn->prepare("INSERT INTO visitor_logs (ip_address, user_agent, page_visited, referrer) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssss", $ip_address, $user_agent, $current_page, $referrer);
        $stmt->execute();
        $stmt->close();
    }
    $conn->close();

    // Update session to prevent immediate re-logging
    $_SESSION['last_visit_time'] = time();
    $_SESSION['last_page_visited'] = $current_page;
}
?>