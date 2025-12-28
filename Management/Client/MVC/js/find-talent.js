document.addEventListener('DOMContentLoaded', () => {
    fetchWorkers();

    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');

    searchBtn.addEventListener('click', () => {
        fetchWorkers(searchInput.value);
    });

    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            fetchWorkers(searchInput.value);
        }
    });
});

async function fetchWorkers(query = '') {
    const grid = document.getElementById('talentGrid');
    // Show spinner while fetching
    grid.innerHTML = `<div class="loading-state"><i class="fas fa-spinner fa-spin"></i> Loading talent...</div>`;

    try {
        const basePath = '/project-simulator-ShobKaaj/Management/Shared/MVC/php/';
        const url = `${basePath}authAPI.php?action=get_workers&search=${encodeURIComponent(query)}`;
        const response = await fetch(url);
        const result = await response.json();

        if (result.status === 'success' && result.workers.length > 0) {
            let cardsHtml = '';

            result.workers.forEach(worker => {
                const avatar = window.getAvatarPath(worker.avatar, 'worker');
                const rating = parseFloat(worker.rating || 0).toFixed(1);
                const reviewCount = worker.reviews_count || 0;
                const jobsDone = worker.completed_jobs || 0;
                const earnings = parseInt(worker.total_earnings || 0).toLocaleString();

                cardsHtml += `
                <div class="talent-card" onclick="window.location.href='/project-simulator-ShobKaaj/Management/Shared/MVC/html/view-profile.php?id=${worker.id}'" style="cursor:pointer;">
                    <img src="${avatar}" alt="${worker.first_name}" class="talent-avatar">
                    <h3 class="talent-name">${worker.first_name} ${worker.last_name}</h3>
                    
                    <div class="talent-rating">
                        <i class="fas fa-star"></i> ${rating} <span>(${reviewCount} reviews)</span>
                    </div>

                    <div style="margin: 10px 10px; display:flex; flex-wrap:wrap; gap:4px;">
                        ${worker.skills ? worker.skills.split(',').slice(0, 3).map(s => `<span class="badge secondary" style="font-size:0.75rem; align-items:center">${s.trim()}</span>`).join('') : ''}
                    </div>

                    <div class="talent-stats">
                        <div class="stat-item">
                            <span class="stat-val">${jobsDone}</span>
                            <span class="stat-label">Jobs Done</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-val">৳${earnings}</span>
                            <span class="stat-label">Earned</span>
                        </div>
                    </div>

                    <button class="btn outline view-profile-btn">View Profile</button>
                </div>
                `;
            });
            grid.innerHTML = cardsHtml;
        } else {
            grid.innerHTML = `<div class="empty-state">No workers found matching your search.</div>`;
        }
    } catch (error) {
        console.error('Failed to load workers:', error);
        grid.innerHTML = `<div class="empty-state" style="color:var(--error)">Unable to load talent list. Please execute 'Refresh' or try again later.</div>`;
    }
}
