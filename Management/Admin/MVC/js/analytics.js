document.addEventListener('DOMContentLoaded', () => {

    // Check if user is admin (simple frontend check, mostly relied on backend)
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user || user.role !== 'admin') {
        window.location.href = '../../../Shared/MVC/html/auth.php';
        return;
    }

    const els = {
        refreshBtn: document.getElementById('refreshBtn')
    };

    const jobEls = {
        startDate: document.getElementById('startDate'),
        endDate: document.getElementById('endDate'),
        filterBtn: document.getElementById('filterBtn')
    };

    const userEls = {
        startDate: document.getElementById('userStartDate'),
        endDate: document.getElementById('userEndDate'),
        filterBtn: document.getElementById('userFilterBtn')
    };

    // Set default dates (Last 7 days)
    const today = new Date();
    const lastWeek = new Date();
    lastWeek.setDate(today.getDate() - 6);

    // Initialize Job Filter
    if (jobEls.startDate && jobEls.endDate && jobEls.filterBtn) {
        jobEls.startDate.valueAsDate = lastWeek;
        jobEls.endDate.valueAsDate = today;
        jobEls.filterBtn.addEventListener('click', loadJobAnalytics);
    }

    // Initialize User Filter
    if (userEls.startDate && userEls.endDate && userEls.filterBtn) {
        userEls.startDate.valueAsDate = lastWeek;
        userEls.endDate.valueAsDate = today;
        userEls.filterBtn.addEventListener('click', loadUserAnalytics);
    }

    if (els.refreshBtn) {
        els.refreshBtn.addEventListener('click', () => {
            loadJobAnalytics();
            loadUserAnalytics();
        });
    }

    // Initial Load
    loadJobAnalytics();
    loadUserAnalytics();

    async function loadJobAnalytics() {
        const start = jobEls.startDate ? jobEls.startDate.value : '';
        const end = jobEls.endDate ? jobEls.endDate.value : '';

        try {
            const res = await fetch(`../php/adminAPI.php?action=get_job_analytics&start_date=${start}&end_date=${end}`);
            const data = await res.json();

            if (data.status === 'success') {
                const title = `Jobs Posted (${new Date(start).toLocaleDateString()} - ${new Date(end).toLocaleDateString()})`;
                renderChart(data.analytics, title);
            }
        } catch (e) {
            console.error("Failed to load job analytics", e);
        }
    }

    async function loadUserAnalytics() {
        const start = userEls.startDate ? userEls.startDate.value : '';
        const end = userEls.endDate ? userEls.endDate.value : '';

        try {
            const res = await fetch(`../php/adminAPI.php?action=get_user_analytics&start_date=${start}&end_date=${end}`);
            const data = await res.json();

            if (data.status === 'success') {
                const title = `New Users (${new Date(start).toLocaleDateString()} - ${new Date(end).toLocaleDateString()})`;
                renderUserChart(data.analytics, title);
            }
        } catch (e) {
            console.error("Failed to load user analytics", e);
        }
    }

    let jobChart = null;
    function renderChart(analyticsData, titleText) {
        const ctx = document.getElementById('jobAnalyticsChart').getContext('2d');

        // Destroy existing chart if it exists to avoid overlaps on refresh
        if (jobChart) {
            jobChart.destroy();
        }

        jobChart = new Chart(ctx, {
            type: 'line',
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
                        text: titleText || 'Jobs Posted'
                    },
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
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

    let userChart = null;
    function renderUserChart(analyticsData, titleText) {
        const ctx = document.getElementById('userAnalyticsChart').getContext('2d');

        if (userChart) {
            userChart.destroy();
        }

        userChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: analyticsData.labels,
                datasets: [{
                    label: 'New Users',
                    data: analyticsData.data,
                    borderColor: '#22c55e', // Green
                    backgroundColor: 'rgba(34, 197, 94, 0.1)', // Light green
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#22c55e',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: titleText || 'New Users'
                    },
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: { display: false }
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

});
