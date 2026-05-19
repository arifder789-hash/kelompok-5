<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<section class="contact-hero" id="hero">
  <div class="section-container">
    <div class="reveal">
      <div class="hero-tag">
        <span class="dot"></span>
        Layanan Pelanggan
      </div>
      <h1 class="hero-title">
        Mari Terhubung dengan<br/>
        <span class="highlight">Rameza Farm</span>
      </h1>
      <p class="hero-desc">
        Punya pertanyaan seputar produk, harga grosir, atau kemitraan? Jangan ragu untuk menghubungi kami. Tim kami siap merespons Anda secepat mungkin.
      </p>
    </div>
  </div>
</section>

<section class="cta-section" id="kontak">
  <div class="section-container cta-grid">
    
    <div class="reveal">
      <div class="section-label">Hubungi Kami</div>
      <h2 class="section-title">Kami Siap<br/><span class="blue italic">Melayani Anda</span></h2>
      <p class="section-desc">Pilih metode komunikasi yang paling nyaman untuk Anda. Kami menyarankan WhatsApp untuk respons yang lebih cepat.</p>

      <div class="cta-contact-list">
        <div class="cta-contact-item">
          <div class="cta-icon">📱</div>
          <div>
            <div class="cta-contact-label">WhatsApp (Fast Response)</div>
            <div class="cta-contact-value">+62 819-1410-3735</div>
          </div>
        </div>
        <div class="cta-contact-item">
          <div class="cta-icon">📍</div>
          <div>
            <div class="cta-contact-label">Lokasi Peternakan</div>
            <div class="cta-contact-value">Bondowoso, Jawa Timur</div>
          </div>
        </div>
        <div class="cta-contact-item">
          <div class="cta-icon">🕐</div>
          <div>
            <div class="cta-contact-label">Jam Operasional</div>
            <div class="cta-contact-value">Setiap Hari, 07.00 – 16.00 WIB</div>
          </div>
        </div>
      </div>
    </div>

    <div class="reveal reveal-delay-2">
      <div class="cta-form-card">
        <div class="form-header">
          <div class="form-header-icon">💬</div>
          <div>
            <div class="form-header-title">Kirim Pesan Langsung</div>
            <div class="form-header-sub">Isi formulir ini untuk terhubung ke WhatsApp kami</div>
          </div>
        </div>

        <form id="contact-form" novalidate>
          <div class="form-group">
            <label class="form-label" for="nama">Nama Lengkap <span class="req">*</span></label>
            <input type="text" id="nama" class="form-input" placeholder="Masukkan nama Anda" autocomplete="name" required/>
          </div>
          <div class="form-group">
            <label class="form-label" for="wa">No. WhatsApp <span class="req">*</span></label>
            <div class="form-wa-wrap">
              <div class="form-wa-prefix">+62</div>
              <input type="tel" id="wa" class="form-input" placeholder="812-XXXX-XXXX" autocomplete="tel" required/>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label" for="keperluan">Keperluan <span class="req">*</span></label>
            <select id="keperluan" class="form-input" required>
              <option value="" disabled selected>Pilih jenis keperluan...</option>
              <option value="Beli Telur (Grosir/Ecer)">Komplain</option>
              <option value="Beli Bibit / Pakan">Mitra / Kerja Sama</option>
              <option value="Kemitraan">Saran</option>
              <option value="Lainnya">Pertanyaan Lainnya</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="pesan">isi pesan</label>
            <textarea id="pesan" class="form-input" rows="3" placeholder="Tuliskan detail pertanyaan atau pesanan Anda..." style="resize:vertical;"></textarea>
          </div>
          <button type="submit" class="form-submit">
            <span>📤</span> Kirim Pesan
          </button>
        </form>
      </div>
    </div>
    
  </div>
</section>

<section class="map-section" style="padding-top: 0;">
  <div class="section-container reveal">
    <div style="border-radius: 24px; overflow: hidden; height: 400px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 2px solid #e2e8f0;">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126372.43575485458!2d113.74316934335938!3d-7.940561500000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd6c323f46f3329%3A0xcda60e48ee0007e!2sBondowoso%2C%20Kabupaten%20Bondowoso%2C%20Jawa%20Timur!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="assets/js/kontak.js"></script>
</body>
</html>