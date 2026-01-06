<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Jobs - ShobKaaj</title>
    <link rel="stylesheet" href="/project-simulator-ShobKaaj/Management/Shared/MVC/css/base.css">
    <link rel="stylesheet" href="/project-simulator-ShobKaaj/Management/Worker/MVC/css/my-jobs.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div id="navbar-container"></div>

    <main class="my-jobs-container" style="margin-top: 100px; min-height: 80vh;">
        <div class="page-header">
            <div>
                <h1 class="page-title">My Jobs</h1>
                <p class="page-subtitle">Track your applications and active contracts</p>
            </div>
        </div>

        <div class="panels-grid">
            <!-- Completed Jobs Panel -->
            <div class="job-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <i class="fas fa-check-circle" style="color: var(--success);"></i>
                        Completed
                    </div>
                    <span class="count-badge" id="completed-count">0</span>
                </div>
                <div class="panel-content" id="completed-list">
                    <!-- JS Populated -->
                </div>
            </div>

            <!-- Running Jobs Panel -->
            <div class="job-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <i class="fas fa-briefcase" style="color: var(--info);"></i>
                        Running
                    </div>
                    <span class="count-badge" id="running-count">0</span>
                </div>
                <div class="panel-content" id="running-list">
                    <!-- JS Populated -->
                </div>
            </div>

            <!-- Applied Jobs Panel -->
            <div class="job-panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <i class="fas fa-paper-plane" style="color: var(--warning);"></i>
                        Applied
                    </div>
                    <span class="count-badge" id="applied-count">0</span>
                </div>
                <div class="panel-content" id="applied-list">
                    <!-- JS Populated -->
                </div>
            </div>
        </div>
    </main>

    <script src="/project-simulator-ShobKaaj/Management/Shared/MVC/js/navbar.js"></script>
    <script src="../js/my-jobs.js"></script>
</body>

</html>