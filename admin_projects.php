<?php
session_start();
require_once 'db_connection.php';
require_once 'csrf_handler.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}
generate_csrf_token();

// Handle project operations
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
        $_SESSION['error'] = "CSRF token validation failed. Please try again.";
        header('Location: admin_projects.php');
        exit;
    }
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_project':
                addProject($conn);
                break;
            case 'update_project':
                updateProject($conn);
                break;
            case 'delete_project':
                deleteProject($conn);
                break;
        }
    }
}

// Search logic
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_sql = '';
$search_params = [];
$search_types = '';

if (!empty($search_term)) {
    $search_sql = " WHERE (p.title LIKE ? OR p.description LIKE ? OR p.technologies LIKE ? OR p.role LIKE ?)";
    $search_like = "%" . $search_term . "%";
    $search_params = [$search_like, $search_like, $search_like, $search_like];
    $search_types = 'ssss';
}

// Pagination logic
$limit = 10; // Projects per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total number of projects
$totalQuery = "SELECT COUNT(*) as total FROM projects p" . $search_sql;
$totalStmt = $conn->prepare($totalQuery);
if (!empty($search_term)) {
    $totalStmt->bind_param($search_types, ...$search_params);
}
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalProjects = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalProjects / $limit);

// Fetch projects for the current page
 $projectsQuery = "
    SELECT p.*, 
           (SELECT COUNT(*) FROM project_images WHERE project_id = p.id) as image_count
    FROM projects p" . $search_sql . "
    ORDER BY p.is_featured DESC, p.created_at DESC
    LIMIT ? OFFSET ?
";
 $stmt = $conn->prepare($projectsQuery);
if (!empty($search_term)) {
    $all_params = array_merge($search_params, [$limit, $offset]);
    $stmt->bind_param($search_types . "ii", ...$all_params);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}
 $stmt->execute();
 $projectsResult = $stmt->get_result();

$page_title = 'Manage Projects';
require_once 'admin_header.php'; // Now, we can safely include the header

function addProject($conn) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $technologies = $_POST['technologies'];
    $role = $_POST['role'];
    $project_url = $_POST['project_url'] ?? '';
    $github_url = $_POST['github_url'] ?? '';
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    // Handle featured image upload
    $featured_image = '';
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $featured_image = uploadImage($_FILES['featured_image'], 'projects');
    }
    
    $stmt = $conn->prepare("INSERT INTO projects (title, description, technologies, role, project_url, github_url, featured_image, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssi", $title, $description, $technologies, $role, $project_url, $github_url, $featured_image, $is_featured);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Project added successfully!";
    } else {
        $_SESSION['error'] = "Error adding project: " . $stmt->error;
    }
    
    header('Location: admin_projects.php');
    exit;
}

function updateProject($conn) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $technologies = $_POST['technologies'];
    $role = $_POST['role'];
    $project_url = $_POST['project_url'] ?? '';
    $github_url = $_POST['github_url'] ?? '';
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    // Handle featured image upload
    $params = [$title, $description, $technologies, $role, $project_url, $github_url, $is_featured];
    $types = "ssssssi";
    $sql = "UPDATE projects SET title = ?, description = ?, technologies = ?, role = ?, project_url = ?, github_url = ?, is_featured = ?";

    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $featured_image = uploadImage($_FILES['featured_image'], 'projects');
        $sql .= ", featured_image = ?";
        $params[] = $featured_image;
        $types .= "s";
    }
    
    $sql .= " WHERE id = ?";
    $params[] = $id;
    $types .= "i";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Project updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating project: " . $stmt->error;
    }
    
    header('Location: admin_projects.php');
    exit;
}

