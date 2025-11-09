<?php
$page_title = 'Dashboard';
require_once 'admin_header.php'; // Includes session, db, auth check
require_once 'admin_visitor_logs.php'; // Include functions from visitor logs page

// Handle success/error messages
 $success_message = isset($_GET['success']) ? $_GET['success'] : '';
 $error_message = isset($_GET['error']) ? $_GET['error'] : '';

// =================================
// Fetch Dashboard Stats
// =================================

// Visitor Stats
$totalVisitorsResult = $conn->query("SELECT COUNT(*) as total FROM visitor_logs");
$totalVisitors = $totalVisitorsResult->fetch_assoc()['total'];

$todayVisitorsResult = $conn->query("SELECT COUNT(DISTINCT ip_address) as today FROM visitor_logs WHERE DATE(visit_time) = CURDATE()");
$todayVisitors = $todayVisitorsResult->fetch_assoc()['today'];

// Message Stats
$totalMessagesResult = $conn->query("SELECT COUNT(*) as total FROM contact_messages");
$totalMessages = $totalMessagesResult->fetch_assoc()['total'];

// Project Stats
$totalProjectsResult = $conn->query("SELECT COUNT(*) as total FROM projects");
$totalProjects = $totalProjectsResult->fetch_assoc()['total'];

// Fetch recent visitors
$recentVisitorsStmt = $conn->prepare("SELECT * FROM visitor_logs ORDER BY visit_time DESC LIMIT 5");
$recentVisitorsStmt->execute();
$recentVisitorsResult = $recentVisitorsStmt->get_result();
$recentVisitors = [];
while ($row = $recentVisitorsResult->fetch_assoc()) {
    $recentVisitors[] = $row;
}

// =================================
// Fetch Chart Data (Last 7 Days)
// =================================
$chart_unique_visitors_data = [];
$chart_total_views_data = [];
$chart_labels = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $chart_labels[] = date('D, M j', strtotime($date)); // Format: "Mon, Jan 1"
    
    $stmt = $conn->prepare("SELECT COUNT(DISTINCT ip_address) as unique_visitors, COUNT(*) as total_views FROM visitor_logs WHERE DATE(visit_time) = ?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $chart_unique_visitors_data[] = $row['unique_visitors'] ?? 0;
    $chart_total_views_data[] = $row['total_views'] ?? 0;
}
$chart_labels_json = json_encode($chart_labels);
$chart_unique_visitors_json = json_encode($chart_unique_visitors_data);
$chart_total_views_json = json_encode($chart_total_views_data);

// Fetch all documents
 $stmt = $conn->prepare("SELECT * FROM documents ORDER BY upload_date DESC");
 $stmt->execute();
 $result = $stmt->get_result();

 $documents = [];
while ($row = $result->fetch_assoc()) {
    $documents[] = $row;
}

 $stmt->close();
 $recentVisitorsStmt->close();
?>
            <div class="content-header">
                <h1>Dashboard</h1>
                <a href="admin_logout.php" class="logout-btn">Logout</a>
            </div>
            
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <h4>Today's Visitors</h4>
                        <p><?php echo $todayVisitors; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-chart-bar"></i></div>
                    <div class="stat-info">
                        <h4>Total Visits</h4>
                        <p><?php echo $totalVisitors; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                    <div class="stat-info">
                        <h4>Total Messages</h4>
                        <p><?php echo $totalMessages; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-project-diagram"></i></div>
                    <div class="stat-info">
                        <h4>Total Projects</h4>
                        <p><?php echo $totalProjects; ?></p>
                    </div>
                </div>
            </div>

            <!-- Visitor Chart Card -->
            <div class="card">
                <div class="card-header">
                    <h3>Weekly Visitor Trend</h3>
                </div>
                <div class="card-body">
                    <canvas id="visitorChart" style="height: 300px;"></canvas>
                </div>
            </div>

            <!-- Recent Visitors Card -->
            <?php require_once 'admin_visitor_log_summary.php'; ?>
            
            <div class="card" id="upload">
                <div class="card-header">
                    <h3>Upload New Document</h3>
                </div>
                <div class="card-body">
                    <form action="upload_document.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="form-group">
                            <label for="title">Document Title</label>
                            <input type="text" id="title" name="title" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category" class="form-control" required>
                                <option value="">Select Category</option>
                                <option value="CV">CV</option>
                                <option value="Application Letter">Application Letter</option>
                                <option value="Education">Education</option>
                                <option value="Certificate">Certificate</option>
                                <option value="Experience">Experience</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="document">Select File</label>
                            <input type="file" id="document" name="document" class="form-control" required>
                        </div>
                        
                        <button type="submit" class="btn">Upload Document</button>
                    </form>
                </div>
            </div>
            
            <div class="card" id="documents">
                <div class="card-header">
                    <h3>Documents</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($documents)): ?>
                        <div class="empty-state">
                            <i class="fas fa-folder-open"></i>
                            <p>No documents uploaded yet.</p>
                        </div>
                    <?php else: ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Upload Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($documents as $document): ?>
                                    <tr>
                                        <td><?php echo $document['title']; ?></td>
                                        <td><?php echo $document['category']; ?></td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" class="status-toggle" 
                                                       data-id="<?php echo $document['id']; ?>" 
                                                       <?php echo $document['is_active'] ? 'checked' : ''; ?>>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($document['upload_date'])); ?></td>
                                        <td class="table-actions">
                                            <a href="<?php echo $document['file_path']; ?>" target="_blank" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo $document['file_path']; ?>" download title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <form action="delete_document.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                                <input type="hidden" name="id" value="<?php echo $document['id']; ?>">
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
                    <?php endif; ?>
                </div>
            </div>

<script>
    // Handle status toggle
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const documentId = this.dataset.id;
            const isActive = this.checked ? 1 : 0;
            const csrfToken = document.querySelector('input[name="csrf_token"]').value;

            fetch('toggle_document_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id: documentId,
                    is_active: isActive,
                    csrf_token: csrfToken
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Optionally show a success toast/message
                    console.log('Status updated successfully');
                } else {
                    // Revert the toggle and show an error
                    this.checked = !this.checked;
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                this.checked = !this.checked;
                alert('An unexpected error occurred. Please try again.');
                console.error('Error:', error);
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('visitorChart').getContext('2d');
        const visitorChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo $chart_labels_json; ?>,
                datasets: [
                    {
                        label: 'Unique Visitors',
                        data: <?php echo $chart_unique_visitors_json; ?>,
                        backgroundColor: 'rgba(255, 215, 0, 0.6)', // Primary color
                        borderColor: 'rgba(255, 215, 0, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Total Page Views',
                        data: <?php echo $chart_total_views_json; ?>,
                        backgroundColor: 'rgba(0, 0, 0, 0.6)', // Secondary color
                        borderColor: 'rgba(0, 0, 0, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            // This ensures the y-axis has integer steps for visitor counts
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    });
</script>

<?php
 $conn->close();
 require_once 'admin_footer.php';
?>