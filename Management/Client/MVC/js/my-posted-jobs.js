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

    // 3. Load jobs
    loadMyJobs(user.id);
});

let currentUser = null;

// Fetch and display jobs by category
async function loadMyJobs(clientId) {
    currentUser = { id: clientId };

    const containerEls = {
        completed: document.getElementById('completed-list'),
        running: document.getElementById('running-list'),
        open: document.getElementById('open-list')
    };

    const countEls = {
        completed: document.getElementById('completed-count'),
        running: document.getElementById('running-count'),
        open: document.getElementById('open-count')
    };

    // Show loading state
    Object.values(containerEls).forEach(el => {
        el.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading jobs...</p>
            </div>`;
    });

    try {
        const basePath = '/project-simulator-ShobKaaj/Management/Shared/MVC/php/';
        const response = await fetch(`${basePath}jobAPI.php?action=my_jobs&client_id=${clientId}`);
        const result = await response.json();

        if (result.status === 'success' && result.jobs.length > 0) {
            // Categorize jobs
            const completed = result.jobs.filter(job => job.status === 'completed');
            const running = result.jobs.filter(job => job.status === 'in_progress');
            const open = result.jobs.filter(job => job.status === 'open');

            // Render each category
            renderJobList(containerEls.completed, completed, 'completed', countEls.completed);
            renderJobList(containerEls.running, running, 'running', countEls.running);
            renderJobList(containerEls.open, open, 'open', countEls.open);
        } else {
            // No jobs at all
            showEmptyState(containerEls.completed, 'No completed jobs');
            showEmptyState(containerEls.running, 'No running jobs');
            showEmptyState(containerEls.open, 'No open jobs');
            countEls.completed.textContent = '0';
            countEls.running.textContent = '0';
            countEls.open.textContent = '0';
        }
    } catch (e) {
        console.error(e);
        showErrorState(containerEls, 'Failed to load jobs.');
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

    if (categoryType === 'running') {
        displayStatus = 'In Progress';
        badgeClass = 'status-in_progress';
    } else if (categoryType === 'open') {
        displayStatus = 'Open';
        badgeClass = 'status-open';
    }

    const title = job.title || 'Untitled Job';
    const budget = job.budget ? `৳${parseFloat(job.budget).toFixed(2)}` : 'N/A';
    const dateStr = job.created_at;
    const formattedDate = dateStr ? new Date(dateStr).toLocaleDateString() : 'Date N/A';
    const clientName = `${job.first_name || ''} ${job.last_name || ''}`.trim() || 'You';

    const card = document.createElement('div');
    card.className = 'job-card';
    card.onclick = () => {
        window.location.href = `manage-job.php?id=${job.id}`;
    };

    card.innerHTML = `
        <div class="job-header">
            <div class="job-info">
                <h3>${title}</h3>
                <div class="job-meta">
                    <span class="meta-item">
                        <i class="fas fa-user"></i> Client: ${clientName}
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

function showEmptyState(container, msg) {
    container.innerHTML = `
        <div class="empty-state">
            <i class="fas fa-folder-open"></i>
            <p>${msg}</p>
        </div>
    `;
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
