document.addEventListener('DOMContentLoaded', () => {

    const state = {
        filter: 'all',
        notifications: []
    };

    const container = document.getElementById('notificationList');
    const tabs = document.querySelectorAll('.tab-btn');
    const markAllBtn = document.getElementById('markAllRead');

    fetchNotifications();

    tabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            tabs.forEach(t => t.classList.remove('active'));
            e.target.classList.add('active');

            state.filter = e.target.dataset.filter;
            fetchNotifications();
        });
    });

    if (markAllBtn) {
        markAllBtn.addEventListener('click', async () => {
            await apiCall('mark_read', { id: 'all' });
            fetchNotifications();
            if (typeof initNotifications === 'function') {

            }
        });
    }

    async function apiCall(action, data = {}) {
        const url = `/project-simulator-ShobKaaj/Management/Shared/MVC/php/notificationsAPI.php?action=${action}`;

        let options = {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        };

        if (action.startsWith('get_')) {

            const params = new URLSearchParams(data).toString();
            const getUrl = url + '&' + params;
            const res = await fetch(getUrl);
            return await res.json();
        } else {
            options.body = JSON.stringify(data);
            const res = await fetch(url, options);
            return await res.json();
        }
    }

    async function fetchNotifications() {
        container.innerHTML = '<div class="empty-state">Loading...</div>';
        try {
            const res = await apiCall('get_notifications', { filter: state.filter });
            if (res.status === 'success') {
                state.notifications = res.notifications;
                render();
            } else {
                container.innerHTML = `<div class="empty-state">Error: ${res.message}</div>`;
            }
        } catch (e) {
            console.error(e);
            container.innerHTML = '<div class="empty-state">Failed to load notifications.</div>';
        }
    }

    function render() {
        if (state.notifications.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-bell-slash"></i>
                    <p>No notifications found.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = '';
        state.notifications.forEach(notif => {
            const el = createNotificationElement(notif);
            container.appendChild(el);
        });
    }

    function createNotificationElement(notif) {
        const div = document.createElement('div');
        div.className = `notification-item ${notif.is_read == 0 ? 'unread' : ''}`;


        let iconClass = 'fa-bell';
        if (notif.type === 'warning' || notif.type === 'alert') {
            div.classList.add('important');
            iconClass = 'fa-exclamation-circle';
        } else if (notif.type === 'success') {
            iconClass = 'fa-check-circle';
        }

        const date = new Date(notif.created_at).toLocaleString();

        div.innerHTML = `
            <div class="notification-icon">
                <i class="fas ${iconClass}"></i>
            </div>
            <div class="notification-content">
                <div class="notif-header-row">
                    <span class="notif-title">${escapeHtml(notif.title)}</span>
                    <div class="notif-time">${date}</div>
                </div>
                <p class="notif-message">${escapeHtml(notif.message)}</p>
            </div>
            <div class="item-controls">
                ${notif.is_read == 0 ? `<button class="icon-btn mark-read" title="Mark as Read"><i class="fas fa-check"></i></button>` : ''}
                <button class="icon-btn delete-btn" title="Delete"><i class="fas fa-trash"></i></button>
            </div>
        `;


        const markReadBtn = div.querySelector('.mark-read');
        if (markReadBtn) {
            markReadBtn.addEventListener('click', async (e) => {
                e.stopPropagation();
                await apiCall('mark_read', { id: notif.id });

                div.classList.remove('unread');
                markReadBtn.remove();
            });
        }

        const deleteBtn = div.querySelector('.delete-btn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', async (e) => {
                e.stopPropagation();
                if (!confirm('Delete this notification?')) return;

                await apiCall('delete', { id: notif.id });

                div.style.opacity = '0';
                setTimeout(() => {
                    div.remove();
                    if (container.children.length === 0) render();
                }, 200);
            });
        }

        return div;
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text.replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});
