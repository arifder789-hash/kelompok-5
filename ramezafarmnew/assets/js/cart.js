/* ============================================
   assets/js/cart.js
   Handles: Add to cart, update qty, remove,
            cart sidebar, toast, badge update
   ============================================ */

'use strict';

// ── Utility: AJAX ke cart_action.php ────────
async function cartAction(action, payload = {}) {
  try {
    const res = await fetch('../ajax/cart_action.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action, ...payload }),
    });
    return await res.json();
  } catch {
    return { ok: false, msg: 'Koneksi gagal. Coba lagi.' };
  }
}

// ── Utility: Format Rupiah ───────────────────
function rupiah(num) {
  return 'Rp ' + Number(num).toLocaleString('id-ID');
}

function imagePath(path) {
  if (!path) return '../assets/img/no-image.png';
  return path.startsWith('assets/') ? `../${path}` : path;
}

// ── Toast notification ───────────────────────
function showToast(msg, type = 'success') {
  const t = document.getElementById('toast');
  if (!t) return;
  t.textContent = msg;
  t.className = `toast toast-${type} show`;
  clearTimeout(t._timer);
  t._timer = setTimeout(() => t.classList.remove('show'), 3000);
}

// ── Update cart badge count ──────────────────
function updateBadge(count) {
  document.querySelectorAll('#cart-badge, .cart-badge').forEach(el => {
    el.textContent = count;
    el.style.display = count > 0 ? '' : 'none';
  });
}

// ── Cart Sidebar ─────────────────────────────
const sidebar    = document.getElementById('cart-sidebar');
const overlay    = document.getElementById('cart-overlay');
const closeSbBtn = document.getElementById('close-sidebar');

function openSidebar()  { sidebar?.classList.add('open'); overlay?.classList.add('open'); document.body.style.overflow = 'hidden'; }
function closeSidebar() { sidebar?.classList.remove('open'); overlay?.classList.remove('open'); document.body.style.overflow = ''; }

closeSbBtn?.addEventListener('click', closeSidebar);
overlay?.addEventListener('click', closeSidebar);

async function refreshSidebar() {
  if (!sidebar) return;
  const data = await cartAction('summary');
  if (!data.ok) return;

  updateBadge(data.count);
  document.getElementById('sidebar-total').textContent = rupiah(data.total);

  const listEl = document.getElementById('cart-sidebar-items');
  if (!listEl) return;

  if (!data.items.length) {
    listEl.innerHTML = '<p class="cart-empty-msg">Keranjang masih kosong.</p>';
    return;
  }

  listEl.innerHTML = data.items.map(item => `
    <div class="sidebar-item">
      <img src="${imagePath(item.gambar)}"
           alt="${item.nama}"
           onerror="this.src='https://placehold.co/48x48/e8eefb/2d5be3?text=📦'"/>
      <div class="sidebar-item-info">
        <div class="sidebar-item-name">${item.nama}</div>
        <div class="sidebar-item-price">${rupiah(item.harga)} / ${item.satuan}</div>
      </div>
      <div>
        <div class="sidebar-item-sub">${rupiah(item.subtotal)}</div>
        <small style="color:#94a3b8;font-size:11px;">x${item.qty}</small>
      </div>
    </div>
  `).join('');
}

// ── Halaman Produk: Add to Cart ──────────────
document.querySelectorAll('.add-to-cart-btn:not(.disabled)').forEach(btn => {
  btn.addEventListener('click', async function () {
    const card = this.closest('.product-card');
    const qtyInput = card?.querySelector('.qty-input');
    const qty = qtyInput ? parseInt(qtyInput.value) || 1 : 1;
    const produkId = parseInt(this.dataset.id);

    // Visual feedback
    this.textContent = '⏳ Menambah…';
    this.disabled = true;

    const data = await cartAction('add', { produk_id: produkId, qty });

    if (data.ok) {
      this.textContent = '✅ Ditambahkan!';
      updateBadge(data.count);
      await refreshSidebar();
      openSidebar();
      showToast(`${this.dataset.nama || 'Produk'} ditambahkan ke keranjang!`, 'success');
    } else {
      this.textContent = '❌ Gagal';
      showToast(data.msg || 'Gagal menambahkan produk.', 'error');
    }

    setTimeout(() => {
      this.textContent = '🛒 Tambah';
      this.disabled = false;
    }, 2000);
  });
});

