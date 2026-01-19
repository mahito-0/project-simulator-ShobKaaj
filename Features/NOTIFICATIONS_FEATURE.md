# Notifications Feature

## Overview
The **Notifications Feature** provides real-time updates and alerts to users regarding critical platform activities. Whether it's a new job application, a hiring decision, or a payment confirmation, users are instantly notified via a dynamic badge in the navigation bar and a dedicated notifications center.

## User Flow
1. **Trigger**: An action (like applying for a job or hiring a worker) occurs.
2. **Persistence**: The system generates a notification record in the database.
3. **Alert**: The user sees a red badge with the unread count on the "Notifications" link in the navbar.
4. **View**: Clicking "Notifications" takes the user to the Notification Center.
5. **Management**: Users can filter notifications (All, Unread, Important), mark them as read, or delete them.

## Technical Implementation

### Frontend
- **Navbar Integration (`navbar.js`)**: Polls the unread count every 60 seconds and updates the `nav-badge-inline`.
- **Notification Center (`notifications.php`)**: A dedicated page to view and manage all notifications.
- **Dynamic Rendering (`notifications.js`)**: Fetches data from the API and renders it with appropriate icons based on the notification type (success, warning, etc.).

### Backend
- **Database Table (`notifications`)**: Stores `user_id`, `type`, `title`, `message`, `is_read`, and `created_at`.
- **API Handler (`notificationsAPI.php`)**: Provides endpoints for:
  - `get_notifications`: Fetch notifications with filters.
  - `get_unread_count`: Returns the number of unread notifications for the current user.
  - `mark_read`: Updates notification status to read.
  - `delete`: Removes a notification record.
- **Utility Function (`JobAPI::createNotification`)**: A centralized method used throughout the system to trigger new notifications.

## Key Components
- **Filter Tabs**: Quickly switch between all alerts, unread items, or important warnings.
- **Mark All as Read**: Bulk update functionality for convenience.
- **Categorized Icons**:
  - <i class="fas fa-check-circle"></i> **Success**: Payment released, job completed.
  - <i class="fas fa-exclamation-circle"></i> **Warning/Alert**: New applications, rejections.
  - <i class="fas fa-bell"></i> **Info**: General updates.

## Notifications Triggers
| Action | Receiver | Notification Content |
|--------|----------|----------------------|
| Job Application | Client | "[Worker Name] applied for your job: [Job Title]" |
| Hiring | Worker | "You're Hired! You have been hired for [Job Title]" |
| Rejection | Worker | "Application Rejected for [Job Title]" |
| Job Completion | Worker | "Job Completed & Paid: [Job Title]" |

## Security & Validation
- **Session Protection**: Users can only fetch, read, or delete notifications belonging to their `user_id`.
- **API Authorization**: All requests to `notificationsAPI.php` require a valid session; otherwise, an 'Unauthorized' error is returned.
- **XSS Prevention**: Frontend uses `escapeHtml` to sanitize notification content before rendering.
