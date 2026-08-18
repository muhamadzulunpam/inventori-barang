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
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo">
            </div>
            
            <!-- Judul Tengah -->
            <div class="title-center">
                <h2>LAPORAN DATA BARANG</h2>
                <p>Sistem Informasi Inventory Barang</p>
                <p>PT. Rancang Mode Indonesia</p>
                <p>Tanggal Cetak: <?= $tanggal_cetak ?></p>
            </div>
            
            <!-- Spacer kanan untuk keseimbangan (bisa diisi logo kedua jika perlu) -->
            <div class="spacer-right"></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Kode Barang</th>
                <th width="20%">Nama Barang</th>
                <th width="8%">Warna</th>
                <th width="12%">Jenis</th>
                <th width="8%">Stok</th>
                <th width="8%">Satuan</th>
                <th width="10%">Status</th>
                <?php if(session()->get('level') != 'staff_gudang'): ?>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($stok_barang as $item): ?>
            <?php
                if ($item['stok'] <= $item['min_stok'] && $item['stok'] > 0) {
                    $status_text = 'Stok Menipis';
                } elseif ($item['stok'] == 0) {
                    $status_text = 'Habis';
                } else {
                    $status_text = 'Aman';
                }
                $nilai_stok = $item['stok'] * ($item['harga_beli'] ?? 0);
            ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= $item['id_barang'] ?></td>
                <td><?= $item['nama_barang'] ?></td>
                <td><?= $item['warna'] ?? '-' ?></td>
                <td><?= $item['nama_jenis'] ?? '-' ?></td>
                <td class="text-right"><?= number_format($item['stok']) ?></td>
                <td><?= $item['nama_satuan'] ?? '-' ?></td>
                <td><?= $status_text ?></td>
                <?php if(session()->get('level') != 'staff_gudang'): ?>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="total-row">
            <tr>
                <th colspan="1"></th>
                <th colspan="<?= session()->get('level') != 'staff_gudang' ? '4' : '3' ?>" class="text-right">Total:</th>
                <th class="text-right"><?= number_format(array_sum(array_column($stok_barang, 'stok'))) ?></th>
                <th colspan="2"></th>
                <?php if(session()->get('level') != 'staff_gudang'): ?>
                <?php endif; ?>
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