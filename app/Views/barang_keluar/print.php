<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        
        /* Container Header dengan Flexbox */
        .header {
            margin-bottom: 25px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            position: relative;
        }
        
        /* Layout logo dan teks menggunakan flex */
        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }
        
        /* Container logo di kiri */
        .logo-left {
            flex-shrink: 0;
            width: 100px;
        }
        
        .logo-left img {
            width: 100%;
            height: auto;
            max-height: 80px;
            object-fit: contain;
            display: block;
        }
        
        /* Container judul di tengah */
        .title-center {
            flex: 1;
            text-align: center;
        }
        
        .title-center h2 {
            margin: 0 0 2px 0;
            font-size: 18px;
        }
        
        .title-center p {
            margin: 0;
            line-height: 1.4;
        }
        
        /* Container kosong untuk keseimbangan */
        .spacer-right {
            flex-shrink: 0;
            width: 100px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 25px;
            text-align: right;
            font-size: 10px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-end {
            text-align: right;
        }
        
        .fw-bold {
            font-weight: bold;
        }
        
        /* CSS untuk cetak PDF */
        @media print {
            body {
                padding: 10px;
            }
            
            .logo-left img {
                max-height: 70px;
            }
            
            th {
                background-color: #f2f2f2 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                background-color: white;
            }
            
            table {
                page-break-inside: avoid;
            }
            
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <!-- Logo Kiri -->
            <div class="logo-left">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo" onerror="this.style.display='none'">
            </div>
            
            <!-- Judul Tengah -->
            <div class="title-center">
                <h2>LAPORAN BARANG KELUAR</h2>
                <p>Sistem Informasi Inventory Barang</p>
                <p>PT. Rancang Mode Indonesia</p>
                <p>Tanggal Cetak: <?= $tanggal_cetak ?></p>
                <?php if (!empty($tanggal_awal) && !empty($tanggal_akhir)): ?>
                <p>Periode: <?= date('d/m/Y', strtotime($tanggal_awal)) ?> - <?= date('d/m/Y', strtotime($tanggal_akhir)) ?></p>
                <?php endif; ?>
            </div>
            
            <!-- Spacer kanan -->
            <div class="spacer-right"></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">ID Transaksi</th>
                <th width="10%">Tanggal</th>
                <th width="20%">Nama Barang</th>
                <th width="10%">Jumlah</th>
                <th width="10%">Satuan</th>
                <th width="13%">User</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($barang_keluar)): ?>
                <?php $no = 1; $grand_jumlah = 0; ?>
                <?php foreach($barang_keluar as $item): ?>
                <?php 
                    $grand_jumlah += $item['jumlah_keluar'];
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= $item['id_barang_keluar'] ?></td>
                    <td class="text-center"><?= date('d/m/Y', strtotime($item['tanggal_keluar'])) ?></td>
                    <td><?= $item['nama_barang'] ?></td>
                    <td class="text-end"><?= number_format($item['jumlah_keluar']) ?></td>
                    <td><?= $item['nama_satuan'] ?></td>
                    <td><?= $item['nama_user'] ?? '-' ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data barang keluar</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <?php if (!empty($barang_keluar)): ?>
            <tr class="table-active">
                <th colspan="5" class="text-end">Total Keseluruhan:</th>
                <th class="text-end"><?= number_format($grand_jumlah) ?></th>
                <th colspan="2"></th>
            </tr>
            <?php endif; ?>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak oleh: <?= session()->get('nama') ?? 'Administrator' ?></p>
        <p>&copy; <?= date('Y') ?> Sistem Informasi Inventory Barang</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
        
        window.onafterprint = function() {
            // window.close();
        }
    </script>
</body>
</html>