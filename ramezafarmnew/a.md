<!DOCTYPE html>
<html class="light" lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Tentang Kami - Rameza Farm</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;600;700;900&family=Work+Sans:wght@400;600&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                "colors": {
                    "secondary-fixed": "#ffdbca",
                    "tertiary-container": "#2c2c28",
                    "surface-container-low": "#fff1e3",
                    "tertiary": "#171814",
                    "on-tertiary-container": "#95938e",
                    "on-tertiary-fixed-variant": "#474743",
                    "on-secondary-fixed": "#331200",
                    "on-secondary-container": "#6e2f00",
                    "inverse-primary": "#b4cdb8",
                    "secondary": "#9a4605",
                    "surface-container": "#f9ecde",
                    "tertiary-fixed-dim": "#c9c6c1",
                    "on-primary-fixed-variant": "#364c3c",
                    "on-error-container": "#93000a",
                    "on-surface": "#211b12",
                    "surface": "#fff8f3",
                    "surface-container-high": "#f3e6d8",
                    "on-primary-fixed": "#0b2013",
                    "tertiary-fixed": "#e5e2dc",
                    "on-primary": "#ffffff",
                    "on-secondary-fixed-variant": "#773300",
                    "on-background": "#211b12",
                    "surface-tint": "#4d6453",
                    "on-primary-container": "#819986",
                    "inverse-on-surface": "#fcefe0",
                    "on-secondary": "#ffffff",
                    "on-tertiary-fixed": "#1c1c18",
                    "surface-dim": "#e5d8ca",
                    "inverse-surface": "#362f26",
                    "error-container": "#ffdad6",
                    "primary-fixed-dim": "#b4cdb8",
                    "error": "#ba1a1a",
                    "background": "#fff8f3",
                    "outline": "#737973",
                    "primary-fixed": "#d0e9d4",
                    "secondary-fixed-dim": "#ffb68e",
                    "surface-variant": "#eee0d2",
                    "primary": "#061b0e",
                    "on-tertiary": "#ffffff",
                    "on-error": "#ffffff",
                    "outline-variant": "#c3c8c1",
                    "on-surface-variant": "#434843",
                    "secondary-container": "#fe9251",
                    "surface-bright": "#fff8f3",
                    "surface-container-lowest": "#ffffff",
                    "surface-container-highest": "#eee0d2",
                    "primary-container": "#1b3022"
                },
                "borderRadius": {
                    "DEFAULT": "0.5rem",
                    "lg": "1rem",
                    "xl": "1.5rem",
                    "full": "9999px"
                },
                "spacing": {
                    "sm": "12px",
                    "gutter": "24px",
                    "xl": "80px",
                    "md": "24px",
                    "base": "8px",
                    "xs": "4px",
                    "lg": "48px",
                    "margin": "32px"
                },
                "fontFamily": {
                    "display-lg": ["Noto Serif"],
                    "headline-md": ["Noto Serif"],
                    "body-md": ["Work Sans"],
                    "label-caps": ["Work Sans"],
                    "body-lg": ["Work Sans"],
                    "headline-sm": ["Noto Serif"]
                },
                "fontSize": {
                    "display-lg": ["48px", { "lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                    "headline-md": ["32px", { "lineHeight": "1.3", "fontWeight": "600" }],
                    "body-md": ["16px", { "lineHeight": "1.5", "fontWeight": "400" }],
                    "label-caps": ["12px", { "lineHeight": "1.2", "letterSpacing": "0.05em", "fontWeight": "600" }],
                    "body-lg": ["18px", { "lineHeight": "1.6", "fontWeight": "400" }],
                    "headline-sm": ["24px", { "lineHeight": "1.4", "fontWeight": "600" }]
                }
            }
        }
    }
