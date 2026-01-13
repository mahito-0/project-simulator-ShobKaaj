# Worker My Jobs Feature Documentation

## Overview
The **Worker My Jobs** feature allows Workers to track all their job applications and contracts in one centralized view. Jobs are categorized into three panels: Completed, Running, and Applied.

## User Flow
1.  **Access**: Worker navigates to "My Jobs" from navbar
2.  **View Completed**: See all successfully finished jobs
3.  **View Running**: See jobs currently in progress
4.  **View Applied**: Track pending applications
5.  **Manage**: Click on any job to view details

## Technical Implementation

### Frontend
-   **File**: `Management/Worker/MVC/html/my-jobs.php`
-   **Styling**: `Management/Worker/MVC/css/my-jobs.css`
-   **JavaScript**: `Management/Worker/MVC/js/my-jobs.js`

### Backend
-   **API**: `Management/Shared/MVC/php/jobAPI.php`
-   **Action**: `get_categorized_worker_jobs`

## Key Components

### Page Header
-   **Title**: "My Jobs"
-   **Subtitle**: "Track your applications and active contracts"

### Three-Panel Grid Layout

#### Completed Panel
-   **Icon**: `fa-check-circle` (green)
-   **Content**: Jobs with `status = 'completed'`
-   **Count Badge**: Total completed jobs

#### Running Panel
-   **Icon**: `fa-briefcase` (blue)
-   **Content**: Jobs with `status = 'in_progress'` where worker is hired
-   **Count Badge**: Total active jobs

#### Applied Panel
-   **Icon**: `fa-paper-plane` (yellow)
-   **Content**: Pending job applications
-   **Count Badge**: Total pending applications
-   **Status Badges**: Pending, Accepted, Rejected

### Job Card Component
-   **Title**: Job name
-   **Client Name**: Who posted the job
-   **Date**: Posted or applied date
-   **Budget**: Payment amount (৳)
-   **Status Badge**: Color-coded status

## JavaScript Functions

### `loadMyJobs(workerId)`
Fetches and categorizes all worker jobs.

### `renderJobList(container, jobs, categoryType, countElement)`
Renders job cards into the panel.

### `createJobCard(job, categoryType)`
Creates a single job card element.

## Security
-   **Authentication**: Redirects if not logged in
-   **Role Check**: Only workers can access
-   **Data Isolation**: Only shows worker's own data

## Responsive Design
-   **Desktop (>1024px)**: 3-column grid
-   **Mobile (≤1024px)**: Stacked single column

## URL Structure
```
my-jobs.php
```
No query parameters required.
