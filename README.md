# ShobKaaj - Project Simulator

## Project Overview
**ShobKaaj** is a comprehensive job marketplace simulator designed to connect Clients (employers) with Workers (freelancers/employees). The platform facilitates job posting, talent discovery, application management, and real-time communication in a modern, secure, and visually immersive environment.

## Technology Stack
- **Frontend**: HTML5, Vanilla JavaScript, Vanilla CSS (Glassmorphism & Neon Design)
- **Backend**: PHP (MVC Architecture)
- **Database**: MySQL
- **Environment**: XAMPP (Apache Server)

## Key Features

### 1. Authentication & User Management
- **Secure Login & Registration**: Robust authentication system for both Clients and Workers [Docs](Features/AUTH_FEATURE.md).
- **Role-Based Access**: Distinct dashboards and functionalities for Clients, Workers, and Admins.
- **Profile Management**: Users can update personal details, avatars, and passwords [Docs](Features/PROFILE_MANAGEMENT_FEATURE.md).
- **Password Recovery**: Forgot password feature with email verification [Docs](Features/FORGOT_PASSWORD_FEATURE.md).

### 2. Client Features
- **Client Dashboard**: Central hub to view posted jobs, hired workers, and recent activity [Docs](Features/CLIENT_DASHBOARD_FEATURE.md).
- **Post a Job**: Detailed form to create new job listings with descriptions, requirements, and budget [Docs](Features/POST_JOB_FEATURE.md).
- **Find Talent**: Searchable interface to discover and view Worker profiles [Docs](Features/FIND_TALENT_FEATURE.md).
- **My Posted Jobs**: 3-column panel view (Completed, Running, Open) to manage all job postings [Docs](Features/MY_POSTED_JOBS_FEATURE.md).
- **Manage Applications**: Review, hire, and reject proposals received for posted jobs.
- **Complete Job**: Mark jobs as done and submit reviews for workers [Docs](Features/COMPLETE_JOB_FEATURE.md).

### 3. Worker Features
- **Worker Dashboard**: Personalized view of applied jobs, total earnings, and stats [Docs](Features/WORKER_DASHBOARD_FEATURE.md).
- **Find Work**: Browse available job listings with filtering options [Docs](Features/FIND_WORK_FEATURE.md).
- **Job Details & Application**: View complete job details and submit proposals [Docs](Features/JOB_DETAILS_FEATURE.md) [Docs](Features/APPLY_JOB_FEATURE.md).
- **My Jobs**: 3-column panel view (Completed, Running, Applied) to track all applications [Docs](Features/WORKER_MY_JOBS_FEATURE.md).
- **Skill Showcase**: Workers can highlight their skills on their public profiles.

### 4. Admin Features
- **Admin Dashboard**: Manage users, verify accounts, and moderate content [Docs](Features/ADMIN_FEATURES.md).
- **User Management**: Activate, terminate, verify, and unverify user accounts.
- **Platform Statistics**: View overall platform metrics.

### 5. Core Functionality
- **Public Profiles**: Publicly accessible profiles to showcase portfolios and work history.
- **View Profile**: Display user stats, reviews, and skills based on role [Docs](Features/VIEW_PROFILE_FEATURE.md).
- **Unified Design System**: Consistent "Glassmorphism" UI with neon accents.
- **In-App Chat**: Real-time messaging system for Clients and Workers to communicate directly [Docs](Features/IN_APP_CHAT_FEATURE.md).
- **Notifications System**: In-app alerts for job applications, hiring, and payments with unread badges [Docs](Features/NOTIFICATIONS_FEATURE.md).
- **AI Chatbot Assistant**: Intelligent virtual assistant powered by local AI (Ollama) to help users navigate the platform and answer queries [Docs](Features/AI_CHATBOT_FEATURE.md).

## Project Structure
The project follows a modular MVC (Model-View-Controller) structure:

```
project-simulator-ShobKaaj/
├── Features/           # Feature documentation 
├── Management/
│   ├── Admin/          # Admin-specific logic and views
│   │   └── MVC/ (css, html, js, php)
│   ├── Client/         # Client-specific logic and views
│   │   └── MVC/ (css, html, js, php)
│   ├── Worker/         # Worker-specific logic and views
│   │   └── MVC/ (css, html, js, php)
│   └── Shared/         # Common resources (Auth, APIs, Navbar)
│       └── MVC/ (css, html, js, php, db)
└── README.md           # Project documentation
```

## Feature Documentation
All features are documented in the `Features/` directory. Each `.md` file contains:
- **Overview**: Feature summary
- **User Flow**: Step-by-step journey
- **Technical Implementation**: Frontend/backend details
- **Key Components**: UI elements and functionality
- **Security**: Authentication and validation measures
- **URL Structure**: Page routes and parameters

## Setup Instructions
1.  **Database**: Import the provided SQL schema into your MySQL database.
2.  **Config**: Ensure `config.php` has the correct database credentials.
3.  **Run**: Host the project directory in your local server (e.g., `xampp/htdocs`) and access via browser.

## Pages Overview

| Page | Role | Description |
|------|------|-------------|
| `index.php` | Public | Landing page with platform overview |
| `auth.php` | Public | Login and registration |
| `forgotpass.php` | Public | Password recovery |
| `client-dashboard.php` | Client | Client's main dashboard |
| `post-job.php` | Client | Create new job listings |
| `my-posted-jobs.php` | Client | Manage posted jobs |
| `find-talent.php` | Client | Browse worker profiles |
| `complete-job.php` | Client | Complete jobs and submit reviews |
| `worker-dashboard.php` | Worker | Worker's main dashboard |
| `find-work.php` | Worker | Browse available jobs |
| `job-details.php` | Worker | View and apply to jobs |
| `my-jobs.php` | Worker | Track applications and contracts |
| `profile.php` | All | View/edit own profile |
| `view-profile.php` | All | View other users' profiles |
| `messages.php` | All | In-app messaging and conversations |
| `notifications.php` | All | User notification center |
| `admin.php` | Admin | Admin control panel |
| `analytics.php` | Admin | View platform analytics |

