<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Job - ShobKaaj</title>
    <link rel="stylesheet" href="/Practice/ShoobKaj-WEBTECH-Project/Management/Shared/MVC/css/base.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .review-container {
            max-width: 600px;
            margin: 100px auto;
            background: var(--surface);
            padding: var(--space-8);
            border-radius: var(--radius-lg);
            border: 1px solid var(--line);
        }

        .rating-stars {
            display: flex;
            gap: 10px;
            font-size: 2rem;
            color: var(--line);
            cursor: pointer;
            justify-content: center;
            margin: var(--space-4) 0;
        }

        .rating-stars i.active {
            color: #fbbf24;
        }
    </style>
</head>

<body>
    <div id="navbar-container"></div>
    <script src="/Practice/ShoobKaj-WEBTECH-Project/Management/Shared/MVC/js/navbar.js"></script>

    <div class="container">
        <div class="review-container">
            <h2 style="text-align:center; margin-bottom:var(--space-4);">Mark Job as Completed</h2>
            <p style="text-align:center; margin-bottom:var(--space-6); color:var(--text-secondary);">
                Please rate your experience with the worker to complete this job.
            </p>

            <form id="completeJobForm">
                <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($_GET['job_id'] ?? ''); ?>">
                <input type="hidden" name="worker_id" value="<?php echo htmlspecialchars($_GET['worker_id'] ?? ''); ?>">

                <div class="form-group">
                    <label class="label" style="text-align:center;">Rate the Worker</label>
                    <div class="rating-stars" id="starRating">
                        <i class="far fa-star" data-rating="1"></i>
                        <i class="far fa-star" data-rating="2"></i>
                        <i class="far fa-star" data-rating="3"></i>
                        <i class="far fa-star" data-rating="4"></i>
                        <i class="far fa-star" data-rating="5"></i>
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" required>
                </div>

                <div class="form-group">
                    <label class="label">Review / Feedback</label>
                    <textarea name="review" class="input" rows="4" placeholder="Share your experience working with this person..." required></textarea>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn outline" onclick="history.back()">Cancel</button>
                    <button type="submit" class="btn primary">Complete Job</button>
                </div>
            </form>
        </div>
    </div>

    
</body>

</html>