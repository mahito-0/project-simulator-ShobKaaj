<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a New Job - ShobKaaj</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="/project-simulator-ShobKaaj/Management/Shared/MVC/css/base.css">
    <link rel="stylesheet" href="/project-simulator-ShobKaaj/Management/Client/MVC/css/post-job.css">

</head>

<body>

    <div id="navbar-container"></div>

    <main class="container" style="margin-top: 100px; max-width: 800px;">
        <div class="card" style="padding: 2rem;">
            <div class="form-header" style="margin-bottom: 2rem;">
                <h1>Post a New Job</h1>
                <p style="color: var(--text-secondary);">Fill in the details to find the best talent.</p>
            </div>

            <form id="jobForm">
                <div class="form-group">
                    <label class="label">Job Title</label>
                    <input type="text" name="title" class="input" placeholder="e.g. Need a Web Developer for E-commerce site" required>
                </div>

                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="label">Category</label>
                        <select name="category" class="input" required style="cursor: pointer;">
                            <option value="" disabled selected>Select Category</option>
                            <option value="Web Development">Web Development</option>
                            <option value="Graphic Design">Graphic Design</option>
                            <option value="Digital Marketing">Digital Marketing</option>
                            <option value="Writing">Writing</option>
                            <option value="Data Entry">Data Entry</option>
                            <option value="IT Support">IT Support</option>
                            <option value="tutoring">Tutoring</option>
                            <option value="delivery">Delivery</option>
                            <option value="repairs">Repairs</option>
                            <option value="household">Household</option>
                            <option value="design">Design & Creative</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="label">Budget (৳)</label>
                        <div class="currency-input">
                            <span class="currency-symbol">৳</span>
                            <input type="number" name="budget" class="input" placeholder="5000" min="100" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label">Description</label>
                    <textarea name="description" class="input" rows="6" placeholder="Describe the project requirements, deliverables, and timeline..." required></textarea>
                </div>

                <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
                    <a href="client-dashboard.php" class="btn outline">Cancel</a>
                    <button type="submit" class="btn primary btn-submit">
                        Post Job Now <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Scripts -->
    <script src="/project-simulator-ShobKaaj/Management/Shared/MVC/js/utils.js"></script>
    <script src="/project-simulator-ShobKaaj/Management/Shared/MVC/js/navbar.js"></script>
    <script src="../js/post-job.js" defer></script>
</body>

</html>