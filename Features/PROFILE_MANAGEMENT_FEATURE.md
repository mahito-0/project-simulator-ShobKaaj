# Profile Management Feature Documentation

## Overview
The **Profile Management** feature allows users to view and update their personal information, avatar, and account settings.

## User Flow
1.  **Access**: User clicks on their avatar or "My Profile" in navbar
2.  **View Profile**: See current profile information
3.  **Edit Mode**: Click "Edit Profile" to modify details
4.  **Update Avatar**: Upload new profile picture
5.  **Save Changes**: Submit updates

## Technical Implementation

### Frontend
-   **File**: `Management/Shared/MVC/html/profile.php`
-   **Styling**: `Management/Shared/MVC/css/profile.css`
-   **JavaScript**: `Management/Shared/MVC/js/profile.js`

### Backend
-   **Controller**: `Management/Shared/MVC/php/profile.php`
-   **API**: `Management/Shared/MVC/php/authAPI.php`
-   **Actions**:
    -   `get_profile` - Fetch user data
    -   `update_profile` - Update user info
    -   `update_avatar` - Upload profile picture

## Key Components

### Profile Header
-   **Avatar**: Large profile picture with upload button
-   **Name**: User's full name
-   **Role Badge**: Worker/Client indicator
-   **Edit Button**: Toggle edit mode

### Profile Information Section
| Field | Editable | Description |
|-------|----------|-------------|
| First Name | Yes | User's first name |
| Last Name | Yes | User's last name |
| Email | No | Email (read-only) |
| Phone | Yes | Phone number |
| Skills | Yes (Workers) | Comma-separated skills |

### Avatar Upload
-   Supports: JPG, PNG, GIF
-   Max Size: 2MB
-   Preview before upload
-   Cropping option

### Password Change Section
-   Current Password (verification)
-   New Password
-   Confirm New Password
-   Password strength indicator

## JavaScript Functions

### `loadProfile()`
Fetches and displays current profile data.

### `enableEditMode()`
Switches form fields to editable state.

### `saveProfile(formData)`
Submits profile updates to API.

### `uploadAvatar(file)`
Handles avatar file upload.

### `changePassword(currentPass, newPass)`
Submits password change request.

## Security
-   **Authentication**: Required to access
-   **Self-Access Only**: Can only edit own profile
-   **Password Verification**: Required for password change
-   **File Validation**: Avatar type and size checks
-   **XSS Prevention**: All output escaped

## URL Structure
```
profile.php
```
No query parameters (uses session user).
