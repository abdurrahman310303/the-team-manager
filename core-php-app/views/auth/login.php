<?php
$title = 'Login - Team Manager';
ob_start();
?>

<style>
/* Login page specific styles */
.login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
}

.login-card {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    padding: 40px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.login-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
    text-align: center;
    margin-bottom: 30px;
}

.login-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.login-input {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #cccccc;
    border-radius: 4px;
    font-size: 14px;
    color: #1a1a1a;
    background: #ffffff;
    transition: border-color 0.2s;
}

.login-input:focus {
    outline: none;
    border-color: #1a1a1a;
    box-shadow: 0 0 0 3px rgba(26, 26, 26, 0.1);
}

.login-button {
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

.login-button:hover {
    background: #2a2a2a;
}

.login-link {
    text-align: center;
    margin-top: 20px;
}

.login-link a {
    color: #1a1a1a;
    text-decoration: none;
    font-size: 14px;
}

.login-link a:hover {
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

<div class="login-container">
    <div class="login-card">
        <h2 class="login-title">Sign in to your account</h2>
        
        <?php $errorMessage = Session::getFlash('error'); ?>
        <?php if ($errorMessage): ?>
        <div class="error-message">
            ⚠ <?= htmlspecialchars($errorMessage) ?>
        </div>
        <?php endif; ?>
        
        <form class="login-form" method="POST" action="/login">
            <div>
                <input id="email" name="email" type="email" required 
                       class="login-input" 
                       placeholder="Email address"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            
            <div>
                <input id="password" name="password" type="password" required 
                       class="login-input" 
                       placeholder="Password">
            </div>

            <button type="submit" class="login-button">
                Sign in
            </button>
            
            <div class="login-link">
                <a href="/register">Don't have an account? Register here</a>
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
