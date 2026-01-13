# Forgot Password Feature Documentation

## Overview
The **Forgot Password** feature allows users to reset their password if they have forgotten it. It provides a secure password recovery flow via email verification.

## User Flow
1.  **Access**: User clicks "Forgot Password?" on login page
2.  **Enter Email**: User submits their registered email
3.  **Verification**: System sends reset link/code to email
4.  **Reset Password**: User sets a new password
5.  **Redirect**: User is redirected to login page

## Technical Implementation

### Frontend
-   **File**: `Management/Shared/MVC/html/forgotpass.php`
-   **Styling**: Uses auth.css styles
-   **JavaScript**: Form validation

### Backend
-   **Controller**: `Management/Shared/MVC/php/forgotpass.php`
-   **API**: `Management/Shared/MVC/php/authAPI.php`
-   **Actions**:
    -   `forgot_password` - Initiate reset
    -   `reset_password` - Complete reset

## Key Components

### Email Form
-   **Email Input**: Registered email address
-   **Submit Button**: Send reset link
-   **Back to Login**: Link to return

### Success Message
-   Confirmation that email was sent
-   Instructions to check inbox

### Reset Password Form
-   **New Password**: Password input
-   **Confirm Password**: Must match
-   **Submit Button**: Save new password

## Security Measures
-   **Token Generation**: Unique, time-limited reset tokens
-   **Token Expiry**: Tokens expire after 1 hour
-   **One-Time Use**: Token is invalidated after use
-   **Rate Limiting**: Prevent abuse of reset requests
-   **Email Verification**: Only sends to registered emails

## Database Schema
```sql
password_resets (
    id INT PRIMARY KEY,
    user_id INT FOREIGN KEY,
    token VARCHAR(255),
    expires_at TIMESTAMP,
    used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP
)
```

## Error Handling
-   Email not found message
-   Expired token message
-   Invalid token message
-   Password mismatch error

## URL Structure
```
forgotpass.php
forgotpass.php?token={reset_token}  (for reset form)
```
