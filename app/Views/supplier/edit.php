<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Edit Supplier</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('/supplier') ?>">Supplier</a></li>
                            <li class="breadcrumb-item active">Edit</li>
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
            <h5 class="mb-0"><i class="fas fa-edit"></i> Form Edit Supplier</h5>
        </div>
        <div class="card-body">
            <form action="<?= base_url('/supplier/update/' . $supplier['id_supplier']) ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="id_supplier">ID Supplier</label>
                            <input type="text" class="form-control" id="id_supplier" name="id_supplier" 
                                   value="<?= $supplier['id_supplier'] ?>" readonly>
                            <small class="text-muted">ID Supplier tidak dapat diubah</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nama_supplier">Nama Supplier <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" 
                                   value="<?= old('nama_supplier', $supplier['nama_supplier']) ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="no_hp">No. HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" 
                                   value="<?= old('no_hp', $supplier['no_hp']) ?>" placeholder="Masukkan nomor HP">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" 
                                      rows="3" placeholder="Masukkan alamat supplier"><?= old('alamat', $supplier['alamat']) ?></textarea>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="<?= base_url('/supplier') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* =========================
       PAGE TITLE + BREADCRUMB
    ========================== */
    .page-title{
        font-weight: 700;
        color: #1e3c72;
        margin-bottom: 4px;
    }

    .breadcrumb{
        background: transparent;
        padding: 0;
        font-size: 13px;
        margin-bottom: 0;
    }

    /* =========================
       CARD STYLE
    ========================== */
    .card{
        border: none !important;
        border-radius: 14px !important;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .card-header{
        background: #f8f9fc !important;
        border-bottom: 1px solid #eef1f5;
        padding: 14px 18px;
    }

    .card-header h5,
    .card-header h6{
        margin: 0;
        font-size: 15px;
        font-weight: 600;
        color: #2d3748;
    }

    .card-body{
        padding: 22px !important;
    }

    /* =========================
       FORM STYLE
    ========================== */
    .form-group label{
        font-weight: 600;
        margin-bottom: 7px;
        color: #444;
        font-size: 14px;
    }

    .form-control,
    .form-select{
        border-radius: 10px !important;
        border: 1px solid #dcdfe6 !important;
        padding: 10px 14px !important;
        font-size: 14px;
        min-height: 44px;
        transition: all 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus{
        border-color: #2a5298 !important;
        box-shadow: 0 0 0 0.15rem rgba(42,82,152,0.15) !important;
    }

    small.text-muted{
        font-size: 12px;
    }

    /* =========================
       BUTTON STYLE
    ========================== */
    .btn-primary-custom{
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        border: none;
        padding: 7px 18px;       /* diperkecil */
        border-radius: 8px;
        transition: 0.25s;
        color: white;
        font-size: 14px;
        font-weight: 500;
    }

    .btn-primary-custom:hover{
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(30,60,114,0.25);
        color: white;
    }

    .btn-secondary{
        padding: 7px 18px !important;   /* diperkecil */
        border-radius: 8px !important;
        font-size: 14px;
    }

    .btn-warning{
        border-radius: 8px !important;
        font-size: 14px;
    }

    /* =========================
       PHOTO PREVIEW
    ========================== */
    #preview-container,
    #current-photo-container{
        background: #fafbfd;
        border: 1px solid #e8edf5;
        padding: 12px;
        border-radius: 10px;
        margin-top: 10px;
    }

    #preview-container img,
    #current-photo-container img{
        border-radius: 10px !important;
        border: 1px solid #d9dee8 !important;
        padding: 4px;
    }

    #preview-container label,
    #current-photo-container label{
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    /* =========================
       ALERT STYLE
    ========================== */
    .alert{
        border: none;
        border-radius: 12px;
        font-size: 14px;
        padding: 14px 18px;
    }

    .alert-warning{
        background: #fff8e8;
        color: #996c00;
    }

    .alert-danger{
        background: #fff1f1;
        color: #b02a37;
    }

    /* =========================
       INFO TABLE
    ========================== */
    .table{
        margin-bottom: 0;
        font-size: 14px;
    }

    .table td{
        padding: 10px !important;
        border-color: #f0f0f0;
        vertical-align: middle;
    }

    /* =========================
       BUTTON SMALL PREVIEW
    ========================== */
    .btn-sm{
        font-size: 12px !important;
        padding: 5px 10px !important;
        border-radius: 7px !important;
    }

    /* =========================
       HORIZONTAL LINE
    ========================== */
    hr{
        margin: 25px 0;
        opacity: .08;
    }
</style>

<?= $this->include('layout/footer') ?>