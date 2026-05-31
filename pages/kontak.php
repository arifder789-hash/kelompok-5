<?php
// WAJIB: session_start() harus dipanggil SEBELUM output HTML apapun
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
// 1. Memanggil header dan navbar
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="k-container">

  <!-- ─── Hero Strip ─── -->
  <div class="k-hero-strip">
    <div class="k-hero-inner">
      <div class="k-hero-tag">
        <span class="k-hero-dot"></span>
        Hubungi Kami &bull; Rameza Egg Farm
      </div>
      <h1 class="k-hero-title">Kami Siap <em>Melayani Anda</em></h1>
      <p class="k-hero-desc">Hubungi kami dengan mengisi formulir di bawah ini. Setiap pesan Anda akan masuk langsung ke
        sistem dashboard admin kami untuk segera ditinjau dan ditindaklanjuti secara profesional oleh tim Rameza Egg
        Farm.</p>
    </div>
  </div>

  <!-- ─── Main Content ─── -->
  <div class="k-content-section">
    <div class="k-grid">

      <div class="k-info-column reveal">
        <div class="k-label">Saluran Komunikasi</div>
        <h2 class="k-title">Kami Siap<br /><span class="k-highlight">Melayani Anda</span></h2>
        <p class="k-desc">Silakan isi formulir di samping untuk mengirimkan pesan atau pertanyaan Anda langsung ke
          sistem pusat kami.</p>

        <div class="k-list">

          <div class="k-item">
            <div class="k-icon">📍</div>
            <div class="k-item-body">
              <span class="k-item-label">Pusat Peternakan</span>
              <span class="k-item-value">Bondowoso, Jawa Timur</span>
            </div>
          </div>
          <div class="k-item">
            <div class="k-icon">🕐</div>
            <div class="k-item-body">
              <span class="k-item-label">Jam Operasional</span>
              <span class="k-item-value">Setiap Hari, 07.00 – 13.00 WIB</span>
            </div>
          </div>
        </div>
      </div>

      <div class="k-form-column reveal reveal-delay-2">
        <div class="k-card">
          <div class="k-card-header">
            <div class="k-header-icon">💬</div>
            <div>
              <h3 class="k-header-title">Kirim Pesan Cepat</h3>
              <p class="k-header-sub">Isi detail di bawah ini untuk mengirim pesan langsung ke dashboard admin kami.</p>
            </div>
          </div>

          <?php if (isset($_SESSION['sukses_kontak']) || isset($_SESSION['error_kontak'])): ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
          <?php endif; ?>

          <?php if (isset($_SESSION['sukses_kontak'])): ?>
            <script>
              document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil!',
                  text: '<?= addslashes($_SESSION['sukses_kontak']) ?>',
                  confirmButtonColor: '#059669'
                });
              });
            </script>
            <?php unset($_SESSION['sukses_kontak']); ?>
          <?php endif; ?>

          <?php if (isset($_SESSION['error_kontak'])): ?>
            <script>
              document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                  icon: 'error',
                  title: 'Gagal!',
                  text: '<?= addslashes($_SESSION['error_kontak']) ?>',
                  confirmButtonColor: '#dc2626'
                });
              });
            </script>
            <?php unset($_SESSION['error_kontak']); ?>
          <?php endif; ?>

          <form id="contact-form" action="../controller/proses_kontak.php" method="POST">

            <div class="k-group">
              <label class="k-form-label" for="keperluan">Kategori Keperluan <span class="k-req">*</span></label>
              <select id="keperluan" name="subjek" class="k-input" required>
                <option value="" disabled selected>Pilih Keperluan Anda...</option>
                <option value="Mitra">Kemitraan & Agen</option>
                <option value="Komplain">Komplain Produk/Layanan</option>
                <option value="Tanya Stok">Tanya Ketersediaan Stok</option>
                <option value="Lainnya">Lainnya</option>
              </select>
            </div>

            <div class="k-group">
              <label class="k-form-label" for="nama">Nama Lengkap <span class="k-req">*</span></label>
              <input type="text" id="nama" name="nama_pengirim" class="k-input" placeholder="Masukkan nama Anda"
                autocomplete="name" required />
            </div>

            <div class="k-group">
              <label class="k-form-label" for="wa">No. WhatsApp <span class="k-req">*</span></label>
              <div class="k-wa-wrap">
                <span class="k-wa-prefix">+62</span>
                <input type="tel" id="wa" name="no_wa" class="k-input" placeholder="812-XXXX-XXXX" autocomplete="tel"
                  required />
              </div>
            </div>

            <div class="k-group">
              <label class="k-form-label" for="email">Alamat Email <span class="k-req">*</span></label>
              <input type="email" id="email" name="email" class="k-input" placeholder="contoh@email.com"
                autocomplete="email" required />
            </div>

            <div class="k-group">
              <label class="k-form-label" for="pesan">Pesan Detail (Opsional)</label>
              <textarea id="pesan" name="pesan" class="k-input" rows="4"
                placeholder="Jelaskan secara singkat detail keperluan Anda..." style="resize:vertical;"></textarea>
            </div>

            <button type="submit" class="k-submit-btn">
              <span>📤</span> Kirim Pesan
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>

</div>


<?php
// Memanggil footer
include '../includes/footer.php';
?>