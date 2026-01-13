# Client Dashboard Feature Documentation

## Overview
The **Client Dashboard** serves as the central hub for Clients to manage their hiring activities. It provides an overview of posted jobs, received proposals, and key statistics at a glance.

## User Flow
1.  **Login**: Client logs in and is redirected to the dashboard
2.  **View Stats**: Quick overview of hiring metrics
3.  **Manage Jobs**: See all posted jobs and their statuses
4.  **Review Proposals**: View and act on received applications
5.  **Quick Actions**: Post new jobs directly from the dashboard

## Technical Implementation

### Frontend
-   **File**: `Management/Client/MVC/html/client-dashboard.php`
-   **Styling**: `Management/Worker/MVC/css/worker-dashboard.css` (shared)
-   **JavaScript**: `Management/Client/MVC/js/client-dashboard.js`

### Backend
-   **API**: `Management/Shared/MVC/php/jobAPI.php`
-   **Actions Used**:
    -   `my_jobs` - Fetch client's posted jobs
    -   `get_client_applications` - Fetch received proposals
    -   `get_client_stats` - Fetch dashboard statistics

## Key Components

### Welcome Section
-   **Personalized Greeting**: "Welcome back, [Name]!"
-   **Subtitle**: "Here is what's happening with your projects."
-   **Primary Action**: "Post a New Job" button

### Stats Grid
Displays key metrics in card format:
-   **Total Jobs Posted**: Count of all job listings
-   **Active Jobs**: Jobs currently open or in progress
-   **Total Spent**: Sum of completed job budgets (৳)
-   **Proposals Received**: Count of applications from workers

### Dashboard Split View (2-Column Layout)

#### Left Panel: My Posted Jobs
-   Lists all jobs posted by the client
-   Shows job title, status badge, and budget
-   Count badge in header
-   Click to navigate to job details

#### Right Panel: Received Proposals
-   Lists all applications from workers
-   Shows worker name, job title, and bid amount
-   Status badges (Pending, Accepted, Rejected)
-   Click to view worker profile

## JavaScript Functions

### `loadDashboard()`
Initializes the dashboard and loads all data.

### `loadStats(clientId)`
Fetches and displays statistics in the stats grid.

### `loadPostedJobs(clientId)`
Fetches and renders the client's job listings.

### `loadApplications(clientId)`
Fetches and renders received proposals.

## Security
-   **Authentication Check**: Redirects to `auth.php` if not logged in
-   **Role Verification**: Only `client` role users can access
-   **Data Isolation**: Only shows data belonging to the logged-in client

## Responsive Design
-   **Desktop**: 2-column grid layout for dashboard sections
-   **Mobile**: Stacked single-column layout

## URL Structure
```
client-dashboard.php
```
No query parameters required.
