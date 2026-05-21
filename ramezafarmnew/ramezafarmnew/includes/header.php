<?php
$currentPage = basename($_SERVER['PHP_SELF']);

// Deteksi subfolder /pages/ agar path asset benar
$prefix = (stripos($_SERVER['PHP_SELF'], '/pages/') !== false) ? '../' : '';

$pageMap = [
  'beranda.php' => [
    'title'       => 'Beranda - Rameza Egg Farm',
    'description' => 'Rameza Egg Farm - peternakan ayam petelur modern di Bondowoso, Jawa Timur.',
    'css'         => 'assets/css/beranda.css',
  ],
  'tentang.php' => [
    'title'       => 'Tentang Kami - Rameza Egg Farm',
    'description' => 'Tentang Rameza Egg Farm - peternakan ayam petelur modern di Bondowoso, Jawa Timur.',
    'css'         => 'assets/css/tentang.css',
  ],
  'kontak.php' => [
    'title'       => 'Hubungi Kami - Rameza Egg Farm',
    'description' => 'Hubungi Rameza Egg Farm untuk pemesanan telur segar, grosir, maupun kemitraan.',
    'css'         => 'assets/css/kontak.css',
  ],
  'detailproduk.php' => [
    'title'       => 'Produk - Rameza Egg Farm',
    'description' => 'Produk dan layanan Rameza Egg Farm.',
    'css'         => 'assets/css/detailproduk.css',
  ],
  'produk.php' => [
    'title'       => 'Produk - Rameza Egg Farm',
    'description' => 'Katalog produk Rameza Egg Farm.',
    'css'         => 'assets/css/shop.css',
  ],
  'checkout.php' => [
    'title'       => 'Checkout - Rameza Egg Farm',
    'description' => 'Selesaikan pembelian Anda di Rameza Egg Farm.',
    'css'         => null,
  ],
  'userdash.php' => [
    'title'       => 'Dashboard - Rameza Egg Farm',
    'description' => 'Dashboard pengguna Rameza Egg Farm.',
    'css'         => null,
  ],
];

$pageConfig = $pageMap[$currentPage] ?? [
  'title'       => 'Rameza Egg Farm',
  'description' => 'Peternakan ayam petelur modern di Jawa Timur.',
  'css'         => null,
];

$v = time(); // cache-bust saat dev
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content="<?= htmlspecialchars($pageConfig['description'], ENT_QUOTES, 'UTF-8') ?>"/>
  <title><?= htmlspecialchars($pageConfig['title'], ENT_QUOTES, 'UTF-8') ?></title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Lora:ital,wght@0,400;0,500;0,600;1,400;1,600&display=swap" rel="stylesheet"/>

  <!-- Core CSS: urutan penting → global → navbar → footer → page -->
  <link rel="stylesheet" href="<?= $prefix ?>assets/css/global.css?v=<?= $v ?>"/>
  <link rel="stylesheet" href="<?= $prefix ?>assets/css/navbar.css?v=<?= $v ?>"/>
  <link rel="stylesheet" href="<?= $prefix ?>assets/css/footer.css?v=<?= $v ?>"/>
  <link rel="stylesheet" href="<?= $prefix ?>assets/css/loginadmin.css?v=<?= $v ?>"/> 
  <link rel="stylesheet" href="<?= $prefix ?>assets/css/style-admin.css?v=<?= $v ?>"/>  
  

  <!-- Page-specific CSS -->
  <?php if (!empty($pageConfig['css'])): ?>
    <link rel="stylesheet" href="<?= $prefix . htmlspecialchars($pageConfig['css'], ENT_QUOTES, 'UTF-8') ?>?v=<?= $v ?>"/>
  <?php endif; ?>
</head>
<body>