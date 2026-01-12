# Job Details Feature Documentation

## Overview
The **Job Details** feature displays comprehensive information about a specific job listing. For Workers, it also provides the application form to submit proposals.

## User Flow

### For Workers
1.  **Access**: Worker clicks on a job card from Find Work or dashboard
2.  **View Details**: See full job information
3.  **Submit Application**: Fill out bid amount and cover letter
4.  **Confirmation**: See success message upon submission

### For Clients
1.  **Access**: Client clicks on a job from their posted jobs
2.  **View Details**: See full job information and applications
3.  **Manage**: View applicants and their proposals

## Technical Implementation

### Frontend
-   **File**: `Management/Worker/MVC/html/job-details.php`
-   **Styling**: `Management/Worker/MVC/css/job-details.css`
-   **JavaScript**: `Management/Worker/MVC/js/job-details.js`

### Backend
-   **API**: `Management/Shared/MVC/php/jobAPI.php`
-   **Actions**:
    -   `get_job_details` - Fetch job information
    -   `apply_job` - Submit application

## Key Components

### Job Information Section
-   **Title**: Job name (large heading)
-   **Status Badge**: Open/In Progress/Completed
-   **Client Info**: Name, avatar, link to profile
-   **Budget**: Payment amount prominently displayed
-   **Category**: Job category
-   **Location**: Job location
-   **Posted Date**: When the job was created
-   **Deadline**: Expected completion date

### Description Section
-   Full job description
-   Requirements list
-   Skills needed

### Application Form (Workers Only)
-   **Bid Amount**: Numeric input with currency symbol (৳)
-   **Cover Letter**: Textarea for proposal
-   **Submit Button**: Sends application

### Error/Success Messages
-   Inline validation errors
-   Success banner on successful application

## JavaScript Functions

### `loadJobDetails(jobId)`
Fetches complete job information from API.

### `submitApplication(jobId, bidAmount, coverLetter)`
Submits the application to the API.

### `checkPreviousApplication(jobId, workerId)`
Checks if worker has already applied.

## Security
-   **Authentication**: Required to access
-   **Duplicate Prevention**: Worker cannot apply twice
-   **Owner Check**: Client cannot apply to own job
-   **Input Validation**: Bid must be positive number
-   **XSS Prevention**: All output is escaped

## URL Structure
```
job-details.php?id={job_id}
```
-   `id`: Required - The unique identifier of the job
