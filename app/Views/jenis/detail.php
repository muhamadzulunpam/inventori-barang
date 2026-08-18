<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Detail Jenis Barang</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('/jenis') ?>">Jenis Barang</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="<?= base_url('/jenis') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Jenis</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="35%">ID Jenis</th>
                            <td><?= $jenis['id_jenis'] ?></td>
                        </tr>
                        <tr>
                            <th>Nama Jenis</th>
                            <td><?= $jenis['nama_jenis'] ?></td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td><?= $jenis['keterangan'] ?: '-' ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Statistik</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-6">
                        <div class="stats-box">
                            <h3><?= number_format($total_barang) ?></h3>
                            <p>Total Barang</p>
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="stats-box">
                            <h3><?= number_format($total_stok) ?></h3>
                            <p>Total Stok</p>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-box"></i> Daftar Barang</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Warna</th>
                                    <th>Stok</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($barang) > 0): ?>
                                    <?php foreach($barang as $item): ?>
                                    <tr>
                                        <td><?= $item['id_barang'] ?></td>
                                        <td><?= $item['nama_barang'] ?></td>
                                        <td><?= $item['warna'] ?: '-' ?></td>
                                        <td class="text-end"><?= number_format($item['stok']) ?></td>
                                        <td><?= $item['nama_satuan'] ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada barang untuk jenis ini</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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
        margin-bottom: 20px;
    }

    .card-header{
        background: linear-gradient(135deg, #1e3c72, #2a5298) !important;
        color: white !important;
        border: none !important;
        padding: 16px 22px;
    }

    .card-header h5{
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .card-body{
        padding: 24px !important;
        background: #ffffff;
    }

    /* =========================
       BUTTON STYLE
    ========================== */
    .btn-warning,
    .btn-secondary{
        border-radius: 8px;
        padding: 7px 18px;
        font-size: 14px;
        font-weight: 500;
    }

    /* =========================
       TABLE STYLE
    ========================== */
    .table{
        margin-bottom: 0;
    }

    .table th{
        font-weight: 600;
        color: #374151;
        background: #f8fbff;
        border-bottom: 1px solid #e5e7eb;
        padding: 12px;
    }

    .table td{
        padding: 12px;
        vertical-align: middle;
        color: #4b5563;
    }

    .table-bordered{
        border: 1px solid #e5e7eb;
    }

    .table-bordered th,
    .table-bordered td{
        border: 1px solid #e5e7eb;
    }

    /* =========================
       STATISTIK BOX
    ========================== */
    .stats-box{
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 22px;
        background: #f8fbff;
        transition: 0.25s ease;
    }

    .stats-box:hover{
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(42,82,152,0.08);
    }

    .stats-box h3{
        margin-bottom: 8px;
        font-weight: 700;
        color: #1e3c72;
    }

    .stats-box p{
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    /* =========================
       RESPONSIVE
    ========================== */
    @media(max-width:768px){

        .card-body{
            padding: 18px !important;
        }

        .btn-warning,
        .btn-secondary{
            width: 100%;
            margin-bottom: 10px;
        }

        .page-title{
            font-size: 22px;
        }
    }
</style>

<?= $this->include('layout/footer') ?>