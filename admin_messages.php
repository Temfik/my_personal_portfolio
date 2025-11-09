<?php
$page_title = 'Contact Messages';
require_once 'admin_header.php';

// Handle bulk message deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete_selected') {
    if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
        $_SESSION['error'] = "CSRF token validation failed.";
    } elseif (isset($_POST['message_ids']) && is_array($_POST['message_ids'])) {
        $ids = array_map('intval', $_POST['message_ids']);
        if (!empty($ids)) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $types = str_repeat('i', count($ids));
            $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id IN ($placeholders)");
            $stmt->bind_param($types, ...$ids);
            if ($stmt->execute()) {
                $_SESSION['success'] = count($ids) . " message(s) deleted successfully.";
            } else {
                $_SESSION['error'] = "Error deleting messages.";
            }
            $stmt->close();
        }
    }
    header('Location: admin_messages.php');
    exit;
}

// Handle message deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete_message') {
    if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
        $_SESSION['error'] = "CSRF token validation failed.";
    } else {
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Message deleted successfully.";
        } else {
            $_SESSION['error'] = "Error deleting message.";
        }
        $stmt->close();
    }
    header('Location: admin_messages.php');
    exit;
}

// Pagination logic
$limit = 15; // Messages per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total number of messages
$totalResult = $conn->query("SELECT COUNT(*) as total FROM contact_messages");
$totalMessages = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalMessages / $limit);

// Fetch messages for the current page
 $stmt = $conn->prepare("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT ? OFFSET ?");
 $stmt->bind_param("ii", $limit, $offset);
 $stmt->execute();
 $result = $stmt->get_result();

 $messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

?>

<style>
        .message-preview {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
</style>

            <div class="content-header">
                <h1>Contact Messages</h1>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3>All Messages</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($messages)): ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>No messages received yet.</p>
                        </div>
                    <?php else: ?>
                        <form id="bulkActionForm" action="admin_messages.php" method="POST" onsubmit="return confirm('Are you sure you want to delete the selected messages?');">
                            <input type="hidden" name="action" value="delete_selected">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Message</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($messages as $message): ?>
                                        <tr>
                                            <td><input type="checkbox" name="message_ids[]" class="message-checkbox" value="<?php echo $message['id']; ?>"></td>
                                            <td><?php echo $message['name']; ?></td>
                                            <td><?php echo $message['email']; ?></td>
                                            <td><?php echo $message['subject']; ?></td>
                                            <td class="message-preview" title="<?php echo $message['message']; ?>">
                                                <?php echo $message['message']; ?>
                                            </td>
                                            <td><?php echo date('M d, Y H:i', strtotime($message['created_at'])); ?></td>
                                            <td class="table-actions">
                                                <form action="admin_messages.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                                    <input type="hidden" name="action" value="delete_message">
                                                    <input type="hidden" name="id" value="<?php echo $message['id']; ?>">
                                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                    <button type="submit" title="Delete" style="color: #dc3545;">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-danger" style="margin-top: 1rem;">Delete Selected</button>
                        </form>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const messageCheckboxes = document.querySelectorAll('.message-checkbox');

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                messageCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }
    });
</script>

<?php
 $stmt->close();
 $conn->close();
 require_once 'admin_footer.php';
?>