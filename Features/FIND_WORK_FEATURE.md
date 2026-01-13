# Find Work Feature Documentation

## Overview
The **Find Work** feature allows Workers to browse and discover available job listings posted by Clients. It provides a searchable interface with filtering options to find relevant opportunities.

## User Flow
1.  **Access**: Worker navigates to "Find Work" from navbar
2.  **Browse Jobs**: View list of open job postings
3.  **Search/Filter**: Use search bar or category filters
4.  **View Details**: Click on a job card to see full details
5.  **Apply**: Submit a proposal for the job

## Technical Implementation

### Frontend
-   **File**: `Management/Worker/MVC/html/find-work.php`
-   **Styling**: `Management/Worker/MVC/css/find-work.css`
-   **JavaScript**: `Management/Worker/MVC/js/find-work.js`

### Backend
-   **API**: `Management/Shared/MVC/php/jobAPI.php`
-   **Action**: `get_open_jobs` or `search_jobs`
-   **Database Table**: `jobs` (where `status = 'open'`)

## Key Components

### Search Bar
-   Text input for keyword search
-   Searches by job title, description, or category

### Filter Options
-   **Category**: Filter by job category
-   **Budget Range**: Filter by minimum/maximum budget
-   **Location**: Filter by job location
-   **Posted Date**: Sort by newest/oldest

### Job Cards
Each job is displayed as a card showing:
-   **Title**: Job name
-   **Client Info**: Client name and avatar
-   **Budget**: Payment amount (৳)
-   **Category**: Job category badge
-   **Location**: Job location
-   **Posted Date**: When the job was created
-   **Description Preview**: Truncated description
-   **Apply Button**: Links to job details page

### Empty State
Displayed when no jobs match the search criteria:
-   Icon and friendly message
-   Suggestion to adjust filters

## JavaScript Functions

### `loadJobs()`
Fetches and displays all open jobs.

### `searchJobs(query)`
Searches jobs by keyword.

### `filterJobs(filters)`
Applies filter criteria to the job list.

### `renderJobCard(job)`
Creates DOM element for a single job card.

## Security
-   **Authentication**: Required to access
-   **Role Check**: Only workers can access this page
-   **Application Prevention**: Cannot apply to own jobs

## Responsive Design
-   **Desktop**: Grid layout with multiple columns
-   **Tablet**: 2-column grid
-   **Mobile**: Single column stacked cards

## URL Structure
```
find-work.php
```
Optional query parameters:
-   `?search=keyword` - Search query
-   `?category=web-development` - Category filter
-   `?min_budget=1000` - Minimum budget filter
