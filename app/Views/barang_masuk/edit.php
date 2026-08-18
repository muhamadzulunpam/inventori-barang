<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Edit Barang Masuk</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('/barang_masuk') ?>">Barang Masuk</a></li>
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

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-edit"></i> Form Edit Barang Masuk</h5>
        </div>
        <div class="card-body">
            <form action="<?= base_url('/barang_masuk/update/' . $barang_masuk['id_barang_masuk']) ?>" method="post" id="formEditBarangMasuk">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="id_barang_masuk">Kode Transaksi</label>
                            <input type="text" class="form-control" id="id_barang_masuk" name="id_barang_masuk" 
                                   value="<?= $barang_masuk['id_barang_masuk'] ?>" readonly>
                            <small class="text-muted">Kode transaksi tidak dapat diubah</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="tanggal_masuk">Tanggal Masuk <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" 
                                   value="<?= $barang_masuk['tanggal_masuk'] ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="id_supplier">Supplier <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_supplier" name="id_supplier" required>
                                <option value="">-- Pilih Supplier --</option>
                                <?php foreach($supplier as $item): ?>
                                <option value="<?= $item['id_supplier'] ?>" 
                                    <?= $barang_masuk['id_supplier'] == $item['id_supplier'] ? 'selected' : '' ?>>
                                    <?= $item['nama_supplier'] ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="id_barang">Barang <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_barang" name="id_barang" required>
                                <option value="">-- Pilih Barang --</option>
                                <?php foreach($barang as $item): ?>
                                <?php 
                                    // Hitung stok yang tampil (stok + jumlah_masuk lama)
                                    $stok_tampil = $item['stok'];
                                    if($barang_masuk['id_barang'] == $item['id_barang']) {
                                        $stok_tampil = $item['stok'] + $barang_masuk['jumlah_masuk'];
                                    }
                                ?>
                                <option value="<?= $item['id_barang'] ?>" 
                                    <?= $barang_masuk['id_barang'] == $item['id_barang'] ? 'selected' : '' ?>
                                    data-stok="<?= $stok_tampil ?>"
                                    data-satuan="<?= $item['nama_satuan'] ?? '-' ?>">
                                    <?= $item['id_barang'] ?> - <?= $item['nama_barang'] ?> 
                                    (Warna: <?= $item['warna'] ?? '-' ?>)
                                    - Stok: <?= number_format($stok_tampil) ?> <?= $item['nama_satuan'] ?? '' ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tambahan field Satuan -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="satuan">Satuan</label>
                            <input type="text" class="form-control" id="satuan" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="stok_sekarang">Stok Sekarang (Sebelum Edit)</label>
                            <input type="text" class="form-control" id="stok_sekarang" readonly>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="jumlah_masuk">Jumlah Masuk <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="jumlah_masuk" name="jumlah_masuk" 
                                   value="<?= $barang_masuk['jumlah_masuk'] ?>" min="1" required>
                            <small class="text-muted">Minimal 1 barang</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="stok_setelah">Stok Setelah Edit</label>
                            <input type="text" class="form-control bg-light" id="stok_setelah" readonly>
                        </div>
                    </div>
                </div>

                <!-- Field Harga Beli -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="harga">Harga Beli <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control" id="harga" name="harga" 
                                       value="<?= number_format($barang_masuk['harga'], 0, ',', '.') ?>" required>
                            </div>
                            <small class="text-muted">Format: Tanpa titik/koma (contoh: 50000)</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" 
                                      rows="1" placeholder="Contoh: Pembelian dari supplier, barang ok"><?= $barang_masuk['keterangan'] ?? '' ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Informasi User Pencatat -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fas fa-user"></i>
                            <strong>Diinput Oleh:</strong> <?= $barang_masuk['nama_user'] ?? session()->get('nama') ?? '-' ?>
                            <br>
                            <small><i class="fas fa-info-circle"></i> Data user tidak dapat diubah</small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Perhatian:</strong> Mengubah data akan mempengaruhi stok barang. 
                    Stok akan disesuaikan secara otomatis berdasarkan perubahan jumlah.
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="<?= base_url('/barang_masuk') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const selectBarang = document.getElementById('id_barang');
    const stokSekarang = document.getElementById('stok_sekarang');
    const stokSetelah = document.getElementById('stok_setelah');
    const jumlahMasuk = document.getElementById('jumlah_masuk');
    const satuanInput = document.getElementById('satuan');
    const hargaInput = document.getElementById('harga');
    
    let currentStok = 0;

    function number_format(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function parseNumber(str) {
        return parseInt(str.replace(/\./g, '')) || 0;
    }

    function hitungStokSetelah() {
        const jumlah = parseInt(jumlahMasuk.value) || 0;
        const stokSetelahValue = currentStok + jumlah;
        
        if (jumlah > 0) {
            stokSetelah.value = number_format(stokSetelahValue);
            stokSetelah.classList.add('bg-success', 'text-white');
            stokSetelah.classList.remove('bg-light');
        } else {
            stokSetelah.value = '';
            stokSetelah.classList.remove('bg-success', 'text-white');
            stokSetelah.classList.add('bg-light');
        }
    }

    function updateStok() {
        const selectedOption = selectBarang.options[selectBarang.selectedIndex];
        const stok = selectedOption.getAttribute('data-stok');
        const satuan = selectedOption.getAttribute('data-satuan');
        
        if (stok) {
            currentStok = parseInt(stok);
            stokSekarang.value = number_format(currentStok);
            satuanInput.value = satuan || '';
            hitungStokSetelah();
        } else {
            stokSekarang.value = '';
            satuanInput.value = '';
            currentStok = 0;
            stokSetelah.value = '';
        }
    }

    // Event Listeners
    selectBarang.addEventListener('change', updateStok);
    jumlahMasuk.addEventListener('input', function() {
        let value = parseInt(this.value);
        if (value < 0) this.value = 0;
        if (this.value === '') {
            stokSetelah.value = '';
            return;
        }
        hitungStokSetelah();
    });

    // Format harga input
    hargaInput.addEventListener('input', function(e) {
        let value = this.value.replace(/\./g, '');
        if (value === '') {
            this.value = '';
            return;
        }
        value = value.replace(/[^\d]/g, '');
        if (value) {
            this.value = number_format(parseInt(value));
        } else {
            this.value = '';
        }
    });

    // Form validation before submit
    document.getElementById('formEditBarangMasuk').addEventListener('submit', function(e) {
        const hargaValue = hargaInput.value.replace(/\./g, '');
        if (!hargaValue || parseInt(hargaValue) <= 0) {
            e.preventDefault();
            alert('Harga beli wajib diisi dan harus lebih dari 0!');
            hargaInput.focus();
            return false;
        }
        
        const jumlah = parseInt(jumlahMasuk.value);
        if (!jumlahMasuk.value || jumlah <= 0) {
            e.preventDefault();
            alert('Jumlah masuk wajib diisi dan minimal 1!');
            jumlahMasuk.focus();
            return false;
        }
        
        const supplier = document.getElementById('id_supplier').value;
        if (!supplier) {
            e.preventDefault();
            alert('Supplier wajib dipilih!');
            document.getElementById('id_supplier').focus();
            return false;
        }
        
        const barang = selectBarang.value;
        if (!barang) {
            e.preventDefault();
            alert('Barang wajib dipilih!');
            selectBarang.focus();
            return false;
        }
        
        return true;
    });

    // Initial update
    updateStok();
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
        background: linear-gradient(135deg, #1e3c72, #2a5298) !important;
        color: white !important;
        border: none;
        padding: 16px 22px;
    }

    .card-header h5,
    .card-header h6{
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

    .btn-primary-custom:disabled{
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
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
    .form-select,
    .input-group-text{
        border-radius: 10px !important;
        border: 1px solid #dcdfe6 !important;
        padding: 11px 14px !important;
        transition: all 0.25s ease;
        font-size: 14px;
        box-shadow: none !important;
    }

    .input-group-text{
        background: #f8f9fc;
        font-weight: 600;
        color: #555;
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

    .bg-light{
        background: #f8f9fc !important;
    }

    /* stok setelah */
    .bg-success.text-white{
        background: #198754 !important;
        color: white !important;
        font-weight: 600;
    }

    /* =========================
       VALIDATION
    ========================== */
    .form-control.is-invalid{
        border-color: #dc3545 !important;
    }

    .invalid-feedback{
        display: block;
        font-size: 13px;
        margin-top: 5px;
    }

    small.text-muted{
        font-size: 12px;
    }

    /* =========================
       ALERT STYLE
    ========================== */
    .alert{
        border-radius: 12px;
        border: none;
        padding: 18px 20px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.04);
    }

    .alert-danger{
        background: #fff5f5;
        border-left: 4px solid #dc3545;
        color: #842029;
    }

    .alert-warning{
        background: #fffaf0;
        border-left: 4px solid #ffc107;
        color: #856404;
    }

    .alert-info{
        background: #f4f9ff;
        border-left: 4px solid #2a5298;
        color: #1e3c72;
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
    .form-group{
        margin-bottom: 18px;
    }

    hr{
        margin: 25px 0;
        opacity: 0.1;
    }

    /* =========================
       TEXTAREA
    ========================== */
    textarea.form-control{
        resize: none;
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

        .input-group{
            flex-wrap: nowrap;
        }
    }
</style>

<?= $this->include('layout/footer') ?>