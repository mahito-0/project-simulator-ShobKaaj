/**
 * Shared Utilities & API Wrapper
 */

const API = {
    // Post FormData (for file uploads and forms)
    postForm: async (action, formData) => {
        try {
            // Adjust path based on where we are being called from
            // Assuming current page is in /html/ or root
            const response = await fetch(`../php/authAPI.php?action=${action}`, {
                method: 'POST',
                body: formData
            });
            const text = await response.text();
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error("Invalid JSON:", text);
                return { status: 'error', message: 'Server returned invalid JSON' };
            }
        } catch (error) {
            console.error("API Error:", error);
            return { status: 'error', message: 'Network request failed' };
        }
    },

    // Post JSON data
    post: async (action, data) => {
        try {
            const response = await fetch(`../php/authAPI.php?action=${action}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            return await response.json();
        } catch (error) {
            console.error("API Error:", error);
            return { status: 'error', message: 'Network request failed' };
        }
    }
};

// Formatting utilities
function formatCurrency(amount) {
    return '৳' + Number(amount).toLocaleString();
}

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString();
}
