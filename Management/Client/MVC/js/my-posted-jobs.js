document.addEventListener('DOMContentLoaded', () => {
    // 1. Auth Check
    const storedUser = localStorage.getItem('user');
    if (!storedUser) {
        window.location.href = 'auth.php';
        return;
    }
    const user = JSON.parse(storedUser);

    // 2. Role Check - Client Only
    if (user.role !== 'client') {
        alert("Access Denied: This page is for Clients only.");
        window.location.href = 'dashboard.php';
        return;
    }

    // 3. Initial Setup
    setupPage(user);

    // 4. Load default tab
    switchTab('posted-jobs');
});

let currentUser = null;
let currentTab = '';

function setupPage(user) {
    currentUser = user;
    const tabsContainer = document.getElementById('tabs-control');
    const actionsContainer = document.getElementById('page-actions');
    const subtitle = document.getElementById('page-subtitle');

    subtitle.textContent = "Manage your job listings and review proposals.";

    // Client Tabs
    tabsContainer.innerHTML = `
        <button class="tab-btn" onclick="switchTab('posted-jobs')" id="tab-posted-jobs">Posted Jobs</button>
        <button class="tab-btn" onclick="switchTab('proposals')" id="tab-proposals">Received Proposals</button>
    `;

    // Client Action
    actionsContainer.innerHTML = `
        <a href="post-job.php" class="btn primary-action">
            <i class="fas fa-plus"></i> Post New Job
        </a>
    `;
}

function switchTab(tabName) {
    currentTab = tabName;

    // Update UI
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    const activeBtn = document.getElementById(`tab-${tabName}`);
    if (activeBtn) activeBtn.classList.add('active');

    // Load Content
    const contentArea = document.getElementById('content-area');
    contentArea.innerHTML = `
        <div class="loading-state">
            <i class="fas fa-circle-notch fa-spin"></i>
            <p>Loading...</p>
        </div>
    `;

    // Route to loader
    if (tabName === 'posted-jobs') loadPostedJobs();
    else if (tabName === 'proposals') loadClientProposals();
}

// ================= CLIENT FUNCTIONS =================

async function loadPostedJobs() {
    const list = document.getElementById('content-area');
    try {
        const basePath = '/project-simulator-ShobKaaj/Management/Shared/MVC/php/';
        const response = await fetch(`${basePath}jobAPI.php?action=my_jobs&client_id=${currentUser.id}`);
        const result = await response.json();

        if (result.status === 'success' && result.jobs.length > 0) {
            let html = '<div class="job-list">';
            result.jobs.forEach(job => {
                const created = new Date(job.created_at).toLocaleDateString();
                const statusClass = `status-${job.status}`; // open, completed, in_progress

                let actions = '';
                if (job.status === 'open') {
                    // actions = `<button class="btn sm outline" disabled>Open</button>`;
                } else if (job.status === 'in_progress') {
                    actions = `<span class="small" style="color:var(--info);">Work in progress</span>`;
                }

                html += `
                <div class="job-card">
                    <div class="job-header">
                        <div class="job-info">
                            <h3>${job.title}</h3>
                            <div class="job-meta">
                                <span class="meta-item"><i class="fas fa-map-marker-alt"></i> ${job.location}</span>
                                <span class="meta-item"><i class="fas fa-folder"></i> ${job.category}</span>
                                <span class="meta-item"><i class="far fa-clock"></i> Posted ${created}</span>
                            </div>
                        </div>
                        <span class="job-status ${statusClass}">${job.status.replace('_', ' ')}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:var(--space-2);">
                        <span class="job-budget">৳${job.budget}</span>
                        <div>${actions}</div>
                    </div>
                </div>`;
            });
            html += '</div>';
            list.innerHTML = html;
        } else {
            showEmptyState('You haven\'t posted any jobs yet.');
        }
    } catch (e) {
        console.error(e);
        showError('Failed to load jobs.');
    }
}

