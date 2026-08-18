<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Tambah Barang Masuk</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('/barang_masuk') ?>">Barang Masuk</a></li>
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

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Form Tambah Barang Masuk</h5>
        </div>
        <div class="card-body">
            <form action="<?= base_url('/barang_masuk/store') ?>" method="post" id="formBarangMasuk">
                <?= csrf_field() ?>
                
                <!-- Row 1: ID Transaksi & Tanggal Masuk -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="id_barang_masuk">ID Transaksi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light" id="id_barang_masuk" 
                                   name="id_barang_masuk" value="<?= $id_transaksi ?? 'BM' . date('Ymd') . '0001' ?>" readonly>
                            <small class="text-muted">ID transaksi otomatis oleh sistem</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="tanggal_masuk">Tanggal Masuk <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" 
                                   value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Supplier & Barang -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="id_supplier">Supplier <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_supplier" name="id_supplier" required>
                                <option value="">-- Pilih Supplier --</option>
                                <?php foreach($supplier as $item): ?>
                                <option value="<?= $item['id_supplier'] ?>"><?= $item['nama_supplier'] ?></option>
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
                                <option value="<?= $item['id_barang'] ?>" 
                                        data-stok="<?= $item['stok'] ?>"
                                        data-satuan="<?= $item['nama_satuan'] ?? '' ?>"
                                        data-harga="<?= $item['harga_terakhir'] ?? $item['harga'] ?? 0 ?>"
                                        data-nama="<?= $item['id_barang'] ?> - <?= $item['nama_barang'] ?>">
                                    <?= $item['id_barang'] ?> - <?= $item['nama_barang'] ?> 
                                    (Stok: <?= number_format($item['stok']) ?> <?= $item['nama_satuan'] ?? '' ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Row 3: Stok Saat Ini & Jumlah Masuk & Stok Setelah -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="stok_saat_ini">Stok Gudang</label>
                            <input type="text" class="form-control bg-light" id="stok_saat_ini" readonly>
                            <small class="text-muted">Stok sebelum penambahan</small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="jumlah_masuk">Jumlah Masuk <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="jumlah_masuk" name="jumlah_masuk" 
                                   placeholder="0" min="1" value="1" required>
                            <small class="text-muted">Minimal 1</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="stok_setelah">Stok Total</label>
                            <input type="text" class="form-control bg-light text-white fw-bold" id="stok_setelah" readonly>
                        </div>
                    </div>
                </div>

                <!-- Row 4: Harga Beli & Satuan -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="harga">Harga Beli <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control" id="harga" name="harga" 
                                       placeholder="0" required>
                            </div>
                            <small class="text-muted">Masukkan angka tanpa titik atau koma</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="satuan_tampilan">Satuan</label>
                            <input type="text" class="form-control bg-light" id="satuan_tampilan" readonly>
                            <small class="text-muted">Satuan barang akan otomatis terisi</small>
                        </div>
                    </div>
                </div>

                <!-- Row 5: Keterangan -->
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" 
                                      rows="2" placeholder="Contoh: Pembelian dari supplier, barang dalam kondisi baik"></textarea>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Informasi Penting -->
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Informasi:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Setelah menyimpan data barang masuk, stok barang akan bertambah secara otomatis</li>
                        <li>ID Transaksi akan digenerate secara otomatis oleh sistem</li>
                        <li>Data user (siapa yang input) akan dicatat otomatis dari session login</li>
                        <li>Harga beli digunakan untuk laporan keuangan dan perhitungan nilai stok</li>
                    </ul>
                </div>

                <!-- Tombol Aksi -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary-custom" id="btnSimpan">
                        <i class="fas fa-save"></i> Simpan
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
    // DOM Elements
    const selectBarang = document.getElementById('id_barang');
    const stokSaatIni = document.getElementById('stok_saat_ini');
    const jumlahMasuk = document.getElementById('jumlah_masuk');
    const stokSetelah = document.getElementById('stok_setelah');
    const satuanTampilan = document.getElementById('satuan_tampilan');
    const hargaInput = document.getElementById('harga');
    const btnSimpan = document.getElementById('btnSimpan');
    
    let currentStok = 0;
    let currentSatuan = '';

    // Format number ke format Indonesia (ribuan pakai titik)
    function formatRibuan(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Parse number dari format ribuan
    function parseRibuan(str) {
        return parseInt(str.replace(/\./g, '')) || 0;
    }

    // Update stok setelah masuk
    function updateStokSetelah() {
        const jumlah = parseInt(jumlahMasuk.value) || 0;
        const stokBaru = currentStok + jumlah;
        
        if (jumlah > 0) {
            stokSetelah.value = formatRibuan(stokBaru);
            stokSetelah.classList.add('bg-success', 'text-white');
            stokSetelah.classList.remove('bg-light');
            
            // Update keterangan jumlah
            if (stokSetelah._tooltip) {
                stokSetelah.title = `Stok saat ini ${formatRibuan(currentStok)} + ${formatRibuan(jumlah)} = ${formatRibuan(stokBaru)}`;
            }
        } else {
            stokSetelah.value = currentStok > 0 ? formatRibuan(currentStok) : '';
            stokSetelah.classList.remove('bg-success', 'text-white');
            stokSetelah.classList.add('bg-light');
        }
    }

    // Event ketika barang dipilih
    selectBarang.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const stok = selectedOption.getAttribute('data-stok');
        const satuan = selectedOption.getAttribute('data-satuan');
        const hargaTerakhir = selectedOption.getAttribute('data-harga');
        const namaBarang = selectedOption.getAttribute('data-nama');
        
        if (stok !== null && stok !== undefined) {
            currentStok = parseInt(stok);
            currentSatuan = satuan || '';
            
            // Set stok saat ini
            stokSaatIni.value = formatRibuan(currentStok);
            
            // Set satuan
            satuanTampilan.value = currentSatuan;
            
            // Set harga terakhir jika ada
            if (hargaTerakhir && parseInt(hargaTerakhir) > 0) {
                hargaInput.value = formatRibuan(parseInt(hargaTerakhir));
            } else {
                hargaInput.value = '';
            }
            
            // Update stok setelah
            updateStokSetelah();
        } else {
            resetForm();
        }
    });

    // Reset form ketika tidak ada barang dipilih
    function resetForm() {
        stokSaatIni.value = '';
        satuanTampilan.value = '';
        currentStok = 0;
        currentSatuan = '';
        stokSetelah.value = '';
        stokSetelah.classList.remove('bg-success', 'text-white');
        stokSetelah.classList.add('bg-light');
    }

    // Event ketika jumlah masuk berubah
    jumlahMasuk.addEventListener('input', function() {
        let value = parseInt(this.value);
        
        // Validasi tidak boleh negatif
        if (value < 0) {
            this.value = 0;
        }
        
        // Validasi tidak boleh kosong
        if (this.value === '' || isNaN(value)) {
            stokSetelah.value = currentStok > 0 ? formatRibuan(currentStok) : '';
            return;
        }
        
        updateStokSetelah();
    });

    // Format harga input (otomatis pakai titik)
    hargaInput.addEventListener('input', function(e) {
        let value = this.value.replace(/\./g, '');
        
        // Hanya angka
        value = value.replace(/[^\d]/g, '');
        
        if (value === '' || value === '0') {
            this.value = '';
            return;
        }
        
        // Format ribuan
        this.value = formatRibuan(parseInt(value));
    });

    // Validasi sebelum submit
    document.getElementById('formBarangMasuk').addEventListener('submit', function(e) {
        // Validasi supplier
        const supplier = document.getElementById('id_supplier').value;
        if (!supplier) {
            e.preventDefault();
            alert('Supplier wajib dipilih!');
            document.getElementById('id_supplier').focus();
            return false;
        }
        
        // Validasi barang
        const barang = selectBarang.value;
        if (!barang) {
            e.preventDefault();
            alert('Barang wajib dipilih!');
            selectBarang.focus();
            return false;
        }
        
        // Validasi jumlah masuk
        const jumlah = parseInt(jumlahMasuk.value);
        if (!jumlahMasuk.value || jumlah <= 0) {
            e.preventDefault();
            alert('Jumlah masuk wajib diisi dan minimal 1!');
            jumlahMasuk.focus();
            return false;
        }
        
        // Validasi harga
        const hargaBersih = hargaInput.value.replace(/\./g, '');
        if (!hargaBersih || parseInt(hargaBersih) <= 0) {
            e.preventDefault();
            alert('Harga beli wajib diisi dan harus lebih dari 0!');
            hargaInput.focus();
            return false;
        }
        
        // Siapkan hidden input untuk harga bersih
        const hiddenHarga = document.createElement('input');
        hiddenHarga.type = 'hidden';
        hiddenHarga.name = 'harga';
        hiddenHarga.value = hargaBersih;
        this.appendChild(hiddenHarga);
        
        return true;
    });
    
    // Trigger initial state
    if (selectBarang.value) {
        selectBarang.dispatchEvent(new Event('change'));
    }
    
    // Tooltip untuk stok setelah
    stokSetelah.addEventListener('mouseenter', function() {
        if (currentStok > 0 && jumlahMasuk.value > 0) {
            this.title = `Stok saat ini ${formatRibuan(currentStok)} + ${formatRibuan(parseInt(jumlahMasuk.value) || 0)} = ${this.value}`;
        }
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

    .btn-primary-custom:active{
        transform: translateY(0);
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

    /* readonly */
    input[readonly]{
        background: #f8f9fc !important;
        cursor: not-allowed;
        font-weight: 600;
        color: #666;
    }

    .bg-light{
        background: #f8f9fc !important;
    }

    .bg-success.text-white{
        background: linear-gradient(135deg, #198754, #20c997) !important;
        color: white !important;
        font-weight: 600;
    }

    /* =========================
       INPUT GROUP
    ========================== */
    .input-group-text{
        border: 1px solid #dcdfe6 !important;
        background: #f8f9fc;
        font-size: 14px;
        font-weight: 500;
        color: #374151;
    }

    /* =========================
       ALERT STYLE
    ========================== */
    .alert{
        border-radius: 14px;
        border: none;
        padding: 18px 22px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.05);
    }

    .alert-danger{
        background: #fff5f5;
        border-left: 4px solid #dc3545;
        color: #842029;
    }

    .alert-success{
        background: #f0fff4;
        border-left: 4px solid #198754;
        color: #146c43;
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
       SMALL TEXT
    ========================== */
    small.text-muted{
        font-size: 12px;
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

        .form-group .btn{
            min-width: 100%;
        }
    }
</style>

<?= $this->include('layout/footer') ?>