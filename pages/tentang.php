<?php 
// 1. Memanggil header dan navbar dengan keluar dari folder 'pages' terlebih dahulu
include '../includes/header.php'; 
include '../includes/navbar.php'; 
?>

<div class="t-hero">
  <div class="t-hero__inner container">

    <div class="t-hero__text reveal">

      <div class="t-hero__tag">
        <span class="t-hero__dot"></span>
        Tentang Kami &bull; Sejak 2015
      </div>

      <h1 class="t-hero__title">
        Mengenal Lebih Dekat<br/>
        <em class="t-hero__title-em">Rameza Farm</em>
      </h1>

      <p class="t-hero__desc">
        Rameza Farm adalah peternakan ayam petelur modern yang berdiri sejak 2015 di Bondowoso, Jawa Timur. Berawal dari sebuah kandang kecil dengan tekad besar, kami tumbuh menjadi salah satu pemasok telur segar terpercaya di Jawa Timur — menjaga kualitas dari kandang hingga ke tangan konsumen.
      </p>

      <div class="t-hero__pills reveal reveal-delay-1">

        <div class="t-hero__pill">
          <span class="t-hero__pill-icon">🥚</span>
          <div class="t-hero__pill-body">
            <span class="t-hero__pill-title">Panen Setiap Hari</span>
            <span class="t-hero__pill-sub">Kesegaran yang tak tertandingi</span>
          </div>
        </div>

        <div class="t-hero__pill">
          <span class="t-hero__pill-icon">🌿</span>
          <div class="t-hero__pill-body">
            <span class="t-hero__pill-title">Pakan Alami</span>
            <span class="t-hero__pill-sub">Bebas bahan kimia berbahaya</span>
          </div>
        </div>

        <div class="t-hero__pill">
          <span class="t-hero__pill-icon">🚚</span>
          <div class="t-hero__pill-body">
            <span class="t-hero__pill-title">Distribusi Cepat</span>
            <span class="t-hero__pill-sub">Langsung dari kandang ke tangan Anda</span>
          </div>
        </div>

        <div class="t-hero__pill">
          <span class="t-hero__pill-icon">🏆</span>
          <div class="t-hero__pill-body">
            <span class="t-hero__pill-title">Terpercaya Sejak 2015</span>
            <span class="t-hero__pill-sub">Satu dekade konsistensi kualitas</span>
          </div>
        </div>

      </div>
    </div>

    <div class="t-hero__visual reveal reveal-delay-2">
      <div class="t-hero__img-wrap">
        <img
          src="../assets/img/foto-kandang3.jpeg"
          alt="Kandang Rameza Farm"
          onerror="this.src='https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?w=900&q=80'"
        />

        <div class="t-hero__img-badge">
          <span class="t-hero__img-badge-icon">🥚</span>
          <div>
            <div class="t-hero__img-badge-title">Panen Setiap Hari</div>
            <div class="t-hero__img-badge-sub">Segar langsung ke tangan Anda</div>
          </div>
        </div>

        <div class="t-hero__img-badge t-hero__img-badge--tl">
          <span class="t-hero__img-badge-icon">📍</span>
          <div>
            <div class="t-hero__img-badge-title">Bondowoso, Jawa Timur</div>
            <div class="t-hero__img-badge-sub">Pusat peternakan kami</div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>


<div class="t-subnav-wrap" id="tentang-subnav">
  <nav class="t-subnav container">
    <a href="#kisah-kami"  class="t-subnav__link active">Kisah Kami</a>
    <span class="t-subnav__dot"></span>
    <a href="#komitmen"    class="t-subnav__link">Visi &amp; Misi</a>
    <span class="t-subnav__dot"></span>
    <a href="#keunggulan"  class="t-subnav__link">Keunggulan</a>
    <span class="t-subnav__dot"></span>
    <a href="#perjalanan"  class="t-subnav__link">Perjalanan Kami</a>
  </nav>
