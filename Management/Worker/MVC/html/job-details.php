<?php
session_start();
// Adjust path to config: html is in Worker/MVC/html, config is in Shared/MVC/db
// ../../Shared/MVC/db/config.php
require_once '../../../Shared/MVC/db/config.php';

$bid_amount = $cover_letter = "";
$bid_err = $cover_err = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Get Params
    $job_id = $_GET['id'] ?? null;
    $worker_id = $_SESSION['user_id'] ?? null;

    // 2. Validate Bid
    if (empty($_POST["bid_amount"])) {
        $bid_err = "Please enter your bid amount";
    } else {
        $bid_amount = trim($_POST["bid_amount"]);
    }

    // 3. Validate Cover Letter
    if (empty($_POST["cover_letter"])) {
        $cover_err = "Please write a cover letter";
    } else {
        $cover_letter = trim($_POST["cover_letter"]);
    }

    // 4. Submit if no errors
    if (empty($bid_err) && empty($cover_err) && $job_id && $worker_id) {

        // Prepare Insert
        $sql = "INSERT INTO applications (job_id, worker_id, bid_amount, status, created_at) VALUES (?, ?, ?, 'pending', NOW())";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iis", $job_id, $worker_id, $bid_amount);

            if ($stmt->execute()) {
                $success_msg = "Application submitted successfully!";
                $bid_amount = $cover_letter = ""; // Clear form
            } else {
                $success_msg = "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details - ShobKaaj</title>
    <link rel="stylesheet" href="/project-simulator-ShobKaaj/Management/Shared/MVC/css/base.css">
    <link rel="stylesheet" href="/project-simulator-ShobKaaj/Management/Worker/MVC/css/job-details.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <div id="navbar-container"></div>

    <!-- Main Content Container -->
    <div class="container">

        <!-- Loading State -->
        <div id="loadingState" style="text-align:center; padding: 40px;">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
        </div>

        <div class="details-grid" id="mainContent" style="display:none;">

            <!-- Left: Main Job Card -->
            <div class="job-main-card">

                <!-- Internal Header -->
                <div class="card-header-content">
                    <div class="job-meta-tags">
                        <span class="badge primary" id="jobCategory">Category</span>
                        <span class="badge" id="jobTime">Posted 2d ago</span>
                    </div>

                    <h1 id="jobTitle" class="card-title">Job Title placeholder</h1>

                    <div class="client-mini-profile">
                        <img src="/project-simulator-ShobKaaj/Management/Shared/MVC/images/logo.png" id="clientAvatar" class="avatar-sm">
                        <div>
                            <div class="client-name" id="clientName">Client Name</div>
                            <div class="client-loc" id="jobLocation"><i class="fas fa-map-marker-alt"></i> Location</div>
                        </div>
                    </div>
                </div>

                <div class="card-divider"></div>

                <!-- Description -->
                <div class="job-description-area">
                    <h3>Project Description</h3>
                    <div class="desc-text" id="jobDescription">
                        Loading description...
                    </div>
                </div>
            </div>

            <!-- Right: Application Form -->
            <aside>
                <div class="application-card">
                    <div class="budget-display">
                        <div class="small">Fixed Budget</div>
                        <div class="budget-amount" id="jobBudget">৳0</div>
                    </div>

                    <form id="applicationForm" method="POST" action="">
                        <!-- action empty submits to same URL including GET params -->

                        <?php if (!empty($success_msg)): ?>
                            <div style="color:var(--success); text-align:center; margin-bottom:10px;"><?php echo $success_msg; ?></div>
                        <?php endif; ?>

                        <h4 style="margin-bottom:var(--space-4);">Submit a Proposal</h4>

                        <div class="form-group">
                            <label class="label">Your Bid Amount</label>
                            <div class="currency-input">
                                <span class="currency-symbol">৳</span>
                                <input type="number" id="bidAmount" name="bid_amount" class="input" placeholder="0.00" value="<?php echo htmlspecialchars($bid_amount ?? ''); ?>">
                            </div>
                            <span style="color:var(--error); font-size:0.85rem;"><?php echo $bid_err ?? ''; ?></span>
                        </div>

                        <div class="form-group">
                            <label class="label">Cover Letter</label>
                            <textarea id="coverLetter" name="cover_letter" class="input" rows="6" placeholder="Describe why you are a good fit..."><?php echo htmlspecialchars($cover_letter ?? ''); ?></textarea>
                            <span style="color:var(--error); font-size:0.85rem;"><?php echo $cover_err ?? ''; ?></span>
                        </div>

                        <button type="submit" class="btn primary lg" style="width:100%;">
                            Submit Application
                        </button>
                    </form>

                    <!-- Removed applyMessage div as we use PHP display now -->
                </div>
            </aside>

        </div>
    </div>

    <script src="/project-simulator-ShobKaaj/Management/Shared/MVC/js/utils.js"></script>
    <script src="/project-simulator-ShobKaaj/Management/Shared/MVC/js/navbar.js"></script>
    <script src="../js/job-details.js"></script>
</body>

</html>