# Post Job Feature Documentation

## Overview
The **Post Job** feature allows Clients to create new job listings on the ShobKaaj platform. It provides a comprehensive form to specify job details, requirements, budget, and category.

## User Flow
1.  **Access**: Client navigates to "Post a Job" from dashboard or navbar
2.  **Fill Form**: Client provides all job details
3.  **Submit**: Form is validated and job is created
4.  **Redirect**: Client is redirected to their jobs page or dashboard

## Technical Implementation

### Frontend
-   **File**: `Management/Client/MVC/html/post-job.php`
-   **Styling**: `Management/Client/MVC/css/post-job.css`
-   **JavaScript**: `Management/Client/MVC/js/post-job.js`

### Backend
-   **API**: `Management/Shared/MVC/php/jobAPI.php`
-   **Action**: `create_job`
-   **Database Table**: `jobs`

## Key Components

### Job Form Fields
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| Title | Text | Yes | Job title/name |
| Description | Textarea | Yes | Detailed job description |
| Category | Select | Yes | Job category (e.g., Web Dev, Design) |
| Location | Text | Yes | Job location or "Remote" |
| Budget | Number | Yes | Payment amount (৳) |
| Deadline | Date | No | Expected completion date |
| Skills Required | Multi-select | No | Required skills for the job |

### Category Options
-   Web Development
-   Mobile Development
-   Graphic Design
-   Content Writing
-   Data Entry
-   Virtual Assistant
-   Other

### Form Validation
-   **Client-side**: JavaScript validation for required fields
-   **Server-side**: PHP validation with error messages

## Security
-   **Authentication**: Only logged-in clients can access
-   **Role Check**: Redirects non-clients to dashboard
-   **Input Sanitization**: All inputs are sanitized
-   **Prepared Statements**: SQL injection prevention
-   **XSS Prevention**: `htmlspecialchars` on output

## Database Schema
```sql
jobs (
    id INT PRIMARY KEY,
    client_id INT FOREIGN KEY,
    title VARCHAR(255),
    description TEXT,
    category VARCHAR(100),
    location VARCHAR(100),
    budget DECIMAL(10,2),
    deadline DATE,
    status ENUM('open', 'in_progress', 'completed'),
    created_at TIMESTAMP
)
```

## Success Flow
1.  Job is inserted into database with `status = 'open'`
2.  Success message displayed to client
3.  Client redirected to posted jobs page

## URL Structure
```
post-job.php
```
No query parameters for creation.
