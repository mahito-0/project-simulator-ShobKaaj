<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Job - ShobKaaj</title>

    <link rel="stylesheet" href="/project-simulator-ShobKaaj/Management/Shared/MVC/css/base.css">
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
            cursor: pointer;
            justify-content: center;
            margin: var(--space-4) 0;
        }

        .rating-stars i.far {
            color: transparent;
            -webkit-text-stroke: 1px #c5aa22ff;
        }

        .rating-stars i.active {
            color: #c5a222ff;
            -webkit-text-stroke: 0;
        }
    </style>
</head>

<body>

    <div id="navbar-container"></div>
    <script src="/project-simulator-ShobKaaj/Management/Client/MVC/js/navbar.js"></script>

    <div class="container">
        <div class="review-container">

            <h2 style="text-align:center; margin-bottom:var(--space-4);">
                Mark Job as Completed
            </h2>

            <p style="text-align:center; margin-bottom:var(--space-6); color:var(--text-secondary);">
                Please rate your experience with the worker to complete this job.
            </p>

            <form id="completeJobForm" method="POST">
                <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($_GET['job_id'] ?? ''); ?>">
                <input type="hidden" name="worker_id" value="<?php echo htmlspecialchars($_GET['worker_id'] ?? ''); ?>">
                <input type="hidden" name="rating" id="ratingInput">

                <div class="form-group">
                    <label class="label" style="text-align:center;">Rate the Worker</label>

                    <div class="rating-stars" id="starRating">
                        <i class="far fa-star" data-rating="1"></i>
                        <i class="far fa-star" data-rating="2"></i>
                        <i class="far fa-star" data-rating="3"></i>
                        <i class="far fa-star" data-rating="4"></i>
                        <i class="far fa-star" data-rating="5"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label">Review / Feedback</label>
                    <textarea name="comment" class="input" rows="4"
                        placeholder="Share your experience working with this person..." required></textarea>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn outline" onclick="history.back()">Cancel</button>
                    <button type="submit" class="btn primary">Complete Job</button>
                </div>
            </form>

        </div>
    </div>

    <script src="/project-simulator-ShobKaaj/Management/Client/MVC/js/utils.js"></script>

    <script>
        const stars = document.querySelectorAll('.rating-stars i');
        const ratingInput = document.getElementById('ratingInput');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = star.dataset.rating;
                ratingInput.value = rating;
                updateStars(rating);
            });
        });

        function updateStars(rating) {
            stars.forEach(star => {
                if (star.dataset.rating <= rating) {
                    star.classList.remove('far');
                    star.classList.add('fas', 'active');
                } else {
                    star.classList.remove('fas', 'active');
                    star.classList.add('far');
                }
            });
        }

        document.getElementById('completeJobForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            // Guard: Get logged-in user
            const storedUser = localStorage.getItem('user');
            const user = storedUser ? JSON.parse(storedUser) : null;

            if (!user || user.role !== 'client') {
                alert('You must be logged in as a client to perform this action.');
                return;
            }

            const formData = new FormData(e.target);
            // Append the missing reviewer_id
            formData.append('reviewer_id', user.id);

            try {
                const response = await fetch('../../../Shared/MVC/php/jobAPI.php?action=complete_job', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    alert('Job completed successfully!');
                    window.location.href = 'client-dashboard.php';
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });
    </script>

</body>

</html>