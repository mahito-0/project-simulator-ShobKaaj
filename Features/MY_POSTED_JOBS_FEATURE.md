# My Posted Jobs Feature Documentation

## Overview
The **My Posted Jobs** feature allows Clients to manage and track all their job postings in one centralized dashboard. Jobs are categorized into three panels based on their status: Completed, Running, and Open, providing a clear overview of the client's hiring activities.

## User Flow
1.  **Access Dashboard**: A Client navigates to the "My Jobs" page from the navbar or dashboard.
2.  **View Categories**: Jobs are automatically sorted into three columns:
    - **Completed**: Jobs that have been finished and closed.
    - **Running**: Jobs currently in progress with a hired worker.
    - **Open**: Jobs that are still accepting applications.
3.  **Manage Jobs**: Clicking on any job card redirects to the job management page.
4.  **Post New Job**: A "Post New Job" button in the header allows quick access to create new listings.

## Technical Implementation

### Frontend
-   **File**: `Management/Client/MVC/html/my-posted-jobs.php`
-   **Styling**: `Management/Client/MVC/css/my-posted-jobs.css`
-   **Dynamic Loading**: JavaScript (`my-posted-jobs.js`) fetches job data via `jobAPI.php` and categorizes them by status.

### Backend
-   **API Endpoint**: `Management/Shared/MVC/php/jobAPI.php`
-   **Action**: `my_jobs` with `client_id` parameter
-   **Database Query**: Fetches all jobs where `client_id` matches the logged-in user.

## Key Components

### Page Header
-   **Title**: "My Jobs"
-   **Subtitle**: "Manage your job postings and proposals"
-   **Action Button**: "Post New Job" - links to `post-job.php`

### Three-Panel Grid Layout
The page uses a responsive 3-column grid (`panels-grid`) that stacks on mobile devices.

#### Completed Panel
-   **Icon**: `fa-check-circle` (green)
-   **Content**: Jobs with `status = 'completed'`
-   **Count Badge**: Displays total number of completed jobs

#### Running Panel
-   **Icon**: `fa-briefcase` (blue)
-   **Content**: Jobs with `status = 'in_progress'`
-   **Count Badge**: Displays total number of active jobs

#### Open Panel
-   **Icon**: `fa-paper-plane` (yellow)
-   **Content**: Jobs with `status = 'open'`
-   **Count Badge**: Displays total number of open listings

### Job Card Component
Each job is displayed as a card with:
-   **Title**: Job name
-   **Meta Information**:
    -   Client name
    -   Posted date
-   **Footer**:
    -   Budget amount (৳)
    -   Status badge (color-coded)

### Status Badges
| Status | Background | Border | Text Color |
|--------|------------|--------|------------|
| Completed | `--status-completed-bg` | `--status-completed-border` | `--status-completed-text` |
| In Progress | `--status-assigned-bg` | `--status-assigned-border` | `--status-assigned-text` |
| Open | `--status-open-bg` | `--status-open-border` | `--status-open-text` |

## JavaScript Functions

### `loadMyJobs(clientId)`
Fetches all jobs for the client and categorizes them into completed, running, and open arrays.

### `renderJobList(container, jobs, categoryType, countElement)`
Renders job cards into the specified panel container and updates the count badge.

### `createJobCard(job, categoryType)`
Creates a DOM element for a single job card with appropriate styling and click handler.

### `showEmptyState(container, msg)`
Displays a friendly message when a category has no jobs.

### `showErrorState(containers, message)`
Displays an error message across all panels if the API call fails.

## Security
-   **Authentication Check**: Redirects to `auth.php` if no user is logged in.
-   **Role Verification**: Only allows `client` role users; others are redirected to `dashboard.php`.
-   **Session Storage**: User data is retrieved from `localStorage`.

## Responsive Design
-   **Desktop (>1024px)**: 3-column grid layout
-   **Tablet/Mobile (≤1024px)**: Single column stacked layout

## Empty States
Each panel displays a styled empty state with:
-   Folder icon (`fa-folder-open`)
-   Descriptive message (e.g., "No completed jobs found")
-   Dashed border styling

## URL Structure
```
my-posted-jobs.php
```
No query parameters required; client ID is retrieved from localStorage.
