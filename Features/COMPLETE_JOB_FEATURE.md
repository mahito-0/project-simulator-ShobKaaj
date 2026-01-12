# Complete Job Feature Documentation

## Overview
The **Complete Job** feature allows Clients to mark a job as completed and submit a review for the Worker who completed the work. This finalizes the project and records feedback for the worker's profile.

## User Flow
1.  **Access**: Client clicks "Complete Job" from their applications or dashboard
2.  **Review Form**: Client sees job summary and review form
3.  **Rate Worker**: Client provides star rating (1-5)
4.  **Write Review**: Client writes feedback comment
5.  **Submit**: Review is saved and job status updated

## Technical Implementation

### Frontend
-   **File**: `Management/Client/MVC/html/complete-job.php`
-   **Styling**: Uses base styles
-   **JavaScript**: Inline or `complete-job.js`

### Backend
-   **API**: `Management/Shared/MVC/php/jobAPI.php`
-   **Action**: `complete_job`
-   **Database Tables**: `jobs`, `reviews`

## Key Components

### Job Summary Section
-   **Job Title**: Name of the completed job
-   **Worker Info**: Name and avatar of the hired worker
-   **Budget**: Payment amount (৳)
-   **Start/End Dates**: Project timeline

### Review Form
-   **Star Rating**: 1-5 star selector
-   **Review Comment**: Textarea for feedback
-   **Submit Button**: Completes the job

### Confirmation Modal
-   Confirms job completion
-   Shows success message
-   Redirects to dashboard

## JavaScript Functions

### `submitReview(jobId, workerId, rating, comment)`
Sends the review data to the API.

### `updateJobStatus(jobId)`
Updates job status to 'completed'.

## Database Operations

### Job Update
```sql
UPDATE jobs SET status = 'completed' WHERE id = ?
```

### Review Insert
```sql
INSERT INTO reviews (job_id, reviewer_id, reviewee_id, rating, comment, created_at)
VALUES (?, ?, ?, ?, ?, NOW())
```

## Security
-   **Authentication**: Required to access
-   **Authorization**: Only job owner can complete
-   **Validation**: Rating must be 1-5
-   **Duplicate Prevention**: Can only complete once

## URL Structure
```
complete-job.php?job_id={job_id}&worker_id={worker_id}&app_id={application_id}
```
-   `job_id`: The job to complete
-   `worker_id`: The hired worker
-   `app_id`: The accepted application
