/**
 * Blood Bank API Client
 * Handles all API communications with the backend
 */

const API_BASE_URL = 'http://localhost:5000/api';

// API Client
const api = {
    // Generic request handler
    async request(endpoint, options = {}) {
        const url = `${API_BASE_URL}${endpoint}`;
        const config = {
            ...options,
            headers: {
                'Content-Type': 'application/json',
                ...options.headers,
            },
            credentials: 'include', // Include cookies for authentication
        };

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Something went wrong');
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    },

    // GET request
    get(endpoint) {
        return this.request(endpoint, { method: 'GET' });
    },

    // POST request
    post(endpoint, body) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(body),
        });
    },

    // PUT request
    put(endpoint, body) {
        return this.request(endpoint, {
            method: 'PUT',
            body: JSON.stringify(body),
        });
    },

    // DELETE request
    delete(endpoint) {
        return this.request(endpoint, { method: 'DELETE' });
    },
};

// Authentication API
const authAPI = {
    async login(email, password) {
        return api.post('/auth/login', { email, password });
    },

    async logout() {
        return api.get('/auth/logout');
    },

    async getMe() {
        return api.get('/auth/me');
    },

    async register(adminData) {
        return api.post('/auth/register', adminData);
    },
};

// Donors API
const donorsAPI = {
    async getAll() {
        return api.get('/donors');
    },

    async getById(id) {
        return api.get(`/donors/${id}`);
    },

    async create(donorData) {
        return api.post('/donors', donorData);
    },

    async update(id, donorData) {
        return api.put(`/donors/${id}`, donorData);
    },

    async delete(id) {
        return api.delete(`/donors/${id}`);
    },

    async getByBloodGroup(bloodGroup) {
        return api.get(`/donors/bloodgroup/${bloodGroup}`);
    },
};

// Receivers API
const receiversAPI = {
    async getAll() {
        return api.get('/receivers');
    },

    async getById(id) {
        return api.get(`/receivers/${id}`);
    },

    async create(receiverData) {
        return api.post('/receivers', receiverData);
    },

    async update(id, receiverData) {
        return api.put(`/receivers/${id}`, receiverData);
    },

    async delete(id) {
        return api.delete(`/receivers/${id}`);
    },

    async getByUrgency(level) {
        return api.get(`/receivers/urgency/${level}`);
    },
};

// Blood Inventory API
const inventoryAPI = {
    async getAll() {
        return api.get('/inventory');
    },

    async getByBloodType(bloodType) {
        return api.get(`/inventory/${bloodType}`);
    },

    async update(bloodType, unitsAvailable) {
        return api.put(`/inventory/${bloodType}`, { unitsAvailable });
    },

    async addUnits(bloodType, units) {
        return api.post(`/inventory/${bloodType}/add`, { units });
    },

    async removeUnits(bloodType, units) {
        return api.post(`/inventory/${bloodType}/remove`, { units });
    },

    async getCritical() {
        return api.get('/inventory/status/critical');
    },
};

// UI Helper Functions
const ui = {
    // Show success message
    showSuccess(message) {
        alert(`✅ ${message}`);
    },

    // Show error message
    showError(message) {
        alert(`❌ ${message}`);
    },

    // Show loading state
    setLoading(element, isLoading) {
        if (isLoading) {
            element.disabled = true;
            element.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
        } else {
            element.disabled = false;
        }
    },

    // Format date for display
    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
    },

    // Get form data as object
    getFormData(form) {
        const formData = new FormData(form);
        const data = {};
        for (const [key, value] of formData.entries()) {
            data[key] = value;
        }
        return data;
    },
};

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { authAPI, donorsAPI, receiversAPI, inventoryAPI, ui };
}
