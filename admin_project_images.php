<?php
session_start();
require_once 'db_connection.php';
require_once 'csrf_handler.php';

$projectId = $_GET['id'] ?? $_POST['project_id'] ?? null;

function uploadImageFile($file, $folder) {
    $upload_dir = "uploads/$folder/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($file_extension, $allowed_extensions) || $file['size'] > 5242880) { // 5MB limit
        return false;
    }
    
    $new_filename = time() . '_' . uniqid() . '.' . $file_extension;
    $target_path = $upload_dir . $new_filename;
    
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        return $target_path;
    }
    return false;
}

// Handle POST actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Re-check auth and CSRF inside POST block for security
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: admin_login.php');
        exit;
    }
    generate_csrf_token();
    if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
        $_SESSION['error'] = "CSRF token validation failed. Please try again.";
        header("Location: admin_project_images.php?id=$projectId");
        exit;
    }

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'upload_images':
                $descriptions = $_POST['descriptions'] ?? [];
                if (isset($_FILES['images'])) {
                    $images = $_FILES['images'];
                    $uploadedCount = 0;
                    for ($i = 0; $i < count($images['name']); $i++) {
                        if ($images['error'][$i] === UPLOAD_ERR_OK) {
                            $file = ['name' => $images['name'][$i], 'type' => $images['type'][$i], 'tmp_name' => $images['tmp_name'][$i], 'error' => $images['error'][$i], 'size' => $images['size'][$i]];
                            if ($image_path = uploadImageFile($file, 'projects')) {
                                $description = $descriptions[$i] ?? '';
                                $stmt = $conn->prepare("INSERT INTO project_images (project_id, image_path, image_description) VALUES (?, ?, ?)");
                                $stmt->bind_param("iss", $projectId, $image_path, $description);
                                if ($stmt->execute()) $uploadedCount++;
                            }
                        }
                    }
                    $_SESSION['success'] = "$uploadedCount image(s) uploaded successfully!";
                }
                break;

            case 'delete_image':
                $imageId = intval($_POST['id']);
                $stmt = $conn->prepare("SELECT image_path FROM project_images WHERE id = ? AND project_id = ?");
                $stmt->bind_param("ii", $imageId, $projectId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                    if (file_exists($row['image_path'])) unlink($row['image_path']);
                    $deleteStmt = $conn->prepare("DELETE FROM project_images WHERE id = ?");
                    $deleteStmt->bind_param("i", $imageId);
                    if ($deleteStmt->execute()) $_SESSION['success'] = "Image deleted successfully!";
                }
                break;

            case 'update_image_description':
                $imageId = intval($_POST['id']);
                $description = $_POST['description'];
                $stmt = $conn->prepare("UPDATE project_images SET image_description = ? WHERE id = ? AND project_id = ?");
                $stmt->bind_param("sii", $description, $imageId, $projectId);
                if ($stmt->execute()) {
                    echo json_encode(['success' => true]);
                    exit;
                }
                echo json_encode(['success' => false]);
                exit;

            case 'update_image_order':
                $orderData = json_decode($_POST['order'], true);
                if ($orderData) {
                    foreach ($orderData as $id => $order) {
                        $stmt = $conn->prepare("UPDATE project_images SET sort_order = ? WHERE id = ? AND project_id = ?");
                        $stmt->bind_param("iii", $order, $id, $projectId);
                        $stmt->execute();
                    }
                    echo json_encode(['success' => true]);
                    exit;
                }
                echo json_encode(['success' => false]);
                exit;
        }
    }
    header("Location: admin_project_images.php?id=$projectId");
    exit;
}

$page_title = 'Manage Project Images';
require_once 'admin_header.php'; // Handles session, db, csrf, auth

if (!$projectId) {
    $_SESSION['error'] = "No project specified.";
    header('Location: admin_projects.php');
    exit;
}

// Get project details
 $projectQuery = "SELECT * FROM projects WHERE id = ?";
 $projectStmt = $conn->prepare($projectQuery);
 $projectStmt->bind_param("i", $projectId);
 $projectStmt->execute();
 $projectResult = $projectStmt->get_result();

if ($projectResult->num_rows === 0) {
    $_SESSION['error'] = "Project not found.";
    header('Location: admin_projects.php');
    exit;
}

 $project = $projectResult->fetch_assoc();

// Get project images
 $imagesQuery = "SELECT * FROM project_images WHERE project_id = ? ORDER BY sort_order ASC";
 $imagesStmt = $conn->prepare($imagesQuery);
 $imagesStmt->bind_param("i", $projectId);
 $imagesStmt->execute();
 $imagesResult = $imagesStmt->get_result();
?>

