const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

if (registerBtn) {
    registerBtn.addEventListener('click', () => {
        container.classList.add("active");
    });
}

if (loginBtn) {
    loginBtn.addEventListener('click', () => {
        container.classList.remove("active");
    });
}

// Sign Up Logic
const signUpForm = document.querySelector('.sign-up form');
if (signUpForm) {
    signUpForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(signUpForm);

        // Submit registration data
        const result = await API.postForm('register', formData);

        if (result.status === 'success') {
            alert('Registration Successful! Please login.');
            container.classList.remove("active"); // Switch to login view
            signUpForm.reset();
        } else {
            alert('Error: ' + result.message);
        }
    });
}

// Sign In Logic
const signInForm = document.querySelector('.sign-in form');
if (signInForm) {
    signInForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(signInForm);

        const result = await API.postForm('login', formData);

        if (result.status === 'success') {
            // Save user to localStorage
            localStorage.setItem('user', JSON.stringify(result.user));

            // Redirect to dashboard
            // Redirect based on role
            const basePath = '/project-simulator-ShobKaaj/Management';
            if (result.user.role === 'client') {
                window.location.href = `${basePath}/Client/MVC/html/client-dashboard.php`;
            } else if (result.user.role === 'worker') {
                window.location.href = `${basePath}/Worker/MVC/html/worker-dashboard.php`;
            } else {
                window.location.href = `${basePath}/Admin/MVC/html/admin.php`;
            }
        } else {
            alert('Login Failed: ' + result.message);
        }
    });
}