</div>


<section class="t-kisah" id="kisah-kami">
  <div class="container t-kisah__grid">

    <div class="t-kisah__body reveal">
      <div class="t-label-bar"></div>
      <p class="t-section-eyebrow">Kisah Kami</p>
      <h2 class="t-section-heading">
        Dimulai dari Peternakan Kecil,<br/>
        <span class="t-blue">Tumbuh With Konsistensi</span>
      </h2>

      <p class="t-kisah__text">
        Rameza Farm merupakan peternakan ayam petelur yang berfokus pada produksi telur berkualitas tinggi dengan sistem perawatan unggas yang modern dan terkontrol. Kami menjaga kebersihan kandang, kualitas pakan, serta kesehatan ayam agar telur yang dihasilkan tetap higienis dan aman dikonsumsi setiap hari.
      </p>

      <p class="t-kisah__text">
        Dengan sistem pemantauan harian dan distribusi cepat, kami memastikan telur sampai ke tangan konsumen dalam kondisi terbaik — segar, bersih, dan bernutrisi. Berawal dari kandang kecil di Bondowoso, kini Rameza Farm tumbuh menjadi mitra terpercaya bagi keluarga, pedagang, dan industri kuliner di Jawa Timur.
      </p>

      <ul class="t-kisah__highlights">
        <li class="t-kisah__hl-item">
          <span class="t-kisah__hl-icon">✔</span>
          <span>Kandang bersih berstandar veteriner, diinspeksi rutin setiap minggu</span>
        </li>
        <li class="t-kisah__hl-item">
          <span class="t-kisah__hl-icon">✔</span>
          <span>Rekam medis unggas digital untuk menjamin kesehatan populasi</span>
        </li>
        <li class="t-kisah__hl-item">
          <span class="t-kisah__hl-icon">✔</span>
          <span>Pakan formulasi khusus tanpa hormon buatan untuk kualitas telur terbaik</span>
        </li>
        <li class="t-kisah__hl-item">
          <span class="t-kisah__hl-icon">✔</span>
          <span>Sistem panen pagi hari agar telur selalu dalam kondisi paling segar</span>
        </li>
      </ul>

      <blockquote class="t-kisah__quote">
        "Dengan sistem pemanenan harian dan distribusi cepat, kami memastikan telur sampai ke tangan konsumen dalam kondisi terbaik."
      </blockquote>
    </div>

    <div class="t-kisah__visual reveal reveal-delay-2">
      <div class="t-kisah__img-main">
        <img
          src="../assets/img/foto-kandang.jpeg"
          alt="Kandang Rameza Farm"
          onerror="this.src='https://images.unsplash.com/photo-1641468981503-a1ba7f1e5b7a?w=800&q=80'"
        />
      </div>
      <div class="t-kisah__img-thumb">
        <img
          src="../assets/img/gambar-telur.jpg"
          alt="Telur segar Rameza Farm"
          onerror="this.src='https://images.unsplash.com/photo-1618512496248-a07fe83aa8cb?w=400&q=80'"
        />
      </div>
      <div class="t-kisah__year-badge">
        <div class="t-kisah__year-num">2015</div>
        <div class="t-kisah__year-label">Berdiri</div>
      </div>
    </div>

  </div>
</section>


