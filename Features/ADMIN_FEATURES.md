# Admin Dashboard Features

## Overview
The Admin Dashboard provides a centralized interface for administrators to manage users, monitor platform activity, and oversee job postings. It is secured with role-based authentication and restricted access.

## Access & Security
- **Role-Based Access**: Only users with the `admin` role can access the dashboard.
- **Login Redirection**: Admins are automatically redirected to `admin-dashboard.php` upon login.
- **Unauthorized Access Prevention**: Direct access attempts by non-admins redirect to the login page.
- **Terminated User Block**: Users with a 'terminated' status are blocked from logging in.

## Dashboard Statistics
The main overview page displays key platform metrics:
- **Total Users**: Count of all registered clients and workers.
- **Verified Users**: Count of users who have been confirmed by admin.
- **Terminated Users**: Count of users who have been banned/terminated.
- **Total Jobs**: Count of all jobs posted on the platform.

## User Management
Admins have full control over user accounts via the "User Management" view.

### Features:
1.  **List Users**: View all registered users (Clients & Workers) with their details (ID, Name, Email, Role, Status, Verification).
2.  **Verify Users**:
    - Mark a user as **Verified** (Displays a green badge).
    - **Unverify** a user (Reverts to standard status).
3.  **Terminate Users**:
    - **Terminate** a user account (Sets status to 'terminated', blocks login, displays red badge).
    - **Activate** a previously terminated user (Restores access).

## Job Management
Admins can oversee all job postings on the platform via the "Job Management" view.

### Features:
1.  **List Jobs**: View all jobs including Title, Client Name, Budget, Status, and Posted Date.
2.  **Delete Jobs**: Permanently remove a job posting from the system (e.g., for violation of terms or spam).

## Technical Implementation
- **Frontend**: Built with vanilla JavaScript (`admin.js`) for dynamic loading of views and handling API requests.
- **Backend**: Powered by `adminAPI.php` which handles secure database operations.
- **Database**: Uses `users` and `jobs` tables, with specific ENUM types for `status` and `is_verified`.