</script>
<style>
    body {
        background-image: url('data:image/svg+xml,%3Csvg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="%23eee0d2" fill-opacity="0.2" fill-rule="evenodd"%3E%3Ccircle cx="3" cy="3" r="3"/%3E%3Ccircle cx="13" cy="13" r="3"/%3E%3C/g%3E%3C/svg%3E');
    }
</style>
</head>
<body class="bg-surface text-on-surface antialiased flex flex-col min-h-screen pt-20">

<!-- TopNavBar -->
<header class="bg-[#fdfcf8] dark:bg-stone-950 fixed top-0 w-full z-50 border-b border-stone-200 dark:border-stone-800 shadow-sm">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-8 py-4">
        <div class="text-2xl font-bold font-serif text-primary dark:text-emerald-500">
            Rameza Farm
        </div>
        <nav class="hidden md:flex gap-6 items-center">
            <a class="font-body-md text-on-surface-variant hover:text-primary transition-colors" href="/">Beranda</a>
            <a class="font-body-md text-on-surface-variant hover:text-primary transition-colors" href="/produk">Produk</a>
            <a class="font-body-md text-primary font-semibold border-b-2 border-primary" href="/tentang">Tentang Kami</a>
            <a class="font-body-md text-on-surface-variant hover:text-primary transition-colors" href="/kontak">Kontak</a>
            <button class="bg-primary text-on-primary font-body-md font-semibold py-2 px-6 rounded-DEFAULT hover:shadow-lg transition-all">
                Hubungi Kami
            </button>
        </nav>
    </div>
</header>

<main class="flex-1 flex flex-col gap-xl pb-xl">

<!-- Hero Section -->
<section class="relative h-[70vh] min-h-[500px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?q=80&w=2000');">
        <div class="absolute inset-0 bg-primary/50 mix-blend-multiply"></div>
    </div>
    <div class="relative z-10 text-center max-w-4xl px-gutter flex flex-col items-center gap-md">
        <span class="font-label-caps text-label-caps text-secondary-fixed-dim bg-secondary/80 px-4 py-1 rounded-full uppercase tracking-widest backdrop-blur-sm border border-secondary-fixed/30">
            Sejak 2015
        </span>
        <h1 class="font-display-lg text-display-lg text-on-primary drop-shadow-md">
            Komitmen Kami untuk Kualitas & Keberlanjutan
        </h1>
        <p class="font-body-lg text-body-lg text-surface-container-low max-w-2xl drop-shadow">
            Dari kandang kami ke meja Anda — membangun kepercayaan melalui peternakan yang bertanggung jawab dan produk berkualitas premium.
        </p>
    </div>
</section>

<!-- Our Story Section -->
<section class="max-w-7xl mx-auto px-gutter w-full">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-lg items-center">
        <div class="order-2 md:order-1">
            <span class="font-label-caps text-label-caps text-secondary uppercase tracking-wider">Kisah Kami</span>
            <h2 class="font-headline-md text-headline-md text-on-surface mt-xs mb-md">Dimulai dari Passion untuk Peternakan Berkualitas</h2>
            <div class="space-y-4 font-body-md text-body-md text-on-surface-variant">
                <p>
                    Rameza Farm didirikan pada tahun 2015 dengan visi sederhana namun kuat: menyediakan produk peternakan unggas terbaik untuk masyarakat Situbondo dan sekitarnya. Kami memulai dengan kandang kecil dan komitmen besar terhadap kesejahteraan hewan dan keberlanjutan lingkungan.
                </p>
                <p>
                    Hari ini, kami bangga telah berkembang menjadi salah satu peternakan unggas terpercaya di Jawa Timur. Namun nilai-nilai kami tetap sama: integritas, kualitas, dan dedikasi untuk memberikan yang terbaik kepada pelanggan kami.
                </p>
                <p>
                    Setiap telur yang kami produksi, setiap bibit unggas yang kami jual, dan setiap produk pakan yang kami distribusikan mencerminkan standar tinggi yang kami jaga dengan cermat. Kami percaya bahwa peternakan yang baik dimulai dengan perhatian terhadap detail dan kasih sayang terhadap hewan.
                </p>
            </div>
            <div class="mt-lg flex gap-sm">
                <div class="flex-1 p-md bg-surface-container-high rounded-lg border border-outline-variant/30">
                    <div class="font-headline-sm text-headline-sm text-primary mb-xs">10,000+</div>
                    <div class="font-body-md text-body-md text-on-surface-variant">Ekor Unggas</div>
                </div>
                <div class="flex-1 p-md bg-surface-container-high rounded-lg border border-outline-variant/30">
                    <div class="font-headline-sm text-headline-sm text-primary mb-xs">500+</div>
                    <div class="font-body-md text-body-md text-on-surface-variant">Pelanggan Setia</div>
                </div>
                <div class="flex-1 p-md bg-surface-container-high rounded-lg border border-outline-variant/30">
                    <div class="font-headline-sm text-headline-sm text-primary mb-xs">9+ Tahun</div>
                    <div class="font-body-md text-body-md text-on-surface-variant">Pengalaman</div>
                </div>
            </div>
        </div>
        <div class="order-1 md:order-2 rounded-xl overflow-hidden shadow-lg">
            <img src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?q=80&w=1000" alt="Rameza Farm" class="w-full h-full object-cover"/>
        </div>
    </div>
