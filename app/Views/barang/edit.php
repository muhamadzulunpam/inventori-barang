<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Edit Barang</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('/barang') ?>">Data Barang</a></li>
                            <li class="breadcrumb-item active">Edit</li>
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
            <h5 class="mb-0"><i class="fas fa-edit"></i> Form Edit Barang</h5>
        </div>
        <div class="card-body">
            <form action="<?= base_url('/barang/update/' . $barang['id_barang']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="id_barang">
                                <i class="fas fa-barcode"></i> ID Barang
                            </label>
                            <input type="text" class="form-control" id="id_barang" name="id_barang" value="<?= $barang['id_barang'] ?>" readonly>
                            <small class="text-muted">ID barang tidak dapat diubah</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nama_barang">
                                <i class="fas fa-tag"></i> Nama Barang <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?= $barang['nama_barang'] ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="warna">
                                <i class="fas fa-palette"></i> Warna
                            </label>
                            <input type="text" class="form-control" id="warna" name="warna" 
                                value="<?= $barang['warna'] ?>" placeholder="Masukkan warna barang">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="stok">
                                <i class="fas fa-database"></i> Stok <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" id="stok" name="stok" value="<?= $barang['stok'] ?>" min="0" required>
                            <small class="text-muted">Stok saat ini</small>
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
                                <option value="<?= $item['id_satuan'] ?>" <?= $barang['id_satuan'] == $item['id_satuan'] ? 'selected' : '' ?>>
                                    <?= $item['nama_satuan'] ?>
                                </option>
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
                                <option value="<?= $item['id_jenis'] ?>" <?= $barang['id_jenis'] == $item['id_jenis'] ? 'selected' : '' ?>>
                                    <?= $item['nama_jenis'] ?>
                                </option>
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
                            <small class="text-muted">Format: JPG, PNG, JPEG (Maks: 2MB). Kosongkan jika tidak ingin mengubah foto.</small>
                            
                            <!-- Preview foto baru (akan muncul saat memilih file) -->
                            <div id="preview-container" style="display: none;" class="mt-2">
                                <label class="text-primary">Preview Foto Baru:</label>
                                <div>
                                    <img id="image-preview" src="#" alt="Preview" style="max-width: 150px; max-height: 150px; border-radius: 8px; border: 2px solid #ddd; padding: 5px;">
                                </div>
                                <button type="button" class="btn btn-sm btn-danger mt-1" onclick="removePreview()">
                                    <i class="fas fa-times"></i> Batalkan
                                </button>
                            </div>
                            
                            <!-- Foto saat ini -->
                            <?php if(!empty($barang['foto']) && $barang['foto'] != 'default.png'): ?>
                            <div id="current-photo-container" class="mt-2">
                                <label class="text-info">Foto Saat Ini:</label>
                                <div>
                                    <img src="<?= base_url('uploads/barang/' . $barang['foto']) ?>" width="100" height="100" class="rounded" style="object-fit: cover; border: 2px solid #ddd; padding: 3px;">
                                </div>
                                <button type="button" class="btn btn-sm btn-warning mt-1" onclick="removeCurrentPhoto()">
                                    <i class="fas fa-sync"></i> Ganti Foto
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Perhatian:</strong> Mengubah stok barang akan mempengaruhi data inventaris.
                    Pastikan perubahan stok sesuai dengan kondisi sebenarnya.
                </div>

                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fas fa-save"></i> Update
                    </button>

                    <a href="<?= base_url('/barang') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Detail -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6><i class="fas fa-info-circle"></i> Informasi Barang</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td width="200">Dibuat Pada</td>
                            <td>: <?= date('d/m/Y H:i:s', strtotime($barang['created_at'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
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

<script>
function previewImage(input) {
    const previewContainer = document.getElementById('preview-container');
    const imagePreview = document.getElementById('image-preview');
    const currentPhotoContainer = document.getElementById('current-photo-container');
    
    if (input.files && input.files[0]) {
        // Validasi ukuran file (2MB)
        if (input.files[0].size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB.');
            input.value = '';
            previewContainer.style.display = 'none';
            return;
        }
        
        // Validasi tipe file
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!allowedTypes.includes(input.files[0].type)) {
            alert('Format file tidak didukung! Gunakan JPG, PNG, atau JPEG.');
            input.value = '';
            previewContainer.style.display = 'none';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            previewContainer.style.display = 'block';
            
            // Sembunyikan foto saat ini jika ada
            if (currentPhotoContainer) {
                currentPhotoContainer.style.display = 'none';
            }
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        previewContainer.style.display = 'none';
        imagePreview.src = '#';
        
        // Tampilkan kembali foto saat ini jika ada
        if (currentPhotoContainer) {
            currentPhotoContainer.style.display = 'block';
        }
    }
}

function removePreview() {
    const fileInput = document.getElementById('foto');
    const previewContainer = document.getElementById('preview-container');
    const imagePreview = document.getElementById('image-preview');
    const currentPhotoContainer = document.getElementById('current-photo-container');
    
    // Reset file input
    fileInput.value = '';
    
    // Sembunyikan preview
    previewContainer.style.display = 'none';
    imagePreview.src = '#';
    
    // Tampilkan kembali foto saat ini
    if (currentPhotoContainer) {
        currentPhotoContainer.style.display = 'block';
    }
}

function removeCurrentPhoto() {
    const currentPhotoContainer = document.getElementById('current-photo-container');
    const fileInput = document.getElementById('foto');
    
    // Sembunyikan foto saat ini
    if (currentPhotoContainer) {
        currentPhotoContainer.style.display = 'none';
    }
    
    // Trigger file input untuk memilih foto baru
    fileInput.click();
    
    // Tambahkan hidden input untuk menandai bahwa foto lama akan dihapus
    let hiddenInput = document.querySelector('input[name="hapus_foto"]');
    if (!hiddenInput) {
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'hapus_foto';
        hiddenInput.value = '1';
        document.querySelector('form').appendChild(hiddenInput);
    }
}
</script>


<?= $this->include('layout/footer') ?>