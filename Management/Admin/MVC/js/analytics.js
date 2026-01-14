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

    // Date Filter logic
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const filterBtn = document.getElementById('filterBtn');

    // Set default dates (Last 7 days)
    const today = new Date();
    const lastWeek = new Date();
    lastWeek.setDate(today.getDate() - 6);

    // Check if inputs exist (to avoid errors if HTML not updated yet)
    if (startDateInput && endDateInput) {
        startDateInput.valueAsDate = lastWeek;
        endDateInput.valueAsDate = today;
        filterBtn.addEventListener('click', loadAnalytics);
    }

    if (els.refreshBtn) {
        els.refreshBtn.addEventListener('click', loadAnalytics);
    }

    loadAnalytics();

    async function loadAnalytics() {
        const start = startDateInput ? startDateInput.value : '';
        const end = endDateInput ? endDateInput.value : '';

        try {
            // AJAX request to fetch job analytics
            const res = await fetch(`../php/adminAPI.php?action=get_job_analytics&start_date=${start}&end_date=${end}`);
            const data = await res.json();

            if (data.status === 'success') {
                const title = `Jobs Posted (${new Date(start).toLocaleDateString()} - ${new Date(end).toLocaleDateString()})`;
                renderChart(data.analytics, title);
            }
        } catch (e) {
            console.error("Failed to load analytics", e);
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

});