function deleteProject($conn) {
    $id = $_POST['id'];
    
    // Get project images to delete files
    $imageQuery = "SELECT image_path FROM project_images WHERE project_id = ?";
    $imageStmt = $conn->prepare($imageQuery);
    $imageStmt->bind_param("i", $id);
    $imageStmt->execute();
    $imageResult = $imageStmt->get_result();
    
    while ($imageRow = $imageResult->fetch_assoc()) {
        if (file_exists($imageRow['image_path'])) {
            unlink($imageRow['image_path']);
        }
    }
    
    // Get featured image
    $projectQuery = "SELECT featured_image FROM projects WHERE id = ?";
    $projectStmt = $conn->prepare($projectQuery);
    $projectStmt->bind_param("i", $id);
    $projectStmt->execute();
    $projectResult = $projectStmt->get_result();
    
    if ($projectRow = $projectResult->fetch_assoc()) {
        if ($projectRow['featured_image'] && file_exists($projectRow['featured_image'])) {
            unlink($projectRow['featured_image']);
        }
    }
    
    // Delete project (cascade will delete images)
    $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Project deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting project: " . $stmt->error;
    }
    
    header('Location: admin_projects.php');
    exit;
}

function uploadImage($file, $folder) {
    $upload_dir = "uploads/$folder/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($file_extension, $allowed_extensions)) {
        throw new Exception("Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.");
    }
    
    $new_filename = time() . '_' . uniqid() . '.' . $file_extension;
    $target_path = $upload_dir . $new_filename;
    
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        return $target_path;
    } else {
        throw new Exception("Error uploading file.");
    }
}

?>

<!-- Modal styles -->
<style>
    .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; }
    .modal-content { background-color: #fff; padding: 2rem; border-radius: 10px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .modal-header h3 { font-size: 1.5rem; }
    .close-modal { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--dark-gray); }
    .project-image-preview { width: 100px; height: 100px; object-fit: cover; border-radius: 5px; }
    .image-count { background-color: var(--primary-color); color: var(--secondary-color); padding: 2px 8px; border-radius: 12px; font-size: 0.8rem; font-weight: 600; }
</style>
<style>
    .form-group-checkbox { display: flex; align-items: center; gap: 10px; margin-bottom: 1.5rem; }
    .form-group-checkbox label { margin-bottom: 0; }
    .form-group-checkbox input { width: auto; }
    .search-form {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        align-items: center;
    }