<section class="t-visi" id="komitmen">
  <div class="container">

    <div class="t-visi__header reveal">
      <p class="t-section-eyebrow t-section-eyebrow--center">Masa Depan Kami</p>
      <h2 class="t-section-heading t-section-heading--center">
        Visi &amp; Misi Utama
      </h2>
    </div>

    <div class="t-visi__vision-card reveal">
      <div class="t-visi__quote-mark">"</div>
      <p class="t-visi__vision-eyebrow">Visi Kami</p>
      <h3 class="t-visi__vision-text">
        Menjadi pemimpin industri peternakan ayam petelur di Jawa Timur yang mengedepankan inovasi, kualitas tanpa kompromi, dan keberlanjutan demi nutrisi bangsa.
      </h3>
    </div>

    <div class="t-visi__grid">

      <div class="t-visi__card reveal">
        <div class="t-visi__card-icon">🛡️</div>
        <h4 class="t-visi__card-title">Kualitas Tanpa Batas</h4>
        <p class="t-visi__card-desc">Menghasilkan telur dengan standar kebersihan tertinggi melalui pengawasan ketat di setiap rantai produksi.</p>
      </div>

      <div class="t-visi__card reveal reveal-delay-1">
        <div class="t-visi__card-icon">🐔</div>
        <h4 class="t-visi__card-title">Etika Peternakan</h4>
        <p class="t-visi__card-desc">Mengutamakan kesejahteraan unggas dengan lingkungan yang alami dan bebas stres demi hasil yang maksimal.</p>
      </div>

      <div class="t-visi__card reveal reveal-delay-2">
        <div class="t-visi__card-icon">🚚</div>
        <h4 class="t-visi__card-title">Kecepatan Distribusi</h4>
        <p class="t-visi__card-desc">Memastikan rantai pasok yang cepat agar kesegaran telur tetap terjaga hingga ke meja makan konsumen.</p>
      </div>

      <div class="t-visi__card reveal">
        <div class="t-visi__card-icon">♻️</div>
        <h4 class="t-visi__card-title">Ekosistem Hijau</h4>
        <p class="t-visi__card-desc">Mengintegrasikan pengelolaan limbah ramah lingkungan menjadi sumber daya baru bagi pertanian lokal.</p>
      </div>

      <div class="t-visi__card reveal reveal-delay-1">
        <div class="t-visi__card-icon">🤝</div>
        <h4 class="t-visi__card-title">Sinergi Komunitas</h4>
        <p class="t-visi__card-desc">Tumbuh bersama mitra dan masyarakat sekitar melalui hubungan kerja sama yang transparan dan jujur.</p>
      </div>

      <div class="t-visi__card reveal reveal-delay-2">
        <div class="t-visi__card-icon">🏆</div>
        <h4 class="t-visi__card-title">Kualitas Terjamin</h4>
        <p class="t-visi__card-desc">Setiap batch telur melalui seleksi ketat sebelum dikirim — kami tidak pernah berkompromi dengan standar.</p>
      </div>

    </div>
  </div>
</section>


