document.addEventListener('DOMContentLoaded', async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const jobId = urlParams.get('id');

    if (!jobId) {
        window.location.href = 'find-work.php';
        return;
    }

    const user = JSON.parse(localStorage.getItem('user'));

    // Elements
    const els = {
        loading: document.getElementById('loadingState'),
        // content: document.getElementById('jobContent'),
        main: document.getElementById('mainContent'),
        title: document.getElementById('jobTitle'),
        category: document.getElementById('jobCategory'),
        time: document.getElementById('jobTime'),
        location: document.getElementById('jobLocation'),
        budget: document.getElementById('jobBudget'),
        description: document.getElementById('jobDescription'),
        clientName: document.getElementById('clientName'),
        clientAvatar: document.getElementById('clientAvatar'),
        form: document.getElementById('applicationForm'),
        message: document.getElementById('applyMessage')
    };

    // Fetch details
    try {
        const basePath = '/project-simulator-ShobKaaj/Management/Shared/MVC/php/';
        const response = await fetch(`${basePath}jobAPI.php?action=get_job&id=${jobId}`);
        const result = await response.json();

        if (result.status === 'success') {
            const job = result.job;
            renderJob(job);
        } else {
            alert('Job not found');
            window.location.href = 'find-work.php';
        }
    } catch (error) {
        console.error(error);
    }

    function renderJob(job) {
        els.loading.style.display = 'none';
        // els.content.style.display = 'block';
        els.main.style.display = 'grid';

        els.title.textContent = job.title;
        els.category.textContent = job.category;

        let clientHtml = job.clientName || 'Client Name';

        const fullName = `${job.first_name} ${job.last_name}`;
        els.clientName.innerHTML = fullName + (job.is_verified === 'verified' ? ' <i class="fas fa-check-circle" style="color:#22c55e; margin-left:4px;" title="Verified"></i>' : '');
        els.location.innerHTML = `<i class="fas fa-map-marker-alt"></i> ${job.location || 'Remote'}`;
        els.budget.textContent = '৳' + parseFloat(job.budget).toLocaleString();
        els.description.textContent = job.description;
        if (job.avatar) els.clientAvatar.src = window.getAvatarPath(job.avatar, 'client');

        const date = new Date(job.created_at).toLocaleDateString();
        els.time.textContent = `Posted ${date}`;
    }

});
