<?php
$page_title = 'Visitor Logs';
require_once 'admin_header.php';

// Handle clear logs action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'clear_logs') {
    if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
        $_SESSION['error'] = "CSRF token validation failed. Action aborted.";
    } else {
        // Use TRUNCATE for efficiency
        if ($conn->query("TRUNCATE TABLE visitor_logs")) {
            $_SESSION['success'] = "Visitor logs have been cleared successfully.";
        } else {
            $_SESSION['error'] = "Error clearing visitor logs: " . $conn->error;
        }
    }
    // Redirect to the same page to show the result and prevent form resubmission
    header('Location: admin_visitor_logs.php');
    exit;
}

// Pagination logic
$limit = 20; // Logs per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total number of logs
$totalResult = $conn->query("SELECT COUNT(*) as total FROM visitor_logs");
$totalLogs = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalLogs / $limit);

// Fetch logs for the current page
$stmt = $conn->prepare("SELECT * FROM visitor_logs ORDER BY visit_time DESC LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$logs = [];
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

function get_country_from_ip($ip) {
    // This is a basic example. For production, consider a local database like GeoLite2.
    // This external API call can be slow.
    $url = "http://ip-api.com/json/{$ip}?fields=country,countryCode";
    $response = @file_get_contents($url);
    if ($response) {
        $data = json_decode($response, true);
        if ($data && $data['country']) {
            return "{$data['country']} ({$data['countryCode']})";
        }
    }
    return 'Unknown';
}
?>

<div class="content-header">
    <h1>Visitor Logs</h1>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3>Recent Visitors (Total: <?php echo $totalLogs; ?>)</h3>
        <form method="POST" action="admin_visitor_logs.php" onsubmit="return confirm('Are you sure you want to permanently delete all visitor logs? This action cannot be undone.');">
            <input type="hidden" name="action" value="clear_logs">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" class="btn btn-danger" <?php echo $totalLogs === 0 ? 'disabled' : ''; ?>>Clear All Logs</button>
        </form>
    </div>
    <div class="card-body">
        <?php if (empty($logs)): ?>
            <div class="empty-state">
                <i class="fas fa-user-secret"></i>
                <p>No visitor data recorded yet.</p>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>IP Address</th>
                        <th>Location</th>
                        <th>Page Visited</th>
                        <th>Referrer</th>
                        <th>User Agent</th>
                        <th>Visit Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($log['ip_address']); ?></td>
                            <td><?php echo htmlspecialchars(get_country_from_ip($log['ip_address'])); ?></td>
                            <td><?php echo htmlspecialchars($log['page_visited']); ?></td>
                            <td>
                                <?php if ($log['referrer']): ?>
                                    <a href="<?php echo htmlspecialchars($log['referrer']); ?>" target="_blank" rel="noopener noreferrer">
                                        <?php echo htmlspecialchars(parse_url($log['referrer'], PHP_URL_HOST)); ?>
                                    </a>
                                <?php else: ?>
                                    Direct Visit
                                <?php endif; ?>
                            </td>
                            <td title="<?php echo htmlspecialchars($log['user_agent']); ?>" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <?php echo htmlspecialchars($log['user_agent']); ?>
                            </td>
                            <td><?php echo date('M d, Y H:i:s', strtotime($log['visit_time'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>
</div>

<?php
$stmt->close();
$conn->close();
require_once 'admin_footer.php';
?>