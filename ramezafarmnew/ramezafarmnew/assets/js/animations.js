/**
 * Animation & Effects
 * animations.js
 */

class AnimationManager {
    constructor() {
        this.init();
    }
    
    init() {
        this.setupScrollObserver();
        this.setupHoverEffects();
        this.setupParallax();
    }
    
    setupScrollObserver() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in-up');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        // Observe all elements with animation data attribute
        document.querySelectorAll('[data-animate], .fade-on-scroll').forEach(el => {
            observer.observe(el);
        });
    }
    
    setupHoverEffects() {
        document.querySelectorAll('[data-hover-effect]').forEach(element => {
            const effect = element.getAttribute('data-hover-effect');
            
            element.addEventListener('mouseenter', () => {
                element.style.animation = `${effect} 0.6s ease forwards`;
            });
            
            element.addEventListener('mouseleave', () => {
                element.style.animation = 'none';
            });
        });
    }
    
    setupParallax() {
        const parallaxElements = document.querySelectorAll('[data-parallax]');
        if (parallaxElements.length === 0) return;
        
        window.addEventListener('scroll', throttle(() => {
            parallaxElements.forEach(element => {
                const scrollY = window.scrollY;
                const elementOffset = element.offsetTop;
                const distance = scrollY - elementOffset;
                const speed = element.getAttribute('data-parallax') || 0.5;
                
                element.style.transform = `translateY(${distance * speed}px)`;
            });
        }, 16)); // ~60fps
    }
    
    // Animate counter numbers
    animateCounter(element, target, duration = 2000) {
        const start = 0;
        const increment = target / (duration / 16);
        let current = start;
        
        const counter = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target;
                clearInterval(counter);
            } else {
                element.textContent = Math.floor(current);
            }
        }, 16);
    }
    
    // Smooth color transition
    transitionColor(element, startColor, endColor, duration = 1000) {
        const start = Date.now();
        
        const animate = () => {
            const elapsed = Date.now() - start;
            const progress = Math.min(elapsed / duration, 1);
            
            const [r1, g1, b1] = this.hexToRgb(startColor);
            const [r2, g2, b2] = this.hexToRgb(endColor);
            
            const r = Math.round(r1 + (r2 - r1) * progress);
            const g = Math.round(g1 + (g2 - g1) * progress);
            const b = Math.round(b1 + (b2 - b1) * progress);
            
            element.style.color = `rgb(${r}, ${g}, ${b})`;
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        animate();
    }
    
    hexToRgb(hex) {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? [
            parseInt(result[1], 16),
            parseInt(result[2], 16),
            parseInt(result[3], 16)
        ] : [0, 0, 0];
    }
}

// CSS Animations to add to stylesheet
const animationStyles = `
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }
    
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
    
    .slide-in-left {
        animation: slideInLeft 0.6s ease-out;
    }
    
    .slide-in-right {
        animation: slideInRight 0.6s ease-out;
    }
    
    .scale-in {
        animation: scaleIn 0.6s ease-out;
    }
    
    .pulse {
        animation: pulse 2s infinite;
    }
</style>
`;

// Inject animation styles
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        document.head.insertAdjacentHTML('beforeend', animationStyles);
        new AnimationManager();
    });
} else {
    document.head.insertAdjacentHTML('beforeend', animationStyles);
    new AnimationManager();
}
