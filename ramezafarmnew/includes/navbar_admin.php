<?php
// Mendapatkan nama file saat ini untuk menentukan class 'active'
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
    /* Reset & Navbar Base */
    .navbar { 
        background: #0056b3; 
        padding: 0 50px; 
        height: 70px;
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        color: white; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Logo Style */
    .logo { 
        font-weight: bold; 
        font-size: 1.4rem; 
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    /* Navigation Links */
    .nav-links {
        display: flex;
        gap: 10px;
        height: 100%;
        align-items: center;
    }

    .nav-links a { 
        color: rgba(255, 255, 255, 0.8); 
        text-decoration: none; 
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: 500; 
        font-size: 15px;
        transition: all 0.3s ease; 
    }

    /* Hover Effect */
    .nav-links a:hover { 
        color: white;
        background: rgba(255, 255, 255, 0.1);
    }

    /* Active Page Style */
    .nav-links a.active { 
        color: white;
        background: rgba(255, 255, 255, 0.2);
        font-weight: bold;
    }

    /* Logout Specific Style */
    .nav-links a.logout-btn {
        color: #ffb3b3;
    }

    .nav-links a.logout-btn:hover {
        background: rgba(255, 82, 82, 0.2);
        color: #ff5252;
    }
</style>

<div class="navbar">
    <div class="logo">RAMEZA ADMIN</div>
    <div class="nav-links">
        <a href="admin_dashboard.php" class="<?= ($current_page == 'admin_dashboard.php') ? 'active' : '' ?>">
            Pesanan Pelanggan
        </a>
        <a href="layanan_kontak.php" class="<?= ($current_page == 'layanan_kontak.php') ? 'active' : '' ?>">
            Layanan Kontak
        </a>
        <a href="logout_admin.php" class="logout-btn" onclick="return confirm('Yakin ingin keluar?')">
            Logout
        </a>
    </div>
</div>