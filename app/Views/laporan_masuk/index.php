<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Laporan Barang Masuk</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item active">Laporan Barang Masuk</li>
                        </ol>
                    </nav>
                </div>

                <div>
                    <a href="<?= base_url('/laporan_masuk/print?' . http_build_query($filter)) ?>" 
                       class="btn btn-secondary me-2" target="_blank">
                        <i class="fas fa-print"></i> Print PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ALERT -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- CARD STAT -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Total Transaksi</h6>
                        <h2 class="mb-0"><?= number_format($ringkasan['total_transaksi'] ?? 0) ?></h2>
                    </div>
                    <i class="fas fa-exchange-alt fa-2x opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Total Barang Masuk</h6>
                        <h2 class="mb-0"><?= number_format($ringkasan['total_jumlah'] ?? 0) ?></h2>
                    </div>
                    <i class="fas fa-boxes fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER (KOTAK RAPI SAMA KAYA KELUAR) -->
    <div class="card filter-card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filter</h5>
        </div>

        <div class="card-body">
            <form method="GET" action="<?= base_url('/laporan_masuk') ?>">
                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label">Tanggal Awal</label>
                        <input type="date" name="start_date" class="form-control custom-input"
                               value="<?= esc($filter['start_date'] ?? '') ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control custom-input"
                               value="<?= esc($filter['end_date'] ?? '') ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Supplier</label>
                        <select name="id_supplier" class="form-select custom-input">
                            <option value="">-- Semua Supplier --</option>
                            <?php foreach($supplier as $item): ?>
                                <option value="<?= $item['id_supplier'] ?>"
                                    <?= (($filter['id_supplier'] ?? '') == $item['id_supplier']) ? 'selected' : '' ?>>
                                    <?= esc($item['nama_supplier']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control custom-input"
                               placeholder="Cari kode / barang / supplier..."
                               value="<?= esc($filter['search'] ?? '') ?>">
                    </div>

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
            </form>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0"><i class="fas fa-list"></i> Riwayat Barang Masuk</h5>
            <small class="text-muted">Total: <?= count($laporan) ?> transaksi</small>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kode</th>
                            <th>Supplier</th>
                            <th>Barang</th>
                            <th>Qty</th>
                            <th>Satuan</th>
                            <th>User</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if(!empty($laporan)): ?>
                            <?php $no = 1; foreach($laporan as $item): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= date('d/m/Y', strtotime($item['tanggal_masuk'])) ?></td>
                                <td><span class="badge bg-secondary"><?= esc($item['id_barang_masuk']) ?></span></td>
                                <td><?= esc($item['nama_supplier'] ?? '-') ?></td>
                                <td><?= esc($item['nama_barang']) ?></td>
                                <td class="text-end fw-bold text-success"><?= number_format($item['jumlah_masuk']) ?></td>
                                <td><?= esc($item['nama_satuan'] ?? '-') ?></td>
                                <td><?= esc($item['nama_user'] ?? '-') ?></td>
                                <td><?= esc($item['keterangan'] ?? '-') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-box-open fa-3x text-muted"></i>
                                    <p class="text-muted">Tidak ada data</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                    <tfoot>
                        <tr class="table-active">
                            <th colspan="5" class="text-end">Total Barang Masuk:</th>
                            <th class="text-end"><?= number_format($ringkasan['total_jumlah'] ?? 0) ?></th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>

                </table>
            </div>
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
</style>


<?= $this->include('layout/footer') ?>