<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$scriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$rootPath = preg_match('~/(pages|controller|admin)$~', '/' . $scriptDir) ? '../' : '';
$pageMap = [
  'beranda.php' => [
    'title' => 'Beranda - Rameza Egg Farm',
    'description' => 'Rameza Egg Farm - peternakan ayam petelur modern di Bondowoso, Jawa Timur.',
    'css' => $rootPath . 'assets/css/beranda.css',
  ],
  'tentang.php' => [
    'title' => 'Tentang Kami - Rameza Egg Farm',
    'description' => 'Tentang Rameza Egg Farm - peternakan ayam petelur modern di Bondowoso, Jawa Timur.',
    'css' => $rootPath . 'assets/css/tentang.css',
  ],
  'detailproduk.php' => [
    'title' => 'Produk - Rameza Egg Farm',
    'description' => 'Produk dan layanan Rameza Egg Farm.',
    'css' => $rootPath . 'assets/css/detailproduk.css',
  ],
  'produk.php' => [
    'title' => 'Produk - Rameza Egg Farm',
    'description' => 'Katalog produk Rameza Egg Farm.',
    'css' => $rootPath . 'assets/css/shop.css',
  ],
];

$pageConfig = $pageMap[$currentPage] ?? [
  'title' => 'Rameza Egg Farm',
  'description' => 'Peternakan ayam petelur modern di Jawa Timur.',
  'css' => null,
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content="<?= htmlspecialchars($pageConfig['description'], ENT_QUOTES, 'UTF-8') ?>"/>
  <title><?= htmlspecialchars($pageConfig['title'], ENT_QUOTES, 'UTF-8') ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Lora:ital,wght@0,400;0,500;0,600;1,400;1,600&display=swap" rel="stylesheet"/>

  <link rel="stylesheet" href="<?= htmlspecialchars($rootPath, ENT_QUOTES, 'UTF-8') ?>assets/css/global.css"/>
  <?php if (!empty($pageConfig['css'])): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($pageConfig['css'], ENT_QUOTES, 'UTF-8') ?>"/>
  <?php endif; ?>
</head>
<body>