<section class="t-unggulan" id="keunggulan">
  <div class="container">

    <div class="t-unggulan__header reveal">
      <div class="t-label-bar"></div>
      <p class="t-section-eyebrow">Keunggulan Kami</p>
      <h2 class="t-section-heading">
        Mengapa Memilih<br/>
        <span class="t-blue">Rameza Farm?</span>
      </h2>
      <p class="t-unggulan__sub">
        Setiap detail proses produksi kami dirancang untuk memberikan yang terbaik — dari kesehatan unggas hingga kepuasan konsumen akhir.
      </p>
    </div>

    <div class="t-unggulan__grid">

      <div class="t-unggulan__card reveal">
        <div class="t-unggulan__card-img">
          <img
            src="../assets/img/foto-kandang.jpeg"
            alt="Kandang bersih Rameza Farm"
            onerror="this.src='https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?w=600&q=80'"
          />
          <div class="t-unggulan__card-overlay">
            <span class="t-unggulan__card-num">01</span>
          </div>
        </div>
        <div class="t-unggulan__card-body">
          <div class="t-unggulan__card-icon">🏠</div>
          <h4 class="t-unggulan__card-title">Kandang Modern & Higienis</h4>
          <p class="t-unggulan__card-desc">
            Kandang kami dirancang dengan sistem ventilasi optimal dan pembersihan otomatis. Setiap sudut dijaga kebersihannya untuk meminimalkan risiko penyakit dan stres pada ayam.
          </p>
        </div>
      </div>

      <div class="t-unggulan__card reveal reveal-delay-1">
        <div class="t-unggulan__card-img">
          <img
            src="../assets/img/gambar-telur.jpg"
            alt="Telur segar berkualitas"
            onerror="this.src='https://images.unsplash.com/photo-1506976785307-8732e854ad03?w=600&q=80'"
          />
          <div class="t-unggulan__card-overlay">
            <span class="t-unggulan__card-num">02</span>
          </div>
        </div>
        <div class="t-unggulan__card-body">
          <div class="t-unggulan__card-icon">🥚</div>
          <h4 class="t-unggulan__card-title">Telur Segar Grading A</h4>
          <p class="t-unggulan__card-desc">
            Setiap telur melewati proses seleksi visual dan timbang sebelum dikemas. Hanya telur dengan ukuran, cangkang, dan bobot terbaik yang sampai ke tangan Anda.
          </p>
        </div>
      </div>

      <div class="t-unggulan__card reveal reveal-delay-2">
        <div class="t-unggulan__card-img">
          <img
            src="../assets/img/foto-kandang3.jpeg"
            alt="Sistem pakan alami Rameza Farm"
            onerror="this.src='https://images.unsplash.com/photo-1535268647677-300dbf3d78d1?w=600&q=80'"
          />
          <div class="t-unggulan__card-overlay">
            <span class="t-unggulan__card-num">03</span>
          </div>
        </div>
        <div class="t-unggulan__card-body">
          <div class="t-unggulan__card-icon">🌾</div>
          <h4 class="t-unggulan__card-title">Pakan Formulasi Khusus</h4>
          <p class="t-unggulan__card-desc">
            Komposisi pakan kami diformulasikan oleh ahli nutrisi unggas untuk memastikan kandungan protein dan vitamin yang optimal — hasilnya: kuning telur lebih pekat, lebih bergizi.
          </p>
        </div>
      </div>

    </div>

    <div class="t-unggulan__trust reveal">
      <div class="t-unggulan__trust-item">
        <span class="t-unggulan__trust-icon">📋</span>
        <span>Inspeksi rutin setiap minggu</span>
      </div>
      <div class="t-unggulan__trust-sep"></div>
      <div class="t-unggulan__trust-item">
        <span class="t-unggulan__trust-icon">🌡️</span>
        <span>Suhu penyimpanan terkontrol</span>
      </div>
      <div class="t-unggulan__trust-sep"></div>
      <div class="t-unggulan__trust-item">
        <span class="t-unggulan__trust-icon">📦</span>
        <span>Kemasan aman & ramah lingkungan</span>
      </div>
      <div class="t-unggulan__trust-sep"></div>
      <div class="t-unggulan__trust-item">
        <span class="t-unggulan__trust-icon">📞</span>
        <span>Layanan pelanggan responsif</span>
      </div>
    </div>

  </div>
</section>


