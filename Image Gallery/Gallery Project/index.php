<?php
require_once 'config.php';

if (isset($_GET['delete']) && isset($_GET['id'])) {
    if (isAdmin()) {
        $id = intval($_GET['id']);
        
        $stmt = $conn->prepare("SELECT file_path FROM images WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $filepath = $row['file_path'];
            
            $stmt = $conn->prepare("DELETE FROM images WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            
            header("Location: index.php?deleted=success");
        }
    }
}

$images = $conn->query("SELECT * FROM images ORDER BY upload_date DESC");
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oblivion Image Gallery</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        

        .nav-container {
            background: linear-gradient(130deg, var(--primary-purple), var(--dark-purple));
            padding: 1rem;
        }
        
        .main-nav {
            display: flex;
            justify-content: space-between;
            max-width: 250px;
            margin: 0 auto;
        }
        
        .nav-btn {
            width: 100%;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #6a0dad, #8e44ad);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
            box-shadow: 0 4px 12px rgba(106, 13, 173, 0.25);
            position: relative;
            overflow: hidden;
        }
        
        .nav-btn:hover {
            background: linear-gradient(135deg, #8e44ad, #6a0dad);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(106, 13, 173, 0.35);
        }
        
        .nav-btn:active {
            transform: translateY(0);
        }
        
        

        h1 {
            text-align: center;
            margin: 2rem 0;
            color: white;
        }
    </style>
</head>
<body>







<header class="header">
        <h1><i class="fas fa-camera-retro"></i> Oblivion Image Gallery</h1>


        
    <div class="nav-container">
        <div class="main-nav">
            <a href="home.php" class="nav-btn home-btn">
                <i class="fas fa-home"></i> Home
            </a>
            <?php if (!isAdmin()): ?>
                <a href="login.php" class="nav-btn admin-btn-nav">
                    <i class="fas fa-user-shield"></i> Admin
                </a>
            <?php endif; ?>
        </div>
    </div>


</header>
    




    <div class="container">
        <?php if (isAdmin()): ?>
        <div class="upload-form">
            <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_password'): ?>
            <div class="error-message" id="password-error">
                <span class="error-icon"><i class="fas fa-exclamation-circle"></i></span>
                Invalid admin password. Please try again.
            </div>
            <?php endif; ?>
            <h2>Upload New Image</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data" id="upload-form">
                <div class="form-group">
                    <label for="title"><i class="fas fa-heading"></i> Title:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="description"><i class="fas fa-align-left"></i> Description:</label>
                    <textarea id="description" name="description"></textarea>
                </div>
                <div class="form-group">
                    <label for="image"><i class="fas fa-image"></i> Image:</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label for="admin_password"><i class="fas fa-lock"></i> Admin Password:</label>
                    <input type="password" id="admin_password" name="admin_password" required>
                </div>
                <button type="submit" class="upload-btn">
                    <i class="fas fa-upload"></i> Upload Image
                </button>
            </form>
        </div>
        <?php endif; ?>
        
        <div class="gallery-grid">
            <?php if ($images->num_rows > 0): ?>
                <?php while ($image = $images->fetch_assoc()): ?>
                    <div class="gallery-item <?php echo isAdmin() ? 'admin-mode' : ''; ?>">
                        <img src="<?php echo htmlspecialchars($image['file_path']); ?>" 
                             alt="<?php echo htmlspecialchars($image['title']); ?>"
                             data-title="<?php echo htmlspecialchars($image['title']); ?>"
                             data-description="<?php echo htmlspecialchars($image['description']); ?>"
                             data-date="<?php echo date('M j, Y', strtotime($image['upload_date'])); ?>">
                        <div class="image-info">
                            <h3><?php echo htmlspecialchars($image['title']); ?></h3>
                            <p><?php echo htmlspecialchars($image['description']); ?></p>
                            <div class="image-actions">
                                <small><?php echo date('M j, Y', strtotime($image['upload_date'])); ?></small>
                                <?php if (isAdmin()): ?>
                                <a href="index.php?delete=1&id=<?php echo $image['id']; ?>" 
                                   class="delete-btn" 
                                   onclick="return confirm('Are you sure you want to delete this image?'); event.stopPropagation();">
                                   <i class="fas fa-trash-alt"></i> Delete
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-images">No images found. <?php if (isAdmin()) echo 'Upload some images to get started!'; ?></p>
            <?php endif; ?>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>