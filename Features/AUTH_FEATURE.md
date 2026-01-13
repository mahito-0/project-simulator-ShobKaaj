# Authentication Feature Documentation

## Overview
The **Authentication** feature provides secure login and registration functionality for both Clients and Workers on the ShobKaaj platform. It uses a dual-panel sliding interface for seamless switching between Sign In and Sign Up forms.

## User Flow

### Registration
1.  **Access**: User navigates to `auth.php` and clicks "Sign Up"
2.  **Fill Form**: User provides:
    - First Name & Last Name
    - Email Address
    - Phone Number
    - Password & Confirm Password
    - Role Selection (Worker/Client)
3.  **Submit**: Form is validated and user account is created
4.  **Redirect**: User is redirected to their role-specific dashboard

### Login
1.  **Access**: User navigates to `auth.php`
2.  **Fill Form**: User enters email and password
3.  **Submit**: Credentials are validated against database
4.  **Redirect**: User is redirected to their role-specific dashboard

## Technical Implementation

### Frontend
-   **File**: `Management/Shared/MVC/html/auth.php`
-   **Styling**: `Management/Shared/MVC/css/auth.css`
-   **JavaScript**: `Management/Shared/MVC/js/auth.js`
-   **Design**: Sliding dual-panel interface with toggle animation

### Backend
-   **Controller**: `Management/Shared/MVC/php/auth.php`
-   **API**: `Management/Shared/MVC/php/authAPI.php`
-   **Database Table**: `users`

## Key Components

### Sign Up Form
-   **Input Fields**:
    -   First Name (required)
    -   Last Name (required)
    -   Email (required, unique)
    -   Phone Number (required)
    -   Password (required, min length)
    -   Confirm Password (must match)
    -   Role Selector (Worker/Client)
-   **Validation**: Server-side PHP validation with error indicators

### Sign In Form
-   **Input Fields**:
    -   Email (required)
    -   Password (required)
-   **Links**: "Forgot Your Password?" link to password recovery

### Toggle Panel
-   **Left Panel**: "Already a Member?" - switches to Sign In
-   **Right Panel**: "New Here?" - switches to Sign Up
-   **Animation**: Smooth sliding transition

### Social Login (UI Only)
-   Google, Facebook, LinkedIn icons (placeholder for future OAuth)

## Security
-   **Password Hashing**: Passwords are hashed before storage
-   **Input Sanitization**: All inputs are sanitized to prevent XSS
-   **Prepared Statements**: SQL injection prevention
-   **Session Management**: Secure session handling after login
-   **CSRF Protection**: Form tokens for cross-site request forgery protection

## Error Handling
-   Inline error indicators (asterisks) on invalid fields
-   Pre-filled form values on validation failure
-   User-friendly error messages

## Role-Based Redirection
| Role | Dashboard URL |
|------|---------------|
| Client | `client-dashboard.php` |
| Worker | `worker-dashboard.php` |
| Admin | `admin.php` |

## URL Structure
```
auth.php
```
No query parameters; form submission via POST.
