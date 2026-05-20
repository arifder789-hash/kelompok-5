'use strict';

/* ============================================================
   RAMEZA FARM — loginadmin.js
   Animasi & interaksi halaman login admin
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

  /* ────────────────────────────────────────
     1. ENTRANCE ANIMATIONS
     Elemen muncul satu per satu dari bawah
  ──────────────────────────────────────── */
  const animateEntrance = () => {
    const items = [
      '.admin-login-brand',
      '.admin-login-kicker',
      '.admin-login-heading h1',
      '.admin-login-heading p:last-child',
      '.admin-login-alert',
      '.admin-field:nth-child(1)',
      '.admin-field:nth-child(2)',
      '.admin-login-btn',
      '.admin-or-divider',
      '.admin-dashboard-link',
      '.admin-login-links',
    ];

    // Set initial state via inline style
    items.forEach(sel => {
      document.querySelectorAll(sel).forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(22px)';
        el.style.transition = 'none';
      });
    });

    // Stagger animate in
    items.forEach((sel, i) => {
      setTimeout(() => {
        document.querySelectorAll(sel).forEach(el => {
          el.style.transition = 'opacity 0.55s cubic-bezier(.4,0,.2,1), transform 0.55s cubic-bezier(.4,0,.2,1)';
          el.style.opacity    = '1';
          el.style.transform  = 'translateY(0)';
        });
      }, 80 + i * 70);
    });

    // Right panel: slide in from right
    const visual = document.querySelector('.admin-login-visual');
    if (visual) {
      const card = document.querySelector('.admin-visual-card');
      const copy = document.querySelector('.admin-visual-copy');
      [card, copy].forEach((el, i) => {
        if (!el) return;
        el.style.opacity   = '0';
        el.style.transform = `translateY(${i === 0 ? '-20px' : '30px'})`;
        el.style.transition = 'none';
        setTimeout(() => {
          el.style.transition = 'opacity 0.7s cubic-bezier(.4,0,.2,1), transform 0.7s cubic-bezier(.4,0,.2,1)';
          el.style.opacity   = '1';
          el.style.transform = 'translateY(0)';
        }, 300 + i * 160);
      });
    }
  };

  animateEntrance();


  /* ────────────────────────────────────────
     2. SHOW / HIDE PASSWORD
  ──────────────────────────────────────── */
  const toggle   = document.querySelector('[data-toggle-password]');
  const passInput = document.getElementById('password');

  if (toggle && passInput) {
    toggle.addEventListener('click', () => {
      const isHidden = passInput.type === 'password';
      passInput.type  = isHidden ? 'text' : 'password';
      toggle.textContent = isHidden ? 'Sembunyikan' : 'Lihat';
      toggle.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');

      // Micro-animation: shake the toggle slightly
      toggle.animate([
        { transform: 'scale(1)' },
        { transform: 'scale(0.9)' },
        { transform: 'scale(1.05)' },
        { transform: 'scale(1)' },
      ], { duration: 220, easing: 'ease' });
    });
  }


  /* ────────────────────────────────────────
     3. INPUT FOCUS EFFECTS
     Label glow + border highlight
  ──────────────────────────────────────── */
  document.querySelectorAll('.admin-field input').forEach(input => {
    const label = input.closest('.admin-field');

    input.addEventListener('focus', () => {
      label?.style && (label.style.transform = 'scale(1.01)');
      label?.style && (label.style.transition = 'transform 0.2s ease');
    });
    input.addEventListener('blur', () => {
      label?.style && (label.style.transform = 'scale(1)');
    });

    // Real-time validation feedback
    input.addEventListener('input', () => {
      if (input.value.trim()) {
        input.classList.remove('input-error');
      }
    });
  });


  /* ────────────────────────────────────────
     4. FORM SUBMIT ANIMATION
  ──────────────────────────────────────── */
  const form   = document.querySelector('.admin-login-form');
  const submitBtn = document.querySelector('.admin-login-btn');

  if (form && submitBtn) {
    form.addEventListener('submit', (e) => {
      const username = document.getElementById('username');
      const password = document.getElementById('password');
      let valid = true;

      // Basic client-side validation
      [username, password].forEach(field => {
        if (!field) return;
        if (!field.value.trim()) {
          field.classList.add('input-error');
          shakeElement(field);
          valid = false;
        } else {
          field.classList.remove('input-error');
        }
      });

      if (!valid) {
        e.preventDefault();
        return;
      }

      // Loading state
      submitBtn.disabled = true;
      submitBtn.innerHTML = `
        <span class="login-spinner"></span>
        Memeriksa…
      `;

      // Inject spinner style if not present
      if (!document.getElementById('spinner-style')) {
        const style = document.createElement('style');
        style.id = 'spinner-style';
        style.textContent = `
          .login-spinner {
            display: inline-block;
            width: 16px; height: 16px;
            border: 2.5px solid rgba(255,255,255,.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            flex-shrink: 0;
          }
          @keyframes spin { to { transform: rotate(360deg); } }
        `;
        document.head.appendChild(style);
      }
    });
  }


  /* ────────────────────────────────────────
     5. SHAKE ANIMATION (validation error)
  ──────────────────────────────────────── */
  function shakeElement(el) {
    el.animate([
      { transform: 'translateX(0)'   },
      { transform: 'translateX(-8px)' },
      { transform: 'translateX(8px)'  },
      { transform: 'translateX(-6px)' },
      { transform: 'translateX(6px)'  },
      { transform: 'translateX(-3px)' },
      { transform: 'translateX(0)'   },
    ], { duration: 400, easing: 'ease-out' });
  }


  /* ────────────────────────────────────────
     6. RIPPLE EFFECT on Submit Button
  ──────────────────────────────────────── */
  if (submitBtn) {
    submitBtn.style.position = 'relative';
    submitBtn.style.overflow = 'hidden';

    submitBtn.addEventListener('click', function (e) {
      const rect   = this.getBoundingClientRect();
      const x      = e.clientX - rect.left;
      const y      = e.clientY - rect.top;
      const ripple = document.createElement('span');

      ripple.style.cssText = `
        position: absolute;
        border-radius: 50%;
        background: rgba(255,255,255,0.28);
        width: 10px; height: 10px;
        left: ${x - 5}px; top: ${y - 5}px;
        transform: scale(0);
        pointer-events: none;
      `;
      this.appendChild(ripple);
      ripple.animate([
        { transform: 'scale(0)',  opacity: 1 },
        { transform: 'scale(28)', opacity: 0 },
      ], { duration: 550, easing: 'ease-out' }).onfinish = () => ripple.remove();
    });
  }


  /* ────────────────────────────────────────
     7. COUNTER ANIMATION (card stats)
  ──────────────────────────────────────── */
  const counterEls = document.querySelectorAll('.admin-card-stat-num[data-count]');
  counterEls.forEach(el => {
    const target   = parseFloat(el.dataset.count);
    const suffix   = el.dataset.suffix || '';
    const duration = 1600;
    const start    = performance.now();

    const tick = (now) => {
      const progress = Math.min((now - start) / duration, 1);
      const eased    = 1 - Math.pow(1 - progress, 3); // easeOutCubic
      el.textContent = Math.floor(eased * target) + suffix;
      if (progress < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  });


  /* ────────────────────────────────────────
     8. LIVE CLOCK in visual card
  ──────────────────────────────────────── */
  const clockEl = document.getElementById('admin-live-clock');
  if (clockEl) {
    const updateClock = () => {
      const now = new Date();
      const hh  = String(now.getHours()).padStart(2, '0');
      const mm  = String(now.getMinutes()).padStart(2, '0');
      const ss  = String(now.getSeconds()).padStart(2, '0');
      clockEl.textContent = `${hh}:${mm}:${ss}`;
    };
    updateClock();
    setInterval(updateClock, 1000);
  }


  /* ────────────────────────────────────────
     9. FLOATING PARTICLES on visual panel
  ──────────────────────────────────────── */
  const visual = document.querySelector('.admin-login-visual');
  if (visual) {
    const canvas = document.createElement('canvas');
    canvas.style.cssText = `
      position: absolute; inset: 0;
      width: 100%; height: 100%;
      pointer-events: none;
      z-index: 0; opacity: 0.35;
    `;
    visual.prepend(canvas);

    const ctx    = canvas.getContext('2d');
    let   W, H, dots;

    const resize = () => {
      W = canvas.width  = visual.offsetWidth;
      H = canvas.height = visual.offsetHeight;
    };

    const init = () => {
      resize();
      dots = Array.from({ length: 38 }, () => ({
        x:  Math.random() * W,
        y:  Math.random() * H,
        r:  Math.random() * 1.8 + 0.6,
        vx: (Math.random() - .5) * 0.35,
        vy: (Math.random() - .5) * 0.35,
        a:  Math.random(),
      }));
    };

    const draw = () => {
      ctx.clearRect(0, 0, W, H);
      dots.forEach(d => {
        d.x += d.vx;
        d.y += d.vy;
        if (d.x < 0 || d.x > W) d.vx *= -1;
        if (d.y < 0 || d.y > H) d.vy *= -1;

        ctx.beginPath();
        ctx.arc(d.x, d.y, d.r, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(255,255,255,${d.a * 0.7})`;
        ctx.fill();
      });

      // Draw connecting lines
      dots.forEach((a, i) => {
        dots.slice(i + 1).forEach(b => {
          const dx   = a.x - b.x;
          const dy   = a.y - b.y;
          const dist = Math.sqrt(dx * dx + dy * dy);
          if (dist < 110) {
            ctx.beginPath();
            ctx.moveTo(a.x, a.y);
            ctx.lineTo(b.x, b.y);
            ctx.strokeStyle = `rgba(255,255,255,${0.12 * (1 - dist / 110)})`;
            ctx.lineWidth   = 0.7;
            ctx.stroke();
          }
        });
      });
      requestAnimationFrame(draw);
    };

    window.addEventListener('resize', resize);
    init();
    draw();
  }


  /* ────────────────────────────────────────
     10. AUTO-DISMISS alert setelah 5 detik
  ──────────────────────────────────────── */
  const alert = document.querySelector('.admin-login-alert');
  if (alert) {
    setTimeout(() => {
      alert.animate([
        { opacity: 1, transform: 'translateY(0)' },
        { opacity: 0, transform: 'translateY(-10px)' },
      ], { duration: 400, easing: 'ease', fill: 'forwards' }).onfinish = () => {
        alert.style.display = 'none';
      };
    }, 5000);
  }


  /* ────────────────────────────────────────
     11. KEYBOARD SHORTCUT: Enter to submit
  ──────────────────────────────────────── */
  document.addEventListener('keydown', e => {
    if (e.key === 'Enter' && document.activeElement?.tagName !== 'BUTTON') {
      submitBtn?.click();
    }
  });

}); // end DOMContentLoaded
