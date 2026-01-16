const chatWindow = document.getElementById('chat-window');
const messagesContainer = document.getElementById('chat-messages');
const inputField = document.getElementById('chat-input');
const loader = document.getElementById('chat-loader');

// Toggle chat window visibility
function toggleChat() {
    if (chatWindow.classList.contains('chat-hidden')) {
        chatWindow.classList.remove('chat-hidden');
        chatWindow.classList.add('chat-visible');
        setTimeout(() => inputField.focus(), 100);
    } else {
        chatWindow.classList.remove('chat-visible');
        chatWindow.classList.add('chat-hidden');
    }
}

function handleEnter(e) {
    if (e.key === 'Enter') sendMessage();
}

async function sendMessage() {
    const text = inputField.value.trim();
    if (!text) return;

    appendMessage(text, 'user-msg');
    inputField.value = '';

    loader.style.display = 'block';
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    try {
        // Correct path to chat.php (it is in the sibling 'php' folder)
        // AJAX request to send message to the chatbot
        const response = await fetch('../php/chat.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                message: text
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        loader.style.display = 'none';
        if (data.reply) {
            appendMessage(data.reply, 'bot-msg');
        } else {
            appendMessage("⚠️ Error: " + (data.error || "Unknown error"), 'bot-msg');
        }

    } catch (error) {
        console.error("Chat Error:", error);
        loader.style.display = 'none';
        appendMessage("❌ Error: Could not connect to AI. Check console for details.", 'bot-msg');
    }
}

function appendMessage(text, className) {
    const div = document.createElement('div');
    div.className = `message ${className}`;
    div.innerText = text;
    messagesContainer.insertBefore(div, loader);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}