</style>

            <div class="content-header">
                <h1>Manage Projects</h1>
                <button class="btn" onclick="openAddProjectModal()">
                    <i class="fas fa-plus"></i> Add New Project
                </button>
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
                    <h3>All Projects</h3>
                </div>
                <div class="card-body" style="padding-bottom: 0;">
                    <form action="admin_projects.php" method="GET" class="search-form">
                        <div style="flex-grow: 1;">
                            <input type="text" name="search" class="form-control" placeholder="Search by title, tech, role..." value="<?php echo htmlspecialchars($search_term); ?>">
                        </div>
                        <button type="submit" class="btn">Search</button>
                        <?php if (!empty($search_term)): ?>
                            <a href="admin_projects.php" class="btn btn-secondary">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Featured Image</th>
                                <th>Title</th>
                                <th>Technologies</th>
                                <th>Role</th>
                                <th>Featured</th>
                                <th>Images</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($project = $projectsResult->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <?php if ($project['featured_image']): ?>
                                            <img src="<?php echo $project['featured_image']; ?>" alt="<?php echo $project['title']; ?>" class="project-image-preview">
                                        <?php else: ?>
                                            <div class="project-image-preview" style="background-color: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-image" style="color: #ccc;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $project['title']; ?></td>
                                    <td><?php echo $project['technologies']; ?></td>
                                    <td><?php echo $project['role']; ?></td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="featured-toggle" 
                                                   data-id="<?php echo $project['id']; ?>"
                                                   <?php echo !empty($project['is_featured']) ? 'checked' : ''; ?>>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <span class="image-count"><?php echo $project['image_count']; ?> images</span>
                                    </td>
                                    <td class="table-actions">
                                        <a href="admin_project_images.php?id=<?php echo $project['id']; ?>" title="Manage Images">
                                            <i class="fas fa-images"></i>
                                        </a>
                                        <button onclick="editProject(<?php echo $project['id']; ?>)" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteProject(<?php echo $project['id']; ?>, '<?php echo addslashes($project['title']); ?>')" title="Delete" class="text-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search_term); ?>">&laquo; Previous</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search_term); ?>" class="<?php echo $i == $page ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search_term); ?>">Next &raquo;</a>
                    <?php endif; ?>
                </div>
            </div>
        
    <!-- Add/Edit Project Modal -->
    <div class="modal" id="projectModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Add New Project</h3>
                <button class="close-modal" onclick="closeProjectModal()">&times;</button>
            </div>
            <form id="projectForm" method="post" enctype="multipart/form-data">
                <input type="hidden" id="projectId" name="id">
                <input type="hidden" id="formAction" name="action" value="add_project">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <div class="form-group">
                    <label for="title">Project Title</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="technologies">Technologies</label>
                    <input type="text" id="technologies" name="technologies" class="form-control" placeholder="e.g., PHP, MySQL, Bootstrap" required>
                </div>
                
                <div class="form-group">
                    <label for="role">Your Role</label>
                    <input type="text" id="role" name="role" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="project_url">Project URL (Optional)</label>
                    <input type="url" id="project_url" name="project_url" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="github_url">GitHub URL (Optional)</label>
                    <input type="url" id="github_url" name="github_url" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="featured_image">Featured Image</label>
                    <input type="file" id="featured_image" name="featured_image" class="form-control" accept="image/*">
                </div>
                
                <div class="form-group-checkbox">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1">
                    <label for="is_featured">Feature this project?</label>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn">Save Project</button>
                    <button type="button" class="btn btn-secondary" onclick="closeProjectModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddProjectModal() {
            document.getElementById('modalTitle').textContent = 'Add New Project';
            document.getElementById('formAction').value = 'add_project';
            document.getElementById('projectForm').reset();
            document.getElementById('projectId').value = '';
            document.getElementById('projectModal').style.display = 'flex';
        }

        function closeProjectModal() {
            document.getElementById('projectModal').style.display = 'none';
        }

        function editProject(id) {
            // Fetch project data and populate form
            fetch(`get_project_details.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const project = data.project;
                        document.getElementById('modalTitle').textContent = 'Edit Project';
                        document.getElementById('formAction').value = 'update_project';
                        document.getElementById('projectId').value = project.id;
                        document.getElementById('title').value = project.title;
                        document.getElementById('description').value = project.description;
                        document.getElementById('technologies').value = project.technologies;
                        document.getElementById('role').value = project.role;
                        document.getElementById('project_url').value = project.project_url || '';
                        document.getElementById('github_url').value = project.github_url || '';
                        document.getElementById('is_featured').checked = project.is_featured == 1;
                        document.getElementById('projectModal').style.display = 'flex';
                    }
                });
        }

        function deleteProject(id, title) {
            const confirmationMessage = `Are you sure you want to delete the project "${title}"?\n\nThis action cannot be undone.`;
            if (confirm(confirmationMessage)) {
                const form = document.createElement('form');
                form.method = 'post';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_project">
                    <input type="hidden" name="id" value="${id}">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('projectModal');
            if (event.target === modal) {
                closeProjectModal();
            }
        }

        // Handle featured toggle
        document.querySelectorAll('.featured-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const projectId = this.dataset.id;
                const isFeatured = this.checked ? 1 : 0;
                const csrfToken = '<?php echo $_SESSION['csrf_token']; ?>';

                fetch('toggle_project_featured.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id: projectId,
                        is_featured: isFeatured,
                        csrf_token: csrfToken
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
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

<?php
 $conn->close();
 require_once 'admin_footer.php';
?>