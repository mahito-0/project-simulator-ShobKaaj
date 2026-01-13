document.addEventListener('DOMContentLoaded', () => {

    // Check if user is admin (simple frontend check, mostly relied on backend)
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user || user.role !== 'admin') {
        window.location.href = '../../../Shared/MVC/html/auth.php';
        return;
    }

    const els = {
        totalUsers: document.getElementById('totalUsers'),
        verifiedUsers: document.getElementById('verifiedUsers'),
        terminatedUsers: document.getElementById('terminatedUsers'),
        totalJobs: document.getElementById('totalJobs'),
        tableBody: document.getElementById('usersTableBody'),
        jobsTableBody: document.getElementById('jobsTableBody'),
        refreshBtn: document.getElementById('refreshBtn')
    };

    els.refreshBtn.addEventListener('click', loadData);

    loadData();

    async function loadAnalytics() {
        try {
            const res = await fetch('../php/adminAPI.php?action=get_job_analytics');
            const data = await res.json();

            if (data.status === 'success') {
                renderChart(data.analytics);
            }
        } catch (e) {
            console.error("Failed to load analytics", e);
        }
    }

    let jobChart = null;
    function renderChart(analyticsData) {
        const ctx = document.getElementById('jobAnalyticsChart').getContext('2d');

        // Destroy existing chart if it exists to avoid overlaps on refresh
        if (jobChart) {
            jobChart.destroy();
        }

        jobChart = new Chart(ctx, {
            type: 'line', // Changed from doughnut to line
            data: {
                labels: analyticsData.labels, // Dates
                datasets: [{
                    label: 'Jobs Posted',
                    data: analyticsData.data, // Counts
                    borderColor: '#3b82f6', // Primary Blue
                    backgroundColor: 'rgba(59, 130, 246, 0.1)', // Light blue fill
                    borderWidth: 2,
                    tension: 0.4, // Smooths the line
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Hide legend for single line
                    },
                    title: {
                        display: true,
                        text: 'Jobs Posted (Last 7 Days)'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1 // No decimals for counts
                        },
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    }

    async function loadData() {
        await loadStats();
        await loadUsers();
        await loadJobs();
        await loadAnalytics();
    }


    async function loadStats() {
        try {
            const res = await fetch('../php/adminAPI.php?action=get_stats');
            const data = await res.json();
            if (data.status === 'success') {
                els.totalUsers.textContent = data.stats.total_users;
                els.verifiedUsers.textContent = data.stats.verified_users;
                els.terminatedUsers.textContent = data.stats.terminated_users;
                if (els.totalJobs) els.totalJobs.textContent = data.stats.total_jobs;
            }
        } catch (e) {
            console.error(e);
        }
    }

    async function loadUsers() {
        try {
            const res = await fetch('../php/adminAPI.php?action=get_users');
            const data = await res.json();
            if (data.status === 'success') {
                renderTable(data.users);
            }
        } catch (e) {
            console.error(e);
            els.tableBody.innerHTML = '<tr><td colspan="7" style="text-align:center; color:var(--error);">Failed to load users</td></tr>';
        }
    }

    async function loadJobs() {
        try {
            const res = await fetch('../php/adminAPI.php?action=get_jobs');
            const data = await res.json();
            if (data.status === 'success') {
                renderJobsTable(data.jobs);
            }
        } catch (e) {
            console.error(e);
            els.jobsTableBody.innerHTML = '<tr><td colspan="7" style="text-align:center; color:var(--error);">Failed to load jobs</td></tr>';
        }
    }

    function renderTable(users) {
        if (users.length === 0) {
            els.tableBody.innerHTML = '<tr><td colspan="7" style="text-align:center;">No users found</td></tr>';
            return;
        }

        els.tableBody.innerHTML = users.map(u => `
            <tr>
                <td>#${u.id}</td>
                <td>${u.first_name} ${u.last_name}</td>
                <td>${u.email}</td>
                <td><span class="badge" style="text-transform:capitalize">${u.role}</span></td>
                <td>
                    <span class="badge ${u.status === 'terminated' ? 'terminated' : 'active-status'}">
                        ${u.status}
                    </span>
                </td>
                <td>
                    <span class="badge ${u.is_verified === 'verified' ? 'verified' : 'unverified'}">
                        ${u.is_verified === 'verified' ? 'Verified' : 'Unverified'}
                    </span>
                </td>
                <td>
                    <div style="display:flex; gap:8px;">
                        ${renderVerifyButton(u)}
                        ${renderTerminateButton(u)}
                    </div>
                </td>
            </tr>
        `).join('');

        // Add event listeners to buttons
        document.querySelectorAll('.action-verify').forEach(btn => {
            btn.addEventListener('click', () => handleStatusUpdate(btn.dataset.id, 'verify'));
        });
        document.querySelectorAll('.action-unverify').forEach(btn => {
            btn.addEventListener('click', () => handleStatusUpdate(btn.dataset.id, 'unverify'));
        });
        document.querySelectorAll('.action-terminate').forEach(btn => {
            btn.addEventListener('click', () => handleStatusUpdate(btn.dataset.id, 'terminate'));
        });
        document.querySelectorAll('.action-activate').forEach(btn => {
            btn.addEventListener('click', () => handleStatusUpdate(btn.dataset.id, 'activate'));
        });
    }

    function renderJobsTable(jobs) {
        if (jobs.length === 0) {
            els.jobsTableBody.innerHTML = '<tr><td colspan="7" style="text-align:center;">No jobs found</td></tr>';
            return;
        }

        els.jobsTableBody.innerHTML = jobs.map(j => `
            <tr>
                <td>#${j.id}</td>
                <td><strong>${j.title}</strong></td>
                <td>${j.first_name} ${j.last_name}</td>
                <td>$${j.budget}</td>
                <td><span class="badge" style="text-transform:capitalize">${j.status}</span></td>
                <td>${new Date(j.created_at).toLocaleDateString()}</td>
                <td>
                    <button class="btn sm danger action-delete-job" data-id="${j.id}" title="Delete Job">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');

        document.querySelectorAll('.action-delete-job').forEach(btn => {
            btn.addEventListener('click', () => handleDeleteJob(btn.dataset.id));
        });
    }

    function renderVerifyButton(u) {
        if (u.is_verified === 'verified') {
            return `<button class="btn sm secondary action-unverify" data-id="${u.id}" title="Unverify"><i class="fas fa-times"></i></button>`;
        } else {
            return `<button class="btn sm success action-verify" data-id="${u.id}" title="Verify"><i class="fas fa-check"></i></button>`;
        }
    }

    function renderTerminateButton(u) {
        if (u.status === 'terminated') {
            return `<button class="btn sm primary action-activate" data-id="${u.id}" title="Activate"><i class="fas fa-undo"></i></button>`;
        } else {
            return `<button class="btn sm danger action-terminate" data-id="${u.id}" title="Terminate"><i class="fas fa-ban"></i></button>`;
        }
    }

    async function handleStatusUpdate(id, type) {
        if (!confirm(`Are you sure you want to ${type} this user?`)) return;

        try {
            const res = await fetch('../php/adminAPI.php?action=update_status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: id, type: type })
            });
            const data = await res.json();

            if (data.status === 'success') {
                // partial reload
                loadData();
            } else {
                alert('Action failed: ' + data.message);
            }
        } catch (e) {
            console.error(e);
            alert('Network error');
        }
    }

    async function handleDeleteJob(id) {
        if (!confirm('Are you sure you want to delete this job? This cannot be undone.')) return;

        try {
            const res = await fetch('../php/adminAPI.php?action=delete_job', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ job_id: id })
            });
            const data = await res.json();
            if (data.status === 'success') {
                loadJobs();
                loadStats();
            } else {
                alert('Action failed: ' + data.message);
            }
        } catch (e) {
            console.error(e);
            alert('Network error');
        }
    }



});
