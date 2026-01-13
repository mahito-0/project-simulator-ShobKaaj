document.addEventListener('DOMContentLoaded', () => {
    const jobsContainer = document.getElementById('jobsContainer');
    const searchForm = document.getElementById('searchForm');

    // Initial Load
    fetchJobs();

    // Search Handler
    searchForm.addEventListener('submit', (e) => {
        e.preventDefault();
        fetchJobs();
    });

    // Debounced real-time search (optional polish)
    let timeout = null;
    const inputs = searchForm.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                fetchJobs();
            }, 500);
        });
    });

    async function fetchJobs() {
        const keyword = document.getElementById('searchInput').value;
        const location = document.getElementById('locationInput').value;
        const category = document.getElementById('categoryInput').value;
        const basePath = '/project-simulator-ShobKaaj/Management/Shared/MVC/php/';

        // Construct search parameters
        const params = new URLSearchParams();
        params.append('action', 'list');
        if (keyword) params.append('search', keyword);
        if (location) params.append('location', location);
        if (category && category !== 'all') params.append('category', category);

        // Show Loading State
        jobsContainer.innerHTML = `
            <div style="grid-column: 1/-1; text-align: center; color: var(--text-secondary); padding: 60px;">
                <i class="fas fa-spinner fa-spin fa-2x" style="color:var(--primary); margin-bottom:16px;"></i>
                <p>Searching for opportunities...</p>
            </div>
        `;

        try {
            // AJAX request to fetch jobs 
            const response = await fetch(`${basePath}jobAPI.php?${params.toString()}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const result = await response.json();
            console.log('Job API Result:', result); // Debug log

            if (result.status === 'success') {
                if (result.jobs && result.jobs.length > 0) {
                    renderJobs(result.jobs);
                } else {
                    renderEmpty();
                }
            } else {
                // API returned an error status
                console.error('API Error:', result.message);
                jobsContainer.innerHTML = `
                    <div style="grid-column: 1/-1; text-align: center; color: var(--error); padding: 40px;">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                        <p>Error: ${result.message}</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Job fetch failed:', error);
            jobsContainer.innerHTML = `
                <div style="grid-column: 1/-1; text-align: center; color: var(--error); padding: 40px;">
                    <i class="fas fa-exclamation-circle fa-2x"></i>
                    <p>Connection failed. please check console.</p>
                </div>
            `;
        }
    }

    function renderJobs(jobs) {
        jobsContainer.innerHTML = '';

        jobs.forEach(job => {
            const card = document.createElement('div');
            card.className = 'job-card';

            // Format nice date (e.g., "Dec 21")
            const date = new Date(job.created_at).toLocaleDateString('en-US', {
                month: 'short', day: 'numeric'
            });

            // Fallback for avatar
            const avatar = window.getAvatarPath(job.avatar, 'client');

            card.innerHTML = `
                <div class="job-card-header">
                    <div class="client-info">
                        <img src="${avatar}" alt="Client" class="client-avatar">
                        <span class="client-name">
                            <a href="/project-simulator-ShobKaaj/Management/Shared/MVC/html/view-profile.php?id=${job.client_id}" style="text-decoration:none; color:inherit; hover:underline;">
                                ${job.first_name} ${job.last_name}
                            </a>
                        </span>
                    </div>
                    <span class="job-budget">৳${parseFloat(job.budget).toLocaleString()}</span>
                </div>
                
                <h3 class="job-title">${job.title}</h3>
                <p class="job-desc">${job.description}</p>
                
                <div class="job-tags">
                    <span class="tag"><i class="fas fa-tag"></i> ${job.category}</span>
                    <span class="tag"><i class="far fa-clock"></i> Posted ${date}</span>
                </div>

                <div class="job-footer">
                    <span class="job-location">
                        <i class="fas fa-map-marker-alt"></i> ${job.location || 'Remote'}
                    </span>
                    <a href="/project-simulator-ShobKaaj/Management/Worker/MVC/html/job-details.php?id=${job.id}" class="apply-btn">
                        Apply Now <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            `;
            jobsContainer.appendChild(card);
        });
    }

    function renderEmpty() {
        jobsContainer.innerHTML = `
            <div style="grid-column: 1/-1; text-align: center; color: var(--text-secondary); padding: 80px 20px;">
                <i class="fas fa-search" style="font-size:3rem; margin-bottom:20px; opacity:0.3;"></i>
                <h3>No jobs found matching criteria</h3>
                <p>Try adjusting your search terms or filters.</p>
            </div>
        `;
    }
});
