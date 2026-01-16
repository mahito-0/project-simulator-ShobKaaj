/**
 * Shared Utilities & API Wrapper
 */

const API = {
    // Post FormData (for file uploads and forms)
    postForm: async (action, formData, apiName = 'authAPI') => {
        try {
            // Adjust path based on where we are being called from
            // Assuming current page is in /html/ or root
            const response = await fetch(`../php/${apiName}.php?action=${action}`, {
                method: 'POST',
                body: formData
            });
            const text = await response.text();
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error("Invalid JSON from " + apiName + ":", text);
                return { status: 'error', message: 'Server returned invalid JSON' };
            }
        } catch (error) {
            console.error("API Error (" + apiName + "):", error);
            return { status: 'error', message: 'Network request failed' };
        }
    },

    // Post JSON data
    post: async (action, data, apiName = 'authAPI') => {
        try {
            const response = await fetch(`../php/${apiName}.php?action=${action}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            return await response.json();
        } catch (error) {
            console.error("API Error (" + apiName + "):", error);
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

function getAvatarPath(avatarStr, role = '') {
    const DEFAULT_AVATAR = '/project-simulator-ShobKaaj/Management/Shared/MVC/images/logo.png';
    if (!avatarStr) return DEFAULT_AVATAR;

    // Use default if it's the broken fallback path
    if (avatarStr.includes('avater.png')) return DEFAULT_AVATAR;

    // If it's already a full URL or absolute path starting with /project-simulator-ShobKaaj
    if (avatarStr.startsWith('http') || avatarStr.startsWith('/project-simulator-ShobKaaj')) {
        return avatarStr;
    }

    // Extract filename from any path structure
    let filename = avatarStr;
    if (avatarStr.includes('/') || avatarStr.includes('\\')) {
        filename = avatarStr.split(/[/\\]/).pop();
    }

    // Determine correct directory based on role
    let dir = 'Shared';
    const safeRole = (role || '').toLowerCase();

    if (safeRole === 'client') dir = 'Client';
    else if (safeRole === 'worker') dir = 'Worker';
    else if (safeRole === 'admin') dir = 'Admin';

    const finalPath = `/project-simulator-ShobKaaj/Management/${dir}/MVC/images/users/${filename}`;
    return finalPath;
}

// Expose to window for global access if needed
window.getAvatarPath = getAvatarPath;