<!-- Additional styles for this page -->
<style>
        .breadcrumb {
            margin-bottom: 1rem;
            color: var(--dark-gray);
        }
        .breadcrumb a {
            color: var(--primary-color);
        }
        .images-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .image-card {
            background-color: #fff;
            border: 1px solid #eee;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }
        .image-card.dragging {
            opacity: 0.5;
        }
        .image-preview {
            width: 100%;
            height: 200px;
            object-fit: cover;
            cursor: move;
        }

        .image-info { padding: 1rem; }
        .image-description { font-size: 0.9rem; color: var(--dark-gray); margin-bottom: 0.5rem; }
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }
        .file-input-label {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: var(--secondary-color);
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            transition: var(--transition);
        }
        .file-input-label:hover {
            background-color: var(--secondary-color);
            color: var(--primary-color);
        }

        .drag-handle {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: rgba(0, 0, 0, 0.5);
            color: #fff;
            padding: 5px;
            border-radius: 3px;
            cursor: move;
        }

        @media (max-width: 768px) {            
            .images-grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); }
        }

        /* Image Upload Preview Styles */
        .upload-preview-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        .upload-preview-card {
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 1rem;
        }
        .upload-preview-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
    </style>

            <div class="breadcrumb">
                <a href="admin_projects.php">Projects</a> / <?php echo $project['title']; ?>
            </div>
            
            <div class="content-header">
                <div>
                    <h1>Manage Images</h1>
                    <p>Project: <?php echo $project['title']; ?></p>
                </div>
                <a href="admin_projects.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Projects
                </a>
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
                    <h3>Upload New Images</h3>
                </div>
                <div class="card-body">
                    <form id="uploadForm" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="upload_images">
                        <input type="hidden" name="project_id" value="<?php echo $projectId; ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <div class="form-group">
                            <label>Select Images (Max 10)</label>
                            <div class="file-input-wrapper">
                                <input type="file" id="imageFiles" name="images[]" multiple accept="image/*">
                                <label for="imageFiles" class="file-input-label">
                                    <i class="fas fa-cloud-upload-alt"></i> Choose Images
                                </label>
                            </div>
                            <small id="fileCount">No files selected</small>
                        </div>
                        
                        <div id="uploadPreviewContainer" class="upload-preview-container"></div>
                        
                        <button type="submit" class="btn">Upload Images</button>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3>Project Images (<?php echo $imagesResult->num_rows; ?>)</h3>
                    <small>Drag and drop to reorder</small>
                </div>
                <div class="card-body">
                    <?php if ($imagesResult->num_rows > 0): ?>
                        <div class="images-grid" id="imagesGrid">
                            <?php while ($image = $imagesResult->fetch_assoc()): ?>
                                <div class="image-card" data-id="<?php echo $image['id']; ?>">
                                    <div class="drag-handle">
                                        <i class="fas fa-grip-vertical"></i>
                                    </div>
                                    <img src="<?php echo $image['image_path']; ?>" alt="<?php echo $image['image_description']; ?>" class="image-preview">
                                    <div class="image-info">
                                        <div class="image-description"><?php echo $image['image_description'] ?: 'No description'; ?></div>
                                        <div class="image-actions">
                                            <button onclick="editImageDescription(<?php echo $image['id']; ?>, '<?php echo addslashes($image['image_description']); ?>')" title="Edit Description">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="deleteImage(<?php echo $image['id']; ?>)" title="Delete" style="color: #dc3545;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-images"></i>
                            <p>No images uploaded yet. Upload your first image above.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

    <script>
        let selectedFiles = [];

        // Handle file selection
        document.getElementById('imageFiles').addEventListener('change', function(e) {
            selectedFiles = Array.from(e.target.files);
            updateFileCount();
            generateUploadPreviews();
        });

        function updateFileCount() {
            const count = selectedFiles.length;
            const fileCountEl = document.getElementById('fileCount');
            if (fileCountEl) {
                fileCountEl.textContent = `${count} file(s) selected`;
            }
        }

        function generateUploadPreviews() {
            const container = document.getElementById('uploadPreviewContainer');
            container.innerHTML = '';
            
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'upload-preview-card';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview of ${file.name}">
                        <div class="form-group">
                            <label for="desc_${index}" style="font-size: 0.8rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${file.name}">Description for ${file.name}</label>
                            <input type="text" id="desc_${index}" name="descriptions[]" class="form-control" placeholder="Enter image description">
                        </div>
                    `;
                    container.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        // Drag and drop functionality
        const grid = document.getElementById('imagesGrid');
        if (grid) {
            let draggedElement = null;

            grid.addEventListener('dragstart', function(e) {
                if (e.target.classList.contains('image-card')) {
                    draggedElement = e.target;
                    e.target.classList.add('dragging');
                }
            });

            grid.addEventListener('dragend', function(e) {
                if (e.target.classList.contains('image-card')) {
                    e.target.classList.remove('dragging');
                }
            });

            grid.addEventListener('dragover', function(e) {
                e.preventDefault();
                const afterElement = getDragAfterElement(grid, e.clientY);
                
                if (afterElement == null) {
                    grid.appendChild(draggedElement);
                } else {
                    grid.insertBefore(draggedElement, afterElement);
                }
            });

            grid.addEventListener('drop', function(e) {
                e.preventDefault();
                updateImageOrder();
            });
        }

        function getDragAfterElement(container, y) {
            const draggableElements = [...container.querySelectorAll('.image-card:not(.dragging)')];
            
            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                
                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                } else {
                    return closest;
                }
            }, { offset: Number.NEGATIVE_INFINITY }).element;
        }

        function updateImageOrder() {
            const cards = document.querySelectorAll('.image-card');
            const order = {};
            
            cards.forEach((card, index) => {
                order[card.dataset.id] = index;
            });
            
            fetch('admin_project_images.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=update_image_order&' + new URLSearchParams({
                    order: JSON.stringify(order),
                    csrf_token: '<?php echo $_SESSION['csrf_token']; ?>'
                })
            });
        }

        function editImageDescription(id, currentDescription) {
            const newDescription = prompt('Enter image description:', currentDescription);
            if (newDescription !== null) {
                // Update description via AJAX
                fetch('admin_project_images.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'update_image_description',
                        project_id: <?php echo $projectId; ?>,
                        id: id,
                        description: newDescription,
                        csrf_token: '<?php echo $_SESSION['csrf_token']; ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        }

        function deleteImage(id) {
            if (confirm('Are you sure you want to delete this image?')) {
                const form = document.createElement('form');
                form.method = 'post';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_image">
                    <input type="hidden" name="id" value="${id}">
                    <input type="hidden" name="project_id" value="${<?php echo $projectId; ?>}">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

<?php
 $conn->close();
 require_once 'admin_footer.php';
?>