</section>

<!-- Mission & Values -->
<section class="bg-surface-container-high py-xl">
    <div class="max-w-7xl mx-auto px-gutter w-full">
        <div class="text-center mb-lg">
            <span class="font-label-caps text-label-caps text-secondary uppercase tracking-wider">Nilai-Nilai Kami</span>
            <h2 class="font-headline-md text-headline-md text-on-surface mt-xs">Prinsip yang Memandu Setiap Langkah Kami</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
            <!-- Value 1 -->
            <div class="flex flex-col items-start gap-sm p-lg bg-surface rounded-lg border border-outline-variant/30 hover:shadow-lg transition-shadow">
                <div class="w-14 h-14 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center mb-base">
                    <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">verified</span>
                </div>
                <h3 class="font-headline-sm text-headline-sm text-on-surface">Kualitas Terjamin</h3>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Setiap produk melalui kontrol kualitas ketat untuk memastikan standar tertinggi dalam setiap aspek — dari kesehatan unggas hingga kesegaran telur.
                </p>
            </div>

            <!-- Value 2 -->
            <div class="flex flex-col items-start gap-sm p-lg bg-surface rounded-lg border border-outline-variant/30 hover:shadow-lg transition-shadow">
                <div class="w-14 h-14 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center mb-base">
                    <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">eco</span>
                </div>
                <h3 class="font-headline-sm text-headline-sm text-on-surface">Keberlanjutan</h3>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Kami berkomitmen pada praktik peternakan berkelanjutan yang menjaga keseimbangan ekosistem dan kesejahteraan hewan untuk generasi mendatang.
                </p>
            </div>

            <!-- Value 3 -->
            <div class="flex flex-col items-start gap-sm p-lg bg-surface rounded-lg border border-outline-variant/30 hover:shadow-lg transition-shadow">
                <div class="w-14 h-14 rounded-full bg-tertiary-container text-on-tertiary-container flex items-center justify-center mb-base">
                    <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">handshake</span>
                </div>
                <h3 class="font-headline-sm text-headline-sm text-on-surface">Kepercayaan</h3>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Transparansi dan kejujuran adalah fondasi hubungan kami dengan pelanggan. Kami bangga dengan reputasi yang kami bangun melalui konsistensi dan integritas.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- What We Offer -->
