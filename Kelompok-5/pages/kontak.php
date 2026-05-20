<?php 
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
      <p class="k-hero-desc">Silakan isi formulir di bawah atau hubungi langsung kami melalui WhatsApp untuk respons yang lebih cepat mengenai pemesanan, grosir, maupun kemitraan.</p>
    </div>
  </div>

  <!-- ─── Main Content ─── -->
  <div class="k-content-section">
    <div class="k-grid">

      <div class="k-info-column">
        <div class="k-label">Saluran Komunikasi</div>
        <h2 class="k-title">Kami Siap<br/><span class="k-highlight">Melayani Anda</span></h2>
        <p class="k-desc">Silakan isi formulir di samping atau hubungi langsung kami melalui WhatsApp resmi untuk respons yang lebih cepat mengenai pemesanan telur segar, grosir, maupun kemitraan.</p>

        <div class="k-list">
          <div class="k-item">
            <div class="k-icon">📱</div>
            <div class="k-item-body">
              <span class="k-item-label">Layanan WhatsApp</span>
              <span class="k-item-value">+62 812-XXXX-XXXX</span>
            </div>
          </div>
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

      <div class="k-form-column">
        <div class="k-card">
          <div class="k-card-header">
            <div class="k-header-icon">💬</div>
            <div>
              <h3 class="k-header-title">Kirim Pesan Cepat</h3>
              <p class="k-header-sub">Isi detail di bawah ini, tim kami akan segera merespons</p>
            </div>
          </div>

          <form id="contact-form" novalidate>
            
            <div class="k-group">
              <label class="k-form-label" for="keperluan">Kategori Keperluan <span class="k-req">*</span></label>
              <select id="keperluan" class="k-input" required>
                <option value="" disabled selected>Pilih Keperluan Anda...</option>
                <option value="Pemesanan & Harga Grosir">Pemesanan & Harga Grosir</option>
                <option value="Kemitraan & Agen">Kemitraan & Agen</option>
                <option value="Komplain Produk/Layanan">Komplain Produk/Layanan</option>
                <option value="Kritik & Saran">Kritik & Saran</option>
                <option value="Lainnya">Lainnya</option>
              </select>
            </div>

            <div class="k-group">
              <label class="k-form-label" for="nama">Nama Lengkap <span class="k-req">*</span></label>
              <input type="text" id="nama" class="k-input" placeholder="Masukkan nama Anda" autocomplete="name" required/>
            </div>
            
            <div class="k-group">
              <label class="k-form-label" for="wa">No. WhatsApp <span class="k-req">*</span></label>
              <div class="k-wa-wrap">
                <span class="k-wa-prefix">+62</span>
                <input type="tel" id="wa" class="k-input" placeholder="812-XXXX-XXXX" autocomplete="tel" required/>
              </div>
            </div>
            
            <div class="k-group">
              <label class="k-form-label" for="email">Alamat Email <span class="k-req">*</span></label>
              <input type="email" id="email" class="k-input" placeholder="contoh@email.com" autocomplete="email" required/>
            </div>
            
            <div class="k-group">
              <label class="k-form-label" for="pesan">Pesan Detail (Opsional)</label>
              <textarea id="pesan" class="k-input" rows="4" placeholder="Jelaskan secara singkat detail keperluan Anda..." style="resize:vertical;"></textarea>
            </div>
            
            <button type="submit" class="k-submit-btn">
              <span>📤</span> Hubungi Kami via WhatsApp
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('contact-form');
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const keperluan = document.getElementById('keperluan').value;
      const nama = document.getElementById('nama').value.trim();
      const wa = document.getElementById('wa').value.trim();
      const email = document.getElementById('email').value.trim();
      const pesan = document.getElementById('pesan').value.trim() || 'Tidak ada pesan khusus.';
      
      // Validasi: pastikan kolom yang wajib sudah diisi semua
      if (!keperluan || !nama || !wa || !email) {
        alert('Mohon lengkapi semua kolom yang wajib diisi (*), termasuk memilih "Keperluan".');
        return;
      }
      
      // TENTUKAN NOMOR WHATSAPP TOKO DI SINI
      const nomorTujuan = "62812XXXXXXXX"; // Ganti tanpa simbol +
      
      // Format pesan yang masuk ke WA
      const textWhatsApp = `Halo Rameza Egg Farm.\n\n` +
                           `Saya ingin menghubungi terkait *${keperluan}*.\n\n` +
                           `*Nama:* ${nama}\n` +
                           `*WhatsApp:* +62${wa}\n` +
                           `*Email:* ${email}\n\n` +
                           `*Detail Pesan:*\n${pesan}`;
      
      // Mengarahkan ke WhatsApp
      const url = `https://wa.me/${nomorTujuan}?text=${encodeURIComponent(textWhatsApp)}`;
      window.open(url, '_blank');
    });
  }
});
</script>

<?php 
// Memanggil footer
include '../includes/footer.php'; 
?>