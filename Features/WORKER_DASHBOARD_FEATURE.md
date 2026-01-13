# Worker Dashboard Feature Documentation

## Overview
The **Worker Dashboard** serves as the central hub for Workers to track their job applications, work history, earnings, and overall performance on the platform.

## User Flow
1.  **Login**: Worker logs in and is redirected to the dashboard
2.  **View Stats**: Overview of earnings, completed jobs, and ratings
3.  **Track Applications**: Monitor status of submitted proposals
4.  **View Work History**: See completed and ongoing projects
5.  **Find Work**: Quick access to browse new opportunities

## Technical Implementation

### Frontend
-   **File**: `Management/Worker/MVC/html/worker-dashboard.php`
-   **Styling**: `Management/Worker/MVC/css/worker-dashboard.css`
-   **JavaScript**: `Management/Worker/MVC/js/worker-dashboard.js`

### Backend
-   **API**: `Management/Shared/MVC/php/jobAPI.php`
-   **Actions Used**:
    -   `get_worker_jobs` - Fetch worker's job history
    -   `get_worker_applications` - Fetch submitted applications
    -   `get_worker_stats` - Fetch dashboard statistics

## Key Components

### Welcome Section
-   **Personalized Greeting**: "Welcome back, [Name]!"
-   **Subtitle**: "Here is what's happening with your projects."
-   **Dynamic Action Button**: Links to Find Work page

### Stats Grid
Displays key metrics in card format:
-   **Total Earnings**: Sum of all completed job payments (৳)
-   **Jobs Completed**: Count of successfully finished projects
-   **Average Rating**: Star rating from client reviews
-   **Active Applications**: Count of pending proposals

### Dashboard Split View (2-Column Layout)

#### Left Panel: Work History
-   Lists all jobs the worker has completed or is working on
-   Shows job title, client name, and status
-   Count badge in header
-   Click to navigate to job details

#### Right Panel: My Applications
-   Lists all submitted job proposals
-   Shows job title, bid amount, and application status
-   Status badges (Pending, Accepted, Rejected)
-   Click to view job details

## JavaScript Functions

### `loadDashboard()`
Initializes the dashboard and loads all data.

### `loadStats(workerId)`
Fetches and displays statistics in the stats grid.

### `loadWorkHistory(workerId)`
Fetches and renders the worker's job history.

### `loadApplications(workerId)`
Fetches and renders submitted applications.

## Security
-   **Authentication Check**: Redirects to `auth.php` if not logged in
-   **Role Verification**: Only `worker` role users can access
-   **Data Isolation**: Only shows data belonging to the logged-in worker

## Responsive Design
-   **Desktop**: 2-column grid layout for dashboard sections
-   **Mobile**: Stacked single-column layout

## URL Structure
```
worker-dashboard.php
```
No query parameters required.
