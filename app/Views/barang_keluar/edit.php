<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Edit Barang Keluar</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('/barang_keluar') ?>">Barang Keluar</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Errors -->
    <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> Terdapat kesalahan:
            <ul class="mb-0 mt-2">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Alert Error Umum -->
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-edit"></i> Form Edit Barang Keluar</h5>
        </div>
        <div class="card-body">
            <form action="<?= base_url('/barang_keluar/update/' . $barang_keluar['id_barang_keluar']) ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label><i class="fas fa-barcode"></i> ID Transaksi</label>
                            <input type="text" class="form-control bg-light" 
                                   value="<?= $barang_keluar['id_barang_keluar'] ?>" readonly>
                            <small class="text-muted">ID transaksi tidak dapat diubah</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label><i class="fas fa-calendar"></i> Tanggal Keluar <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_keluar" class="form-control" 
                                   value="<?= $barang_keluar['tanggal_keluar'] ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label><i class="fas fa-box"></i> Pilih Barang <span class="text-danger">*</span></label>
                            <select name="id_barang" id="id_barang" class="form-select" required>
                                <option value="">-- Pilih Barang --</option>
                                <?php foreach($barang as $item): ?>
                                <?php 
                                    // Hitung stok tersedia untuk barang ini
                                    $stok_tersedia = $item['stok'];
                                    $is_selected = ($barang_keluar['id_barang'] == $item['id_barang']);
                                    
                                    // Jika barang yang sedang diedit, tambahkan kembali jumlah keluar yang lama
                                    if ($is_selected) {
                                        $stok_tersedia = $item['stok'] + $barang_keluar['jumlah_keluar'];
                                    }
                                ?>
                                <option value="<?= $item['id_barang'] ?>" 
                                        data-stok="<?= $stok_tersedia ?>"
                                        data-satuan="<?= $item['nama_satuan'] ?? '' ?>"
                                        <?= $is_selected ? 'selected' : '' ?>>
                                    <?= $item['nama_barang'] ?> 
                                    (Stok: <?= number_format($stok_tersedia) ?> <?= $item['nama_satuan'] ?? '' ?>)
                                    <?php if($item['warna']): ?> - <?= $item['warna'] ?><?php endif; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label><i class="fas fa-database"></i> Stok Tersedia</label>
                            <input type="text" id="stok_tersedia" class="form-control bg-light" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label><i class="fas fa-sort-amount-down"></i> Jumlah Keluar <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_keluar" id="jumlah_keluar" 
                                   class="form-control" value="<?= $barang_keluar['jumlah_keluar'] ?>" 
                                   min="1" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label><i class="fas fa-info-circle"></i> Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3" 
                                      placeholder="Masukkan keterangan (opsional)"><?= $barang_keluar['keterangan'] ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Perhatian:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Mengubah data akan mempengaruhi stok barang secara otomatis</li>
                        <li>Jika mengganti barang, stok barang lama akan dikembalikan</li>
                        <li>Stok barang baru akan berkurang sesuai jumlah yang dimasukkan</li>
                        <li>Pastikan stok mencukupi sebelum menyimpan perubahan</li>
                    </ul>
                </div>

                <hr>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary-custom" id="btnSubmit">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="<?= base_url('/barang_keluar') ?>" class="btn btn-secondary">
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
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Transaksi</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td width="200"><strong>Dibuat Pada</strong></td>
                            <td>: <?= date('d/m/Y H:i:s', strtotime($barang_keluar['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Terakhir Diupdate</strong></td>
                            <td>: <?= date('d/m/Y H:i:s', strtotime($barang_keluar['updated_at'] ?? $barang_keluar['created_at'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Ambil elemen
    const selectBarang = document.getElementById('id_barang');
    const jumlahInput = document.getElementById('jumlah_keluar');
    const stokTersediaInput = document.getElementById('stok_tersedia');
    const btnSubmit = document.getElementById('btnSubmit');
    
    let stokAwal = 0;

    // Event ketika barang dipilih
    selectBarang.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const stok = parseInt(selectedOption.getAttribute('data-stok')) || 0;
        const satuan = selectedOption.getAttribute('data-satuan') || '';
        
        stokAwal = stok;
        stokTersediaInput.value = stok + ' ' + satuan;
        
        validateStok();
    });

    // Event ketika jumlah diubah
    jumlahInput.addEventListener('keyup', validateStok);
    jumlahInput.addEventListener('change', validateStok);

    // Fungsi validasi stok
    function validateStok() {
        const jumlah = parseInt(jumlahInput.value) || 0;
        
        if (jumlah > stokAwal && stokAwal > 0) {
            jumlahInput.classList.add('is-invalid');
            btnSubmit.disabled = true;
            
            let errorDiv = document.getElementById('stokError');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.id = 'stokError';
                errorDiv.className = 'invalid-feedback';
                jumlahInput.parentNode.appendChild(errorDiv);
            }
            errorDiv.innerHTML = 'Jumlah melebihi stok yang tersedia! Maksimal ' + stokAwal;
        } else {
            jumlahInput.classList.remove('is-invalid');
            btnSubmit.disabled = false;
            
            let errorDiv = document.getElementById('stokError');
            if (errorDiv) {
                errorDiv.remove();
            }
        }
    }

    // Trigger validasi awal
    if (selectBarang.value) {
        const event = new Event('change');
        selectBarang.dispatchEvent(event);
        jumlahInput.dispatchEvent(event);
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

    .bg-light{
        background: #f8f9fc !important;
    }

    /* =========================
       VALIDATION STYLE
    ========================== */
    .form-control.is-invalid{
        border-color: #dc3545 !important;
    }

    .invalid-feedback{
        display: block;
        font-size: 13px;
        margin-top: 5px;
    }

    /* =========================
       ALERT STYLE
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

    .alert-warning{
        background: #fffaf0;
        border-left: 4px solid #ffc107;
        color: #856404;
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
       TABLE INFO STYLE
    ========================== */
    .table{
        margin-bottom: 0;
    }

    .table td{
        padding: 10px 6px;
        vertical-align: middle;
        font-size: 14px;
    }

    .table-sm td{
        padding: 8px 6px;
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