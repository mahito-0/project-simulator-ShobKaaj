<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - ShobKaaj</title>
    <meta name="description" content="Manage your jobs, applications, and profile on ShobKaaj dashboard.">
    <link rel="stylesheet" href="/project-simulator-ShobKaaj/Management/Shared/MVC/css/base.css" />
    <link rel="stylesheet" href="/project-simulator-ShobKaaj/Management/Shared/MVC/css/index.css" />
    <link rel="stylesheet" href="/project-simulator-ShobKaaj/Management/Worker/MVC/css/worker-dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div id="navbar-container"></div>
    <script src="/project-simulator-ShobKaaj/Management/Shared/MVC/js/navbar.js"></script>

    <main class="container dashboard-container" style="margin-top: 10px; min-height: 80vh;">
        <!-- Welcome Section -->
        <div class="welcome-section" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem;">
            <div>
                <h1 style="margin:0;">Welcome back, <span id="userName" style="color:var(--primary);">User</span>!</h1>
                <p style="color:var(--text-secondary); margin-top:0.5rem;">Here is what’s happening with your projects.</p>
            </div>
            <div id="actionContainer">
                <button class="btn primary" onclick="window.location.href = 'post-job.php';">
                    <i class="fas fa-plus"></i> Post a New Job
                </button>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid" id="statsGrid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Populated by JS -->
            <div style="text-align:center; padding:20px; color:var(--text-secondary);">Loading stats...</div>
        </div>

        <!-- Dashboard Split View -->
        <div class="dashboard-grid" style="display:grid; grid-template-columns: 2fr 1.5fr; gap: 2rem;">
            <!-- Left: Posted Jobs / Work History -->
            <div class="dashboard-section card" style="padding: 1.5rem;">
                <div class="section-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--line); padding-bottom: 0.5rem;">
                    <h3 style="margin:0;">My Posted Jobs</h3>
                    <span class="badge primary" id="postedJobsCount">0</span>
                </div>
                <div class="card-list" id="postedJobs">
                    <!-- Populated by JS -->
                </div>
            </div>

            <!-- Right: Applications -->
            <div class="dashboard-section card" style="padding: 1.5rem;">
                <div class="section-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--line); padding-bottom: 0.5rem;">
                    <h3 style="margin:0;">Received Proposals</h3>
                    <span class="badge primary" id="applicationsCount">0</span>
                </div>
                <div class="card-list" id="myApplications">
                    <!-- Populated by JS -->
                </div>
            </div>
        </div>
    </main>

    <!-- Use worker-dashboard.js as it contains client logic too -->
    <script src="/project-simulator-ShobKaaj/Management/Client/MVC/js/client-dashboard.js"></script>
</body>

</html>