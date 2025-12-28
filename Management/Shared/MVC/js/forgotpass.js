// State management
let generatedOTP = null;
let userEmail = null;

function generateOTP() {
    const emailInput = document.getElementById('email');
    if (!emailInput || !emailInput.value) {
        alert("Please enter your email address.");
        return;
    }
    userEmail = emailInput.value;

    // Generate random 4-digit number (1000–9999)
    generatedOTP = Math.floor(1000 + Math.random() * 9000);
    alert("Your OTP is: " + generatedOTP);

    // Switch to Step 2: Enter OTP
    renderOTPStep();
}

function renderOTPStep() {
    // Update Header
    document.getElementById('auth-title').textContent = "Verify OTP";
    document.getElementById('auth-subtitle').textContent = `We sent a code to ${userEmail}`;

    // Update Body
    const cardBody = document.getElementById('auth-card-body');
    cardBody.innerHTML = `
        <form id="otp-form" onsubmit="event.preventDefault(); verifyOTP();" class="auth-form">
            <div class="form-group">
                <label for="otp" class="label label-required">Enter OTP</label>
                <input type="number" class="input" id="otp" name="otp" placeholder="e.g. 1234" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn primary-action full-width">Verify OTP</button>
            </div>
            <div class="auth-footer">
                <p>Didn't receive code? <a href="#" onclick="generateOTP()">Resend</a></p>
            </div>
        </form>
    `;
}

function verifyOTP() {
    const otpInput = document.getElementById('otp');
    const enteredOTP = otpInput.value;

    if (parseInt(enteredOTP) === generatedOTP) {
        // Switch to Step 3: Reset Password
        renderResetPasswordStep();
    } else {
        alert("Invalid OTP. Please try again.");
    }
}

function renderResetPasswordStep() {
    // Update Header
    document.getElementById('auth-title').textContent = "Reset Password";
    document.getElementById('auth-subtitle').textContent = "Create a new secure password";

    // Update Body
    const cardBody = document.getElementById('auth-card-body');
    cardBody.innerHTML = `
        <form id="reset-form" onsubmit="event.preventDefault(); resetPassword();" class="auth-form">
            <div class="form-group">
                <label for="new-password" class="label label-required">New Password</label>
                <input type="password" class="input" id="new-password" name="new-password" placeholder="Min. 8 characters" required>
            </div>
            <div class="form-group">
                <label for="confirm-password" class="label label-required">Confirm Password</label>
                <input type="password" class="input" id="confirm-password" name="confirm-password" placeholder="Confirm new password" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn primary-action full-width">Reset Password</button>
            </div>
        </form>
    `;
}

async function resetPassword() {
    const newPass = document.getElementById('new-password').value;
    const confirmPass = document.getElementById('confirm-password').value;

    if (newPass.length < 8) {
        alert("Password must be at least 8 characters long.");
        return;
    }

    if (newPass !== confirmPass) {
        alert("Passwords do not match!");
        return;
    }

    const data = await API.post('reset_password', {
        email: userEmail,
        new_password: newPass
    });

    if (data.status === 'success') {
        alert("Password Reset Successful! Redirecting to login...");
        window.location.href = 'auth.php';
    } else {
        alert("Error: " + data.message);
    }
}