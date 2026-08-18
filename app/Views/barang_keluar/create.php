<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Tambah Barang Keluar</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('/barang_keluar') ?>">Barang Keluar</a></li>
                            <li class="breadcrumb-item active">Tambah</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- ALERT UNTUK ERROR VALIDASI -->
    <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                <div>
                    <strong class="d-block mb-1">Gagal Menyimpan Data!</strong>
                    <span>Terjadi kesalahan pada input berikut:</span>
                    <ul class="mb-0 mt-2">
                        <?php foreach(session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- ALERT UNTUK ERROR UMUM -->
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-times-circle fa-2x me-3"></i>
                <div>
                    <strong class="d-block mb-1">Gagal Menyimpan Data!</strong>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- ALERT UNTUK SUKSES -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-2x me-3"></i>
                <div>
                    <strong class="d-block mb-1">Berhasil!</strong>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-minus-circle"></i> Form Tambah Barang Keluar</h5>
        </div>
        <div class="card-body">
            <form action="<?= base_url('/barang_keluar/store') ?>" method="post" id="formBarangKeluar">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="id_barang_keluar">
                                <i class="fas fa-barcode"></i> ID Barang Keluar
                            </label>
                            <input 
                                type="text" 
                                class="form-control bg-light" 
                                id="id_barang_keluar" 
                                name="id_barang_keluar"
                                value="<?= $id_barang_keluar ?? '' ?>"
                                readonly
                            >
                            <small class="text-muted">ID barang keluar otomatis oleh sistem</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label><i class="fas fa-calendar"></i> Tanggal Keluar <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_keluar" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label><i class="fas fa-box"></i> Pilih Barang <span class="text-danger">*</span></label>
                            <select name="id_barang" id="id_barang" class="form-select" required>
                                <option value="">-- Pilih Barang --</option>
                                <?php foreach($barang as $item): ?>
                                <option value="<?= $item['id_barang'] ?>" 
                                        data-stok="<?= $item['stok'] ?>"
                                        data-satuan="<?= $item['nama_satuan'] ?? '' ?>">
                                    <?= $item['nama_barang'] ?> (Stok: <?= $item['stok'] ?> <?= $item['nama_satuan'] ?? '' ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label><i class="fas fa-database"></i> Stok Gudang</label>
                            <input type="text" id="stok_saat_ini" class="form-control bg-light" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label><i class="fas fa-sort-amount-down"></i> Jumlah Keluar <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_keluar" id="jumlah_keluar" class="form-control" min="1" required>
                            <small class="text-muted" id="jumlah_error" style="display: none; color: #dc3545;">Jumlah melebihi stok!</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label><i class="fas fa-chart-line"></i> Stok Total</label>
                            <input type="text" id="stok_setelah" class="form-control bg-light" readonly>
                            
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label><i class="fas fa-info-circle"></i> Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Untuk keperluan produksi, Barang rusak, dll"></textarea>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Perhatian:</strong> Setelah menyimpan, stok barang akan berkurang secara otomatis.
                </div>

                <hr>
                <button type="submit" class="btn btn-primary-custom" id="btnSimpan">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="<?= base_url('/barang_keluar') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </form>
        </div>
    </div>
</div>

<script>
    const selectBarang = document.getElementById('id_barang');
    const jumlahInput = document.getElementById('jumlah_keluar');
    const stokSaatIni = document.getElementById('stok_saat_ini');
    const stokSetelah = document.getElementById('stok_setelah');
    const btnSimpan = document.getElementById('btnSimpan');
    const jumlahError = document.getElementById('jumlah_error');
    
    let currentStok = 0;
    let currentSatuan = '';

    selectBarang.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const stok = option.getAttribute('data-stok') || 0;
        const satuan = option.getAttribute('data-satuan') || '';
        
        currentStok = parseInt(stok);
        currentSatuan = satuan;
        
        stokSaatIni.value = currentStok + ' ' + currentSatuan;
        hitungStokSetelah();
    });

    jumlahInput.addEventListener('input', hitungStokSetelah);

    function hitungStokSetelah() {
        const jumlah = parseInt(jumlahInput.value) || 0;
        const stokBaru = currentStok - jumlah;
        
        if (stokBaru < 0) {
            stokSetelah.value = '⚠️ Stok Tidak Cukup!';
            stokSetelah.classList.add('text-danger');
            jumlahError.style.display = 'block';
            btnSimpan.disabled = true;
        } else {
            stokSetelah.value = stokBaru + ' ' + currentSatuan;
            stokSetelah.classList.remove('text-danger');
            jumlahError.style.display = 'none';
            btnSimpan.disabled = false;
        }
    }

    // Validasi sebelum submit
    document.getElementById('formBarangKeluar').addEventListener('submit', function(e) {
        const barang = selectBarang.value;
        const jumlah = jumlahInput.value;
        
        if (!barang) {
            e.preventDefault();
            alert('Barang wajib dipilih!');
            selectBarang.focus();
            return false;
        }
        
        if (!jumlah || parseInt(jumlah) <= 0) {
            e.preventDefault();
            alert('Jumlah keluar wajib diisi minimal 1!');
            jumlahInput.focus();
            return false;
        }
        
        if (parseInt(jumlah) > currentStok) {
            e.preventDefault();
            alert('Jumlah keluar melebihi stok yang tersedia! Stok tersedia: ' + currentStok);
            jumlahInput.focus();
            return false;
        }
        
        return true;
    });
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
       BUTTON FIX
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

    .btn-primary-custom:hover:not(:disabled){
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(42,82,152,0.22);
        color: white;
    }

    .btn-primary-custom:disabled{
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-secondary{
        border-radius: 8px;
        padding: 7px 18px;
        font-size: 14px;
    }

    .btn{
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

    small.text-muted{
        font-size: 12px;
    }

    /* =========================
       ALERT STYLE
    ========================== */
    .alert{
        border: none;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }

    .alert-danger{
        border-left: 4px solid #dc3545;
        background: #fff5f5;
    }

    .alert-success{
        border-left: 4px solid #198754;
        background: #f0fff4;
    }

    .alert-warning{
        background: #fff9eb;
        border-left: 4px solid #ffc107;
        border-radius: 14px;
        padding: 18px 20px;
        box-shadow: 0 3px 12px rgba(255,193,7,0.08);
    }

    .alert-warning strong{
        color: #8a6d3b;
    }

    /* =========================
       STOCK VALIDATION
    ========================== */
    #stok_saat_ini,
    #stok_setelah{
        background: #f8f9fc !important;
        font-weight: 600;
    }

    #stok_setelah.text-danger{
        color: #dc3545 !important;
        font-weight: 700;
    }

    #jumlah_error{
        font-size: 12px;
        font-weight: 500;
        margin-top: 4px;
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
    }
</style>
<?= $this->include('layout/footer') ?>