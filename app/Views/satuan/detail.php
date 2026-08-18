<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Detail Satuan</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('/satuan') ?>">Data Satuan</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="<?= base_url('/satuan') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Informasi Satuan -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Satuan</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-ruler fa-4x text-primary"></i>
                    </div>
                    <table class="table table-borderless">
                        <tr>
                            <td width="35%"><strong>ID Satuan</strong></td>
                            <td width="65%">: <span class="badge bg-info"><?= $satuan['id_satuan'] ?></span></td>
                        </tr>
                        <tr>
                            <td><strong>Nama Satuan</strong></td>
                            <td>: <?= $satuan['nama_satuan'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Keterangan</strong></td>
                            <td>: <?= !empty($satuan['keterangan']) ? $satuan['keterangan'] : '<span class="text-muted">-</span>' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Terdaftar Sejak</strong></td>
                            <td>: <?= date('d/m/Y H:i:s', strtotime($satuan['created_at'])) ?></td>
                        </tr>
                        <?php if(!empty($satuan['updated_at'])): ?>
                        <tr>
                            <td><strong>Terakhir Update</strong></td>
                            <td>: <?= date('d/m/Y H:i:s', strtotime($satuan['updated_at'])) ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>

        <!-- Statistik Satuan -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Statistik</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <h3 class="text-primary"><?= number_format($total_barang) ?></h3>
                                <p class="text-muted mb-0">Total Barang</p>
                                <small class="text-muted">dengan satuan ini</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <h3 class="text-success"><?= number_format($total_stok) ?></h3>
                                <p class="text-muted mb-0">Total Stok</p>
                                <small class="text-muted">dari semua barang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Barang dengan Satuan Ini -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Daftar Barang</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="20%">ID Barang</th>
                                    <th width="35%">Nama Barang</th>
                                    <th width="15%">Warna</th>
                                    <th width="15%">Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($daftar_barang) > 0): ?>
                                    <?php foreach($daftar_barang as $item): ?>
                                    <tr>
                                        <td><span class="badge bg-info"><?= $item['id_barang'] ?></span></td>
                                        <td><strong><?= $item['nama_barang'] ?></strong></td>
                                        <td><?= !empty($item['warna']) ? $item['warna'] : '-' ?></td>
                                        <td class="fw-bold <?= $item['stok'] <= 10 ? 'text-danger' : 'text-success' ?>">
                                            <?= number_format($item['stok']) ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="fas fa-box-open fa-3x text-muted mb-3 d-block"></i>
                                            <p class="text-muted">Belum ada barang dengan satuan ini</p>
                                            <a href="<?= base_url('/barang/new') ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus"></i> Tambah Barang
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if(count($daftar_barang) > 0): ?>
                <div class="card-footer">
                    <small class="text-muted">
                        <i class="fas fa-database"></i> Total Barang: <?= count($daftar_barang) ?>
                    </small>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    /* =========================
       PAGE HEADER
    ========================== */
    .page-title{
        font-weight: 700;
        color: #1e3c72;
        margin-bottom: 2px;
    }

    .breadcrumb{
        background: transparent;
        padding: 0;
        margin-bottom: 0;
        font-size: 13px;
    }

    .breadcrumb-item a{
        text-decoration: none;
        color: #2a5298;
    }

    /* =========================
       BUTTON STYLE
    ========================== */
    .btn-warning,
    .btn-secondary{
        padding: 7px 16px;
        font-size: 14px;
        border-radius: 8px;
        transition: 0.25s ease;
    }

    .btn-warning:hover,
    .btn-secondary:hover{
        transform: translateY(-2px);
    }

    /* =========================
       CARD STYLE
    ========================== */
    .card{
        border: none !important;
        border-radius: 16px !important;
        box-shadow: 0 5px 20px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .card-header{
        background: linear-gradient(135deg, #1e3c72, #2a5298) !important;
        border: none !important;
        padding: 15px 22px;
    }

    .card-header h5{
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }

    .card-body{
        padding: 22px !important;
        background: white;
    }

    /* =========================
       FOTO BARANG
    ========================== */
    .img-fluid{
        max-height: 220px !important;
        object-fit: cover;
        border-radius: 12px !important;
        border: 1px solid #e5e7eb;
        padding: 5px;
        background: #fff;
    }

    /* jika tidak ada gambar */
    .bg-secondary.rounded{
        border-radius: 12px !important;
        background: #eef2f7 !important;
    }

    .bg-secondary.rounded i{
        color: #9ca3af !important;
    }

    /* =========================
       TABLE INFO
    ========================== */
    .table-borderless td{
        padding: 11px 8px !important;
        border: none !important;
        font-size: 14px;
    }

    .table-borderless strong{
        color: #374151;
        font-weight: 600;
    }

    /* =========================
       BADGE STYLE
    ========================== */
    .badge{
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge.bg-info{
        background: #e7f1ff !important;
        color: #2f80ed !important;
    }

    .badge.bg-success{
        background: #eaf8f0 !important;
        color: #27ae60 !important;
    }

    .badge.bg-danger{
        background: #fff0f0 !important;
        color: #e74c3c !important;
    }

    .badge.bg-secondary{
        background: #f2f4f7 !important;
        color: #6b7280 !important;
    }

    /* =========================
       STOCK TEXT
    ========================== */
    .text-success{
        color: #27ae60 !important;
        font-weight: 600;
    }

    .text-danger{
        color: #e74c3c !important;
        font-weight: 600;
    }

    /* =========================
       KETERANGAN CARD
    ========================== */
    .bg-info.text-white{
        background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
    }

    /* =========================
       RESPONSIVE
    ========================== */
    @media(max-width:768px){

        .page-title{
            font-size: 22px;
        }

        .btn-warning,
        .btn-secondary{
            margin-top: 8px;
            width: 100%;
        }

        .card-body{
            padding: 18px !important;
        }

        .img-fluid{
            max-height: 180px !important;
        }
    }
</style>

<?= $this->include('layout/footer') ?>