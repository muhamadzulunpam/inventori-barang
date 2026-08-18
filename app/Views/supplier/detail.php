<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Detail Supplier</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('/supplier') ?>">Data Supplier</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="<?= base_url('/supplier') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Foto/Logo Supplier (opsional) -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-building"></i> Logo/Profile</h5>
                </div>
                <div class="card-body text-center">
                    <?php if(!empty($supplier['foto']) && $supplier['foto'] != 'default.png'): ?>
                        <img src="<?= base_url('uploads/supplier/' . $supplier['foto']) ?>" 
                             alt="Foto Supplier" 
                             class="img-fluid rounded"
                             style="max-height: 200px; width: auto;">
                    <?php else: ?>
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-truck fa-4x text-white"></i>
                        </div>
                    <?php endif; ?>
                    <div class="mt-3">
                        <h5><?= $supplier['nama_supplier'] ?></h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <!-- Info Card -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Supplier</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="35%"><strong>ID Supplier</strong></td>
                                    <td width="65%">: <span class="badge bg-info"><?= $supplier['id_supplier'] ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Supplier</strong></td>
                                    <td>: <?= $supplier['nama_supplier'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Kontak Person</strong></td>
                                    <td>: <?= !empty($supplier['kontak_person']) ? $supplier['kontak_person'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>No. Telepon</strong></td>
                                    <td>: <?= !empty($supplier['no_telp']) ? $supplier['no_telp'] : '-' ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="35%"><strong>Email</strong></td>
                                    <td width="65%">: <?= !empty($supplier['email']) ? $supplier['email'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat</strong></td>
                                    <td>: <?= !empty($supplier['alamat']) ? $supplier['alamat'] : '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Total Barang Masuk</strong></td>
                                    <td>: <span class="fw-bold text-primary"><?= number_format($total_barang_masuk ?? 0) ?></span> Unit</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Transaksi</strong></td>
                                    <td>: <span class="fw-bold text-success"><?= number_format($total_transaksi ?? 0) ?></span> Kali</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Barang Masuk -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Riwayat Barang Masuk</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Tanggal Masuk</th>
                                    <th width="15%">Kode Transaksi</th>
                                    <th width="20%">Nama Barang</th>
                                    <th width="10%">Jumlah</th>
                                    <th width="10%">Satuan</th>
                                    <th width="15%">Harga Beli</th>
                                    <th width="10%">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($riwayat) && count($riwayat) > 0): ?>
                                    <?php $no = 1; foreach($riwayat as $item): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= date('d/m/Y', strtotime($item['tanggal_masuk'])) ?></td>
                                        <td>
                                            <span class="badge bg-secondary"><?= $item['id_barang_masuk'] ?></span>
                                        </td>
                                        <td><?= $item['nama_barang'] ?? '-' ?></td>
                                        <td class="fw-bold text-primary"><?= number_format($item['jumlah_masuk']) ?></td>
                                        <td><?= $item['nama_satuan'] ?? '-' ?></td>
                                        <td>Rp <?= number_format($item['harga'] ?? 0, 0, ',', '.') ?></td>
                                        <td class="fw-bold text-success">Rp <?= number_format(($item['jumlah_masuk'] * ($item['harga'] ?? 0)), 0, ',', '.') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-box-open fa-3x text-muted mb-3 d-block"></i>
                                            <p class="text-muted">Belum ada riwayat barang masuk dari supplier ini</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <?php if(!empty($riwayat) && count($riwayat) > 0): ?>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">Total Keseluruhan:</th>
                                    <th class="text-primary"><?= number_format($total_barang_masuk ?? 0) ?></th>
                                    <th colspan="2"></th>
                                    <th class="text-success">
                                        <?php 
                                            $grand_total = 0;
                                            foreach($riwayat as $item):
                                                $grand_total += ($item['jumlah_masuk'] * ($item['harga'] ?? 0));
                                            endforeach;
                                            echo 'Rp ' . number_format($grand_total, 0, ',', '.');
                                        ?>
                                    </th>
                                </tr>
                            </tfoot>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Keterangan tambahan -->
    <?php if(!empty($supplier['keterangan'])): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Keterangan Tambahan</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?= $supplier['keterangan'] ?></p>
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