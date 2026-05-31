document.addEventListener('DOMContentLoaded', () => {
    // 1. Particle Canvas Background
    const canvas = document.getElementById('particle-canvas');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let width = canvas.width = canvas.offsetWidth;
        let height = canvas.height = canvas.offsetHeight;
        let particles = [];
        const particleCount = 40;

        window.addEventListener('resize', () => {
            width = canvas.width = canvas.offsetWidth;
            height = canvas.height = canvas.offsetHeight;
        });

        class Particle {
            constructor() {
                this.x = Math.random() * width;
                this.y = Math.random() * height;
                this.size = Math.random() * 2 + 1;
                this.speedX = Math.random() * 1 - 0.5;
                this.speedY = Math.random() * 1 - 0.5;
                this.opacity = Math.random() * 0.5 + 0.1;
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                if (this.x > width) this.x = 0;
                else if (this.x < 0) this.x = width;
                if (this.y > height) this.y = 0;
                else if (this.y < 0) this.y = height;
            }
            draw() {
                ctx.fillStyle = `rgba(255, 255, 255, ${this.opacity})`;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        for (let i = 0; i < particleCount; i++) {
            particles.push(new Particle());
        }

        function animate() {
            ctx.clearRect(0, 0, width, height);
            particles.forEach(p => {
                p.update();
                p.draw();
            });
            requestAnimationFrame(animate);
        }
        animate();
    }

    // 2. Counter Animation
    const stats = document.querySelectorAll('.visual-stat-num');
    stats.forEach(stat => {
        const target = +stat.getAttribute('data-count');
        const suffix = stat.getAttribute('data-suffix') || '';
        let current = 0;
        const increment = target / 50; 

        const updateCounter = () => {
            current += increment;
            if (current < target) {
                stat.innerText = Math.ceil(current) + suffix;
                requestAnimationFrame(updateCounter);
            } else {
                stat.innerText = target + suffix;
            }
        };
        updateCounter();
    });

    // 3. Password Toggle & Strength
    const pwInput = document.getElementById('password');
    const pwToggle = document.getElementById('pw-toggle');
    const pwStrengthWrap = document.getElementById('pw-strength-wrap');
    const pwStrengthBar = document.getElementById('pw-strength-bar');
    const pwStrengthLabel = document.getElementById('pw-strength-label');

    if (pwInput && pwToggle) {
        pwToggle.addEventListener('click', () => {
            const type = pwInput.getAttribute('type') === 'password' ? 'text' : 'password';
            pwInput.setAttribute('type', type);
            pwToggle.innerText = type === 'password' ? 'Lihat' : 'Sembunyikan';
        });

        pwInput.addEventListener('input', () => {
            const val = pwInput.value;
            if (!val) {
                pwStrengthWrap.classList.remove('show');
                return;
            }
            pwStrengthWrap.classList.add('show');
            let strength = 0;
            if (val.length > 5) strength += 25;
            if (val.length > 7) strength += 25;
            if (/[A-Z]/.test(val)) strength += 25;
            if (/[0-9]/.test(val)) strength += 25;
            if (/[^A-Za-z0-9]/.test(val)) strength += 25;

            strength = Math.min(100, strength);
            pwStrengthBar.style.width = strength + '%';

            if (strength < 50) {
                pwStrengthBar.style.background = '#ef4444';
                pwStrengthLabel.innerText = 'Lemah';
            } else if (strength < 75) {
                pwStrengthBar.style.background = '#f59e0b';
                pwStrengthLabel.innerText = 'Sedang';
            } else {
                pwStrengthBar.style.background = '#10b981';
                pwStrengthLabel.innerText = 'Kuat';
            }
        });
    }

    const confirmPwInput = document.getElementById('confirm_password');
    const confirmPwToggle = document.getElementById('pw-toggle-confirm');
    
    if (confirmPwInput && confirmPwToggle) {
        confirmPwToggle.addEventListener('click', () => {
            const type = confirmPwInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPwInput.setAttribute('type', type);
            confirmPwToggle.innerText = type === 'password' ? 'Lihat' : 'Sembunyikan';
        });
    }

    // 4. Fade Animations
    const animatedElements = document.querySelectorAll('[data-anim]');
    animatedElements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = el.dataset.anim === 'fadeUp' ? 'translateY(20px)' : (el.dataset.anim === 'fadeDown' ? 'translateY(-20px)' : 'none');
        el.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
        
        setTimeout(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, 100 * (index + 1));
    });
});
