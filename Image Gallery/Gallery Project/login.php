<?php
require_once 'config.php';

if (isAdmin()) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['password'] === ADMIN_PASSWORD) {
        $_SESSION['admin'] = true;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Oblivion Gallery</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f0ff, #e9e4ff);
            padding: 2rem;
        }
        

        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(106, 13, 173, 0.15);
            width: 100%;
            max-width: 480px;
            padding: 3rem;
            text-align: center;
            animation: fadeInUp 0.6s ease-out;
            border: 1px solid rgba(106, 13, 173, 0.1);
        }
        

        .login-header {
            margin-bottom: 2.5rem;
        }
        
        .login-header i {
            font-size: 3rem;
            color: #6a0dad;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #6a0dad, #8e44ad);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .login-header h1 {
            color: #6a0dad;
            font-size: 2rem;
            margin: 0.5rem 0;
            font-weight: 700;
        }
        
        .login-header p {
            color: #7f8c8d;
            font-size: 0.95rem;
        }
        

        .login-form .form-group {
            margin-bottom: 1.8rem;
            text-align: left;
        }
        
        .login-form label {
            display: block;
            margin-bottom: 0.8rem;
            color: #6a0dad;
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        .login-form input[type="password"] {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid #e6e6fa;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #faf9ff;
        }
        
        .login-form input[type="password"]:focus {
            border-color: #8e44ad;
            outline: none;
            box-shadow: 0 0 0 4px rgba(138, 43, 226, 0.15);
            background-color: white;
        }
        

        .login-btn {
            width: 100%;
            padding: 1rem;
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
        
        .login-btn:hover {
            background: linear-gradient(135deg, #8e44ad, #6a0dad);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(106, 13, 173, 0.35);
        }
        
        .login-btn:active {
            transform: translateY(0);
        }
        

        .back-link {
            margin-top: 2rem;
            text-align: center;
        }
        
        .back-link a {
            color: #6a0dad;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            font-weight: 500;
        }
        
        .back-link a:hover {
            color: #4b0082;
        }
        
        .back-link i {
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .back-link a:hover i {
            transform: translateX(-4px);
        }
        

        .error-message {
            background: rgba(255, 107, 107, 0.1);
            color: #ff6b6b;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 4px solid #ff6b6b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            animation: fadeInDown 0.5s ease, shake 0.5s ease 0.5s;
        }
        

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        

        @media (max-width: 600px) {
            .login-card {
                padding: 2rem 1.5rem;
            }
            
            .login-header h1 {
                font-size: 1.7rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-user-shield"></i>
                <h1>Admin</h1>
                <p>Allows you to modify the gallery</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>
            
            <form method="post" class="login-form">
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>
                
                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>
            
            <div class="back-link">
                <a href="home.php"><i class="fas fa-arrow-left"></i> Return to Homepage</a>
            </div>
        </div>
    </div>
</body>
</html>