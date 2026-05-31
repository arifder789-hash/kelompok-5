/**
 * Form Handling & Validation
 * form-handler.js
 */

class FormHandler {
    constructor() {
        this.forms = document.querySelectorAll('[data-form]');
        this.init();
    }
    
    init() {
        this.forms.forEach(form => {
            form.addEventListener('submit', (e) => this.handleSubmit(e));
            
            // Real-time validation
            form.querySelectorAll('input, textarea, select').forEach(field => {
                field.addEventListener('blur', () => this.validateField(field));
            });
        });
    }
    
    handleSubmit(event) {
        event.preventDefault();
        const form = event.target;
        
        // Validate all fields
        if (!this.validateForm(form)) {
            RamezaApp.notify('Mohon periksa kembali form', 'warning');
            return;
        }
        
        // Submit form
        this.submitForm(form);
    }
    
    validateForm(form) {
        let isValid = true;
        form.querySelectorAll('[required]').forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });
        return isValid;
    }
    
    validateField(field) {
        const value = field.value.trim();
        const type = field.getAttribute('type');
        let isValid = true;
        
        // Remove previous error state
        field.classList.remove('error');
        
        // Required check
        if (field.hasAttribute('required') && !value) {
            isValid = false;
        }
        
        // Email validation
        if (type === 'email' && value && !this.isValidEmail(value)) {
            isValid = false;
        }
        
        // Phone validation
        if (field.getAttribute('data-type') === 'phone' && value && !this.isValidPhone(value)) {
            isValid = false;
        }
        
        if (!isValid) {
            field.classList.add('error');
        }
        
        return isValid;
    }
    
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    isValidPhone(phone) {
        const phoneRegex = /^(\+62|62|0)[0-9]{9,12}$/;
        return phoneRegex.test(phone.replace(/\D/g, ''));
    }
    
    submitForm(form) {
        const formData = new FormData(form);
        const endpoint = form.getAttribute('data-endpoint') || form.getAttribute('action');
        
        // Show loading state
        const submitBtn = form.querySelector('[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Mengirim...';
        
        RamezaApp.apiCall(endpoint, {
            method: 'POST',
            body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => {
            if (response.success) {
                RamezaApp.notify('Berhasil dikirim!', 'success');
                form.reset();
            } else {
                RamezaApp.notify(response.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(error => {
            console.error('Form submission error:', error);
            RamezaApp.notify('Gagal mengirim form', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    }
}

// Initialize form handler when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new FormHandler();
    });
} else {
    new FormHandler();
}
