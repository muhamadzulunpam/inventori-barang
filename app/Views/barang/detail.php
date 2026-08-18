<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Detail Barang</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('/barang') ?>">Data Barang</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <?php if(session()->get('level') != 'staff_gudang'): ?>
                    <?php endif; ?>
                    <a href="<?= base_url('/barang') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Foto Barang -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-image"></i> Foto Barang</h5>
                </div>
                <div class="card-body text-center">
                    <?php if(!empty($barang['foto']) && $barang['foto'] != 'default.png'): ?>
                        <img src="<?= base_url('uploads/barang/' . $barang['foto']) ?>" 
                             alt="Foto Barang" 
                             class="img-fluid rounded"
                             style="max-height: 200px; width: auto;">
                    <?php else: ?>
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-box fa-4x text-white"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <!-- Info Card -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Barang</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="35%"><strong>Kode Barang</strong></td>
                                    <td width="65%">: <span class="badge bg-info"><?= $barang['id_barang'] ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Barang</strong></td>
                                    <td>: <?= $barang['nama_barang'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Warna</strong></td>
                                    <td>: <?= !empty($barang['warna']) ? $barang['warna'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Barang</strong></td>
                                    <td>: <?= $barang['nama_jenis'] ?? '-' ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="35%"><strong>Satuan</strong></td>
                                    <td width="65%">: <?= $barang['nama_satuan'] ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Total Stok</strong></td>
                                    <td>: 
                                        <span class="fw-bold <?= $barang['stok'] <= 10 ? 'text-danger' : 'text-success' ?>">
                                            <?= number_format($barang['stok']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status Stok</strong></td>
                                    <td>: 
                                        <?php if($barang['stok'] <= 0): ?>
                                            <span class="badge bg-secondary">Habis</span>
                                        <?php elseif($barang['stok'] <= 10): ?>
                                            <span class="badge bg-danger">Stok Menipis</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Tersedia</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Terakhir Update</strong></td>
                                    <td>: <?= !empty($barang['updated_at']) ? date('d/m/Y H:i:s', strtotime($barang['updated_at'])) : '-' ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Keterangan tambahan -->
    <?php if(!empty($barang['jenis_keterangan'])): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Keterangan Jenis Barang</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?= $barang['jenis_keterangan'] ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
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