<section class="max-w-7xl mx-auto px-gutter w-full">
    <div class="text-center mb-lg">
        <span class="font-label-caps text-label-caps text-secondary uppercase tracking-wider">Produk & Layanan</span>
        <h2 class="font-headline-md text-headline-md text-on-surface mt-xs mb-md">Solusi Lengkap untuk Kebutuhan Peternakan Anda</h2>
        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mx-auto">
            Dari bibit berkualitas hingga pakan premium, kami menyediakan semua yang Anda butuhkan untuk peternakan unggas yang sukses.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
        <!-- Product 1 -->
        <div class="rounded-xl overflow-hidden bg-surface-container-highest border border-outline-variant/30 hover:shadow-lg transition-all group">
            <div class="relative h-48 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?q=80&w=800" alt="Telur Segar" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"/>
                <div class="absolute inset-0 bg-gradient-to-t from-primary/70 to-transparent"></div>
            </div>
            <div class="p-lg">
                <h3 class="font-headline-sm text-headline-sm text-on-surface mb-sm">Telur Segar Berkualitas</h3>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Telur dengan kuning telur oranye cerah dan cangkang kuat, dipanen setiap hari untuk kesegaran maksimal.
                </p>
            </div>
        </div>

        <!-- Product 2 -->
        <div class="rounded-xl overflow-hidden bg-surface-container-highest border border-outline-variant/30 hover:shadow-lg transition-all group">
            <div class="relative h-48 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1612170153139-6f881ff067e0?q=80&w=800" alt="Bibit Unggas" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"/>
                <div class="absolute inset-0 bg-gradient-to-t from-primary/70 to-transparent"></div>
            </div>
            <div class="p-lg">
                <h3 class="font-headline-sm text-headline-sm text-on-surface mb-sm">Bibit Unggas Unggul</h3>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    DOC (Day Old Chick) dan pullet berkualitas tinggi dengan tingkat survival rate tinggi dan pertumbuhan optimal.
                </p>
            </div>
        </div>

        <!-- Product 3 -->
        <div class="rounded-xl overflow-hidden bg-surface-container-highest border border-outline-variant/30 hover:shadow-lg transition-all group">
            <div class="relative h-48 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?q=80&w=800" alt="Pakan Ternak" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"/>
                <div class="absolute inset-0 bg-gradient-to-t from-primary/70 to-transparent"></div>
            </div>
            <div class="p-lg">
                <h3 class="font-headline-sm text-headline-sm text-on-surface mb-sm">Pakan & Pulet Premium</h3>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Formulasi pakan bernutrisi lengkap yang dirancang untuk mendukung pertumbuhan dan produktivitas unggas maksimal.
                </p>
            </div>
        </div>

        <!-- Product 4 -->
        <div class="rounded-xl overflow-hidden bg-surface-container-highest border border-outline-variant/30 hover:shadow-lg transition-all group">
            <div class="relative h-48 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1587854692152-cbe660dbde88?q=80&w=800" alt="Vitamin & Obat" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"/>
                <div class="absolute inset-0 bg-gradient-to-t from-primary/70 to-transparent"></div>
            </div>
            <div class="p-lg">
                <h3 class="font-headline-sm text-headline-sm text-on-surface mb-sm">Vitamin & Obat Ternak</h3>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Suplemen dan obat-obatan berkualitas untuk menjaga kesehatan unggas dan mencegah penyakit.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Facilities Section -->
