/**
 * CartLink System - Main JavaScript Utilities
 * API configuration and helper functions
 */

const API_BASE_URL = '/backend/api';

// Storage helpers
const Storage = {
    set: (key, value) => localStorage.setItem(key, JSON.stringify(value)),
    get: (key) => {
        const item = localStorage.getItem(key);
        try {
            return item ? JSON.parse(item) : null;
        } catch {
            return null;
        }
    },
    remove: (key) => localStorage.removeItem(key),
    clear: () => localStorage.clear()
};

// Auth helpers
const Auth = {
    getToken: () => Storage.get('token'),
    getUser: () => Storage.get('user'),
    setAuth: (token, user) => {
        Storage.set('token', token);
        Storage.set('user', user);
    },
    logout: () => {
        Storage.remove('token');
        Storage.remove('user');
        window.location.href = '/frontend/customer/login.html';
    },
    isAuthenticated: () => !!Auth.getToken(),
    isAdmin: () => {
        const user = Auth.getUser();
        return user && user.role === 'admin';
    }
};

// API helper
const API = {
    request: async (endpoint, options = {}) => {
        const token = Auth.getToken();
        const headers = {
            'Content-Type': 'application/json',
            ...options.headers
        };

        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }

        const config = {
            ...options,
            headers
        };

        try {
            const response = await fetch(`${API_BASE_URL}${endpoint}`, config);
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('API Error:', error);
            return {
                success: false,
                message: 'Network error occurred'
            };
        }
    },

    get: (endpoint) => API.request(endpoint, { method: 'GET' }),
    post: (endpoint, body) => API.request(endpoint, { 
        method: 'POST', 
        body: JSON.stringify(body) 
    }),
    put: (endpoint, body) => API.request(endpoint, { 
        method: 'PUT', 
        body: JSON.stringify(body) 
    }),
    delete: (endpoint, body) => API.request(endpoint, { 
        method: 'DELETE', 
        body: JSON.stringify(body) 
    })
};

// Cart management
const Cart = {
    getItems: () => Storage.get('cart') || [],
    
    addItem: (product, quantity = 1) => {
        const cart = Cart.getItems();
        const existingItem = cart.find(item => item.id === product.id);
        
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cart.push({
                id: product.id,
                name: product.name,
                price: parseFloat(product.price),
                quantity: quantity,
                image_url: product.image_url
            });
        }
        
        Storage.set('cart', cart);
        updateCartBadge();
        return cart;
    },
    
    updateQuantity: (productId, quantity) => {
        const cart = Cart.getItems();
        const item = cart.find(item => item.id === productId);
        
        if (item) {
            item.quantity = quantity;
            Storage.set('cart', cart);
            updateCartBadge();
        }
        
        return cart;
    },
    
    removeItem: (productId) => {
        let cart = Cart.getItems();
        cart = cart.filter(item => item.id !== productId);
        Storage.set('cart', cart);
        updateCartBadge();
        return cart;
    },
    
    clear: () => {
        Storage.remove('cart');
        updateCartBadge();
    },
    
    getTotal: () => {
        const cart = Cart.getItems();
        return cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    },
    
    getItemCount: () => {
        const cart = Cart.getItems();
        return cart.reduce((count, item) => count + item.quantity, 0);
    }
};

// UI helpers
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    const container = document.querySelector('.container') || document.body;
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => alertDiv.remove(), 5000);
}

function showLoading() {
    const spinner = document.createElement('div');
    spinner.className = 'spinner';
    spinner.id = 'loading-spinner';
    document.body.appendChild(spinner);
}

function hideLoading() {
    const spinner = document.getElementById('loading-spinner');
    if (spinner) spinner.remove();
}

function updateCartBadge() {
    const badge = document.querySelector('.cart-badge');
    if (badge) {
        const count = Cart.getItemCount();
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline-block' : 'none';
    }
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function getStatusClass(status) {
    return `status-${status}`;
}

function capitalizeWords(str) {
    return str.split('_').map(word => 
        word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ');
}

// Initialize cart badge on page load
document.addEventListener('DOMContentLoaded', () => {
    updateCartBadge();
    
    // Add logout handler
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            Auth.logout();
        });
    }
});
