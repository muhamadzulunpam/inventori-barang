<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Inventory System' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --primary-blue: #1e3c72;
            --secondary-blue: #2a5298;
            --light-blue: #e8f0fe;
            --sidebar-width: 280px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: white;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }

        .sidebar-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .sidebar-header p {
            margin: 5px 0 0;
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .sidebar-menu {
            padding: 20px 0;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Custom scrollbar untuk sidebar */
        .sidebar-menu::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 10px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.5);
        }

        /* For Firefox */
        .sidebar-menu {
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.3) rgba(255,255,255,0.1);
        }

        .menu-item {
            padding: 12px 25px;
            margin: 5px 0;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .menu-item:hover, .menu-item.active {
            background: rgba(255,255,255,0.1);
            border-left: 4px solid #ffd700;
        }

        .menu-item a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .menu-item i {
            width: 24px;
            font-size: 1.2rem;
        }

        .menu-item span {
            font-size: 0.95rem;
        }

        .submenu {
            padding-left: 45px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .submenu.active {
            max-height: 300px;
        }

        .submenu .menu-item {
            padding: 10px 20px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s ease;
        }

        .top-bar {
            background: white;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-blue);
            margin: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-name {
            font-weight: 500;
            color: var(--primary-blue);
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 13px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #c82333;
        }

        .logout-icon {
            color: #dc3545;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .logout-icon:hover {
            color: #c82333;
            transform: scale(1.1);
        }

        /* Cards */
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* Tables */
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border: none;
            padding: 8px 20px;
        }
        
        .logo-wrapper {
            background: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            border-radius: 5%;
            width: 70% ;
            height: 50px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .logo-wrapper img {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            .sidebar-header h3, .sidebar-header p, .menu-item span {
                display: none;
            }
            .main-content {
                margin-left: 70px;
            }
            .sidebar-menu {
                overflow-y: visible;
            }
        }
        
    </style>
</head>
<body>
<div class="sidebar">
    <div class="sidebar-header">
        <h3>
            <div class="logo-wrapper">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo">
            </div>
            Inventori
        </h3>
        <p>Management System</p>
    </div>
    
    <div class="sidebar-menu">
        <!-- Dashboard (Semua Level) -->
        <div class="menu-item <?= ($active_menu == 'dashboard') ? 'active' : '' ?>">
            <a href="<?= base_url('/dashboard') ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </div>
        
        <!-- Menu untuk Admin & Super Admin -->
        <?php if(session()->get('level') == 'admin' || session()->get('level') == 's'): ?>
        <div class="menu-item" onclick="toggleSubmenu('dataMenu')">
            <a href="#">
                <i class="fas fa-database"></i>
                <span>Data Master</span>
                <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
            </a>
        </div>
        <div id="dataMenu" class="submenu">
            <div class="menu-item <?= ($active_menu == 'barang') ? 'active' : '' ?>">
                <a href="<?= base_url('/barang') ?>">
                    <i class="fas fa-box"></i>
                    <span>Data Barang</span>
                </a>
            </div>
            <div class="menu-item <?= ($active_menu == 'supplier') ? 'active' : '' ?>">
                <a href="<?= base_url('/supplier') ?>">
                    <i class="fas fa-truck"></i>
                    <span>Data Supplier</span>
                </a>
            </div>
            <div class="menu-item <?= ($active_menu == 'satuan') ? 'active' : '' ?>">
                <a href="<?= base_url('/satuan') ?>">
                    <i class="fas fa-ruler"></i>
                    <span>Data Satuan</span>
                </a>
            </div>
            <div class="menu-item <?= ($active_menu == 'jenis') ? 'active' : '' ?>">
                <a href="<?= base_url('/jenis') ?>">
                    <i class="fas fa-tags"></i>
                    <span>Data Jenis</span>
                </a>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Menu Transaksi (Semua Level) -->
         <?php if(session()->get('level') == 'admin' || session()->get('level') == 'staff_gudang'): ?>
            <div class="menu-item" onclick="toggleSubmenu('transaksiMenu')">
                <a href="#">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Transaksi</span>
                    <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
                </a>
            </div>
        <?php endif; ?>
        <div id="transaksiMenu" class="submenu">
            <!-- Barang Masuk hanya untuk Admin & Super Admin -->
            <?php if(session()->get('level') == 'admin'): ?>
            <div class="menu-item <?= ($active_menu == 'barang_masuk') ? 'active' : '' ?>">
                <a href="<?= base_url('/barang_masuk') ?>">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Barang Masuk</span>
                </a>
            </div>
            <?php endif; ?>
            
            <!-- Barang Masuk hanya untuk Admin & Super Admin -->
            <?php if(session()->get('level') == 'admin' || session()->get('level') == 'staff_gudang'): ?>
            <div class="menu-item <?= ($active_menu == 'barang_keluar') ? 'active' : '' ?>">
                <a href="<?= base_url('/barang_keluar') ?>">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Barang Keluar</span>
                </a>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Menu Laporan (Semua Level) -->
        <div class="menu-item" onclick="toggleSubmenu('laporanMenu')">
            <a href="#">
                <i class="fas fa-chart-line"></i>
                <span>Laporan</span>
                <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
            </a>
        </div>
        <div id="laporanMenu" class="submenu">
            <?php if(session()->get('level') == 'admin' || session()->get('level') == 'owner'): ?>
                <div class="menu-item">
                    <a href="<?= base_url('/laporan_stok') ?>">
                        <i class="fas fa-chart-bar"></i>
                        <span>Laporan Stok</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="<?= base_url('/laporan_masuk') ?>">
                        <i class="fas fa-chart-line"></i>
                        <span>Laporan Masuk</span>
                    </a>
                </div>
            <?php endif; ?>
            <div class="menu-item">
                <a href="<?= base_url('/laporan_keluar') ?>">
                    <i class="fas fa-chart-line"></i>
                    <span>Laporan Keluar</span>
                </a>
            </div>
        </div>
        
        <!-- Menu Pengaturan hanya untuk Super Admin -->
        <?php if(session()->get('level') == 'admin'): ?>
        <div class="menu-item" onclick="toggleSubmenu('pengaturanMenu')">
            <a href="#">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
                <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
            </a>
        </div>
        <div id="pengaturanMenu" class="submenu">
            <div class="menu-item <?= ($active_menu == 'user') ? 'active' : '' ?>">
                <a href="<?= base_url('/user') ?>">
                    <i class="fas fa-users"></i>
                    <span>Manajemen User</span>
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="main-content">
    <div class="top-bar">
        <h4 class="page-title"></h4>
        <div class="user-info">
            <span class="user-name">
                <i class="fas fa-user-circle"></i> <?= session()->get('nama') ?>
            </span>
            <span class="badge <?= session()->get('level') == 'admin' ? 'bg-danger' : (session()->get('level') == 'admin' ? 'bg-info' : 'bg-warning') ?>">
                <?php 
                $levelLabel = '';
                switch(session()->get('level')) {
                    case 'admin': $levelLabel = 'Admin'; break;
                    case 'staff_gudang': $levelLabel = 'Staff Gudang'; break;
                    case 'owner': $levelLabel = 'Owner'; break;
                    default: $levelLabel = 'User';
                }
                echo $levelLabel;
                ?>
            </span>
            <a href="<?= base_url('/auth/logout') ?>" class="logout-icon" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>

    <script>
        function toggleSubmenu(menuId) {
            var submenu = document.getElementById(menuId);
            submenu.classList.toggle('active');
        }

        // Optional: Keep submenu open based on active menu
        document.addEventListener('DOMContentLoaded', function() {
            // Check which submenu should be open based on active menu
            const activeMenuItem = document.querySelector('.menu-item.active');
            if (activeMenuItem) {
                let parentSubmenu = activeMenuItem.closest('.submenu');
                if (parentSubmenu) {
                    parentSubmenu.classList.add('active');
                }
            }
        });
    </script>
</body>
</html>