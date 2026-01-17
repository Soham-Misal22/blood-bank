/**
 * Admin Authentication Handler
 * Manages admin login, logout, and session checking
 */

// Check if admin is logged in
function checkAuth() {
    const isLoggedIn = localStorage.getItem('adminLoggedIn');
    const adminData = localStorage.getItem('adminData');

    if (isLoggedIn === 'true' && adminData) {
        return JSON.parse(adminData);
    }
    return null;
}

// Protect admin pages - redirect to login if not authenticated
function protectAdminPage() {
    const admin = checkAuth();
    if (!admin) {
        window.location.href = 'admin-login.html';
        return false;
    }
    return admin;
}

// Redirect to dashboard if already logged in (for login page)
function redirectIfLoggedIn() {
    const admin = checkAuth();
    if (admin) {
        window.location.href = 'admin-dashboard.html';
    }
}

// Logout function
async function logout() {
    try {
        // Call logout API
        await authAPI.logout();
    } catch (error) {
        console.error('Logout error:', error);
    } finally {
        // Clear local storage
        localStorage.removeItem('adminLoggedIn');
        localStorage.removeItem('adminData');
        // Redirect to login
        window.location.href = 'admin-login.html';
    }
}

// Export functions
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { checkAuth, protectAdminPage, redirectIfLoggedIn, logout };
}
