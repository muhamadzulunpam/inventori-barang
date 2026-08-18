<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Detail Barang Keluar</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('/barang_keluar') ?>">Barang Keluar</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <?php if(session()->get('level') != 'staff_gudang'): ?>
                    <?php endif; ?>
                    <a href="<?= base_url('/barang_keluar') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Error jika stok menipis -->
    <?php if(($barang_keluar['stok_sekarang'] ?? 0) <= 10 && ($barang_keluar['stok_sekarang'] ?? 0) > 0): ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle"></i> 
            <strong>Peringatan!</strong> Stok barang ini tersisa <?= number_format($barang_keluar['stok_sekarang'] ?? 0) ?> <?= $barang_keluar['nama_satuan'] ?? '' ?> dan hampir habis. Segera lakukan pengadaan!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif(($barang_keluar['stok_sekarang'] ?? 0) == 0): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-times-circle"></i> 
            <strong>Stok Habis!</strong> Stok barang ini sudah habis. Segera lakukan pengadaan barang!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Card Informasi Transaksi -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt"></i> Informasi Transaksi</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>ID Transaksi</strong></td>
                            <td>: <span class="badge bg-danger"><?= $barang_keluar['id_barang_keluar'] ?></span></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Keluar</strong></td>
                            <td>: <?= date('d/m/Y', strtotime($barang_keluar['tanggal_keluar'])) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Waktu Input</strong></td>
                            <td>: <?= date('d/m/Y H:i:s', strtotime($barang_keluar['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Keterangan</strong></td>
                            <td>: <?= $barang_keluar['keterangan'] ?: '<span class="text-muted">- Tidak ada keterangan -</span>' ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Card Informasi Barang -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-box"></i> Informasi Barang</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Nama Barang</strong></td>
                            <td>: <strong><?= $barang_keluar['nama_barang'] ?></strong></td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah Keluar</strong></td>
                            <td class="text-danger fw-bold">: <?= number_format($barang_keluar['jumlah_keluar']) ?> <?= $barang_keluar['nama_satuan'] ?? '' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Satuan</strong></td>
                            <td>: <?= $barang_keluar['nama_satuan'] ?? '-' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Stok Sekarang</strong></td>
                            <td class="<?= ($barang_keluar['stok_sekarang'] ?? 0) <= 10 && ($barang_keluar['stok_sekarang'] ?? 0) > 0 ? 'text-warning fw-bold' : (($barang_keluar['stok_sekarang'] ?? 0) == 0 ? 'text-danger fw-bold' : 'text-success fw-bold') ?>">
                                : <?= number_format($barang_keluar['stok_sekarang'] ?? 0) ?> <?= $barang_keluar['nama_satuan'] ?? '' ?>
                                <?php if(($barang_keluar['stok_sekarang'] ?? 0) <= 10 && ($barang_keluar['stok_sekarang'] ?? 0) > 0): ?>
                                    <span class="badge bg-warning ms-2">Stok Menipis</span>
                                <?php elseif(($barang_keluar['stok_sekarang'] ?? 0) == 0): ?>
                                    <span class="badge bg-danger ms-2">Stok Habis</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Informasi User -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Informasi Penginput</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Diinput Oleh</strong></td>
                                    <td>: <?= $barang_keluar['nama_user'] ?? session()->get('nama') ?></td>
                                </tr>
                                
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-exclamation-triangle text-warning fa-4x"></i>
                </div>
                <p>Apakah Anda yakin ingin menghapus transaksi barang keluar untuk:</p>
                <p class="fw-bold text-center">"<span id="deleteNama"></span>"</p>
                <p class="text-danger"><small>Data yang dihapus tidak dapat dikembalikan!</small></p>
                <p class="text-warning"><small>Stok barang akan dikembalikan secara otomatis!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Ya, Hapus</a>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, nama) {
        document.getElementById('deleteNama').innerText = nama;
        document.getElementById('confirmDeleteBtn').href = '/barang_keluar/delete/' + id;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    // Print functionality
    function printPage() {
        window.print();
    }
</script>

<style>
    /* =========================
       PAGE TITLE
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
        color: white !important;
        border: none;
        padding: 15px 22px;
    }

    .card-header h5{
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .card-body{
        padding: 22px !important;
        background: #fff;
    }

    /* =========================
       BUTTON STYLE
    ========================== */
    .btn{
        border-radius: 8px !important;
        font-size: 14px;
        padding: 7px 18px;
        transition: 0.25s ease;
    }

    .btn:hover{
        transform: translateY(-2px);
    }

    .btn-warning{
        color: white !important;
    }

    .btn-success{
        padding: 7px 16px;
    }

    /* =========================
       TABLE STYLE
    ========================== */
    .table-borderless td{
        padding: 9px 0;
        font-size: 14px;
        color: #374151;
        vertical-align: middle;
    }

    .table-borderless strong{
        color: #1f2937;
    }

    /* =========================
       BADGE STYLE
    ========================== */
    .badge{
        font-size: 12px;
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: 500;
    }

    /* =========================
       ALERT STYLE
    ========================== */
    .alert{
        border: none;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.05);
        padding: 16px 20px;
        font-size: 14px;
    }

    .alert-warning{
        background: #fff8e6;
        border-left: 4px solid #f0ad4e;
    }

    .alert-danger{
        background: #fff0f0;
        border-left: 4px solid #dc3545;
    }

    /* =========================
       ACTION BUTTON AREA
    ========================== */
    .action-buttons{
        background: white;
        padding: 18px;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.05);
    }

    /* =========================
       MODAL
    ========================== */
    .modal-content{
        border: none;
        border-radius: 14px;
        overflow: hidden;
    }

    .modal-header{
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
        padding: 15px 20px;
    }

    .modal-title{
        font-size: 16px;
        font-weight: 600;
        color: #1e3c72;
    }

    .modal-body{
        padding: 22px;
        font-size: 14px;
    }

    .modal-footer{
        border-top: 1px solid #eee;
        padding: 15px 20px;
    }

    /* =========================
       PRINT MODE
    ========================== */
    @media print {
        .btn,
        .sidebar,
        .top-bar,
        .logout-btn,
        .modal,
        .alert,
        .action-buttons{
            display: none !important;
        }

        .main-content{
            margin-left: 0 !important;
            padding: 0 !important;
        }

        .card{
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }

        body{
            background: white !important;
        }
    }

    /* =========================
       MOBILE
    ========================== */
    @media(max-width:768px){

        .card-body{
            padding: 16px !important;
        }

        .btn{
            width: 100%;
            margin-bottom: 10px;
        }

        .page-title{
            font-size: 22px;
        }

        .action-buttons{
            padding: 12px;
        }
    }
</style>

<?= $this->include('layout/footer') ?>