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

    // Role Guard: Ensure user is a client
    if (user.role !== 'client') {
        // Optional: Redirect to worker dashboard if a worker tries to access this
        // window.location.href = 'worker-dashboard.php';
        return;
    }

    // Update UI with user details
    const userNameEl = document.getElementById('userName');
    if (userNameEl) userNameEl.textContent = user.first_name || user.name || 'User';

    // Render Dashboard Elements
    const statsGrid = document.getElementById('statsGrid');
    const actionContainer = document.getElementById('actionContainer');

    renderClientStats(statsGrid);
    renderClientJobs();
    renderClientApplications(user);

    if (actionContainer) {
        actionContainer.innerHTML = `
            <button class="btn primary" onclick="window.location.href = 'post-job.php';">
                <i class="fas fa-plus"></i> Post a New Job
            </button>
        `;
    }
});

function renderClientStats(container) {
    container.innerHTML = `
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-icon primary"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-value" id="stat-jobs-posted">0</div>
                    <div class="stat-label">Jobs Posted</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-icon blue"><i class="fas fa-users"></i></div>
                <div>
                    <div class="stat-value" id="stat-active-workers">0</div>
                    <div class="stat-label">Active Workers</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-icon orange"><i class="fas fa-wallet"></i></div>
                <div>
                    <div class="stat-value" id="stat-total-spent">$0</div>
                    <div class="stat-label">Total Spent</div>
                </div>
            </div>
        </div>
    `;
}

async function renderClientJobs() {
    const list = document.getElementById('postedJobs');
    if (!list) return;

    // Header customization
    const header = document.querySelector('.dashboard-section:first-child h3');
    if (header) header.textContent = "My Posted Jobs";

    // Show Loading
    list.innerHTML = `<div style="text-align:center; padding:2rem;"><i class="fas fa-spinner fa-spin"></i> Loading jobs...</div>`;

    const storedUser = localStorage.getItem('user');
    const user = storedUser ? JSON.parse(storedUser) : null;
    if (!user) return;

    try {
        // Fetch Jobs
        // AJAX: Fetch client posted jobs
        const response = await fetch(`${basePath}jobAPI.php?action=my_jobs&client_id=${user.id}`);
        const result = await response.json();

        if (result.status === 'success' && result.jobs.length > 0) {
            const jobs = result.jobs;
            // Update Count
            const countEl = document.getElementById('postedJobsCount');
            if (countEl) countEl.textContent = jobs.length;

            // Update Stats
            const statPosted = document.getElementById('stat-jobs-posted');
            const statSpent = document.getElementById('stat-total-spent');

            if (statPosted) statPosted.textContent = jobs.length;

            // Calculate Total Budget
            const totalBudget = jobs.reduce((sum, job) => sum + parseFloat(job.budget || 0), 0);
            if (statSpent) statSpent.textContent = '$' + totalBudget.toLocaleString();

            // Render List
            let html = '<div class="list">';
            jobs.forEach(job => {
                const date = new Date(job.created_at).toLocaleDateString();
                const statusColor = job.status === 'open' ? 'success' : 'secondary';

                html += `
                <div class="item">
                    <div style="flex:1;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                            <h4 style="margin:0;">${job.title}</h4>
                            <span class="badge ${statusColor}">${job.status.toUpperCase()}</span>
                        </div>
                        <p class="small" style="margin:0;">${job.category} • ৳${job.budget}</p>
                    </div>
                </div>`;
            });
            html += '</div>';
            list.innerHTML = html;
        } else {
            // Empty State
            list.innerHTML = `<div style="text-align:center; padding:2rem; color:var(--text-secondary);">
                <i class="fas fa-clipboard" style="font-size:2rem; margin-bottom:1rem; opacity:0.5;"></i>
                <p>You haven't posted any jobs yet.</p>
            </div>`;
        }
    } catch (error) {
        console.error("Failed to fetch jobs:", error);
        list.innerHTML = `<div style="text-align:center; color:var(--error);">Failed to load jobs</div>`;
    }
}

