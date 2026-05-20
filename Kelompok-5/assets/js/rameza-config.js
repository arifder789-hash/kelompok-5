/**
 * Global Configuration & Initialization
 * rameza-config.js
 */

// Global App Configuration
window.RamezaApp = {
    config: {
        siteUrl: '<?php echo SITE_URL; ?>',
        assetsPath: '<?php echo ASSETS_PATH; ?>',
        cssPath: '<?php echo CSS_PATH; ?>',
        jsPath: '<?php echo JS_PATH; ?>',
        siteName: '<?php echo SITE_NAME; ?>',
    },
    
    // Initialize application
    init: function() {
        console.log('Initializing Rameza Farm Application...');
        this.setupEventListeners();
        this.setupScrollAnimations();
        this.setupSmoothScroll();
    },
    
    // Setup global event listeners
    setupEventListeners: function() {
        // Mobile menu toggle
        const mobileMenuBtn = document.querySelector('[data-mobile-menu-toggle]');
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', this.toggleMobileMenu);
        }
        
        // Scroll event for navbar
        window.addEventListener('scroll', debounce(this.onWindowScroll, 100));
    },
    
    // Setup scroll animations
    setupScrollAnimations: function() {
        const elements = document.querySelectorAll('[data-animate]');
        if (elements.length === 0) return;
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        elements.forEach(el => observer.observe(el));
    },
    
    // Setup smooth scroll behavior
    setupSmoothScroll: function() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(anchor.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    },
    
    // Window scroll handler
    onWindowScroll: function() {
        const navbar = document.querySelector('header');
        if (!navbar) return;
        
        if (window.scrollY > 50) {
            navbar.classList.add('shadow-md');
        } else {
            navbar.classList.remove('shadow-md');
        }
    },
    
    // Toggle mobile menu
    toggleMobileMenu: function() {
        const menu = document.querySelector('[data-mobile-menu]');
        if (menu) {
            menu.classList.toggle('hidden');
        }
    },
    
    // Utility: Show notification
    notify: function(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => notification.remove(), 5000);
    },
    
    // Utility: Make API call
    apiCall: function(endpoint, options = {}) {
        const url = this.config.siteUrl + endpoint;
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
            }
        };
        
        return fetch(url, { ...defaultOptions, ...options })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`API Error: ${response.status}`);
                }
                return response.json();
            })
            .catch(error => {
                console.error('API Call Error:', error);
                this.notify('Terjadi kesalahan', 'error');
                throw error;
            });
    }
};

// Debounce utility function
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

// Throttle utility function
function throttle(func, limit) {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Initialize app when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        RamezaApp.init();
    });
} else {
    RamezaApp.init();
}
