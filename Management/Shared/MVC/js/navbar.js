/**
 * Dynamic Navbar Component
 * Handles navigation rendering, authentication state, and notifications.
 */

// --- Service Mocks ---

// Auth Service Stub
const $auth = {
    getMe: async function () {
        const stored = localStorage.getItem('user');
        return stored ? JSON.parse(stored) : null;
    },
    logout: async function () {
        try {
            await fetch('API/authAPI.php?action=logout', { method: 'POST' });
        } catch (e) {
            console.error('Logout error:', e);
        }
        localStorage.removeItem('user');
        window.location.href = '/project-simulator-ShobKaaj/Management/Shared/MVC/html/index.php';
    }
};

// API Service Stub
const $api = async function (endpoint) {
    // console.debug('API Call:', endpoint);
    return { notifications: [] };
};


window.getAvatarPath = function (avatarStr, role = '') {
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
    else if (safeRole === 'admin') dir = 'Admin'; // Explicitly check for Admin

    const finalPath = `/project-simulator-ShobKaaj/Management/${dir}/MVC/images/users/${filename}`;
    return finalPath;
};


// --- Navbar Implementation ---

function updateBell(count) {
    const el = document.getElementById('notifCount');
    if (!el) return;
    if (count > 0) { el.style.display = 'inline-block'; el.textContent = count > 99 ? '99+' : String(count); }
    else { el.style.display = 'none'; el.textContent = '0'; }
}

async function initNotifications(user) {
    if (!user) return;
    // Fetch unread count initially
    try {
        const { notifications } = await $api('/api/notifications?unread=1');
        updateBell(notifications.length || 0);
    } catch { }

    // Load socket.io client dynamically
    function ensureSocket() {
        return new Promise((resolve) => {
            if (window.io) return resolve(window.io);
            // Check if socket.io script exists, otherwise fail gracefully
            if (!document.querySelector('script[src*="socket.io"]')) {
                // console.debug("Socket.io script not found, skipping real-time notifications");
                return resolve(null);
            }
            const s = document.createElement('script');
            s.src = '/socket.io/socket.io.js';
            s.onload = () => resolve(window.io);
            s.onerror = () => resolve(null); // Resolve null on error
            document.head.appendChild(s);
        });
    }

    const ioFactory = await ensureSocket();
    if (ioFactory) {
        const socket = ioFactory({ withCredentials: true });
        socket.on('notify', (n) => {
            // increase bell count
            const el = document.getElementById('notifCount');
            const curr = Number(el?.textContent || 0) || 0;
            updateBell(curr + 1);
        });
    }
}

