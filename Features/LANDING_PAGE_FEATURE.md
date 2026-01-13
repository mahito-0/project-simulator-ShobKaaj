# Landing Page Feature Documentation

## Overview
The **Landing Page** (index.php) is the main entry point for the ShobKaaj platform. It showcases the platform's features, displays statistics, and provides clear calls-to-action for new visitors to join.

## User Flow
1.  **Arrive**: User visits the homepage
2.  **Explore**: Browse features, stats, and testimonials
3.  **Call-to-Action**: Click "Get Started" or "Sign In"
4.  **Navigate**: Use navbar to access other sections

## Technical Implementation

### Frontend
-   **File**: `Management/Shared/MVC/html/index.php`
-   **Styling**: `Management/Shared/MVC/css/index.css`
-   **JavaScript**: `Management/Shared/MVC/js/index.js`
-   **Design**: Glassmorphism with neon accents

## Key Components

### Hero Section
-   **Headline**: Bold welcome message
-   **Subtitle**: Platform description
-   **CTA Buttons**:
    -   "Get Started" → Registration
    -   "Learn More" → Features section
-   **Background**: Animated gradient or image

### Stats Section
Displays platform metrics:
-   Total Users
-   Jobs Posted
-   Jobs Completed
-   Total Earnings

### Features Section
Highlights key platform capabilities:
-   **For Clients**: Post jobs, find talent, manage projects
-   **For Workers**: Find work, build portfolio, get paid
-   Cards with icons and descriptions

### How It Works Section
Step-by-step guide:
1.  Create an Account
2.  Post/Find Jobs
3.  Connect & Collaborate
4.  Complete & Get Paid

### Testimonials Section
-   User reviews/testimonials
-   Carousel or grid layout
-   Avatar, name, and quote

### Footer
-   Navigation links
-   Social media icons
-   Copyright notice
-   Contact information

## Responsive Design
-   **Desktop**: Full-width hero, multi-column features
-   **Tablet**: Adjusted grid layouts
-   **Mobile**: Stacked sections, hamburger menu

## SEO Optimization
-   Descriptive `<title>` tag
-   Meta description
-   Semantic HTML structure
-   Open Graph tags for social sharing

## Performance
-   Lazy loading for images
-   Minified CSS/JS in production
-   Optimized hero image

## URL Structure
```
index.php (or /)
```
No query parameters.