async function loadClientProposals() {
    const list = document.getElementById('content-area');
    try {
        const basePath = '/project-simulator-ShobKaaj/Management/Shared/MVC/php/';
        const response = await fetch(`${basePath}jobAPI.php?action=get_client_applications&client_id=${currentUser.id}`);
        const result = await response.json();

        if (result.status === 'success' && result.applications.length > 0) {
            // Group by Job (Optional, but flat list is fine for now as API returns all)
            // Let's render a grid of applicants
            let html = '<div class="applicant-grid">';

            result.applications.forEach(app => {
                // Determine actions based on status
                let actionBtn = '';
                if (app.status === 'pending') {
                    actionBtn = `
                   <div class="action-row">
                        <button class="btn primary-action sm" style="flex:1" onclick="hireWorker(${app.id}, ${app.job_id})">Hire</button>
                        <button class="btn outline sm destructive" onclick="rejectWorker(${app.id})"><i class="fas fa-times"></i> Reject</button>
                   </div>`;
                } else if (app.status === 'accepted') {
                    actionBtn = `
                   <div class="action-row">
                        <button class="btn secondary sm" style="flex:1" onclick="window.location.href='complete-job.php?job_id=${app.job_id}&worker_id=${app.worker_id}&app_id=${app.id}'">Complete Job</button>
                   </div>`;
                } else if (app.status === 'rejected') {
                    actionBtn = `<div class="action-row"><span class="badge error" style="flex:1; text-align:center;">Rejected</span></div>`;
                }

                html += `
                <div class="applicant-card" onclick="window.location.href='/project-simulator-ShobKaaj/Management/Shared/MVC/html/view-profile.php?id=${app.worker_id}'">
                    <div class="applicant-header">
                        <img src="${window.getAvatarPath(app.avatar, 'worker')}" class="applicant-avatar">
                        <div class="applicant-details">
                            <h4>${app.first_name} ${app.last_name}</h4>
                            <p>Applied for: <strong>${app.job_title}</strong></p>
                        </div>
                    </div>
                    
                    <div class="bid-amount">Bid: ৳${app.bid_amount}</div>
                    
                    <div class="cover-letter">
                        "${app.cover_letter}"
                    </div>
                    
                    <div style="display:flex; justify-content:space-between; font-size:0.8rem; color:var(--text-muted);">
                        <span>${new Date(app.created_at).toLocaleDateString()}</span>
                        <span class="badge ${app.status === 'accepted' ? 'success' : 'secondary'}">${app.status.toUpperCase()}</span>
                    </div>

                    ${actionBtn}
                </div>`;
            });
            html += '</div>';
            list.innerHTML = html;
        } else {
            showEmptyState('No proposals received yet.');
        }
    } catch (e) {
        console.error(e);
        showError('Failed to load proposals.');
    }
}

async function rejectWorker(appId) {
    if (!confirm("Are you sure you want to reject this application?")) return;

    try {
        const formData = new FormData();
        formData.append('application_id', appId);

        const basePath = '/project-simulator-ShobKaaj/Management/Shared/MVC/php/';
        const response = await fetch(`${basePath}jobAPI.php?action=reject_application`, {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.status === 'success') {
            loadClientProposals(); // reload
        } else {
            alert(result.message);
        }
    } catch (e) {
        console.error(e);
        alert('Failed to reject application');
    }
}

async function hireWorker(appId, jobId) {
    if (!confirm("Are you sure you want to hire this worker?")) return;
    try {
        const formData = new FormData();
        formData.append('application_id', appId);
        formData.append('job_id', jobId);

        const basePath = '/project-simulator-ShobKaaj/Management/Shared/MVC/php/';
        const response = await fetch(`${basePath}jobAPI.php?action=hire_worker`, {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.status === 'success') {
            alert("Worker hired!");
            loadClientProposals(); // reload
        } else {
            alert(result.message);
        }
    } catch (e) {
        console.error(e);
        alert("Error hiring worker");
    }
}

// Helpers
function showEmptyState(msg) {
    document.getElementById('content-area').innerHTML = `
        <div style="text-align:center; padding:4rem; color:var(--text-muted);">
            <i class="fas fa-folder-open" style="font-size:3rem; margin-bottom:1rem; opacity:0.3;"></i>
            <p>${msg}</p>
        </div>
    `;
}

function showError(msg) {
    document.getElementById('content-area').innerHTML = `<p style="color:var(--error); text-align:center; padding:2rem;">${msg}</p>`;
}
