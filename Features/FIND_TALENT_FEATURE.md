# Find Talent Feature Documentation

## Overview
The **Find Talent** feature allows Clients to discover and browse Worker profiles on the platform. It provides a searchable interface to find skilled professionals for their projects.

## User Flow
1.  **Access**: Client navigates to "Find Talent" from navbar
2.  **Browse Workers**: View list of available worker profiles
3.  **Search/Filter**: Use search bar or filters to narrow results
4.  **View Profile**: Click on a worker card to see full profile
5.  **Contact**: Send message or view worker's contact details

## Technical Implementation

### Frontend
-   **File**: `Management/Client/MVC/html/find-talent.php`
-   **Styling**: `Management/Client/MVC/css/find-talent.css`
-   **JavaScript**: `Management/Client/MVC/js/find-talent.js`

### Backend
-   **API**: `Management/Shared/MVC/php/authAPI.php`
-   **Action**: `get_all_workers` or `search_workers`
-   **Database Table**: `users` (where `role = 'worker'`)

## Key Components

### Search Bar
-   Text input for keyword search
-   Searches by name, skills, or description

### Filter Options
-   **Skills**: Filter by specific skills
-   **Rating**: Filter by minimum rating
-   **Availability**: Filter by worker availability

### Worker Cards
Each worker is displayed as a card showing:
-   **Avatar**: Profile picture
-   **Name**: Full name
-   **Skills**: List of skill badges
-   **Rating**: Star rating with count
-   **Completed Jobs**: Number of finished projects
-   **View Profile Button**: Links to full profile

### Empty State
Displayed when no workers match the search criteria.

## JavaScript Functions

### `loadWorkers()`
Fetches and displays all available workers.

### `searchWorkers(query)`
Searches workers by keyword.

### `filterWorkers(filters)`
Applies filter criteria to the worker list.

### `renderWorkerCard(worker)`
Creates DOM element for a single worker card.

## Security
-   **Authentication**: Required to access
-   **Role Check**: Only clients can access this page
-   **Data Privacy**: Only public profile info is shown

## Responsive Design
-   **Desktop**: Grid layout with multiple columns
-   **Tablet**: 2-column grid
-   **Mobile**: Single column stacked cards

## URL Structure
```
find-talent.php
```
Optional query parameters:
-   `?search=keyword` - Search query
-   `?skill=web-development` - Skill filter
