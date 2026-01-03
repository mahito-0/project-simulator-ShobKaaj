<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posted Jobs - ShobKaaj</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="/project-simulator-ShobKaaj/Management/Shared/MVC/css/base.css">
    <link rel="stylesheet" href="/project-simulator-ShobKaaj/Management/Client/MVC/css/my-jobs.css">
    <style>
        /* Loading State */
        .loading-state {
            text-align: center;
            padding: 4rem;
            color: var(--text-secondary);
        }

        .loading-state i {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--primary);
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <div id="navbar-container"></div>

    <!-- Main Content -->
    <div class="container" style="margin-top: 100px; min-height: 80vh;">

        <div class="page-header">
            <div>
                <h1 class="page-title">My Jobs</h1>
                <p class="small" id="page-subtitle">Manage your work and applications</p>
            </div>
            <div id="page-actions">
                <!-- Dynamic Actions (e.g. Post Job) -->
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs-container">
            <div class="tabs" id="tabs-control">
                <!-- Populated by JS -->
            </div>
        </div>

        <!-- Content Area -->
        <div id="content-area">
            <div class="loading-state">
                <i class="fas fa-circle-notch fa-spin"></i>
                <p>Loading your dashboard...</p>
            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script src="/project-simulator-ShobKaaj/Management/Shared/MVC/js/utils.js"></script>
    <script src="/project-simulator-ShobKaaj/Management/Shared/MVC/js/navbar.js"></script>
    <script src="../js/my-posted-jobs.js"></script>

</body>

</html>