<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">LAPORAN STOK BARANG</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="<?= base_url('/dashboard') ?>">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Laporan Stok</li>
                        </ol>
                    </nav>
                </div>

                <div>
                    <a href="<?= base_url('/laporan_stok/print?' . http_build_query($filter)) ?>"
                       class="btn btn-add"
                       target="_blank">
                        <i class="fas fa-print"></i> Print PDF
                    </a>
                </div>
            </div>
        </div>
    </div>


    <!-- CARD STATISTIK -->
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Total Barang</h6>
                            <h2><?= number_format($statistik['total_barang'] ?? 0) ?></h2>
                        </div>
                        <i class="fas fa-boxes fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Total Stok</h6>
                            <h2><?= number_format($statistik['total_stok'] ?? 0) ?></h2>
                        </div>
                        <i class="fas fa-database fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Stok Menipis</h6>
                            <h2><?= number_format($statistik['stok_menipis'] ?? 0) ?></h2>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Stok Habis</h6>
                            <h2><?= number_format($statistik['stok_habis'] ?? 0) ?></h2>
                        </div>
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- FILTER -->
    <div class="card filter-card mb-4">
        <div class="card-body">

            <form method="GET" action="<?= base_url('/laporan_stok') ?>">

                <div class="row align-items-end g-3">

                    <div class="col-md-4">
                        <label class="form-label custom-label">
                            <i class="fas fa-tags"></i> Jenis Barang
                        </label>

                        <select name="id_jenis" class="form-select custom-input">
                            <option value="">Semua Jenis</option>

                            <?php foreach($jenis_barang as $item): ?>
                                <option value="<?= esc($item['id_jenis']) ?>"
                                    <?= (($filter['id_jenis'] ?? '') == $item['id_jenis']) ? 'selected' : '' ?>>
                                    <?= esc($item['nama_jenis']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <div class="col-md-4">
                        <label class="form-label custom-label">
                            <i class="fas fa-filter"></i> Status
                        </label>

                        <select name="status" class="form-select custom-input">
                            <option value="">Semua Status</option>

                            <option value="aman"
                                <?= (($filter['status'] ?? '') == 'aman') ? 'selected' : '' ?>>
                                Aman
                            </option>

                            <option value="menipis"
                                <?= (($filter['status'] ?? '') == 'menipis') ? 'selected' : '' ?>>
                                Menipis
                            </option>

                            <option value="habis"
                                <?= (($filter['status'] ?? '') == 'habis') ? 'selected' : '' ?>>
                                Habis
                            </option>
                        </select>
                    </div>


                    <div class="col-md-4">
                        <label class="form-label custom-label">
                            <i class="fas fa-search"></i> Cari Barang
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control custom-input"
                               placeholder="Cari kode/nama barang..."
                               value="<?= esc($filter['search'] ?? '') ?>">
                    </div>


                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="<?= base_url('/laporan_masuk') ?>" class="btn btn-secondary">
                                <i class="fas fa-sync"></i> Reset
                            </a>
                        </div>
                    </div>
                    

                </div>

            </form>

        </div>
    </div>


    <!-- TABLE -->
    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-boxes"></i> Data Stok Barang
            </h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Warna</th>
                            <th>Jenis</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if(!empty($stok_barang)): ?>

                        <?php $no=1; foreach($stok_barang as $item): ?>

                        <?php
                            // BATAS MINIMUM
                            $stok_minimum = 10;

                            // STATUS
                            if($item['stok'] == 0){
                                $status_class = "danger";
                                $status_text = "Habis";
                            }
                            elseif($item['stok'] <= $stok_minimum){
                                $status_class = "warning";
                                $status_text = "Menipis";
                            }
                            else{
                                $status_class = "success";
                                $status_text = "Aman";
                            }

                            // WARNA ANGKA STOK
                            if($item['stok'] <= $stok_minimum){
                                $stok_class='text-danger fw-bold';
                            } else {
                                $stok_class='text-success fw-bold';
                            }
                        ?>

                        <tr>

                        <td><?= $no++ ?></td>

                        <td>
                            <span class="badge bg-info">
                                <?= esc($item['id_barang']) ?>
                            </span>
                        </td>

                        <td>
                            <strong><?= esc($item['nama_barang']) ?></strong>
                        </td>

                        <td><?= esc($item['warna']) ?></td>

                        <td><?= esc($item['nama_jenis'] ?? '-') ?></td>

                        <td>
                            <span class="<?= $stok_class ?>">
                                <?= number_format($item['stok']) ?>
                            </span>
                        </td>

                        <td><?= esc($item['nama_satuan'] ?? '-') ?></td>

                        <td>
                            <span class="badge bg-<?= $status_class ?>">
                                <?= $status_text ?>
                            </span>
                        </td>

                        </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-box-open fa-3x text-muted mb-3 d-block"></i>
                                <p class="text-muted">Tidak ada data stok barang</p>
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                    <tfoot>
                        <tr class="table-light">
                            <th colspan="5" class="text-end">
                                Total Keseluruhan :
                            </th>

                            <th>
                                <?= number_format(array_sum(array_column($stok_barang,'stok'))) ?>
                            </th>

                            <th colspan="3"></th>
                        </tr>
                    </tfoot>

                </table>

            </div>

        </div>

        <div class="card-footer">
            <small class="text-muted">
                <i class="fas fa-database"></i>
                Total Data: <?= !empty($stok_barang) ? count($stok_barang) : 0 ?>
            </small>
        </div>

    </div>

</div>



<style>
    /* =========================
       BUTTON CUSTOM
    ========================== */
    .btn-primary {
        background: linear-gradient(135deg, #1e3c72, #2a5298) !important;
        border: none !important;
        border-radius: 10px;
        padding: 8px 18px;
        font-weight: 500;
        transition: 0.25s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(30,60,114,0.20);
    }

    /* =========================
       CARD MODERN
    ========================== */
    .card.bg-primary,
    .card.bg-danger,
    .card.bg-success,
    .card.bg-warning {
        border-radius: 14px !important;
        box-shadow: 0 6px 18px rgba(0,0,0,0.08) !important;
        transition: all 0.25s ease;
    }

    .card.bg-primary:hover,
    .card.bg-danger:hover,
    .card.bg-success:hover,
    .card.bg-warning:hover {
        transform: translateY(-3px);
    }

    .card-body {
        padding: 14px 16px !important;
    }

    .card h6 {
        font-size: 13px !important;
        font-weight: 600;
        margin-bottom: 6px !important;
    }

    .card h2 {
        font-size: 22px !important;
        font-weight: 700;
    }

    .card i {
        opacity: 1 !important;
        filter: drop-shadow(0 2px 2px rgba(0,0,0,0.25));
    }

    .opacity-50 {
        opacity: 0.5;
    }

    /* =========================
       FORM FILTER
    ========================== */
    .form-control,
    .form-select {
        border-radius: 10px !important;
        border: 1px solid #dde4f0 !important;
        padding: 10px 12px !important;
        font-size: 14px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #2a5298 !important;
        box-shadow: 0 0 0 3px rgba(42,82,152,0.08) !important;
    }

    /* =========================
       CARD GLOBAL
    ========================== */
    .card {
        border: none !important;
        border-radius: 14px !important;
    }

    .card-header {
        background: white !important;
        border-bottom: 1px solid #edf2f7 !important;
        padding: 18px 20px !important;
    }

    .card-header h5 {
        color: #1e3c72;
        font-weight: 650;
        font-size: 17px;
    }

    /* =========================
       TABLE DENGAN GARIS JELAS
    ========================== */
    .table {
        width: 100%;
        font-size: 14px;
        border-collapse: collapse !important;
    }

    .table th {
        background: #f8f9fc !important;
        font-weight: 600;
        text-align: center;
        padding: 12px !important;
        border: 1px solid #bfc6d1 !important;
    }

    .table td {
        padding: 10px !important;
        vertical-align: middle;
        border: 1px solid #bfc6d1 !important; /* garis tabel */
    }

    .table-hover tbody tr:hover {
        background: #f7faff;
    }

    .table tfoot th {
        border: 1px solid #bfc6d1 !important;
        background: #f4f6fa !important;
    }

    /* badge merah kode */
    .badge.bg-danger {
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: 600;
    }

    /* breadcrumb */
    .breadcrumb {
        background: transparent;
        padding: 0;
        font-size: 13px;
    }

    .page-title {
        font-weight: 700;
    }

    /* =========================
       PRINT PDF RAPI
    ========================== */
    @media print {

        .btn,
        .breadcrumb,
        form,
        .card-header small {
            display: none !important;
        }

        .table {
            border-collapse: collapse !important;
        }

        .table th,
        .table td {
            border: 1px solid black !important;
            padding: 8px !important;
            font-size: 12px;
        }

        .table th {
            background: #eaeaea !important;
            color: black !important;
        }

        .card {
            box-shadow: none !important;
        }
    }
    /* =========================
    BUTTON PRINT
    ========================== */
    .btn-add{
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        border: none;
        color: white !important;
        padding: 10px 22px;
        border-radius: 12px;
        font-weight: 500;
        transition: 0.25s ease;
        font-size: 14px;
    }

    .btn-add:hover{
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(30,60,114,0.18);
    }

    /* =========================
    FILTER CARD
    ========================== */
    .filter-card{
        border-radius: 16px !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    }

    /* label */
    .custom-label{
        font-size: 13px;
        font-weight: 600;
        color: #1e3c72;
        margin-bottom: 8px;
    }

    /* input */
    .custom-input{
        border: 1px solid #dde4f0 !important;
        border-radius: 12px !important;
        padding: 11px 14px !important;
        font-size: 14px;
    }

    /* tombol cari */
    .btn-search{
        background: #2a5298 !important;
        border: none !important;
        color: white !important;
        border-radius: 12px !important;
        padding: 11px;
        font-size: 14px;
        font-weight: 500;
        transition: 0.25s ease;
    }

    .btn-search:hover{
        background: #1e3c72 !important;
    }

    /* tombol reset */
    .btn-reset{
        background: #eef2f7 !important;
        border: none !important;
        color: #4b5563 !important;
        border-radius: 12px !important;
        padding: 11px;
        font-size: 14px;
        font-weight: 500;
    }

    .btn-reset:hover{
        background: #dce4ef !important;
    }
</style>


<?= $this->include('layout/footer') ?>