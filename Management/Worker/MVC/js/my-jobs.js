document.addEventListener('DOMContentLoaded', async () => {
    // Auth Check
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user || user.role !== 'worker') {
        window.location.href = '/project-simulator-ShobKaaj/Management/Shared/MVC/html/index.php';
        return;
    }

    // Load Jobs
    await loadMyJobs(user.id);
});

// Fetch and display jobs by category
async function loadMyJobs(workerId) {
    const containerEls = {
        completed: document.getElementById('completed-list'),
        running: document.getElementById('running-list'),
        applied: document.getElementById('applied-list')
    };

    const countEls = {
        completed: document.getElementById('completed-count'),
        running: document.getElementById('running-count'),
        applied: document.getElementById('applied-count')
    };

    Object.values(containerEls).forEach(el => {
        el.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading your jobs...</p>
            </div>`;
    });

    try {
        const apiPath = '/project-simulator-ShobKaaj/Management/Shared/MVC/php/jobAPI.php';
        // AJAX: Fetch my jobs
        const response = await fetch(`${apiPath}?action=get_categorized_worker_jobs&worker_id=${workerId}`);
        const result = await response.json();

        if (result.status === 'success') {
            renderJobList(containerEls.completed, result.completed, 'completed', countEls.completed);
            renderJobList(containerEls.running, result.running, 'running', countEls.running);
            renderJobList(containerEls.applied, result.applied, 'applied', countEls.applied);
        } else {
            console.error(result.message);
            showErrorState(containerEls, 'Failed to load jobs. Please try again.');
        }
    } catch (error) {
        console.error(error);
        showErrorState(containerEls, 'Unable to connect to the server.');
    }
}

function renderJobList(container, jobs, categoryType, countElement) {
    if (!jobs || jobs.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <p>No ${categoryType} jobs found</p>
            </div>`;
        countElement.textContent = '0';
        return;
    }

    countElement.textContent = jobs.length;
    container.innerHTML = '';

    jobs.forEach(job => {
        const cardHTML = createJobCard(job, categoryType);
        container.appendChild(cardHTML);
    });
}

function createJobCard(job, categoryType) {
    let displayStatus = categoryType;
    let badgeClass = `status-${categoryType}`;

    if (categoryType === 'applied' && job.app_status) {
        displayStatus = job.app_status;
        badgeClass = `status-${job.app_status}`;
    } else if (categoryType === 'running') {
        displayStatus = 'In Progress';
        badgeClass = 'status-running';
    }

    const title = job.title || job.job_title || 'Untitled Job';
    const budget = job.budget ? `৳${job.budget}` : 'N/A';
    const dateStr = job.created_at || job.applied_at;
    const formattedDate = dateStr ? new Date(dateStr).toLocaleDateString() : 'Date N/A';
    const clientName = `${job.first_name || ''} ${job.last_name || ''}`.trim() || 'Unknown Client';

    const card = document.createElement('div');
    card.className = 'job-card';
    card.onclick = () => {
        const jobId = job.job_id || job.id;
        window.location.href = `job-details.php?id=${jobId}`;
    };

    card.innerHTML = `
        <div class="job-header">
            <div class="job-info">
                <h3>${title}</h3>
                <div class="job-meta">
                    <span class="meta-item">
                        <i class="fas fa-user"></i> ${clientName}
                    </span>
                    <span class="meta-item">
                        <i class="far fa-clock"></i> ${formattedDate}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="job-footer">
            <span class="job-budget">${budget}</span>
            <span class="status-badge ${badgeClass}">${displayStatus}</span>
        </div>
    `;

    return card;
}

function showErrorState(containers, message) {
    Object.values(containers).forEach(el => {
        el.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-exclamation-circle" style="color:var(--error);"></i>
                <p>${message}</p>
            </div>`;
    });
}
