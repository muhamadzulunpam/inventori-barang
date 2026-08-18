<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Detail Barang Masuk</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('/barang_masuk') ?>">Barang Masuk</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="<?= base_url('/barang_masuk') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt"></i> Informasi Transaksi</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>ID Transaksi</strong></td>
                            <td>: <span class="badge bg-success"><?= $barang_masuk['id_barang_masuk'] ?></span></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Masuk</strong></td>
                            <td>: <?= date('d/m/Y', strtotime($barang_masuk['tanggal_masuk'])) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Supplier</strong></td>
                            <td>: <?= $barang_masuk['nama_supplier'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Keterangan</strong></td>
                            <td>: <?= $barang_masuk['keterangan'] ?: '-' ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-box"></i> Informasi Barang</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>ID Barang</strong></td>
                            <td>: <?= $barang_masuk['id_barang'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Nama Barang</strong></td>
                            <td>: <?= $barang_masuk['nama_barang'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah Masuk</strong></td>
                            <td>: <?= number_format($barang_masuk['jumlah_masuk']) ?> <?= $barang_masuk['nama_satuan'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Harga</strong></td>
                            <td>: Rp <?= number_format($barang_masuk['harga'] ?? 0, 0, ',', '.') ?> / <?= $barang_masuk['nama_satuan'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Total Harga</strong></td>
                            <td class="fw-bold">: Rp <?= number_format(($barang_masuk['jumlah_masuk'] * ($barang_masuk['harga'] ?? 0)), 0, ',', '.') ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Informasi User</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Diinput Oleh</strong></td>
                            <td>: <?= $barang_masuk['nama_user'] ?? '-' ?> (@<?= session()->get('username') ?>)</td>
                        </tr>
                        <tr>
                            <td><strong>Waktu Input</strong></td>
                            <td>: <?= date('d/m/Y H:i:s', strtotime($barang_masuk['created_at'] ?? date('Y-m-d H:i:s'))) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

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
        transition: 0.25s ease;
    }

    .card:hover{
        transform: translateY(-2px);
    }

    .card-header{
        background: linear-gradient(135deg, #1e3c72, #2a5298) !important;
        color: white !important;
        border: none;
        padding: 16px 22px;
    }

    .card-header h5{
        margin: 0;
        font-size: 17px;
        font-weight: 600;
    }

    .card-body{
        padding: 25px !important;
        background: #ffffff;
    }

    /* =========================
       BUTTON STYLE
    ========================== */
    .btn-secondary{
        border-radius: 8px;
        padding: 7px 18px;
        font-size: 14px;
        transition: 0.25s ease;
    }

    .btn-secondary:hover{
        transform: translateY(-2px);
    }

    /* =========================
       TABLE STYLE
    ========================== */
    .table{
        margin-bottom: 0;
    }

    .table-borderless td{
        padding: 10px 6px;
        vertical-align: middle;
        font-size: 14px;
        border: none !important;
    }

    .table-borderless td strong{
        color: #374151;
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

    /* =========================
       HIGHLIGHT VALUE
    ========================== */
    .fw-bold{
        color: #1e3c72;
    }

    /* =========================
       RESPONSIVE
    ========================== */
    @media(max-width:768px){

        .card-body{
            padding: 18px !important;
        }

        .page-title{
            font-size: 22px;
        }

        .btn-secondary{
            width: 100%;
            margin-top: 10px;
        }
    }
</style>

<?= $this->include('layout/footer') ?>