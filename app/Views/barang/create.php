<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Tambah Barang</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('/barang') ?>">Data Barang</a></li>
                            <li class="breadcrumb-item active">Tambah</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Errors -->
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

    <!-- Form Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Form Tambah Barang</h5>
        </div>
        <div class="card-body">
            <form action="<?= base_url('/barang') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="id_barang">
                                <i class="fas fa-barcode"></i> ID Barang <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="id_barang" name="id_barang" value="<?= $id_barang ?>" readonly>
                            <small class="text-muted">ID barang otomatis</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nama_barang">
                                <i class="fas fa-tag"></i> Nama Barang <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Masukkan nama barang" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="warna">
                                <i class="fas fa-palette"></i> Warna
                            </label>
                            <input type="text" class="form-control" id="warna" name="warna" placeholder="Masukkan warna">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="stok">
                                <i class="fas fa-database"></i> Stok Awal <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" id="stok" name="stok" value="0" min="0" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="id_satuan">
                                <i class="fas fa-ruler"></i> Satuan <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="id_satuan" name="id_satuan" required>
                                <option value="">-- Pilih Satuan --</option>
                                <?php foreach($satuan as $item): ?>
                                <option value="<?= $item['id_satuan'] ?>"><?= $item['nama_satuan'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="id_jenis">
                                <i class="fas fa-tags"></i> Jenis Barang <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="id_jenis" name="id_jenis" required>
                                <option value="">-- Pilih Jenis --</option>
                                <?php foreach($jenis as $item): ?>
                                <option value="<?= $item['id_jenis'] ?>"><?= $item['nama_jenis'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="foto">
                                <i class="fas fa-image"></i> Foto Barang
                            </label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewImage(this)">
                            <small class="text-muted">Format: JPG, PNG, JPEG (Maks: 2MB)</small>
                            
                            <!-- Tempat preview gambar -->
                            <div class="mt-2" id="preview-container" style="display: none;">
                                <img id="image-preview" src="#" alt="Preview Foto" style="max-width: 200px; max-height: 200px; border-radius: 5px; border: 1px solid #ddd; padding: 5px;">
                                <br>
                                <button type="button" class="btn btn-sm btn-danger mt-1" onclick="removeImage()">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="<?= base_url('/barang') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Card -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                <strong>Informasi:</strong>
                <ul class="mb-0 mt-2">
                    <li>ID barang akan digenerate secara otomatis oleh sistem</li>
                    <li>Stok awal bisa diisi sesuai kondisi awal barang</li>
                    <li>Foto bersifat opsional, bisa dikosongkan</li>
                    <li>Warna bersifat opsional, bisa dikosongkan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
function previewImage(input) {
    const previewContainer = document.getElementById('preview-container');
    const imagePreview = document.getElementById('image-preview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        previewContainer.style.display = 'none';
        imagePreview.src = '#';
    }
}

function removeImage() {
    const fileInput = document.getElementById('foto');
    const previewContainer = document.getElementById('preview-container');
    const imagePreview = document.getElementById('image-preview');
    
    // Reset file input
    fileInput.value = '';
    
    // Sembunyikan preview
    previewContainer.style.display = 'none';
    imagePreview.src = '#';
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
    }

    .card-header{
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: white;
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
        BUTTON FIX (LEBIH KECIL)
        ========================== */
        .btn-primary-custom{
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            border: none;
            color: white;
            padding: 7px 18px;          /* sebelumnya 10px 24px */
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

        /* tombol batal */
        .btn-secondary{
            border-radius: 8px;
            padding: 7px 18px;          /* diperkecil */
            font-size: 14px;
        }

        /* kasih jarak antar tombol */
        .form-group .btn{
            min-width: 110px;           /* biar sama rata */
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
       FILE INPUT
    ========================== */
    #foto{
        padding: 9px !important;
    }

    small.text-muted{
        font-size: 12px;
    }

    /* =========================
       IMAGE PREVIEW
    ========================== */
    #preview-container{
        margin-top: 15px;
        padding: 12px;
        border: 1px dashed #d6dbe4;
        border-radius: 12px;
        background: #f8fbff;
        width: fit-content;
    }

    #image-preview{
        max-width: 180px;
        max-height: 180px;
        object-fit: cover;
        border-radius: 10px !important;
        border: 1px solid #e0e0e0 !important;
        padding: 4px !important;
        background: white;
    }

    #preview-container .btn-danger{
        border-radius: 8px;
        font-size: 13px;
    }

    /* =========================
       ALERT ERROR
    ========================== */
    .alert-danger{
        border: none;
        border-left: 4px solid #dc3545;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(220,53,69,0.08);
    }

    /* =========================
       INFO CARD
    ========================== */
    .alert-info{
        background: #f4f9ff;
        border: none;
        border-left: 4px solid #2a5298;
        border-radius: 14px;
        padding: 18px 22px;
        box-shadow: 0 3px 12px rgba(42,82,152,0.05);
    }

    .alert-info strong{
        color: #1e3c72;
    }

    .alert-info ul{
        margin-top: 8px;
        padding-left: 18px;
    }

    .alert-info li{
        margin-bottom: 4px;
        font-size: 14px;
        color: #4b5563;
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

        #preview-container{
            width: 100%;
            text-align: center;
        }
    }
</style>

<?= $this->include('layout/footer') ?>
