# View Profile Feature Documentation

## Overview
The **View Profile** feature allows users (Workers, Clients, and Admins) to view another user's public profile. This functionality is shared across the platform and provides different information based on the user's role, displaying relevant statistics, skills (for Workers), and reviews.

## User Flow
1.  **Navigate to Profile**: A user clicks on a profile link (e.g., from a job listing, application, or dashboard).
2.  **Profile Loading**: The system fetches the user's public data based on the `id` query parameter.
3.  **Display Profile**: The page renders the user's avatar, name, role, contact information, and role-specific statistics.
4.  **Interaction**: If logged in and viewing another user's profile, the viewer can send a message via the "Send Message" button.

## Technical Implementation

### Frontend
-   **File**: `Management/Shared/MVC/html/view-profile.php`
-   **Styling**: `Management/Shared/MVC/css/view-profile.css`
-   **Dynamic Loading**: JavaScript (`view-profile.js`) fetches user data via `authAPI.php?action=get_public_profile` and renders it on the client side.
-   **Hybrid Rendering**: The page supports both Server-Side Rendering (SSR) via PHP variables and Client-Side Rendering (CSR) via JavaScript for a seamless experience.

### Backend
-   **Controller**: `Management/Shared/MVC/php/view-profile.php`
-   **Database Queries**:
    -   Fetches user basic info (`id`, `first_name`, `last_name`, `email`, `phone`, `role`, `avatar`, `skills`) from the `users` table.
    -   **For Workers**: Fetches statistics (completed jobs, total earnings, average rating) from `applications`, `jobs`, and `reviews` tables.
    -   **For Clients**: Fetches statistics (jobs posted, total spent) from the `jobs` table.
    -   **Reviews**: Fetches the 10 most recent reviews for Workers from the `reviews` table.

## Key Components

### Profile Card
The main profile card displays:
-   **Avatar**: User's profile picture with a fallback to a default logo.
-   **Name & Role**: Full name and their platform role (Worker/Client).
-   **Contact Info**: Email and phone number.
-   **Action Button**: A "Send Message" button for logged-in users viewing other profiles.

### Worker-Specific Sections
-   **Skills Section**: A dynamically rendered list of skills displayed as badges.
-   **Worker Stats**:
    -   Jobs Completed
    -   Average Rating
    -   Total Earnings (৳)
-   **Reviews Section**: A list of the 10 most recent reviews, including job title, rating, comment, and reviewer's name.

### Client-Specific Sections
-   **Client Stats**:
    -   Jobs Posted
    -   Total Spent (৳)

## Security
-   **Session Check**: `session_start()` is used to access session data for conditional rendering (e.g., "Send Message" button).
-   **Input Sanitization**: `htmlspecialchars` is used throughout the template to prevent Cross-Site Scripting (XSS) attacks.
-   **Prepared Statements**: All database queries use PDO prepared statements to prevent SQL injection.
-   **Error Handling**: Displays user-friendly error messages for invalid or missing user IDs.

## URL Structure
```
view-profile.php?id={user_id}
```
- `id`: The unique identifier of the user whose profile is being viewed.

## Loading States
-   **Loading Spinner**: Displayed while fetching profile data.
-   **Error State**: Shown if the profile cannot be loaded (e.g., user not found, database error).
-   **Empty State**: For reviews section, displays "No reviews yet." if the Worker has no reviews.