async function renderClientApplications(user) {
    const list = document.getElementById('myApplications');
    if (!list) return;

    // Header customization
    const header = document.querySelector('#myApplications').parentElement.querySelector('h3');
    if (header) header.textContent = "Received Proposals";

    list.innerHTML = `<p class="text-muted">Loading proposals...</p>`;

    try {
        // AJAX: Fetch client applications
        const response = await fetch(`${basePath}jobAPI.php?action=get_client_applications&client_id=${user.id}`);
        const result = await response.json();

        if (result.status === 'success' && result.applications.length > 0) {
            document.getElementById('applicationsCount').textContent = result.applications.length;

            let html = '<div class="list">';
            result.applications.forEach(app => {
                let statusBadge, actionBtn;

                if (app.status === 'accepted') {
                    statusBadge = `<span class="badge success">Active</span>`;
                    actionBtn = `<button class="btn secondary sm" onclick="window.location.href='complete-job.php?job_id=${app.job_id}&worker_id=${app.worker_id}&app_id=${app.id}'">Complete Job</button>`;
                } else if (app.status === 'rejected') {
                    statusBadge = `<span class="badge error">Rejected</span>`;
                    actionBtn = '';
                } else {
                    statusBadge = `<span class="badge primary">Pending</span>`;
                    actionBtn = `
                    <div style="display:flex; gap:5px;">
                        <button class="btn primary sm" onclick="hireWorker(${app.id}, ${app.job_id})">Hire</button>
                        <button class="btn outline sm destructive" onclick="rejectWorker(${app.id})"><i class="fas fa-times"></i></button>
                    </div>`;
                }

                html += `
                <div class="item">
                    <img src="${window.getAvatarPath(app.avatar, 'worker')}" class="avatar-sm" style="width:40px;height:40px;border-radius:50%;">
                    <div style="flex:1;">
                        <div style="display:flex; justify-content:space-between;">
                            <h4><a href="${basePath}view-profile.php?id=${app.worker_id}" style="text-decoration:none; color:inherit; hover:underline;">${app.first_name} ${app.last_name}</a></h4>
                            ${statusBadge}
                        </div>
                        <p class="small">Applied for: <strong>${app.job_title}</strong></p>
                        <p class="small">Bid: <span style="color:var(--primary); font-weight:600;">৳${app.bid_amount}</span></p>
                    </div>
                    ${actionBtn}
                </div>`;
            });
            html += '</div>';
            list.innerHTML = html;
        } else {
            list.innerHTML = `<p class="text-muted">No proposals received yet.</p>`;
        }
    } catch (error) {
        console.error(error);
        list.innerHTML = `<p class="error">Failed to load recommendations.</p>`;
    }
}

async function rejectWorker(appId) {
    if (!confirm("Are you sure you want to reject this application?")) return;

    try {
        const formData = new FormData();
        formData.append('application_id', appId);

        // AJAX: Reject application
        const response = await fetch(`${basePath}jobAPI.php?action=reject_application`, {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.status === 'success') {
            const user = JSON.parse(localStorage.getItem('user')); // Reload using existing user context
            renderClientApplications(user);
        } else {
            alert(result.message);
        }
    } catch (e) {
        console.error(e);
        alert('Failed to reject application');
    }
}

async function hireWorker(appId, jobId) {
    if (!confirm('Are you sure you want to hire this worker? This will accept their proposal.')) return;

    try {
        const formData = new FormData();
        formData.append('application_id', appId);
        formData.append('job_id', jobId);

        // AJAX: Hire worker
        const response = await fetch(`${basePath}jobAPI.php?action=hire_worker`, {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.status === 'success') {
            alert('Worker hired successfully!');
            location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error(error);
        alert('Failed to process request');
    }
}


