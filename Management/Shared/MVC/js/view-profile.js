document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('id');

    if (userId) {
        fetchUserProfile(userId);
    } else {
        showError('No user ID specified');
    }
});

async function fetchUserProfile(id) {
    const loading = document.getElementById('loading');
    const content = document.getElementById('profileContent');
    const errorState = document.getElementById('errorState');

    try {
        // AJAX: Fetch public profile
        const response = await fetch(`../php/authAPI.php?action=get_public_profile&id=${id}`);
        const result = await response.json();
        console.log('Profile API Result:', result); // Debugging

        if (result.status === 'success') {
            const user = result.user;
            const stats = result.stats;
            const reviews = result.reviews;

            document.getElementById('profileName').textContent = `${user.first_name} ${user.last_name}`;
            document.getElementById('userRole').textContent = user.role;
            document.getElementById('userEmail').textContent = user.email;
            document.getElementById('userPhone').textContent = user.phone || 'N/A';

            document.getElementById('userAvatar').src = window.getAvatarPath(user.avatar, user.role);

            if (user.role === 'worker') {
                document.getElementById('workerStats').style.display = 'grid';
                document.getElementById('reviewsSection').style.display = 'block';
                document.getElementById('jobsCompleted').textContent = stats.completed_jobs || 0;
                document.getElementById('userRating').textContent = parseFloat(stats.rating || 0).toFixed(1);
                document.getElementById('totalEarnings').textContent = '৳' + parseFloat(stats.total_earnings || 0).toLocaleString();
            } else {
                document.getElementById('clientStats').style.display = 'grid';
                document.getElementById('jobsPosted').textContent = stats.jobs_posted || 0;
                document.getElementById('totalSpent').textContent = '৳' + parseFloat(stats.total_spent || 0).toLocaleString();

                document.getElementById('reviewsSection').style.display = 'none';
            }

            if (user.role === 'worker' && reviews.length > 0) {
                const reviewsList = document.getElementById('reviewsList');
                let html = '';
                reviews.forEach(review => {
                    html += `
                        <div class="review-item">
                            <div class="review-header">
                                <strong>Job: ${review.job_title}</strong>
                                <span class="review-rating"><i class="fas fa-star"></i> ${review.rating}</span>
                            </div>
                            <p>${review.comment}</p>
                            <small class="text-muted">Reviewed by ${review.reviewer_name}</small>
                        </div>
                    `;
                });
                reviewsList.innerHTML = html;
            } else if (user.role === 'worker') {
                document.getElementById('reviewsList').innerHTML = '<p class="text-muted">No reviews yet.</p>';
            }

            loading.style.display = 'none';
            content.style.display = 'block';
        } else {
            showError();
        }
    } catch (error) {
        console.error(error);
        showError();
    }
}

function showError(msg) {
    document.getElementById('loading').style.display = 'none';
    document.getElementById('errorState').style.display = 'block';
}
