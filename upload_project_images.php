<?php
session_start();
require_once 'db_connection.php';

// ✅ Check if user is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// ✅ Handle image upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'upload_images') {
    $project_id = intval($_POST['project_id']);
    $descriptions = $_POST['descriptions'] ?? [];

    // ✅ Ensure folder exists
    $upload_dir = 'uploads/projects/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // ✅ Check if images exist
    if (isset($_FILES['images'])) {
        $images = $_FILES['images'];
        $image_count = count($images['name']);

        if ($image_count > 10) {
            $_SESSION['error'] = "Maximum 10 images allowed per project.";
            header('Location: admin_projects.php?error=' . urlencode($_SESSION['error']));
            exit;
        }

        $uploaded_count = 0;

        // ✅ Loop through files
        for ($i = 0; $i < $image_count; $i++) {
            $file = $images['name'][$i];
            $file_tmp = $images['tmp_name'][$i];
            $file_size = $images['size'][$i];
            $file_type = $images['type'][$i];
            $file_error = $images['error'][$i];

            if ($file_error !== UPLOAD_ERR_OK) continue;

            $file_extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            // ✅ Validate file
            if (!in_array($file_extension, $allowed_extensions)) {
                continue;
            }

            if ($file_size > 5242880) { // 5MB
                continue;
            }

            // ✅ Unique filename
            $new_filename = time() . '_' . uniqid() . '.' . $file_extension;
            $file_path = $upload_dir . $new_filename;

            // ✅ Move file
            if (move_uploaded_file($file_tmp, $file_path)) {
                $desc = $descriptions[$i] ?? '';
                $sort_order = $i + 1;

                // ✅ Insert into DB
                $stmt = $conn->prepare("INSERT INTO project_images (project_id, image_path, image_description, sort_order) VALUES (?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("issi", $project_id, $file_path, $desc, $sort_order);
                    if ($stmt->execute()) {
                        $uploaded_count++;
                    }
                    $stmt->close();
                }
            }
        }

        if ($uploaded_count > 0) {
            $_SESSION['success'] = "$uploaded_count image(s) uploaded successfully!";
            header('Location: admin_projects.php?success=' . urlencode($_SESSION['success']));
            exit;
        } else {
            $_SESSION['error'] = "No images were uploaded. Please check file types or sizes.";
            header('Location: admin_projects.php?error=' . urlencode($_SESSION['error']));
            exit;
        }
    } else {
        $_SESSION['error'] = "No images selected.";
        header('Location: admin_projects.php?error=' . urlencode($_SESSION['error']));
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header('Location: admin_projects.php?error=' . urlencode($_SESSION['error']));
    exit;
}

$conn->close();
?>
