<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Oblivion Gallery</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .hero {
            background: linear-gradient(135deg, #6a0dad, #4b0082);
            color: white;
            text-align: center;
            padding: 100px 20px;
            min-height: 60vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            animation: fadeInDown 1s ease;
        }
        
        .hero p {
            font-size: 1.5rem;
            max-width: 800px;
            margin-bottom: 40px;
            animation: fadeIn 1.5s ease;
        }
        
        .enter-btn {
            background: white;
            color: #6a0dad;
            border: none;
            padding: 15px 40px;
            font-size: 1.2rem;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            animation: fadeInUp 1s ease;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            position: relative;
            z-index: 10;
            opacity: 1 !important;
            transform: translateY(0) !important;
}

.enter-btn:hover {
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 15px 30px rgba(0,0,0,0.3);
    background: #f5f5f5;
}

.enter-btn i {
    margin-left: 10px;
    transition: transform 0.3s ease;
}

.enter-btn:hover i {
    transform: translateX(5px);
}
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .features {
            padding: 80px 20px;
            background: #faf5ff;
            text-align: center;
        }
        
        .features h2 {
            color: #6a0dad;
            margin-bottom: 50px;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(106, 13, 173, 0.1);
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: #6a0dad;
            margin-bottom: 20px;
        }
        
        footer {
            background: #2c003e;
            color: white;
            text-align: center;
            padding: 30px 20px;
        }

        .fixed-nav {
            display: flex;
            justify-content: space-between;
            max-width: 250px;
            margin: 0 auto;
        }
        
        .admin-btn {
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
        
        .admin-btn:hover {
            background: linear-gradient(135deg, #8e44ad, #6a0dad);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(106, 13, 173, 0.35);
        }
        
        .admin-btn:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <div class="hero">
        <h1>Welcome to Oblivion Gallery</h1>
        <p>Discover a world of stunning visual art and photography in our exclusive collection</p>
        <a href="index.php" class="enter-btn">
            Oblivion Gallery <i class="fas fa-arrow-right"></i>
        </a>
    </div>
    
    <section class="features">
        <h2>Gallery Features</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-images"></i>
                </div>
                <h3>Beautiful Collections</h3>
                <p>Explore curated collections of stunning photographs and artworks from talented creators.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-expand"></i>
                </div>
                <h3>Fullscreen View</h3>
                <p>View images in full resolution with our immersive fullscreen experience.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h3>Admin Features</h3>
                <p>Authorized users can upload and manage gallery content with our admin tools.</p>
            </div>
        </div>
        <div class="fixed-nav">
    <?php if (isAdmin()): ?>
        <a href="logout.php" class="btn admin-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    <?php else: ?>
        <a href="login.php" class="btn admin-btn">
            <i class="fas fa-user-shield"></i> Admin  
        </a>
    <?php endif; ?>
</div>
    </section>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Oblivion Gallery. All rights reserved.</p>
    </footer>
</body>
</html>
