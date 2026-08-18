<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Total Barang</h6>
                        <h3 class="mb-0"><?= $total_barang ?? 0 ?></h3>
                    </div>
                    <div class="bg-primary rounded-circle p-3">
                        <i class="fas fa-box text-white fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Total Jenis Barang</h6>
                        <h3 class="mb-0"><?= $total_jenis_barang ?? 0 ?></h3>
                    </div>
                    <div class="bg-success rounded-circle p-3">
                        <i class="fas fa-tags text-white fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Barang Masuk (Bulan Ini)</h6>
                        <h3 class="mb-0"><?= $total_barang_masuk ?? 0 ?></h3>
                        <small class="text-muted"><?= $barang_masuk_bulan_ini ?? 0 ?> transaksi</small>
                    </div>
                    <div class="bg-info rounded-circle p-3">
                        <i class="fas fa-sign-in-alt text-white fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Barang Keluar (Bulan Ini)</h6>
                        <h3 class="mb-0"><?= $total_barang_keluar ?? 0 ?></h3>
                        <small class="text-muted"><?= $barang_keluar_bulan_ini ?? 0 ?> transaksi</small>
                    </div>
                    <div class="bg-warning rounded-circle p-3">
                        <i class="fas fa-sign-out-alt text-white fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Alert Stok Menipis -->
    <?php if(($total_stok_menipis ?? 0) > 0): ?>
    <div class="row mt-3">
        <div class="col-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> 
                Terdapat <strong><?= $total_stok_menipis ?? 0 ?></strong> barang dengan stok menipis (≤ 10)! 
                Segera lakukan pengadaan barang.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Alert Stok Habis -->
    <?php if(($total_stok_habis ?? 0) > 0): ?>
    <div class="row mt-2">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> 
                Terdapat <strong><?= $total_stok_habis ?? 0 ?></strong> barang dengan stok habis (0)!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="table-container">
                <h5><i class="fas fa-exclamation-triangle text-warning"></i> Stok Menipis</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                                <th>Warna</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($stok_menipis) && count($stok_menipis) > 0): ?>
                                <?php foreach($stok_menipis as $item): ?>
                                <tr>
                                    <td><?= $item['id_barang'] ?? '-' ?></td>
                                    <td><?= $item['nama_barang'] ?? '-' ?></td>
                                    <td class="text-danger fw-bold"><?= $item['stok'] ?? 0 ?></td>
                                    <td><?= $item['nama_satuan'] ?? '-' ?></td>
                                    <td><?= $item['warna'] ?? '-' ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center">Tidak ada data stok menipis</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="table-container">
                <h5><i class="fas fa-history"></i> Transaksi Terbaru</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode Transaksi</th>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>Jenis</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($transaksi_terbaru) && count($transaksi_terbaru) > 0): ?>
                                <?php foreach($transaksi_terbaru as $item): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($item['tanggal'] ?? date('Y-m-d'))) ?></td>
                                    <td><?= $item['kode_transaksi'] ?? '-' ?></td>
                                    <td><?= $item['nama_barang'] ?? '-' ?></td>
                                    <td><?= $item['jumlah'] ?? 0 ?></td>
                                    <td>
                                        <span class="badge <?= ($item['jenis_transaksi'] ?? '') == 'Masuk' ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $item['jenis_transaksi'] ?? '-' ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center">Tidak ada transaksi terbaru</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik 7 Hari Terakhir -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="table-container">
                <h5><i class="fas fa-chart-line"></i> Grafik Barang Masuk (7 Hari Terakhir)</h5>
                <canvas id="chartMasuk" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="table-container">
                <h5><i class="fas fa-chart-line"></i> Grafik Barang Keluar (7 Hari Terakhir)</h5>
                <canvas id="chartKeluar" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Top 5 Barang -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="table-container">
                <h5><i class="fas fa-arrow-up text-success"></i> Top 5 Barang Stok Terbanyak</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($barang_terbanyak) && count($barang_terbanyak) > 0): ?>
                                <?php foreach($barang_terbanyak as $item): ?>
                                <tr>
                                    <td><?= $item['id_barang'] ?? '-' ?></td>
                                    <td><?= $item['nama_barang'] ?? '-' ?></td>
                                    <td class="fw-bold text-success"><?= $item['stok'] ?? 0 ?></td>
                                    <td><?= $item['nama_satuan'] ?? '-' ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="table-container">
                <h5><i class="fas fa-arrow-down text-danger"></i> Top 5 Barang Stok Tersedikit</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($barang_tersedikit) && count($barang_tersedikit) > 0): ?>
                                <?php foreach($barang_tersedikit as $item): ?>
                                <tr>
                                    <td><?= $item['id_barang'] ?? '-' ?></td>
                                    <td><?= $item['nama_barang'] ?? '-' ?></td>
                                    <td class="fw-bold text-danger"><?= $item['stok'] ?? 0 ?></td>
                                    <td><?= $item['nama_satuan'] ?? '-' ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <td><td colspan="4" class="text-center">Tidak ada data</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Barang Masuk
    const grafikMasuk = <?= json_encode($grafik_masuk ?? []) ?>;
    const ctxMasuk = document.getElementById('chartMasuk')?.getContext('2d');
    if (ctxMasuk && grafikMasuk.length > 0) {
        new Chart(ctxMasuk, {
            type: 'line',
            data: {
                labels: grafikMasuk.map(item => item.tanggal),
                datasets: [{
                    label: 'Barang Masuk',
                    data: grafikMasuk.map(item => item.total),
                    borderColor: '#2a5298',
                    backgroundColor: 'rgba(42, 82, 152, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    }

    // Grafik Barang Keluar
    const grafikKeluar = <?= json_encode($grafik_keluar ?? []) ?>;
    const ctxKeluar = document.getElementById('chartKeluar')?.getContext('2d');
    if (ctxKeluar && grafikKeluar.length > 0) {
        new Chart(ctxKeluar, {
            type: 'line',
            data: {
                labels: grafikKeluar.map(item => item.tanggal),
                datasets: [{
                    label: 'Barang Keluar',
                    data: grafikKeluar.map(item => item.total),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    }
</script>

<style>

    body{
        background:#f7f9fc;
    }

    /* HEADER DASHBOARD */

    .dashboard-header{
        margin-bottom:25px;
    }

    .dashboard-header h3{
        color:#1e3c72;
        font-size:28px;
        font-weight:700;
        margin-bottom:5px;
    }

    .dashboard-header p{
        color:#64748b;
        font-size:14px;
        margin:0;
    }

    /* =========================
       STAT CARD ATAS
    ========================== */

    .stat-card{
        background:#ffffff;
        border-radius:16px;
        padding:22px;
        box-shadow:0 4px 18px rgba(0,0,0,.05);
        transition:all .25s ease;
        margin-bottom:20px;

        border-top:4px solid #2a5298;

        /* supaya rata */
        min-height:145px;
        display:flex;
        align-items:center;
    }

    .stat-card:hover{
        transform:translateY(-4px);
        box-shadow:0 8px 24px rgba(0,0,0,.08);
    }

    /* FLEX DALAM CARD */

    .stat-card .d-flex{
        width:100%;
    }

    /* JUDUL CARD */

    .stat-card h6{
        font-size:13px !important;
        font-weight:600;
        color:#64748b !important;
        margin-bottom:10px;
        line-height:1.4;
    }

    /* ANGKA */

    .stat-card h3{
        font-size:38px;
        font-weight:700;
        margin-bottom:4px !important;
        color:#1e293b;
        line-height:1;
    }

    /* SUBTITLE */

    .stat-card small{
        font-size:12px;
        color:#94a3b8 !important;

        /* supaya card tanpa small tetap balance */
        display:block;
        min-height:18px;
    }

    /* ICON */

    .stat-card .rounded-circle{

        width:58px !important;
        height:58px !important;

        display:flex;
        align-items:center;
        justify-content:center;

        flex-shrink:0;
    }

    .stat-card i.fa-2x{
        font-size:22px !important;
    }

    /* WARNA ICON */

    .bg-primary{
        background:#2a5298 !important;
    }

    .bg-success{
        background:#198754 !important;
    }

    .bg-info{
        background:#0ea5e9 !important;
    }

    .bg-warning{
        background:#f59e0b !important;
    }

    /* =========================
       ALERT
    ========================== */

    .alert{
        border:none;
        border-radius:14px;
        padding:15px 18px;
        box-shadow:0 2px 10px rgba(0,0,0,.04);
        font-size:14px;
    }

    .alert-warning{
        background:#fff8e6;
        color:#9a6700;
    }

    .alert-danger{
        background:#fff1f2;
        color:#dc2626;
    }

    /* =========================
       TABLE CONTAINER
    ========================== */

    .table-container{
        background:white;
        border-radius:16px;
        padding:22px;
        box-shadow:0 4px 20px rgba(0,0,0,.04);
        margin-bottom:22px;
        height:100%;
    }

    .table-container h5{
        margin-bottom:18px;
        color:#1e3c72;
        font-weight:600;
        font-size:18px;

        border-bottom:1px solid #eef2f7;
        padding-bottom:10px;
    }

    /* =========================
       TABLE
    ========================== */

    .table{
        margin-bottom:0;
    }

    .table th{
        background:#f8fafc;
        border:none;
        padding:12px;
        font-size:12px;
        font-weight:600;
        color:#475569;
    }

    .table td{
        padding:12px;
        font-size:13px;
        vertical-align:middle;
        border-color:#f1f5f9;
    }

    .table tbody tr{
        transition:.2s;
    }

    .table tbody tr:hover{
        background:#f8fbff;
    }

    /* =========================
       BADGE
    ========================== */

    .badge{
        font-size:11px;
        padding:6px 10px;
        border-radius:8px;
        font-weight:500;
    }

    /* =========================
       CHART
    ========================== */

    canvas{
        margin-top:12px;
    }

    /* =========================
       SCROLL TABLE
    ========================== */

    .table-responsive{
        border-radius:10px;
    }

    /* =========================
       RESPONSIVE
    ========================== */

    @media(max-width:768px){

        .stat-card{
            min-height:auto;
            padding:18px;
        }

        .stat-card h3{
            font-size:28px;
        }

        .table-container{
            padding:15px;
            overflow-x:auto;
        }

        .table td,
        .table th{
            white-space:nowrap;
        }

    }

</style>

<?= $this->include('layout/footer') ?>