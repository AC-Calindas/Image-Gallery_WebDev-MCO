<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isAdmin() || !isset($_POST['admin_password']) || $_POST['admin_password'] !== ADMIN_PASSWORD) {
        header("Location: index.php?error=invalid_password");
        exit();
    }

    $file = $_FILES['image'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        die("Only JPG, PNG, and GIF files are allowed.");
    }

    if (!file_exists(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0777, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    $filepath = UPLOAD_DIR . $filename;

    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $stmt = $conn->prepare("INSERT INTO images (title, description, file_path) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $description, $filepath);
        
        if ($stmt->execute()) {
            header("Location: index.php?upload=success");
        } else {
            unlink($filepath); 
            die("Error saving to database.");
        }
    } else {
        die("Error uploading file.");
    }
} else {
    header("Location: index.php");
}
?>