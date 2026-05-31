/* ============================================================
   RAMEZA FARM — produk.js
   Interaktivitas halaman katalog produk
   Fitur: Cart AJAX, Quick View modal, Filter/Sort/View,
          Wishlist, Toast, Ripple, Skeleton
============================================================ */
'use strict';

document.addEventListener('DOMContentLoaded', () => {

  /* ──────────────────────────────────────────
     UTILITY
  ────────────────────────────────────────── */
  const $ = s => document.querySelector(s);
  const $$ = s => [...document.querySelectorAll(s)];

  function rupiah(n) {
    return 'Rp ' + Number(n).toLocaleString('id-ID');
  }

  function showToast(msg, type = 'success') {
    const t = $('#toast');
    if (!t) return;
    t.textContent = '';
    const icon = document.createElement('span');
    icon.textContent = type === 'success' ? '✅' : type === 'error' ? '⚠️' : 'ℹ️';
    t.appendChild(icon);
    t.append(' ' + msg);
    t.className = `toast toast-${type} show`;
    clearTimeout(t._timer);
    t._timer = setTimeout(() => t.classList.remove('show'), 3000);
  }

  /* Ripple on any element */
  function addRipple(el, e) {
    const rect = el.getBoundingClientRect();
    const x = (e?.clientX ?? rect.left + rect.width / 2) - rect.left;
    const y = (e?.clientY ?? rect.top + rect.height / 2) - rect.top;
    const rip = document.createElement('span');
    rip.style.cssText = `
      position:absolute; border-radius:50%;
      background:rgba(255,255,255,.3);
      width:8px; height:8px;
      left:${x - 4}px; top:${y - 4}px;
      transform:scale(0); pointer-events:none;
    `;
    el.appendChild(rip);
    rip.animate([
      { transform: 'scale(0)', opacity: 1 },
      { transform: 'scale(28)', opacity: 0 },
    ], { duration: 550, easing: 'ease-out' }).onfinish = () => rip.remove();
  }


  /* ──────────────────────────────────────────
     1. CART — AJAX actions
  ────────────────────────────────────────── */
  const CART_ENDPOINT = '../ajax/cart_action.php';

  async function cartAction(action, payload = {}) {
    try {
      const res = await fetch(CART_ENDPOINT, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action, ...payload }),
      });
      return await res.json();
    } catch {
      return { ok: false, msg: 'Koneksi gagal.' };
    }
  }

  /* Update FAB badge */
  function updateBadge(count) {
    $$('.cart-badge').forEach(el => {
      el.textContent = count;
      el.style.display = count > 0 ? '' : 'none';
    });
    const fab = $('#cart-fab');
    if (fab && count > 0) {
      fab.classList.add('bump');
      setTimeout(() => fab.classList.remove('bump'), 450);
    }
  }

  /* Render sidebar items */
  async function refreshSidebar() {
    const data = await cartAction('summary');
    if (!data.ok) return;
    updateBadge(data.count);

    const list = $('#cart-sidebar-items');
    const totalEl = $('#sidebar-total');
    if (totalEl) totalEl.textContent = rupiah(data.total);

    if (!list) return;
    if (!data.items?.length) {
      list.innerHTML = `
        <div class="cart-empty-msg">
          <div class="cart-empty-icon">🛒</div>
          <p>Keranjang masih kosong.<br/>Yuk tambah produk!</p>
        </div>`;
      return;
    }

    list.innerHTML = data.items.map(item => `
      <div class="cart-item-row" id="si-${item.produk_id}">
        <div class="cart-item-img">
          <img src="${item.gambar || '../assets/img/no-image.png'}"
               alt="${item.nama}"
               onerror="this.src='https://placehold.co/60x60/e8eefb/2d5be3?text=📦'"/>
        </div>
        <div>
          <div class="cart-item-name">${item.nama}</div>
          <div class="cart-item-price">${rupiah(item.harga)} / ${item.satuan}</div>
          <div class="sidebar-qty-control" style="display:flex; align-items:center; gap:8px; margin-top:4px;">
            <button type="button" class="sidebar-qty-btn minus" data-id="${item.produk_id}" data-qty="${item.qty}" style="width:24px; height:24px; border:1px solid #e2e8f0; background:#f8fafc; border-radius:4px; cursor:pointer; display:flex; align-items:center; justify-content:center; font-weight:bold; color:#64748b;">-</button>
            <span style="font-size:13px; font-weight:600; min-width:16px; text-align:center;">${item.qty}</span>
            <button type="button" class="sidebar-qty-btn plus" data-id="${item.produk_id}" data-qty="${item.qty}" style="width:24px; height:24px; border:1px solid #e2e8f0; background:#f8fafc; border-radius:4px; cursor:pointer; display:flex; align-items:center; justify-content:center; font-weight:bold; color:#64748b;">+</button>
          </div>
        </div>
        <div class="cart-item-right">
          <div class="cart-item-sub">${rupiah(item.subtotal)}</div>
          <button class="cart-item-remove" data-id="${item.produk_id}" aria-label="Hapus">✕</button>
        </div>
      </div>
    `).join('');

    // Remove buttons
    list.querySelectorAll('.cart-item-remove').forEach(btn => {
      btn.addEventListener('click', async () => {
        const id = +btn.dataset.id;
        btn.closest('.cart-item-row').style.opacity = '.4';
        const d = await cartAction('remove', { produk_id: id });
        if (d.ok) {
          refreshSidebar();
          showToast('Item dihapus dari keranjang.', 'success');
        }
      });
    });

    // Qty control buttons
    list.querySelectorAll('.sidebar-qty-btn').forEach(btn => {
      btn.addEventListener('click', async () => {
        const id = +btn.dataset.id;
        let qty = +btn.dataset.qty;
        if (btn.classList.contains('minus')) {
          qty--;
        } else {
          qty++;
        }
        
        if (qty < 1) return; // Use remove button for 0

        btn.closest('.cart-item-row').style.opacity = '.4';
        const d = await cartAction('update', { produk_id: id, qty: qty });
        if (d.ok) {
          refreshSidebar();
        } else {
          showToast(d.msg || 'Gagal mengubah jumlah.', 'error');
          refreshSidebar(); // reload to reset opacity
        }
      });
    });
  }


  /* ──────────────────────────────────────────
     2. ADD TO CART — product cards
  ────────────────────────────────────────── */
  function bindAddToCart() {
    $$('.add-to-cart-btn:not(.disabled)').forEach(btn => {
      btn.addEventListener('click', async function (e) {
        const card = this.closest('.product-card');
        const qtyInput = card?.querySelector('.qty-input');
        const qty = qtyInput ? Math.max(1, +qtyInput.value) : 1;
        const id  = +this.dataset.id;

        addRipple(this, e);
        this.classList.add('adding');
        this.textContent = '✓ Ditambahkan!';

        const data = await cartAction('add', { produk_id: id, qty });

        if (data.ok) {
          updateBadge(data.count);
          await refreshSidebar();
          openSidebar();
          showToast(`${this.dataset.nama || 'Produk'} ditambahkan ke keranjang!`);
        } else {
          showToast(data.msg || 'Gagal menambahkan.', 'error');
        }

        setTimeout(() => {
          this.classList.remove('adding');
          this.innerHTML = '🛒 Tambah';
        }, 2000);
      });
    });
  }
  bindAddToCart();


  /* ──────────────────────────────────────────
     3. QTY CONTROL — +/- buttons
  ────────────────────────────────────────── */
  function bindQtyControls() {
    $$('.qty-control').forEach(ctrl => {
      const minus = ctrl.querySelector('.minus');
      const plus  = ctrl.querySelector('.plus');
      const input = ctrl.querySelector('.qty-input');
      if (!input) return;
      const min = +input.min || 1;
      const max = +input.max || 9999;

      minus?.addEventListener('click', () => {
        input.value = Math.max(min, +input.value - 1);
      });
      plus?.addEventListener('click', () => {
        input.value = Math.min(max, +input.value + 1);
      });
      input.addEventListener('change', () => {
        let v = +input.value;
        input.value = Math.min(max, Math.max(min, v || min));
      });
    });
  }
  bindQtyControls();


  /* ──────────────────────────────────────────
     4. CART SIDEBAR — open / close
  ────────────────────────────────────────── */
  const sidebar  = $('#cart-sidebar');
  const overlay  = $('#cart-overlay');

  function openSidebar()  {
    sidebar?.classList.add('open');
    overlay?.classList.add('open');
    document.body.style.overflow = 'hidden';
    refreshSidebar();
  }
  function closeSidebar() {
    sidebar?.classList.remove('open');
    overlay?.classList.remove('open');
    document.body.style.overflow = '';
  }

  $('#close-sidebar')?.addEventListener('click', closeSidebar);
  overlay?.addEventListener('click', closeSidebar);

  // FAB button
  const fab = $('#cart-fab');
  fab?.addEventListener('click', e => { e.preventDefault(); openSidebar(); });


  /* ──────────────────────────────────────────
     5. QUICK VIEW MODAL
  ────────────────────────────────────────── */
  const modalOverlay = $('#modal-overlay');
  const modalClose   = $('#modal-close');

  function openModal(data) {
    if (!modalOverlay) return;
    // Fill modal content
    const img   = $('#modal-img-el');
    const kode  = $('#modal-kode');
    const name  = $('#modal-name');
    const desc  = $('#modal-desc');
    const price = $('#modal-price');
    const meta  = $('#modal-meta');
    const stok  = $('#modal-stok');

    if (img)   img.src = data.gambar || '../assets/img/no-image.png';
    if (kode)  kode.textContent  = data.kode;
    if (name)  name.textContent  = data.nama;
    if (desc)  desc.textContent  = data.deskripsi;
    if (price) price.textContent = rupiah(data.harga);
    if (meta)  meta.textContent  = `Per ${data.satuan} · Min. pesan: ${data.min_pesan}`;
    if (stok) {
      stok.textContent = data.stok > 0 ? `✓ Stok tersedia (${data.stok} ${data.satuan})` : '✕ Stok habis';
      stok.className   = `modal-stok${data.stok === 0 ? ' habis' : ''}`;
    }

    // Modal add-to-cart
    const modalAddBtn = $('#modal-add-btn');
    if (modalAddBtn) {
      modalAddBtn.dataset.id    = data.id;
      modalAddBtn.dataset.nama  = data.nama;
      modalAddBtn.dataset.harga = data.harga;
      modalAddBtn.dataset.min   = data.min_pesan;
      modalAddBtn.disabled      = data.stok === 0;
      modalAddBtn.className     = `add-to-cart-btn${data.stok === 0 ? ' disabled' : ''}`;
    }

    modalOverlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    modalOverlay?.classList.remove('open');
    document.body.style.overflow = '';
  }

  modalClose?.addEventListener('click', closeModal);
  modalOverlay?.addEventListener('click', e => { if (e.target === modalOverlay) closeModal(); });

  // Quick view buttons
  function bindQuickView() {
    $$('.quick-view-btn').forEach(btn => {
      btn.addEventListener('click', e => {
        e.stopPropagation();
        const card = btn.closest('.product-card');
        const data = {
          id         : card.dataset.id,
          kode       : card.querySelector('.product-kode')?.textContent || '',
          nama       : card.querySelector('.product-name')?.textContent || '',
          deskripsi  : card.dataset.deskripsi || card.querySelector('.product-desc')?.textContent || '',
          harga      : card.dataset.harga || 0,
          satuan     : card.dataset.satuan || '',
          min_pesan  : card.dataset.min || 1,
          stok       : +card.dataset.stok || 0,
          gambar     : card.querySelector('.product-img-wrap img')?.src || '',
        };
        openModal(data);
      });
    });
  }
  bindQuickView();

  // Modal add-to-cart
  $('#modal-add-btn')?.addEventListener('click', async function (e) {
    if (this.disabled) return;
    const qty = +($('#modal-qty')?.value || 1);
    addRipple(this, e);
    this.classList.add('adding');
    this.textContent = '✓ Ditambahkan!';
    const data = await cartAction('add', { produk_id: +this.dataset.id, qty });
    if (data.ok) {
      updateBadge(data.count);
      await refreshSidebar();
      showToast(`${this.dataset.nama} ditambahkan ke keranjang!`);
    } else {
      showToast(data.msg || 'Gagal.', 'error');
    }
    setTimeout(() => { this.classList.remove('adding'); this.textContent = '🛒 Tambah ke Keranjang'; }, 2000);
  });


  /* ──────────────────────────────────────────
     6. VIEW TOGGLE — grid / list
  ────────────────────────────────────────── */
  const grid     = $('#product-grid');
  const btnGrid  = $('#view-grid');
  const btnList  = $('#view-list');

  btnGrid?.addEventListener('click', () => {
    grid?.classList.remove('list-view');
    btnGrid.classList.add('active');
    btnList?.classList.remove('active');
    localStorage.setItem('rmz_view', 'grid');
  });
  btnList?.addEventListener('click', () => {
    grid?.classList.add('list-view');
    btnList.classList.add('active');
    btnGrid?.classList.remove('active');
    localStorage.setItem('rmz_view', 'list');
  });

  // Restore saved view
  if (localStorage.getItem('rmz_view') === 'list') {
    grid?.classList.add('list-view');
    btnList?.classList.add('active');
    btnGrid?.classList.remove('active');
  }


  /* ──────────────────────────────────────────
     7. CLIENT-SIDE SORT (tanpa reload)
  ────────────────────────────────────────── */
  const sortSelect = $('#sort-select');
  sortSelect?.addEventListener('change', () => {
    if (!grid) return;
    const cards = [...grid.querySelectorAll('.product-card')];
    const val = sortSelect.value;

    cards.sort((a, b) => {
      const pa = +a.dataset.harga || 0;
      const pb = +b.dataset.harga || 0;
      const na = a.querySelector('.product-name')?.textContent || '';
      const nb = b.querySelector('.product-name')?.textContent || '';
      if (val === 'harga-asc')  return pa - pb;
      if (val === 'harga-desc') return pb - pa;
      if (val === 'nama-asc')   return na.localeCompare(nb);
      if (val === 'nama-desc')  return nb.localeCompare(na);
      return 0;
    });

    cards.forEach((card, i) => {
      card.style.animationDelay = `${i * 0.04}s`;
      card.style.animation = 'none';
      requestAnimationFrame(() => {
        card.style.animation = '';
        grid.appendChild(card);
      });
    });
  });


  /* ──────────────────────────────────────────
     8. WISHLIST toggle
  ────────────────────────────────────────── */
  let wishlist = JSON.parse(localStorage.getItem('rmz_wishlist') || '[]');

  function syncWishlist() {
    $$('.wishlist-btn').forEach(btn => {
      const id = btn.dataset.id;
      btn.textContent = wishlist.includes(id) ? '❤️' : '🤍';
      btn.classList.toggle('active', wishlist.includes(id));
    });
  }

  function bindWishlist() {
    $$('.wishlist-btn').forEach(btn => {
      btn.addEventListener('click', e => {
        e.stopPropagation();
        const id = btn.dataset.id;
        if (wishlist.includes(id)) {
          wishlist = wishlist.filter(x => x !== id);
          showToast('Dihapus dari wishlist.', 'error');
        } else {
          wishlist.push(id);
          showToast('Ditambahkan ke wishlist! ❤️');
        }
        localStorage.setItem('rmz_wishlist', JSON.stringify(wishlist));
        syncWishlist();

        btn.animate([
          { transform: 'scale(1)' }, { transform: 'scale(1.5)' }, { transform: 'scale(1)' }
        ], { duration: 350 });
      });
    });
  }
  syncWishlist();
  bindWishlist();


  /* ──────────────────────────────────────────
     9. KEYBOARD SHORTCUTS
  ────────────────────────────────────────── */
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeSidebar(); closeModal(); }
    // Ctrl+K = focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
      e.preventDefault();
      $('.search-input')?.focus();
    }
  });


  /* ──────────────────────────────────────────
     10. NAVBAR scroll effect
  ────────────────────────────────────────── */
  const navbar = $('#navbar');
  window.addEventListener('scroll', () => {
    navbar?.classList.toggle('scrolled', window.scrollY > 50);
  }, { passive: true });


  /* ──────────────────────────────────────────
     11. INITIAL cart badge load
  ────────────────────────────────────────── */
  (async () => {
    const data = await cartAction('summary');
    if (data.ok) updateBadge(data.count);
  })();


  /* ──────────────────────────────────────────
     12. IMAGE lazy-load fallback
  ────────────────────────────────────────── */
  if ('IntersectionObserver' in window) {
    const imgObs = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          if (img.dataset.src) { img.src = img.dataset.src; delete img.dataset.src; }
          imgObs.unobserve(img);
        }
      });
    }, { rootMargin: '100px' });
    $$('img[data-src]').forEach(img => imgObs.observe(img));
  }

}); // end DOMContentLoaded
