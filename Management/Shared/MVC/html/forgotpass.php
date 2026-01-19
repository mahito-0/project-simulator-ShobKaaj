<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - ShobKaaj</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/forgotpass.css">
    <script src="../js/navbar.js"></script>
    <script src="../js/utils.js"></script>
    <script src="../js/forgotpass.js"></script>
</head>

<body>
    <!-- Navbar will be injected by navbar.js -->

    <main class="main-content">
        <div class="container">
            <div class="auth-card-wrapper">
                <div class="card auth-card">
                    <div class="card-header">
                        <h2 class="auth-title" id="auth-title">Forgot Password</h2>
                        <p class="auth-subtitle" id="auth-subtitle">Enter your email to reset your password</p>
                    </div>
                    <div class="card-body" id="auth-card-body">
                        <form id="email-form" onsubmit="event.preventDefault(); generateOTP();" class="auth-form">
                            <div class="form-group">
                                <label for="email" class="label label-required">Email Address</label>
                                <input type="email" class="input" id="email" name="email" placeholder="Enter your email" required>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn primary-action full-width">Send Reset Link</button>
                            </div>
                            <div class="auth-footer">
                                <p>Remember your password? <a href="auth.php">Login here</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>