<section class="t-perjalanan" id="perjalanan">
  <div class="container">

    <div class="t-perjalanan__header reveal">
      <div class="t-label-bar"></div>
      <p class="t-section-eyebrow">Perjalanan Kami</p>
      <h2 class="t-section-heading">
        Satu Dekade <em class="t-section-heading__em">Dedikasi</em><br/>
        dalam Peternakan Berkualitas
      </h2>
    </div>

    <div class="t-tl">
      <div class="t-tl__line"></div>

      <div class="t-tl__item reveal">
        <div class="t-tl__card">
          <div class="t-tl__card-top">
            <span class="t-tl__year-tag">2015</span>
            <span class="t-tl__card-badge t-tl__card-badge--start">🌱 Awal Mula</span>
          </div>
          <h3 class="t-tl__title">Langkah Awal Rameza Farm</h3>
          <p class="t-tl__desc">Rameza Farm resmi berdiri dengan kandang pertama berkapasitas 500 ekor ayam petelur. Berawal dari tekad untuk menghadirkan telur segar berkualitas untuk masyarakat Bondowoso.</p>
          <div class="t-tl__card-metric">
            <span>🐔 500 ekor</span>
            <span>📍 Bondowoso</span>
          </div>
        </div>
        <div class="t-tl__dot"></div>
        <div class="t-tl__spacer"></div>
      </div>

      <div class="t-tl__item t-tl__item--right reveal">
        <div class="t-tl__spacer"></div>
        <div class="t-tl__dot"></div>
        <div class="t-tl__card">
          <div class="t-tl__card-top">
            <span class="t-tl__year-tag">2017</span>
            <span class="t-tl__card-badge t-tl__card-badge--grow">📈 Ekspansi</span>
          </div>
          <h3 class="t-tl__title">Ekspansi Kandang Pertama</h3>
          <p class="t-tl__desc">Kapasitas meningkat menjadi 2.000 ekor. Membangun jaringan distribusi langsung ke pasar tradisional dan warung-warung di wilayah Bondowoso dan sekitarnya.</p>
          <div class="t-tl__card-metric">
            <span>🐔 2.000 ekor</span>
            <span>🏪 Pasar lokal</span>
          </div>
        </div>
      </div>

      <div class="t-tl__item reveal">
        <div class="t-tl__card">
          <div class="t-tl__card-top">
            <span class="t-tl__year-tag">2019</span>
            <span class="t-tl__card-badge t-tl__card-badge--tech">⚙️ Modernisasi</span>
          </div>
          <h3 class="t-tl__title">Modernisasi Sistem Pakan</h3>
          <p class="t-tl__desc">Mengadopsi sistem pakan otomatis dan rekam medis unggas digital. Produksi meningkat 40% dengan penurunan angka kematian unggas yang signifikan.</p>
          <div class="t-tl__card-metric">
            <span>📈 +40% produksi</span>
            <span>💻 Sistem digital</span>
          </div>
        </div>
        <div class="t-tl__dot"></div>
        <div class="t-tl__spacer"></div>
      </div>

      <div class="t-tl__item t-tl__item--right reveal">
        <div class="t-tl__spacer"></div>
        <div class="t-tl__dot"></div>
        <div class="t-tl__card">
          <div class="t-tl__card-top">
            <span class="t-tl__year-tag">2021</span>
            <span class="t-tl__card-badge t-tl__card-badge--expand">🏬 Diversifikasi</span>
          </div>
          <h3 class="t-tl__title">Pusat Kebutuhan Ternak</h3>
          <p class="t-tl__desc">Mulai menyediakan bibit unggas, pakan, dan obat-obatan ternak. Rameza Farm bertransformasi menjadi penyedia kebutuhan peternakan yang lebih lengkap.</p>
          <div class="t-tl__card-metric">
            <span>🌾 Pakan & bibit</span>
            <span>💊 Obat ternak</span>
          </div>
        </div>
      </div>

      <div class="t-tl__item reveal">
        <div class="t-tl__card">
          <div class="t-tl__card-top">
            <span class="t-tl__year-tag">2025</span>
            <span class="t-tl__card-badge t-tl__card-badge--milestone">🏆 1 Dekade</span>
          </div>
          <h3 class="t-tl__title">Satu Dekade Kepercayaan</h3>
          <p class="t-tl__desc">Merayakan 10 tahun beroperasi dengan lebih dari 5.000 ekor ayam produktif dan jaringan pelanggan yang terus berkembang di seluruh Jawa Timur.</p>
          <div class="t-tl__card-metric">
            <span>🐔 5.000+ ekor</span>
            <span>🗺️ Se-Jawa Timur</span>
          </div>
        </div>
        <div class="t-tl__dot"></div>
        <div class="t-tl__spacer"></div>
      </div>

    </div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>