function renderNavbar(user) {
    const navbarEl = document.getElementById('navbar');
    if (!navbarEl) return;
    const avatar = window.getAvatarPath(user?.avatar, user?.role);
    const currentPath = window.location.pathname.toLowerCase() || '/';

    // Update hero profile pill on the homepage
    if (currentPath.includes('index') || currentPath === '/') {
        const heroProfilePill = document.querySelector('.hero-section .profile-pill');
        if (heroProfilePill) {
            if (user) {
                const avatarElement = heroProfilePill.querySelector('.avatar');
                const nameElement = heroProfilePill.querySelector('span');

                if (avatarElement) {
                    avatarElement.style.backgroundImage = `url('${avatar}')`;
                    avatarElement.style.backgroundSize = 'cover';
                    avatarElement.style.backgroundPosition = 'center';
                    avatarElement.textContent = ''; // Clear any existing content like initials
                    avatarElement.setAttribute('aria-label', user.name); // Add accessibility
                }
                if (nameElement) {
                    nameElement.textContent = user.name;
                }
                heroProfilePill.style.display = 'flex';
            } else {
                heroProfilePill.style.display = 'none';
            }
        }
    }

    const basePath = '/project-simulator-ShobKaaj/Management';
    const sharedPath = `${basePath}/Shared/MVC/html`;
    const clientPath = `${basePath}/Client/MVC/html`;
    const workerPath = `${basePath}/Worker/MVC/html`;

    if (user) {
        if (user.role === 'admin') {
            navItems = [
                { href: `/project-simulator-ShobKaaj/Management/Admin/MVC/html/admin.php`, text: 'Dashboard', auth: true },
            ];
        } else {
            // Dashboard depends on Role
            const dashboardLink = user.role === 'client' ? `${clientPath}/client-dashboard.php` : `${workerPath}/worker-dashboard.php`;

            navItems = [
                { href: dashboardLink, text: 'Dashboard', auth: true },
                { href: `${workerPath}/find-work.php`, text: 'Find Work', auth: true },
                { href: `${clientPath}/find-talent.php`, text: 'Find Workers', auth: true, role: 'client' },
                // My Jobs depends on Role
                { href: user.role === 'client' ? `${clientPath}/my-posted-jobs.php` : `${workerPath}/my-active-jobs.php`, text: 'My Jobs', auth: true },
                { href: `${sharedPath}/messages.php`, text: 'Messages', auth: true },
                { href: `${sharedPath}/notifications.php`, text: 'Notifications', auth: true },

            ];

            if (user.role === 'client') {
                navItems = navItems.filter(item => item.text !== 'Find Work');
            } else if (user.role === 'worker') {
                navItems = navItems.filter(item => item.text !== 'Find Workers');
            }
        }
    } else {
        // Guest Nav Items - Only show on Home Page
        if (currentPath.includes('index') || currentPath === '/') {
            navItems = [
                { href: `${sharedPath}/index.php#home`, text: 'Home', auth: false },
                { href: `${sharedPath}/index.php#services`, text: 'Services', auth: false },
                { href: `${sharedPath}/index.php#about`, text: 'How It Works', auth: false },
                { href: `${sharedPath}/index.php#contact`, text: 'Contact', auth: false }
            ];
        } else {
            navItems = [];
        }
    }

    const isHomePage = currentPath.includes('index') || currentPath === '/';
    const isLoginPage = currentPath.includes('auth') || currentPath.includes('login');
    const isRegisterPage = currentPath.includes('register');

    // Determine what auth buttons to show
    let authButtons = '';
    if (user) {
        authButtons = `
      <a href="/project-simulator-ShobKaaj/Management/Shared/MVC/html/profile.php" style="display: flex; align-items: center; gap: 10px;">
          <img class="avatar" src="${avatar}" onerror="this.src='/project-simulator-ShobKaaj/Management/Shared/MVC/images/logo.png'" style="width:32px;height:32px;border-radius:50%;object-fit:cover;"/>
      </a>
      <button class="btn outline" id="logoutBtn">Logout</button>
    `;
    } else {
        authButtons = `
        <a href="/project-simulator-ShobKaaj/Management/Shared/MVC/html/auth.php" class="btn outline">Login</a>
      `;
    }

    navbarEl.innerHTML = `
    <div class="navbar-container">
      <div class="brand"><a href="/project-simulator-ShobKaaj/Management/Shared/MVC/html/index.php"><span>ShobKaaj</span></a></div>
     
      <div class="navbar-menu" id="navLinks">
        <div class="navbar-nav">
        ${navItems.map(item => {
        if (item.auth && !user) return '';
        if (item.role && item.role !== user?.role) return '';
        const isActive = currentPath.includes(item.href.toLowerCase().split('#')[0]) ? 'active' : '';
        return `<a href="${item.href}" class="nav-item ${isActive}">${item.text}</a>`;
    }).join('')}
        </div>

        <div class="navbar-actions">
            ${authButtons}
        </div>
      </div>
    </div>
  `;

    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) logoutBtn.onclick = () => $auth.logout();

    initNotifications(user);
}

// Main initialization logic
async function runNavbar() {
    // Determine if we need to create the wrapper
    let nav = document.getElementById('navbar');
    if (!nav && !document.querySelector('.navbar')) {
        // If the body is not ready yet, we can't prepend.
        if (!document.body) return;

        nav = document.createElement('nav');
        nav.id = 'navbar';
        nav.className = 'navbar';
        document.body.prepend(nav);
    } else if (document.querySelector('.navbar')) {
        const existing = document.querySelector('.navbar');
        if (!existing.id) existing.id = 'navbar';
    }

    const user = await $auth.getMe();
    renderNavbar(user);

    // Register Service Worker for web push and notifications
    if ('serviceWorker' in navigator) {
        try {
            // const reg = await navigator.serviceWorker.register('/sw.js');
        } catch (e) { /* ignore */ }
    }
}

// Execute when DOM is fully ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', runNavbar);
} else {
    runNavbar();
}
