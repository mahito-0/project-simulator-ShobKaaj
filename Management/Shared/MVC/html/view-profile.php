<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($user) ? htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) : 'Profile Not Found'; ?> - ShobKaaj</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/view-profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div id="navbar-container"></div>
    <script src="../js/navbar.js"></script>
    <script src="../js/view-profile.js"></script>

    <main class="container">
       
        <div id="loading" style="display: <?php echo (!empty($error) || !empty($user)) ? 'none' : 'block'; ?>; text-align: center; padding: 2rem;">
            <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary);"></i>
        </div>
        <div id="errorState" style="display: none;" class="empty-state">
            <i class="fas fa-exclamation-circle" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <p>Something went wrong. Please try again.</p>
            <a href="dashboard.php" class="btn primary" style="margin-top: 1rem;">Go Back</a>
        </div>

        <?php if (!empty($error)): ?>
            <div class="empty-state">
                <i class="fas fa-exclamation-circle" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                <p><?php echo htmlspecialchars($error); ?></p>
                <a href="dashboard.php" class="btn primary" style="margin-top: 1rem;">Go Back</a>
            </div>
        <?php endif; ?>

        <div id="profileContent" class="profile-card" style="display: <?php echo !empty($user) ? 'block' : 'none'; ?>;">
            <div class="profile-header">
                <img id="userAvatar" src="<?php echo htmlspecialchars($user['avatar'] ?? ''); ?>" class="profile-avatar" alt="Profile">
                <div class="profile-info">
                    <h1 id="profileName"><?php echo htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')); ?></h1>
                    <p id="userRole" class="profile-role"><?php echo ucfirst(htmlspecialchars($user['role'] ?? '')); ?></p>
                    <div class="profile-meta">
                        <span><i class="fas fa-envelope"></i> <span id="userEmail"><?php echo htmlspecialchars($user['email'] ?? ''); ?></span></span>
                        <span><i class="fas fa-phone"></i> <span id="userPhone"><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></span></span>
                    </div>
                    <div class="profile-actions" style="margin-top: 1.5rem;">
                        <?php if (isset($_SESSION['user_id']) && isset($user['id']) && $_SESSION['user_id'] != $user['id']): ?>
                            <a href="messages.php?user_id=<?php echo $user['id']; ?>" class="btn primary-action">
                                <i class="fas fa-comment-alt"></i> Send Message
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="profile-skills" style="margin-top: 1.5rem; text-align: center; display: <?php echo (!empty($user['role']) && $user['role'] === 'worker' && !empty($user['skills'])) ? 'block' : 'none'; ?>;" id="skillsSection">
                <h3>Skills</h3>
                <div style="display:flex; flex-wrap:wrap; gap:8px; margin-top:0.5rem; justify-content: center;" id="skillsList">
                    <?php if (!empty($user['skills'])): ?>
                        <?php foreach (explode(',', $user['skills']) as $skill): ?>
                            <span class="badge secondary"><?php echo htmlspecialchars(trim($skill)); ?></span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        
            <div id="workerStats" class="profile-stats" style="display: <?php echo (!empty($user['role']) && $user['role'] === 'worker') ? 'grid' : 'none'; ?>;">
                <div class="stat-box">
                    <div id="jobsCompleted" class="stat-value"><?php echo $stats['completed_jobs'] ?? 0; ?></div>
                    <div class="stat-label">Jobs Completed</div>
                </div>
                <div class="stat-box">
                    <div id="userRating" class="stat-value"><?php echo number_format($stats['rating'] ?? 0, 1); ?></div>
                    <div class="stat-label">Average Rating</div>
                </div>
                <div class="stat-box">
                    <div id="totalEarnings" class="stat-value">৳<?php echo number_format($stats['total_earnings'] ?? 0); ?></div>
                    <div class="stat-label">Total Earnings</div>
                </div>
            </div>

    
            <div id="clientStats" class="profile-stats" style="display: <?php echo (!empty($user['role']) && $user['role'] === 'client') ? 'grid' : 'none'; ?>;">
                <div class="stat-box">
                    <div id="jobsPosted" class="stat-value"><?php echo $stats['jobs_posted'] ?? 0; ?></div>
                    <div class="stat-label">Jobs Posted</div>
                </div>
                <div class="stat-box">
                    <div id="totalSpent" class="stat-value">৳<?php echo number_format($stats['total_spent'] ?? 0); ?></div>
                    <div class="stat-label">Total Spent</div>
                </div>
            </div>

        
            <div id="reviewsSection" class="reviews-section" style="display: <?php echo (!empty($user['role']) && $user['role'] === 'worker') ? 'block' : 'none'; ?>;">
                <h3>Reviews</h3>
                <div id="reviewsList" class="reviews-list">
                    <?php if (!empty($reviews) && count($reviews) > 0): ?>
                        <?php foreach ($reviews as $review): ?>
                            <div class="review-item">
                                <div class="review-header">
                                    <strong>Job: <?php echo htmlspecialchars($review['job_title'] ?? ''); ?></strong>
                                    <span class="review-rating"><i class="fas fa-star"></i> <?php echo $review['rating'] ?? 0; ?></span>
                                </div>
                                <p><?php echo htmlspecialchars($review['comment'] ?? ''); ?></p>
                                <small class="text-muted">Reviewed by <?php echo htmlspecialchars($review['reviewer_name'] ?? ''); ?></small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No reviews yet.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>
</body>

</html>