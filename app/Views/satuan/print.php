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
                <h2>LAPORAN DATA SATUAN</h2>
                <p>Sistem Informasi Inventory Barang</p>
                <p>PT. Rancang Mode Indonesia</p>
                <p>Tanggal Cetak: <?= $tanggal_cetak ?></p>
            </div>
            
            <!-- Spacer kanan -->
            <div class="spacer-right"></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">ID Satuan</th>
                <th width="30%">Nama Satuan</th>
                <th width="25%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($satuan)): ?>
                <?php $no = 1; foreach($satuan as $item): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= $item['id_satuan'] ?></td>
                    <td><?= $item['nama_satuan'] ?></td>
                    <td><?= $item['keterangan'] ?? '-' ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data satuan</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total Satuan:</th>
                <th class="text-center"><?= $total_satuan ?></th>
            </tr>
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