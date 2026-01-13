# AI Chatbot Assistant Feature

## Overview
The AI Chatbot Assistant is a smart, interactive widget integrated into the ShobKaaj platform. It serves as a virtual guide for both Clients and Workers, helping them navigate the platform, understand services, and troubleshoot basic account issues 24/7. It leverages a local **Ollama (Llama 3.2)** model to generate intelligent, context-aware responses.

## Key Capabilities (Prompts)
The chatbot is pre-configured with a comprehensive system prompt that allows it to answer questions about:
*   **Platform Mission:** Connecting local professionals with daily tasks.
*   **Services & Pricing:** Provides estimated rates for Tutoring, Delivery, Repairs, and Household tasks.
*   **User Roles:** Guides users on how to "Post a Job" (Client) vs. "Find Work" (Worker).
*   **Safety & Trust:** Explains NID verification, secure payments, and safety tips.
*   **Account Support:** Assists with login issues and password resets.

## Technical Implementation
*   **Frontend:**
    *   **UI:** A floating chat widget built with HTML/CSS (`css/index.css`) that can be toggled open/closed.
    *   **Logic:** Vanilla JavaScript (`js/chat.js`) handles user input, displays "typing" animations, and sends async POST requests to the backend.
    *   **Error Handling:** Displays user-friendly error messages if the AI service is unreachable.

*   **Backend:**
    *   **Processor:** `php/chat.php` acts as the bridge between the frontend and the AI model.
    *   **Communication:** Uses PHP **cURL** to send JSON payloads to the locally running Ollama instance (port 11434).
    *   **System Prompt:** A rich, multi-line system prompt is injected into every request to ensure the AI stays in character as the "ShobKaaj Assistant".
    *   **Resource Management:** Efficiently manages cURL resources to prevent memory leaks and ensure connection stability.

## File Structure
*   `Management/Shared/MVC/html/index.php` - Contains the chat widget HTML structure.
*   `Management/Shared/MVC/js/chat.js` - Client-side logic for sending messages and UI definition.
*   `Management/Shared/MVC/php/chat.php` - Server-side handler for Ollama API communication.

## Dependencies
*   **Ollama:** Must be running locally (`ollama run llama3.2`) on port **11434**.
*   **PHP cURL Extension:** Required for making HTTP requests to the Ollama API.
