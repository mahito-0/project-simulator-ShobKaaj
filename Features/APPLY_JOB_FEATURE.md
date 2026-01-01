# Apply Job Feature Documentation

## Overview
The **Apply Job** feature allows Workers to submit proposals for jobs posted by Clients. This functionality is integrated directly into the Job Details page, providing a seamless experience from viewing job requirements to submitting an application.

## User Flow
1.  **Find Work**: A Worker navigates to the "Find Work" page and clicks on a job card.
2.  **View Details**: The Worker is redirected to the **Job Details** page (`job-details.php`), which fetches and displays full project specifications dynamically.
3.  **Submit Proposal**: On the right-hand panel, the Worker fills out the application form.
4.  **Confirmation**: Upon successful submission, a success message is displayed, and the application is recorded in the database.

## Technical Implementation

### Frontend
-   **File**: `Management/Worker/MVC/html/job-details.php`
-   **Dynamic Loading**: JavaScript (`job-details.js`) fetches job metadata (Title, Client, Budget, Description) via `jobAPI.php` and renders it on the client side.
-   **Form Interface**: A styled HTML form allows input for:
    -   **Bid Amount** (Numeric input with currency symbol ৳).
    -   **Cover Letter** (Text area for pitch/proposal).

### Backend
-   **Logic**: PHP script embedded at the top of `job-details.php`.
-   **Validation**: Server-side checks ensure that:
    -   `bid_amount` is not empty and is a valid number.
    -   `cover_letter` is provided.
-   **Database Interaction**:
    -   Inserts a new record into the `applications` table.
    -   Associates the application with `job_id` and `worker_id`.
    -   Sets initial status to `'pending'` and records the timestamp (`NOW()`).

## Key Components

### Application Form
The form is designed with accessibility and ease of use in mind:
-   **Currency Input**: Distinct visual styling for the bid amount.
-   **Error Handling**: Inline error messages appear immediately below invalid fields if submission fails.
-   **Success Feedback**: A green success banner confirms when the proposal has been sent.

### Security
-   **Session Check**: `session_start()` ensures only logged-in Workers can access the page.
-   **Input Sanitization**: `htmlspecialchars` is used to prevent XSS when re-displaying input values on error.
-   **Prepared Statements**: SQL injections are prevented using parameterized queries (`$stmt->bind_param`).
