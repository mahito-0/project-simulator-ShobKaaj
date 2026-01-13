<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ShobKaaj - Find Work, Get Work Done</title>
    <meta name="description"
        content="Bangladesh's premier job marketplace connecting skilled workers with clients. Find local services, post jobs, and build your freelance career.">
    <link rel="stylesheet" href="../css/base.css" />
    <link rel="stylesheet" href="../css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="../js/navbar.js"></script>

</head>

<body>
    <!-- Navbar will be injected by navbar.js -->

    <main>
        <!-- Enhanced Hero Section -->
        <section class="hero-section" id="home">
            <div class="hero-background">

                <div class="hero-container">
                    <div class="hero-content">

                        <h1 class="hero-title">
                            <span class="title-line">Find Work.</span>
                            <span class="title-line">Get Work Done.</span>
                            <span class="title-subtitle">One Platform for All Your Needs</span>
                        </h1>

                        <p class="hero-description">
                            Connect with skilled professionals or find your next opportunity in Bangladesh's
                            fastest-growing job marketplace.
                        </p>

                        <div class="hero-actions">
                            <a href="/project-simulator-ShobKaaj/Management/Client/MVC/html/find-talent.php" class="btn primary large">
                                <span class="btn-text">Hire Talent</span>
                                <span class="btn-icon">→</span>
                            </a>
                            <a href="/project-simulator-ShobKaaj/Management/Worker/MVC/html/find-work.php" class="btn secondary large">
                                <span class="btn-text">Find Work</span>
                                <span class="btn-icon">🔍</span>
                            </a>
                        </div>

                        <div class="trust-indicators">
                            <div class="trust-item">
                                <span class="trust-icon">✅</span>
                                <span>Verified Profiles</span>
                            </div>
                            <div class="trust-item">
                                <span class="trust-icon">🛡️</span>
                                <span>Secure Payments</span>
                            </div>
                            <div class="trust-item">
                                <span class="trust-icon">📍</span>
                                <span>Local Network</span>
                            </div>
                            <div class="trust-item">
                                <span class="trust-icon">🇧🇩</span>
                                <span>Bangla-First</span>
                            </div>
                        </div>
                    </div>
                </div>
        </section>

        <!-- Interactive Services Showcase -->
        <section class="services-showcase" id="services">
            <div class="container">
                <div class="section-header">
                    <div class="section-badge">
                        <span class="badge-icon">🛠️</span>
                        <span>Our Services</span>
                    </div>
                    <h2 class="section-title">Popular Job Categories</h2>
                    <p class="section-description">From household tasks to professional services, find the right help
                        for any job.</p>
                </div>

                <div class="services-grid">
                    <div class="service-card" data-category="tutoring">
                        <div class="service-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3 class="service-title">Tutoring</h3>
                        <p class="service-description">Academic support and skill development</p>
                        <div class="service-stats">
                            <span class="stat">250+ tutors</span>
                            <span class="stat-divider">•</span>
                            <span class="stat">৳500-2000/hr</span>
                        </div>
                        <div class="service-hover-content">
                            <div class="popular-jobs">
                                <span class="popular-job">Math Tutoring</span>
                                <span class="popular-job">English Classes</span>
                                <span class="popular-job">Computer Skills</span>
                            </div>
                            <a href="/project-simulator-ShobKaaj/Management/Worker/MVC/html/find-work.php?category=tutoring" class="service-cta">Browse Tutoring Jobs</a>
                        </div>
                    </div>

                    <div class="service-card" data-category="delivery">
                        <div class="service-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h3 class="service-title">Delivery</h3>
                        <p class="service-description">Fast and reliable delivery services</p>
                        <div class="service-stats">
                            <span class="stat">400+ drivers</span>
                            <span class="stat-divider">•</span>
                            <span class="stat">৳50-500/trip</span>
                        </div>
                        <div class="service-hover-content">
                            <div class="popular-jobs">
                                <span class="popular-job">Food Delivery</span>
                                <span class="popular-job">Package Pickup</span>
                                <span class="popular-job">Document Courier</span>
                            </div>
                            <a href="/project-simulator-ShobKaaj/Management/Worker/MVC/html/find-work.php?category=delivery" class="service-cta">Browse Delivery Jobs</a>
                        </div>
                    </div>

                    <div class="service-card" data-category="repairs">
                        <div class="service-icon">
                            <i class="fas fa-wrench"></i>
                        </div>
                        <h3 class="service-title">Repairs</h3>
                        <p class="service-description">Fix and maintain your devices</p>
                        <div class="service-stats">
                            <span class="stat">180+ technicians</span>
                            <span class="stat-divider">•</span>
                            <span class="stat">৳200-3000/job</span>
                        </div>
                        <div class="service-hover-content">
                            <div class="popular-jobs">
                                <span class="popular-job">Phone Repair</span>
                                <span class="popular-job">AC Service</span>
                                <span class="popular-job">Plumbing</span>
                            </div>
                            <a href="/project-simulator-ShobKaaj/Management/Worker/MVC/html/find-work.php?category=repairs" class="service-cta">Browse Repair Jobs</a>
                        </div>
                    </div>

                    <div class="service-card" data-category="household">
                        <div class="service-icon">
                            <i class="fas fa-house"></i>
                        </div>
                        <h3 class="service-title">Household</h3>
                        <p class="service-description">Home cleaning and maintenance</p>
                        <div class="service-stats">
                            <span class="stat">320+ cleaners</span>
                            <span class="stat-divider">•</span>
                            <span class="stat">৳800-2500/day</span>
                        </div>
                        <div class="service-hover-content">
                            <div class="popular-jobs">
                                <span class="popular-job">House Cleaning</span>
                                <span class="popular-job">Cooking</span>
                                <span class="popular-job">Gardening</span>
                            </div>
                            <a href="/project-simulator-ShobKaaj/Management/Worker/MVC/html/find-work.php?category=household" class="service-cta">Browse Household Jobs</a>
                        </div>
                    </div>

                    <div class="service-card featured" data-category="all">
                        <div class="service-icon">
                            <i class="fas fa-th"></i>
                        </div>
                        <h3 class="service-title">View All Categories</h3>
                        <p class="service-description">Explore 50+ job categories</p>
                        <div class="service-stats">
                            <span class="stat">1,200+ workers</span>
                            <span class="stat-divider">•</span>
                            <span class="stat">24/7 support</span>
                        </div>
                        <div class="service-hover-content">
                            <div class="popular-jobs">
                                <span class="popular-job">Photography</span>
                                <span class="popular-job">Event Planning</span>
                                <span class="popular-job">Data Entry</span>
                            </div>
                            <a href="/project-simulator-ShobKaaj/Management/Worker/MVC/html/find-work.php" class="service-cta primary">Browse All Jobs</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section class="how-it-works" id="about">
            <div class="container">
                <div class="section-header">
                    <div class="section-badge">
                        <span class="badge-icon">⚙️</span>
                        <span>How It Works</span>
                    </div>
                    <h2 class="section-title">How It Works</h2>
                    <p class="section-description">Simple 3-step process to connect workers and clients</p>
                </div>

                <div class="process-steps">
                    <div class="process-track">
                        <div class="track-line"></div>
                        <div class="track-progress"></div>
                    </div>

                    <div class="step-cards">
                        <div class="step-card" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <div class="step-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <h3 class="step-title">Post or Browse</h3>
                                <p class="step-description">Post a job with your budget and requirements, or browse
                                    available opportunities near you.</p>
                                <div class="step-features">
                                    <span class="feature">✓ Set your budget</span>
                                    <span class="feature">✓ Location-based matching</span>
                                    <span class="feature">✓ Category selection</span>
                                </div>
                            </div>
                        </div>

                        <div class="step-card" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <div class="step-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3 class="step-title">Connect & Agree</h3>
                                <p class="step-description">Review applications, check ratings, and use in-app chat to
                                    finalize job details.</p>
                                <div class="step-features">
                                    <span class="feature">✓ Secure messaging</span>
                                    <span class="feature">✓ Profile verification</span>
                                    <span class="feature">✓ Rating system</span>
                                </div>
                            </div>
                        </div>

                        <div class="step-card" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <div class="step-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <h3 class="step-title">Complete & Pay</h3>
                                <p class="step-description">Get the job done, receive payment securely, and leave
                                    feedback for future users.</p>
                                <div class="step-features">
                                    <span class="feature">✓ Secure payments</span>
                                    <span class="feature">✓ Job tracking</span>
                                    <span class="feature">✓ Mutual feedback</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- Top categories -->
        <section class="section">
            <h2>Top Categories</h2>
            <div class="chips">
                <span class="chip">Tutoring</span><span class="chip">Delivery</span><span class="chip">Housekeeping</span>
                <span class="chip">Electrical</span><span class="chip">Plumbing</span><span class="chip">Device Repair</span>
                <span class="chip">Design</span><span class="chip">Photography</span><span class="chip">Event Setup</span><span class="chip">Data Entry</span>
            </div>
        </section>

        <!-- Why ShobKaaj -->
        <section class="section">
            <h2>Why Choose ShobKaaj?</h2>
            <div class="benefits-layout">
                <div class="grid cards benefits-grid">
                    <div class="card">
                        <h3>Location-Based Matching</h3>
                        <p class="small">Find work and workers in your local area instantly.</p>
                    </div>
                    <div class="card">
                        <h3>Verified Profiles</h3>
                        <p class="small">Trust through phone verification and a robust rating system.</p>
                    </div>
                    <div class="card">
                        <h3>User-Friendly Interface</h3>
                        <p class="small">Simple and accessible design for everyone, everywhere.</p>
                    </div>
                    <div class="card">
                        <h3>Flexible Income</h3>
                        <p class="small">Opportunities for students, freelancers, and professionals to earn.</p>
                    </div>
                </div>
                <div class="benefits-image">
                    <img src="../images/why_choose_feature.png" alt="ShobKaaj Community">
                </div>
            </div>
        </section>

        <!-- Safety & Trust -->
        <section class="section">
            <h2>Safety & Trust</h2>
            <div class="grid cards">
                <div class="card">
                    <h3>Secure Chat</h3>
                    <p class="small">In-app messaging to keep your personal contact details private.</p>
                </div>
                <div class="card">
                    <h3>Ratings & Reviews</h3>
                    <p class="small">Feedback system to reward good work and ensure quality.</p>
                </div>
                <div class="card">
                    <h3>Secure Payments</h3>
                    <p class="small">Integrated digital payment options for safe transactions.</p>
                </div>
            </div>
        </section>

        <!-- For Clients and Workers -->
        <section id="services-more" class="section">
            <div class="grid cards">
                <div class="card card-overlay">
                    <img src="../images/client_image.png" alt="Happy Client" class="card-bg-img">
                    <div class="card-content">
                        <h3>For Clients</h3>
                        <ul>
                            <li>Post jobs with budget and deadlines</li>
                            <li>View applicant skills and ratings</li>
                            <li>Chat and assign tasks easily</li>
                        </ul>
                        <div class="form-actions">
                            <a href="/project-simulator-ShobKaaj/Management/Client/MVC/html/post-job.php" class="btn">Post a Job</a>
                            <a href="/project-simulator-ShobKaaj/Management/Client/MVC/html/find-talent.php" class="btn outline">Find Workers</a>
                        </div>
                    </div>
                </div>
                <div class="card card-overlay">
                    <img src="../images/worker_image.png" alt="Skilled Worker" class="card-bg-img">
                    <div class="card-content">
                        <h3>For Workers</h3>
                        <ul>
                            <li>Browse local jobs and apply</li>
                            <li>Keep your profile and skills updated</li>
                            <li>Track assigned tasks</li>
                        </ul>
                        <div class="form-actions">
                            <a href="/project-simulator-ShobKaaj/Management/Worker/MVC/html/find-work.php" class="btn">Browse Jobs</a>
                            <a href="profile.php" class="btn outline">Update Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQs -->
        <section class="section">
            <h2>Frequently Asked Questions</h2>
            <div class="small-cards">
                <details class="card small-card">
                    <summary><strong>How does verification work?</strong></summary>
                    <p class="small">We build trust through phone/NID verification and a community rating system.</p>
                </details>
                <details class="card small-card">
                    <summary><strong>Are payments secure?</strong></summary>
                    <p class="small">Yes, we support digital wallets and are working on escrow services for added safety.</p>
                </details>
                <details class="card small-card">
                    <summary><strong>Is this service available everywhere?</strong></summary>
                    <p class="small">We are expanding rapidly and focusing on providing a localized experience for all users.</p>
                </details>
            </div>
        </section>

        <!-- Promo CTA -->
        <section class="section">
            <div class="promo reveal">
                <div>
                    <h3>Ready to get started?</h3>
                    <p class="small">Join thousands of others getting work done today.</p>
                </div>
                <a id="getNow" href="auth.php" class="btn">Get Started</a>
            </div>
        </section>

        <!-- Footer -->
        <footer id="contact" class="site-footer">
            <div class="footer-content">
                <div class="footer-section about">
                    <h4>ShobKaaj</h4>
                    <p>Connecting skilled professionals with clients across Bangladesh. Find local services, post jobs,
                        and build your freelance career with ease and security.</p>
                    <div class="contact-info">
                        <p><i class="fas fa-envelope"></i> info@shobkaaj.com</p>
                    </div>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="footer-section links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="/project-simulator-ShobKaaj/Management/Worker/MVC/html/find-work.php">Browse Jobs</a></li>
                        <li><a href="/project-simulator-ShobKaaj/Management/Client/MVC/html/find-talent.php">Find Workers</a></li>
                        <li><a href="/project-simulator-ShobKaaj/Management/Client/MVC/html/post-job.php">Post a Job</a></li>
                        <li><a href="auth.php">Join Us</a></li>
                    </ul>
                </div>
                <div class="footer-section resources">
                    <h4>Resources</h4>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Safety Guidelines</a></li>
                        <li><a href="#">How It Works</a></li>
                        <li><a href="#">FAQs</a></li>
                    </ul>
                </div>
                <div class="footer-section legal">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 ShobKaaj. All rights reserved.</p>
            </div>
        </footer>
    </main>

    <div id="shobkaaj-chat-widget">

        <button id="chat-toggle-btn" onclick="toggleChat()">
            <i class="fas fa-robot"></i>
        </button>

        <div id="chat-window" class="chat-hidden">
            <div class="chat-header">
                <div class="header-info">
                    <span class="bot-avatar">🤖</span>
                    <div>
                        <strong>ShobKaaj AI</strong>
                        <span class="status">Online</span>
                    </div>
                </div>
                <button class="close-btn" onclick="toggleChat()">×</button>
            </div>

            <div id="chat-messages">
                <div class="message bot-msg">
                    Welcome to ShobKaaj! Need help finding a tutor, repairman, or job?
                </div>
                <div id="chat-loader" class="message bot-msg loading-dots" style="display: none;">
                    <span>•</span><span>•</span><span>•</span>
                </div>
            </div>

            <div class="chat-input-area">
                <input type="text" id="chat-input" placeholder="Ask about jobs..." onkeypress="handleEnter(event)">
                <button id="send-btn" onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>
    <script src="../js/chat.js"></script>
</body>

</html>