<section class="bg-primary text-on-primary py-xl">
    <div class="max-w-7xl mx-auto px-gutter w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-lg items-center">
            <div class="order-2 md:order-1 rounded-xl overflow-hidden">
                <img src="https://images.unsplash.com/photo-1464226184884-fa280b87c399?q=80&w=1000" alt="Fasilitas Modern" class="w-full h-full object-cover"/>
            </div>
            <div class="order-1 md:order-2">
                <span class="font-label-caps text-label-caps text-secondary-fixed uppercase tracking-wider">Fasilitas Kami</span>
                <h2 class="font-headline-md text-headline-md mt-xs mb-md">Teknologi Modern, Sentuhan Tradisional</h2>
                <div class="space-y-4 font-body-md text-body-md text-surface-container-low">
                    <p>
                        Fasilitas peternakan kami menggabungkan teknologi modern dengan praktik peternakan tradisional yang terbukti efektif. Kandang kami dirancang dengan sistem ventilasi optimal dan sanitasi terkontrol.
                    </p>
                    <p>
                        Kami menggunakan sistem monitoring kesehatan unggas yang canggih untuk memastikan setiap hewan mendapatkan perawatan terbaik. Lingkungan kandang yang nyaman dan bersih adalah prioritas utama kami.
                    </p>
                </div>
                <div class="mt-lg grid grid-cols-2 gap-sm">
                    <div class="flex items-center gap-sm">
                        <span class="material-symbols-outlined text-secondary-fixed text-2xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        <span class="font-body-md">Kandang Modern</span>
                    </div>
                    <div class="flex items-center gap-sm">
                        <span class="material-symbols-outlined text-secondary-fixed text-2xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        <span class="font-body-md">Sistem Ventilasi</span>
                    </div>
                    <div class="flex items-center gap-sm">
                        <span class="material-symbols-outlined text-secondary-fixed text-2xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        <span class="font-body-md">Kontrol Kualitas</span>
                    </div>
                    <div class="flex items-center gap-sm">
                        <span class="material-symbols-outlined text-secondary-fixed text-2xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        <span class="font-body-md">Sanitasi Ketat</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="max-w-7xl mx-auto px-gutter w-full">
    <div class="bg-gradient-to-r from-primary to-primary-container rounded-2xl p-xl text-center text-on-primary relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
        <div class="relative z-10 max-w-3xl mx-auto">
            <h2 class="font-headline-md text-headline-md mb-md">Siap Memulai Kemitraan dengan Kami?</h2>
            <p class="font-body-lg text-body-lg text-surface-container-low mb-lg">
                Hubungi kami hari ini untuk konsultasi gratis tentang kebutuhan peternakan Anda. Tim kami siap membantu Anda menemukan solusi terbaik.
            </p>
            <div class="flex flex-col sm:flex-row gap-sm justify-center">
                <button class="bg-surface text-primary font-body-md font-semibold py-4 px-8 rounded-DEFAULT hover:shadow-xl transition-all transform hover:-translate-y-1">
                    Hubungi Kami Sekarang
                </button>
                <button class="bg-transparent border-2 border-surface text-surface font-body-md font-semibold py-4 px-8 rounded-DEFAULT hover:bg-surface hover:text-primary transition-all">
                    Lihat Katalog Produk
                </button>
            </div>
        </div>
    </div>
</section>

</main>

<!-- Footer -->
<footer class="bg-stone-100 dark:bg-stone-900 border-t border-stone-200 dark:border-stone-800 w-full py-12 px-8">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start gap-8">
        <div class="flex flex-col gap-4">
            <span class="font-serif font-black text-xl text-primary dark:text-emerald-500">
                Rameza Farm
            </span>
            <span class="font-serif text-sm text-primary dark:text-emerald-500 opacity-80">
                © 2024 Rameza Farm. Peternakan Unggas Berkualitas.
            </span>
        </div>
        <nav class="flex flex-col md:flex-row gap-6">
            <a class="font-serif text-sm text-stone-500 dark:text-stone-400 hover:text-primary dark:hover:text-emerald-400 transition-all" href="#">Produk</a>
            <a class="font-serif text-sm text-stone-500 dark:text-stone-400 hover:text-primary dark:hover:text-emerald-400 transition-all" href="#">Tentang Kami</a>
            <a class="font-serif text-sm text-stone-500 dark:text-stone-400 hover:text-primary dark:hover:text-emerald-400 transition-all" href="#">Kontak</a>
            <a class="font-serif text-sm text-stone-500 dark:text-stone-400 hover:text-primary dark:hover:text-emerald-400 transition-all" href="#">Kebijakan Privasi</a>
        </nav>
    </div>
</footer>

</body>
</html>