// ── Qty control di halaman produk ────────────
document.querySelectorAll('.qty-control').forEach(ctrl => {
  const minusBtn = ctrl.querySelector('.minus');
  const plusBtn  = ctrl.querySelector('.plus');
  const input    = ctrl.querySelector('.qty-input');

  if (!input) return;
  const min = parseInt(input.min) || 1;
  const max = parseInt(input.max) || 9999;

  minusBtn?.addEventListener('click', () => {
    const v = parseInt(input.value) || min;
    input.value = Math.max(min, v - 1);
  });
  plusBtn?.addEventListener('click', () => {
    const v = parseInt(input.value) || min;
    input.value = Math.min(max, v + 1);
  });
  input.addEventListener('change', () => {
    let v = parseInt(input.value) || min;
    input.value = Math.min(max, Math.max(min, v));
  });
});

// ── Halaman Keranjang: Update Qty ────────────
document.querySelectorAll('.cart-qty-btn').forEach(btn => {
  btn.addEventListener('click', async function () {
    const produkId  = parseInt(this.dataset.id);
    const action    = this.dataset.action;
    const qtyEl     = document.getElementById(`qty-${produkId}`);
    const subEl     = document.getElementById(`sub-${produkId}`);
    const summarySubEl = document.getElementById('summary-subtotal');
    const summaryTotEl = document.getElementById('summary-total');

    let currentQty = parseInt(qtyEl?.textContent) || 1;
    let newQty = action === 'plus' ? currentQty + 1 : currentQty - 1;

    if (newQty < 1) {
      // Remove item
      const data = await cartAction('remove', { produk_id: produkId });
      if (data.ok) {
        document.getElementById(`item-${produkId}`)?.remove();
        updateBadge(data.count);
        if (summarySubEl) summarySubEl.textContent = rupiah(data.total);
        if (summaryTotEl) summaryTotEl.textContent = rupiah(data.total);
        if (data.count === 0) location.reload();
      }
      return;
    }

    const data = await cartAction('update', { produk_id: produkId, qty: newQty });
    if (data.ok) {
      if (qtyEl) qtyEl.textContent = newQty;
      updateBadge(data.count);

      // Update subtotal per item (recalculate from summary diff isn't reliable,
      // fetch fresh summary instead)
      const summary = await cartAction('summary');
      if (summary.ok) {
        if (summarySubEl) summarySubEl.textContent = rupiah(summary.total);
        if (summaryTotEl) summaryTotEl.textContent = rupiah(summary.total);
        // Update individual subtotal
        const freshItem = summary.items.find(i => i.produk_id === produkId);
        if (freshItem && subEl) subEl.textContent = rupiah(freshItem.subtotal);
      }
    }
  });
});

// ── Halaman Keranjang: Remove Item ───────────
document.querySelectorAll('.remove-item-btn').forEach(btn => {
  btn.addEventListener('click', async function () {
    const produkId = parseInt(this.dataset.id);
    const itemEl   = document.getElementById(`item-${produkId}`);

    // Fade out
    if (itemEl) { itemEl.style.opacity = '0.4'; itemEl.style.pointerEvents = 'none'; }

    const data = await cartAction('remove', { produk_id: produkId });
    if (data.ok) {
      itemEl?.remove();
      updateBadge(data.count);
      const summarySubEl = document.getElementById('summary-subtotal');
      const summaryTotEl = document.getElementById('summary-total');
      if (summarySubEl) summarySubEl.textContent = rupiah(data.total);
      if (summaryTotEl) summaryTotEl.textContent = rupiah(data.total);
      showToast('Item dihapus dari keranjang.', 'success');
      if (data.count === 0) setTimeout(() => location.reload(), 600);
    } else {
      if (itemEl) { itemEl.style.opacity = '1'; itemEl.style.pointerEvents = ''; }
    }
  });
});

// ── Cart button click: open sidebar (from produk page) ──
document.getElementById('cart-btn')?.addEventListener('click', function (e) {
  // Hanya buka sidebar di halaman produk (bukan keranjang/checkout)
  if (!sidebar) return;
  e.preventDefault();
  openSidebar();
  refreshSidebar();
});

// ── Init: update badge on load ───────────────
(async () => {
  const data = await cartAction('summary');
  if (data.ok) {
    updateBadge(data.count);
  }
})();

// ── Escape key closes sidebar ─────────────────
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeSidebar();
});
