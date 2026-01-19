<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - ShobKaaj</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/notifications.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>


    <main class="notification-container">
        <div class="notification-header">
            <div class="header-left">
                <h2>Notifications</h2>
            </div>
            <div class="notification-tabs">
                <button class="tab-btn active" data-filter="all">All</button>
                <button class="tab-btn" data-filter="unread">Unread</button>
                <button class="tab-btn" data-filter="important">Important</button>
            </div>
            <div class="notification-actions">
                <button class="action-btn" id="markAllRead">
                    <i class="fas fa-check-double"></i> Mark all as read
                </button>
            </div>
        </div>

        <div id="notificationList" class="notification-list">
            <div class="empty-state">Loading...</div>
        </div>
    </main>
    <script src="../js/navbar.js"></script>
    <script src="../js/notifications.js"></script>
</body>

</html>