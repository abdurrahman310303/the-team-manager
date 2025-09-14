<?php
$title = 'Register - Team Manager';
ob_start();
?>

<style>
/* Register page specific styles */
.register-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
}

.register-card {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    padding: 40px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.register-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
    text-align: center;
    margin-bottom: 30px;
}

.register-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.register-input, .register-select {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #cccccc;
    border-radius: 4px;
    font-size: 14px;
    color: #1a1a1a;
    background: #ffffff;
    transition: border-color 0.2s;
}

.register-input:focus, .register-select:focus {
    outline: none;
    border-color: #1a1a1a;
    box-shadow: 0 0 0 3px rgba(26, 26, 26, 0.1);
}

.register-button {
    width: 100%;
    padding: 12px 16px;
    background: #1a1a1a;
    color: #ffffff;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s;
}

.register-button:hover {
    background: #2a2a2a;
}

.register-link {
    text-align: center;
    margin-top: 20px;
}

.register-link a {
    color: #1a1a1a;
    text-decoration: none;
    font-size: 14px;
}

.register-link a:hover {
    text-decoration: underline;
}

.error-message {
    background: #ffffff;
    color: #1a1a1a;
    border: 2px solid #1a1a1a;
    padding: 12px;
    border-radius: 4px;
    margin-bottom: 20px;
    font-size: 14px;
}
</style>

<div class="register-container">
    <div class="register-card">
        <h2 class="register-title">Create your account</h2>
        
        <?php $errorMessage = Session::getFlash('error'); ?>
        <?php if ($errorMessage): ?>
        <div class="error-message">
            ⚠ <?= htmlspecialchars($errorMessage) ?>
        </div>
        <?php endif; ?>
        
        <form class="register-form" method="POST" action="/register">
            <div>
                <input id="name" name="name" type="text" required 
                       class="register-input" 
                       placeholder="Full Name"
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            </div>
            
            <div>
                <input id="email" name="email" type="email" required 
                       class="register-input" 
                       placeholder="Email address"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            
            <div>
                <input id="password" name="password" type="password" required 
                       class="register-input" 
                       placeholder="Password">
            </div>
            
            <div>
                <input id="password_confirmation" name="password_confirmation" type="password" required 
                       class="register-input" 
                       placeholder="Confirm Password">
            </div>

            <button type="submit" class="register-button">
                Create Account
            </button>
            
            <div class="register-link">
                <a href="/login">Already have an account? Sign in here</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            background: #ffffff;
            color: #000000;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <?= $content ?>
</body>
</html>
