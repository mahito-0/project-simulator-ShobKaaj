document.addEventListener('DOMContentLoaded', () => {
    // 1. Check Authentication & Authorization
    const storedUser = localStorage.getItem('user');
    const user = storedUser ? JSON.parse(storedUser) : null;

    if (!user) {
        window.location.href = 'auth.php';
        return;
    }

    // Only clients can post jobs
    if (user.role !== 'client') {
        alert('Access Denied: Only Clients can post jobs.');
        window.location.href = 'dashboard.php';
        return;
    }

    // 2. Handle Form Submission
    const jobForm = document.getElementById('jobForm');

    // Dedicated Job API Helper
    const JobAPI = {
        create: async (data) => {
            try {
                const basePath = '/project-simulator-ShobKaaj/Management/Shared/MVC/php/';
                // AJAX: Create a new job
                const response = await fetch(`${basePath}jobAPI.php?action=create`, {
                    method: 'POST',
                    body: data
                });

                const text = await response.text();
                let result;
                try {
                    result = JSON.parse(text);
                } catch (e) {
                    console.error("Server Error (Not JSON):", text);
                    return { status: 'error', message: 'Server returned invalid response. Check console.' };
                }
                return result;
            } catch (error) {
                return { status: 'error', message: error.message };
            }
        }
    };

    if (jobForm) {
        jobForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const submitBtn = jobForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            // Set Loading State
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Posting...';

            // Gather Data
            const formData = new FormData(jobForm);
            formData.append('client_id', user.id); // Add current user ID

            try {
                // Use the correct API endpoint, not the default authAPI one
                const result = await JobAPI.create(formData);

                if (result.status === 'success') {
                    // Success Feedback
                    alert('Job Posted Successfully!');
                    jobForm.reset();
                    window.location.href = 'dashboard.php';
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error(error);
                alert('Failed to connect to the server.');
            } finally {
                // Reset Button State
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    }
});
