# In-App Chat Feature

## Overview
The In-App Chat Feature enables real-time messaging between Clients and Workers. It facilitates seamless communication for negotiating job details, discussing requirements, and maintaining contact throughout the project lifecycle. The system supports a Facebook Messenger-style layout with a list of active conversations and a dedicated chat area.

## Key Capabilities
- **Real-Time Messaging:** Send and receive text-based messages instantly.
- **Conversation Management:** View a list of all active conversations with the latest message preview.
- **Unread Indicators:** Visual cues for unread messages within the conversation list.
- **User Context:** Displays the chat partner's name, avatar, and role.
- **Secure Communication:** Ensures that users can only message valid users and cannot message themselves.

## Technical Implementation
### Frontend
- **UI Structure:** `Management/Shared/MVC/html/messages.php`
    - Implements a 3-column layout (Conversations List, Chat Window, User Details).
    - Responsive design adapting to different screen sizes.
- **Logic:** `Management/Shared/MVC/js/messages.js`
    - Handles fetching conversations and messages via AJAX.
    - Manages the "active" conversation state.
    - Implements polling (or similar mechanism) to refresh specific chat windows.
    - auto-scrolls to the newest message.

### Backend
- **API Endpoint:** `Management/Shared/MVC/php/messagesAPI.php`
- **Actions:**
    - `get_conversations`: Retrieves a list of users the current user has chatted with, ordered by the most recent message.
    - `get_messages`: Fetches the full message history between the current user and a selected partner.
    - `send_message`: Inserts a new message into the `messages` table.
- **Database Table:** `messages`
    - `id`: Primary Key
    - `sender_id`: User ID of the sender
    - `receiver_id`: User ID of the receiver
    - `message`: Text content
    - `is_read`: Boolean flag for read status
    - `created_at`: Timestamp

## User Flow
1.  **Access:** Users click the "Messages" icon in the navbar.
2.  **Select Chat:** The sidebar displays a list of recent conversations. Clicking one opens the chat history.
3.  **Send Message:** Users type in the input field and press send. The message is saved and the view updates.
4.  **Notifications:** (If implemented) Users see unread counts in the conversation list.

## Security
- **Authentication:** Only logged-in users (Clients or Workers) can access the chat.
- **Validation:** Server-side checks ensure `sender_id` matches the session user.
- **Sanitization:** Inputs are sanitized to prevent SQL injection and XSS.
