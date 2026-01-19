const basePath = '/project-simulator-ShobKaaj/Management/Shared/MVC/php/';

document.addEventListener('DOMContentLoaded', () => {
    // Retrieve user session
    const storedUser = localStorage.getItem('user');
    let user = storedUser ? JSON.parse(storedUser) : null;

    // Guard: Redirect to login if no session found
    if (!user) {
        window.location.href = 'auth.php';
        return;
    }

    // Ensure user is a worker
    if (user.role !== 'worker') {
        // window.location.href = 'client-dashboard.php';
        return;
    }

    // Update UI with user details
    const userNameEl = document.getElementById('userName');
    if (userNameEl) userNameEl.textContent = user.first_name || user.name || 'User';

    // Render Dashboard Elements
    const statsGrid = document.getElementById('statsGrid');
    const actionContainer = document.getElementById('actionContainer');

    renderWorkerStats(statsGrid);
    renderWorkerJobs();
    renderWorkerApplications(user);

    if (actionContainer) {
        actionContainer.innerHTML = `
            <button class="btn primary" onclick="window.location.href = 'find-work.php';">
                <i class="fas fa-search"></i> Find Work
            </button>
        `;
    }
});

async function renderWorkerStats(container) {
    const user = JSON.parse(localStorage.getItem('user'));

    // Default structure with loaders
    container.innerHTML = `
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-icon primary"><i class="fas fa-clipboard-check"></i></div>
                <div>
                    <div class="stat-value" id="stat-worker-completed">-</div>
                    <div class="stat-label">Jobs Completed</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-icon purple"><i class="fas fa-star"></i></div>
                <div>
                    <div class="stat-value" id="stat-worker-rating">-</div>
                    <div class="stat-label">Rating</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-icon orange"><i class="fas fa-wallet"></i></div>
                <div>
                    <div class="stat-value" id="stat-worker-earnings">-</div>
                    <div class="stat-label">Earnings</div>
                </div>
            </div>
        </div>
    `;

    try {
        // AJAX: Fetch worker stats
        const response = await fetch(`${basePath}jobAPI.php?action=get_worker_stats&worker_id=${user.id}`);
        const result = await response.json();

        if (result.status === 'success') {
            document.getElementById('stat-worker-completed').textContent = result.completed_jobs;
            document.getElementById('stat-worker-rating').textContent = result.rating;
            document.getElementById('stat-worker-earnings').textContent = '৳' + parseFloat(result.earnings).toLocaleString();
        }
    } catch (error) {
        console.error("Failed to load worker stats", error);
    }
}

async function renderWorkerJobs() {

    const list = document.getElementById('postedJobs');
    if (!list) return;

    // Change Header Text dynamically
    const header = document.querySelector('.dashboard-section:first-child h3');
    if (header) header.textContent = "Work History";

    // Reset Count
    document.getElementById('postedJobsCount').textContent = '0';
    list.innerHTML = `<p class="text-muted">Loading history...</p>`;

    const user = JSON.parse(localStorage.getItem('user'));

    try {
        // AJAX: Fetch worker history
        const response = await fetch(`${basePath}jobAPI.php?action=get_worker_history&worker_id=${user.id}`);
        const result = await response.json();

        if (result.status === 'success' && result.history.length > 0) {
            document.getElementById('postedJobsCount').textContent = result.history.length;

            let html = '<div class="list">';
            result.history.forEach(job => {
                const ratingHtml = job.rating ? `<span style="color:#fbbf24; font-weight:600;"><i class="fas fa-star"></i> ${job.rating}</span>` : '<span class="text-muted" style="font-size:0.85rem;">No rating</span>';

                html += `
                <div class="item">
                    <div style="flex:1;">
                        <h4 style="margin:0 0 6px 0; font-size:1.1rem;">${job.title}</h4>
                        <div style="display:flex; align-items:center; gap:12px; font-size:0.9rem;">
                             <span style="color:var(--text-secondary);">Client: <a href="view-profile.php?id=${job.client_id}" style="color:var(--primary); text-decoration:none;">${job.first_name} ${job.last_name}</a></span>
                             <span style="color:var(--text-secondary);">Earned: <strong style="color:var(--success);">৳${job.budget}</strong></span>
                             <span style="color:var(--surface-border);">|</span>
                             ${ratingHtml}
                        </div>
                    </div>
                </div>`;
            });
            html += '</div>';
            list.innerHTML = html;
        } else {
            list.innerHTML = `<div style="text-align:center; padding:2rem; color:var(--text-secondary);">
                <i class="fas fa-check-circle" style="font-size:2rem; margin-bottom:1rem; opacity:0.5;"></i>
                <p>No completed jobs yet.</p>
            </div>`;
        }
    } catch (error) {
        console.error(error);
        list.innerHTML = `<p class="error">Failed to load history.</p>`;
    }
}

async function renderWorkerApplications(user) {
    const list = document.getElementById('myApplications');
    if (!list) return;

    try {
        // AJAX: Fetch worker applications
        const response = await fetch(`${basePath}jobAPI.php?action=get_worker_applications&worker_id=${user.id}`);
        const result = await response.json();

        if (result.status === 'success' && result.applications.length > 0) {
            document.getElementById('applicationsCount').textContent = result.applications.length;

            let html = '<div class="list">';
            result.applications.forEach(app => {
                html += `
                 <div class="item">
                     <div style="flex:1;">
                         <div style="display:flex; justify-content:space-between;">
                             <h4>${app.job_title}</h4>
                             <span class="badge secondary">${app.status.toUpperCase()}</span>
                         </div>
                         <p class="small">Your Bid: ৳${app.bid_amount} • Budget: ৳${app.budget}</p>
                     </div>
                 </div>`;
            });
            html += '</div>';
            list.innerHTML = html;
        } else {
            list.innerHTML = `<p class="text-muted">You haven't applied to any jobs yet.</p>`;
        }
    } catch (error) {
        console.error(error);
    }
}


