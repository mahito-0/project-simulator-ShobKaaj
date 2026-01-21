<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication - ShobKaaj</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/base.css">

    <link rel="stylesheet" href="../css/auth.css">
    <script src="../js/navbar.js"></script>
    <script src="../js/utils.js"></script>
</head>

<body>
    <?php

    $first_name = $first_name ?? '';
    $last_name = $last_name ?? '';
    $email = $email ?? '';
    $phone = $phone ?? '';
    $user_type = $user_type ?? '';

    $first_name_err = $first_name_err ?? '';
    $last_name_err = $last_name_err ?? '';
    $email_err = $email_err ?? '';
    $phone_err = $phone_err ?? '';
    $password_err = $password_err ?? '';
    $confirm_password_err = $confirm_password_err ?? '';
    $user_type_err = $user_type_err ?? '';
    ?>

    <main class="auth-wrapper">
        <div class="auth-container" id="container">
            <!-- Sign Up Form -->
            <div class="form-container sign-up">
                <form action="auth.php" method="POST">
                    <h1>Create Account</h1>
                    <div class="social-icons">
                        <a href="#" class="icon"><i class="fa-brands fa-google"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                        <br>
                        <span>or use your email for registration</span>
                    </div>

                    <div class="input-group">
                        <input type="text" name="first_name" class="input" placeholder="First Name <?php echo $first_name_err ? '*' : ''; ?>" value="<?php echo $first_name; ?>">
                        <input type="text" name="last_name" class="input" placeholder="Last Name <?php echo $last_name_err ? '*' : ''; ?>" value="<?php echo $last_name; ?>">
                    </div>

                    <input type="email" name="email" class="input" placeholder="Email <?php echo (isset($_POST['first_name']) && $email_err) ? '*' : ''; ?>" value="<?php echo (isset($_POST['first_name'])) ? $email : ''; ?>">
                    <input type="tel" name="phone" class="input" placeholder="Phone Number <?php echo $phone_err ? '*' : ''; ?>" value="<?php echo $phone; ?>">

                    <div class="input-group">
                        <input type="password" name="password" class="input" placeholder="Password <?php echo (isset($_POST['first_name']) && $password_err) ? '*' : ''; ?>">
                        <input type="password" name="confirm_password" class="input" placeholder="Confirm Password <?php echo $confirm_password_err ? '*' : ''; ?>">
                    </div>

                    <select name="user_type" class="input">
                        <option value="" disabled <?php echo empty($user_type) ? "selected" : ""; ?>>Select Role <?php echo $user_type_err ? '*' : ''; ?></option>
                        <option value="worker" <?php echo ($user_type == "worker") ? "selected" : ""; ?>>Worker</option>
                        <option value="client" <?php echo ($user_type == "client") ? "selected" : ""; ?>>Client</option>
                    </select>

                    <button type="submit" class="btn primary-action">Sign Up</button>
                </form>
            </div>

            <!--Sign In Form-->
            <div class="form-container sign-in">
                <form action="auth.php" method="POST">
                    <h1>Sign In</h1>
                    <div class="social-icons">
                        <a href="#" class="icon"><i class="fa-brands fa-google"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                        <br>
                        <span>or use your email password</span>
                    </div>

                    <input type="email" name="email" id="login-email" class="input" placeholder="Email <?php echo (!isset($_POST['first_name']) && $email_err) ? '*' : ''; ?>" value="<?php echo (!isset($_POST['first_name'])) ? $email : ''; ?>">
                    <input type="password" name="password" class="input" placeholder="Password <?php echo (!isset($_POST['first_name']) && $password_err) ? '*' : ''; ?>">
                    <div style="display: flex; align-items: center; gap: 8px; margin: 10px 0;">
                        <input type="checkbox" name="remember_me" id="remember-me" style="width: auto;">
                        <label for="remember-me" style="margin: 0; font-size: 14px;">Remember Me</label>
                    </div>
                    <a href="forgotpass.php">Forget Your Password?</a>
                    <button type="submit" class="btn primary-action">Sign In</button>
                </form>
            </div>

            <!-- Toggle Overlay -->
            <div class="toggle-container">
                <div class="toggle">
                    <div class="toggle-panel toggle-left">
                        <h1>Already a Member?</h1>
                        <p>Log in to access your dashboard, manage your projects, and connect with professionals.</p>
                        <button class="btn btn-transparent" id="login">Sign In</button>
                    </div>
                    <div class="toggle-panel toggle-right">
                        <h1>New Here?</h1>
                        <p>Join ShobKaaj today to discover new opportunities, hire skilled talent, and grow your career.</p>
                        <button class="btn btn-transparent" id="register">Sign Up</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="../js/auth.js"></script>
</body>

</html>