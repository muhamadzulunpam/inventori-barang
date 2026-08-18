<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Tambah Jenis Barang</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('/jenis') ?>">Jenis Barang</a></li>
                            <li class="breadcrumb-item active">Tambah</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> Terdapat kesalahan:
            <ul class="mb-0 mt-2">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Form Tambah Jenis Barang</h5>
        </div>
        <div class="card-body">
            <form action="<?= base_url('/jenis') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="id_jenis">
                                <i class="fas fa-barcode"></i> ID Jenis <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="id_jenis" 
                                name="id_jenis" 
                                value="<?= $id_jenis ?>" 
                                readonly
                            >
                            <small class="text-muted">ID jenis otomatis</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nama_jenis">Nama Jenis <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_jenis" name="nama_jenis" 
                                   placeholder="Masukkan nama jenis barang" value="<?= old('nama_jenis') ?>" required>
                            <small class="text-muted">Contoh: Cotton Combed, Polyester, Kancing, Benang, dll.</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" 
                                      rows="3" placeholder="Masukkan keterangan (opsional)"><?= old('keterangan') ?></textarea>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="<?= base_url('/jenis') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                </div>
            </form>
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
    .btn-primary-custom{
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        border: none;
        color: white;
        padding: 7px 18px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 14px;
        transition: 0.25s ease;
    }

    .btn-primary-custom:hover{
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(42,82,152,0.22);
        color: white;
    }

    .btn-secondary{
        border-radius: 8px;
        padding: 7px 18px;
        font-size: 14px;
    }

    .form-group .btn{
        min-width: 110px;
    }

    /* =========================
       FORM STYLE
    ========================== */
    .form-group label{
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control,
    .form-select{
        border-radius: 10px !important;
        border: 1px solid #dcdfe6 !important;
        padding: 11px 14px !important;
        transition: all 0.25s ease;
        font-size: 14px;
        box-shadow: none !important;
    }

    .form-control:focus,
    .form-select:focus{
        border-color: #2a5298 !important;
        box-shadow: 0 0 0 0.18rem rgba(42,82,152,0.18) !important;
    }

    /* readonly input */
    input[readonly]{
        background: #f8f9fc !important;
        cursor: not-allowed;
        font-weight: 600;
        color: #666;
    }

    /* =========================
       ALERT ERROR
    ========================== */
    .alert{
        border-radius: 12px;
        border: none;
        padding: 18px 20px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }

    .alert-danger{
        background: #fff5f5;
        border-left: 4px solid #dc3545;
        color: #842029;
    }

    .alert ul{
        padding-left: 18px;
        margin-top: 8px;
        margin-bottom: 0;
    }

    .alert li{
        font-size: 14px;
        margin-bottom: 4px;
    }

    /* =========================
       FORM SPACING
    ========================== */
    hr{
        margin: 25px 0;
        opacity: 0.1;
    }

    .form-group{
        margin-bottom: 18px;
    }

    small.text-muted{
        font-size: 12px;
    }

    /* =========================
       RESPONSIVE
    ========================== */
    @media(max-width:768px){

        .card-body{
            padding: 18px !important;
        }

        .btn-primary-custom,
        .btn-secondary{
            width: 100%;
            margin-bottom: 10px;
        }

        .page-title{
            font-size: 22px;
        }

        .form-group .btn{
            min-width: 100%;
        }
    }
</style>

<?= $this->include('layout/footer') ?>