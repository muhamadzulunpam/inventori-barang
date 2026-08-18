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
            max-height: 100%;
            object-fit: contain;
            display: block;
        }
        
        /* Container judul di tengah */
        .title-center {
            flex: 1;
            text-align: center;
        }
        
        .title-center h2 {
            margin: 0 0 5px 0;
            font-size: 18px;
        }
        
        .title-center p {
            margin: 3px 0;
        }
        
        /* Container kosong untuk keseimbangan (opsional) */
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
        
        /* CSS untuk cetak PDF */
        @media print {
            body {
                padding: 10px;
            }
            
            .logo-left img {
                max-height: 50%;
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
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <!-- Logo Kiri -->
            <div class="logo-left">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo" style="width: 100%; height: auto; max-height: 100%; object-fit: contain; display: block;">
            </div>
            
            <!-- Judul Tengah -->
            <div class="title-center">
                <h2>LAPORAN DATA BARANG KELUAR</h2>
                <p>Sistem Informasi Inventory Barang</p>
                <p>PT. Rancang Mode Indonesia</p>
                <p>Tanggal Cetak: <?= $tanggal_cetak ?></p>
            </div>
            
            <!-- Spacer kanan untuk keseimbangan (bisa diisi logo kedua jika perlu) -->
            <div class="spacer-right"></div>
        </div>
    </div>

    <?php if(!empty($filter['start_date']) || !empty($filter['end_date']) || !empty($barang_nama) || !empty($filter['search'])): ?>
    <div class="filter-info">
        <strong>Filter:</strong><br>
        <?php if(!empty($filter['start_date']) && !empty($filter['end_date'])): ?>
        - Periode: <?= date('d/m/Y', strtotime($filter['start_date'])) ?> - <?= date('d/m/Y', strtotime($filter['end_date'])) ?><br>
        <?php elseif(!empty($filter['start_date'])): ?>
        - Dari: <?= date('d/m/Y', strtotime($filter['start_date'])) ?><br>
        <?php elseif(!empty($filter['end_date'])): ?>
        - Sampai: <?= date('d/m/Y', strtotime($filter['end_date'])) ?><br>
        <?php endif; ?>
        <?php if(!empty($barang_nama)): ?>- Barang: <?= $barang_nama ?><br><?php endif; ?>
        <?php if(!empty($filter['search'])): ?>- Search: <?= $filter['search'] ?><br><?php endif; ?>
    </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Tanggal Keluar</th>
                <th width="12%">Kode</th>
                <th width="20%">Barang</th>
                <th width="10%">Warna</th>
                <th width="8%">Qty</th>
                <th width="8%">Satuan</th>
                <th width="15%">Input Oleh</th>
                <th width="10%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($laporan as $item): ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td class="text-center"><?= date('d/m/Y', strtotime($item['tanggal_keluar'])) ?></td>
                <td class="text-center"><?= $item['id_barang_keluar'] ?></td>
                <td><?= $item['nama_barang'] ?></td>
                <td><?= $item['warna'] ?? '-' ?></td>
                <td class="text-right"><?= number_format($item['jumlah_keluar']) ?></td>
                <td><?= $item['nama_satuan'] ?? '-' ?></td>
                <td><?= $item['nama_user'] ?? '-' ?></td>
                <td><?= $item['keterangan'] ?? '-' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="total-row">
            <tr>
                <th colspan="5" class="text-right">Total Barang Keluar:</th>
                <th class="text-right"><?= number_format($ringkasan['total_jumlah']) ?></th>
                <th colspan="3"></th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak oleh: <?= session()->get('nama') ?></p>
        <p>&copy; <?= date('Y') ?> Sistem Informasi Inventory Barang</p>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>