/* ============================================================
   RAMEZA FARM — loginuser.js
   Animasi & interaksi halaman login pelanggan
============================================================ */
'use strict';

document.addEventListener('DOMContentLoaded', () => {

  /* ── 1. STAGGERED ENTRANCE ──────────────────────────────── */
  document.querySelectorAll('[data-anim]').forEach((el, i) => {
    const type = el.dataset.anim;
    el.style.opacity   = '0';
    el.style.transform =
      type === 'fadeDown' ? 'translateY(-24px)' :
      type === 'fadeIn'   ? 'scale(.96)'        :
                            'translateY(28px)';
    el.style.transition = 'none';
    setTimeout(() => {
      el.style.transition = 'opacity .6s cubic-bezier(.4,0,.2,1), transform .6s cubic-bezier(.4,0,.2,1)';
      el.style.opacity    = '1';
      el.style.transform  = 'none';
    }, 100 + i * 90);
  });


  /* ── 2. PARTICLE CANVAS ─────────────────────────────────── */
  const canvas = document.getElementById('particle-canvas');
  if (canvas) {
    const ctx = canvas.getContext('2d');
    let W, H, dots;

    const resize = () => {
      W = canvas.width  = canvas.parentElement.offsetWidth;
      H = canvas.height = canvas.parentElement.offsetHeight;
    };

    const init = () => {
      resize();
      dots = Array.from({ length: 45 }, () => ({
        x: Math.random() * W,  y: Math.random() * H,
        r: Math.random() * 1.6 + .5,
        vx: (Math.random() - .5) * .4, vy: (Math.random() - .5) * .4,
        a: Math.random() * .7 + .1,
      }));
    };

    const draw = () => {
      ctx.clearRect(0, 0, W, H);
      dots.forEach(d => {
        d.x += d.vx; d.y += d.vy;
        if (d.x < 0 || d.x > W) d.vx *= -1;
        if (d.y < 0 || d.y > H) d.vy *= -1;
        ctx.beginPath();
        ctx.arc(d.x, d.y, d.r, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(255,255,255,${d.a})`;
        ctx.fill();
      });
      dots.forEach((a, i) => {
        dots.slice(i + 1).forEach(b => {
          const dx = a.x - b.x, dy = a.y - b.y;
          const dist = Math.sqrt(dx * dx + dy * dy);
          if (dist < 120) {
            ctx.beginPath();
            ctx.moveTo(a.x, a.y); ctx.lineTo(b.x, b.y);
            ctx.strokeStyle = `rgba(255,255,255,${.1 * (1 - dist / 120)})`;
            ctx.lineWidth = .8; ctx.stroke();
          }
        });
      });
      requestAnimationFrame(draw);
    };

    window.addEventListener('resize', resize);
    init(); draw();
  }


  /* ── 3. COUNTER ANIMATION ───────────────────────────────── */
  document.querySelectorAll('.visual-stat-num[data-count]').forEach(el => {
    const target = +el.dataset.count;
    const suffix = el.dataset.suffix || '';
    setTimeout(() => {
      const t0 = performance.now();
      const tick = now => {
        const p = Math.min((now - t0) / 1800, 1);
        const e = 1 - Math.pow(1 - p, 3);
        el.textContent = Math.floor(e * target) + suffix;
        if (p < 1) requestAnimationFrame(tick);
      };
      requestAnimationFrame(tick);
    }, 500);
  });


  /* ── 4. SHOW / HIDE PASSWORD ────────────────────────────── */
  const pwInput  = document.getElementById('password');
  const pwToggle = document.getElementById('pw-toggle');

  pwToggle?.addEventListener('click', () => {
    const show = pwInput.type === 'password';
    pwInput.type = show ? 'text' : 'password';
    pwToggle.textContent = show ? 'Sembunyi' : 'Lihat';
    pwToggle.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
    pwToggle.animate([
      { transform:'scale(1)' }, { transform:'scale(.85)' },
      { transform:'scale(1.1)' }, { transform:'scale(1)' },
    ], { duration: 220 });
  });


  /* ── 5. PASSWORD STRENGTH METER ─────────────────────────── */
  const strengthWrap  = document.getElementById('pw-strength-wrap');
  const strengthBar   = document.getElementById('pw-strength-bar');
  const strengthLabel = document.getElementById('pw-strength-label');

  const calcStrength = pw => {
    let s = 0;
    if (pw.length >= 6)          s++;
    if (pw.length >= 10)         s++;
    if (/[A-Z]/.test(pw))        s++;
    if (/[0-9]/.test(pw))        s++;
    if (/[^A-Za-z0-9]/.test(pw)) s++;
    return s;
  };

  const S_COLORS = ['','#ef4444','#f97316','#eab308','#22c55e','#16a34a'];
  const S_LABELS = ['','Sangat Lemah','Lemah','Cukup','Kuat','Sangat Kuat'];
  const S_PCT    = [0,20,40,60,80,100];

  pwInput?.addEventListener('input', () => {
    const val = pwInput.value;
    if (!val) { strengthWrap?.classList.remove('show'); return; }
    strengthWrap?.classList.add('show');
    const s = calcStrength(val);
    if (strengthBar) {
      strengthBar.style.width      = S_PCT[s] + '%';
      strengthBar.style.background = S_COLORS[s] || '#e2e8f0';
    }
    if (strengthLabel) {
      strengthLabel.textContent = S_LABELS[s] || '';
      strengthLabel.style.color = S_COLORS[s] || '#94a3b8';
    }
  });


  /* ── 6. LIVE FIELD VALIDATION ───────────────────────────── */
  const usernameInput = document.getElementById('username');
  const usernameHint  = document.getElementById('username-hint');
  const usernameIcon  = document.getElementById('username-icon');
  const passwordHint  = document.getElementById('password-hint');

  const setHint = (el, msg, type) => {
    if (!el) return;
    el.textContent = msg;
    el.className   = `field-hint show ${type}`;
  };
  const clearHint = el => { if (el) el.className = 'field-hint'; };

  usernameInput?.addEventListener('input', () => {
    const val = usernameInput.value.trim();
    if (!val) {
      clearHint(usernameHint);
      usernameInput.classList.remove('is-valid','is-invalid');
      if (usernameIcon) usernameIcon.textContent = '✏️';
    } else if (val.length < 3) {
      setHint(usernameHint, '⚠️ Minimal 3 karakter', 'error');
      usernameInput.classList.add('is-invalid');
      usernameInput.classList.remove('is-valid');
      if (usernameIcon) usernameIcon.textContent = '❌';
    } else {
      setHint(usernameHint, '✓ Terlihat bagus!', 'ok');
      usernameInput.classList.add('is-valid');
      usernameInput.classList.remove('is-invalid');
      if (usernameIcon) usernameIcon.textContent = '✅';
    }
  });

  pwInput?.addEventListener('input', () => {
    const val = pwInput.value;
    if (!val) { clearHint(passwordHint); return; }
    if (val.length < 6) setHint(passwordHint, '⚠️ Minimal 6 karakter', 'error');
    else clearHint(passwordHint);
  });


  /* ── 7. RIPPLE EFFECT ───────────────────────────────────── */
  const submitBtn = document.getElementById('login-submit');

  submitBtn?.addEventListener('click', function (e) {
    const rect = this.getBoundingClientRect();
    const rip  = document.createElement('span');
    rip.style.cssText = `
      position:absolute; border-radius:50%; background:rgba(255,255,255,.25);
      width:8px; height:8px;
      left:${e.clientX - rect.left - 4}px; top:${e.clientY - rect.top - 4}px;
      transform:scale(0); pointer-events:none;
    `;
    this.appendChild(rip);
    rip.animate([
      { transform:'scale(0)', opacity:1 },
      { transform:'scale(32)', opacity:0 },
    ], { duration:600, easing:'ease-out' }).onfinish = () => rip.remove();
  });


  /* ── 8. FORM SUBMIT ─────────────────────────────────────── */
  const form = document.getElementById('login-form');

  form?.addEventListener('submit', e => {
    const uVal = usernameInput?.value.trim();
    const pVal = pwInput?.value;
    let ok = true;

    if (!uVal) {
      usernameInput?.classList.add('is-invalid');
      shake(usernameInput);
      setHint(usernameHint, '⚠️ Username wajib diisi', 'error');
      ok = false;
    }
    if (!pVal) {
      pwInput?.classList.add('is-invalid');
      shake(pwInput);
      setHint(passwordHint, '⚠️ Password wajib diisi', 'error');
      ok = false;
    }
    if (!ok) { e.preventDefault(); return; }

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.classList.add('loading');
    }
  });


  /* ── 9. SHAKE UTILITY ───────────────────────────────────── */
  function shake(el) {
    el?.animate([
      { transform:'translateX(0)' }, { transform:'translateX(-8px)' },
      { transform:'translateX(8px)' }, { transform:'translateX(-5px)' },
      { transform:'translateX(5px)' }, { transform:'translateX(0)' },
    ], { duration:380, easing:'ease-out' });
  }


  /* ── 10. AUTO-DISMISS ALERT ─────────────────────────────── */
  const alertEl = document.getElementById('login-alert');
  if (alertEl) {
    setTimeout(() => {
      alertEl.animate([
        { opacity:1, transform:'translateY(0)' },
        { opacity:0, transform:'translateY(-10px)' },
      ], { duration:400, fill:'forwards' }).onfinish = () => alertEl.remove();
    }, 5000);
  }


  /* ── 11. AUTO-FOCUS ─────────────────────────────────────── */
  setTimeout(() => {
    const target = usernameInput?.value ? pwInput : usernameInput;
    target?.focus();
  }, 700);


  /* ── 12. 3D TILT (panel kiri, desktop only) ─────────────── */
  const visualPanel = document.querySelector('.login-visual');
  if (visualPanel && window.innerWidth > 900) {
    visualPanel.addEventListener('mousemove', e => {
      const { left, top, width, height } = visualPanel.getBoundingClientRect();
      const x = (e.clientX - left) / width  - .5;
      const y = (e.clientY - top)  / height - .5;
      visualPanel.style.transform =
        `perspective(1200px) rotateY(${x * 3}deg) rotateX(${-y * 2}deg)`;
    });
    visualPanel.addEventListener('mouseleave', () => {
      visualPanel.style.transition = 'transform .6s ease';
      visualPanel.style.transform  = 'none';
      setTimeout(() => { visualPanel.style.transition = ''; }, 600);
    });
  }

}); // end DOMContentLoaded
