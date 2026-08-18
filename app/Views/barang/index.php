<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Data Barang</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item active">Data Barang</li>
                        </ol>
                    </nav>
                </div>
                <?php if(session()->get('level') != 'staff_gudang'): ?>
                <div>
                    <a href="<?= base_url('/barang/new') ?>" class="btn btn-add">
                        <i class="fas fa-plus"></i> Tambah Barang
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Barang</h6>
                            <h2 class="mb-0"><?= number_format($statistik['total_barang'] ?? 0) ?></h2>
                        </div>
                        <i class="fas fa-boxes fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Stok</h6>
                            <h2 class="mb-0"><?= number_format($statistik['total_stok'] ?? 0) ?></h2>
                        </div>
                        <i class="fas fa-database fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Menipis</h6>
                            <h2 class="mb-0"><?= number_format($statistik['stok_menipis'] ?? 0) ?></h2>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Habis</h6>
                            <h2 class="mb-0"><?= number_format($statistik['stok_habis'] ?? 0) ?></h2>
                        </div>
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filter Section -->
    <div class="card filter-card mb-4">
        <div class="card-body">
            <form method="GET" action="<?= base_url('/barang') ?>">
                <div class="row align-items-end g-3">

                    <div class="col-md-7">
                        <label class="form-label custom-label">
                            <i class="fas fa-search"></i> Cari Barang
                        </label>
                        <input type="text"
                            name="search"
                            id="searchInput"
                            class="form-control custom-input"
                            placeholder="Cari ID barang, nama barang, warna..."
                            value="<?= $_GET['search'] ?? '' ?>">
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-search w-100">
                            <i class="fas fa-search"></i> Cari Data
                        </button>
                    </div>

                    <div class="col-md-2">
                        <a href="<?= base_url('/barang') ?>" class="btn btn-reset w-100">
                            <i class="fas fa-rotate-right"></i> Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- Card Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-boxes"></i> Daftar Barang</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">ID Barang</th>
                            <th width="20%">Nama Barang</th>
                            <th width="10%">Warna</th>
                            <th width="10%">Satuan</th>
                            <th width="15%">Jenis</th>
                            <th width="10%">Foto</th>
                            <?php if(session()->get('level') != 'staff_gudang'): ?>
                            <th width="10%">Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($barang) > 0): ?>
                            <?php $no = 1; foreach($barang as $item): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><span class="badge bg-info"><?= $item['id_barang'] ?></span></td>
                                <td><strong><?= $item['nama_barang'] ?></strong></td>
                                <td>
                                    <?php if($item['warna']): ?>
                                        <?= $item['warna'] ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?= $item['nama_satuan'] ?? '-' ?></td>
                                <td><?= $item['nama_jenis'] ?? '-' ?></td>
                                <td>
                                    <?php if($item['foto'] && $item['foto'] != 'default.png'): ?>
                                        <img src="<?= base_url('uploads/barang/' . $item['foto']) ?>" width="40" height="40" class="rounded" style="object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-box text-white"></i>
                                        </div>
                                    <?php endif; ?>
                                 </td>
                                <?php if(session()->get('level') != 'staff_gudang'): ?>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('/barang/detail/' . $item['id_barang']) ?>" class="btn btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('/barang/edit/' . $item['id_barang']) ?>" class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php $hasTransaksi = ($item['total_transaksi'] ?? 0) > 0; ?>



                                    <?php if ($hasTransaksi): ?>
                                        <button type="button"
                                            class="btn btn-secondary"
                                            title="Tidak bisa dihapus karena sudah memiliki transaksi"
                                            disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php else: ?>
                                        <button type="button"
                                            class="btn btn-danger"
                                            title="Hapus"
                                            onclick="confirmDelete(
                                                '<?= $item['id_barang'] ?>',
                                                '<?= addslashes($item['nama_barang']) ?>',
                                                false
                                            )">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <tr>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= session()->get('level') != 'staff_gudang' ? '9' : '8' ?>" class="text-center py-4">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted">Belum ada data barang</p>
                                    <?php if(session()->get('level') != 'staff_gudang'): ?>
                                    <a href="<?= base_url('/barang/new') ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Barang
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted">
                        <i class="fas fa-database"></i> Total Data: <?= count($barang) ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus barang <strong id="deleteNama"></strong>?</p>

                <p class="text-danger" id="warningMessage" style="display:none;">
                    <i class="fas fa-exclamation-triangle"></i>
                    Barang ini memiliki riwayat transaksi dan tidak dapat dihapus!
                </p>

                <p class="text-danger" id="confirmMessage">
                    <small>Data yang dihapus tidak dapat dikembalikan!</small>
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>

                <form id="deleteForm" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" id="deleteButton" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, nama, hasTransaction) {
    document.getElementById('deleteNama').innerText = nama;

    let deleteForm = document.getElementById('deleteForm');
    let deleteButton = document.getElementById('deleteButton');
    let warningMessage = document.getElementById('warningMessage');
    let confirmMessage = document.getElementById('confirmMessage');

    deleteForm.action = '<?= base_url('/barang/delete/') ?>' + id;

    if (hasTransaction) {
        deleteButton.disabled = true;
        warningMessage.style.display = 'block';
        confirmMessage.style.display = 'none';
    } else {
        deleteButton.disabled = false;
        warningMessage.style.display = 'none';
        confirmMessage.style.display = 'block';
    }

    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

    // Search dan Filter
    document.getElementById('searchBtn').addEventListener('click', function() {
        let searchText = document.getElementById('searchInput').value.toLowerCase();
        let filterStok = document.getElementById('filterStok').value;
        let rows = document.querySelectorAll('#dataTable tbody tr');
        let hasData = false;
        
        rows.forEach(row => {
            let idBarang = row.cells[1].innerText.toLowerCase();
            let namaBarang = row.cells[2].innerText.toLowerCase();
            let warna = row.cells[3].innerText.toLowerCase();
            let stok = parseInt(row.cells[4].innerText.replace(/\./g, ''));
            
            let showBySearch = true;
            let showByStok = true;
            
            if (searchText) {
                showBySearch = idBarang.includes(searchText) || namaBarang.includes(searchText) || warna.includes(searchText);
            }
            
            if (filterStok) {
                if (filterStok === 'menipis') showByStok = stok <= 10 && stok > 0;
                else if (filterStok === 'habis') showByStok = stok === 0;
                else if (filterStok === 'tersedia') showByStok = stok > 10;
            }
            
            if (showBySearch && showByStok) {
                row.style.display = '';
                hasData = true;
            } else {
                row.style.display = 'none';
            }
        });
        
        if (!hasData && rows.length > 0) {
            alert('Data tidak ditemukan!');
        }
    });

    // Reset filter
    document.getElementById('resetBtn').addEventListener('click', function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('filterStok').value = '';
        let rows = document.querySelectorAll('#dataTable tbody tr');
        rows.forEach(row => {
            row.style.display = '';
        });
    });

    // Enter key search
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('searchBtn').click();
        }
    });
</script>

<style>
    /* BUTTON CUSTOM */
    .btn-primary-custom {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        border: none;
        color: white;
        padding: 8px 20px;
        border-radius: 10px;
        transition: all 0.25s ease;
        font-weight: 500;
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(30,60,114,0.25);
        color: white;
    }

    /* =========================
       STAT CARD MODERN FIX
    ========================== */
    .card.bg-primary,
    .card.bg-success,
    .card.bg-warning,
    .card.bg-danger {
        border-radius: 14px !important;
        box-shadow: 0 6px 18px rgba(0,0,0,0.08) !important;
        transition: all 0.25s ease;
    }

    .card.bg-primary:hover,
    .card.bg-success:hover,
    .card.bg-warning:hover,
    .card.bg-danger:hover {
        transform: translateY(-3px);
    }

    /* padding lebih rapih */
    .card-body {
        padding: 14px 16px !important;
    }

    /* judul kecil */
    .card h6 {
        font-size: 13px !important;
        font-weight: 600;
        margin-bottom: 6px !important;
    }

    /* angka lebih tegas */
    .card h2 {
        font-size: 22px !important;
        font-weight: 700;
    }

    /* =========================
       ICON FIX (TIDAK PALE / TIDAK PUDAR)
    ========================== */
    .card i {
        opacity: 1 !important;
        filter: drop-shadow(0 2px 2px rgba(0,0,0,0.25));
    }

    /* =========================
       WARNA KUNING (MENIPIS) FIX
       -> TEKS PUTIH SESUAI REQUEST
    ========================== */
    .bg-warning {
        background: linear-gradient(135deg, #f2c94c, #e0b100) !important;
        color: #ffffff !important;
    }

    .bg-warning h6,
    .bg-warning h2 {
        color: #ffffff !important;
    }

    /* =========================
       TABLE CLEAN LOOK
    ========================== */
    .table {
        font-size: 14px;
    }

    .table th {
        background: #f8f9fc;
        font-weight: 600;
        border: none !important;
        padding: 12px !important;
    }

    .table td {
        padding: 10px !important;
        vertical-align: middle;
        border-color: #f1f1f1;
    }

    .table-hover tbody tr:hover {
        background-color: #f6f9ff;
    }

    /* badge ID */
    .badge.bg-info {
        background: #e7f1ff !important;
        color: #2f80ed !important;
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 8px;
    }

    /* card rounding global */
    .card {
        border: none !important;
        border-radius: 14px !important;
    }

    /* breadcrumb */
    .breadcrumb {
        background: transparent;
        padding: 0;
        font-size: 13px;
    }

    .page-title {
        font-weight: 700;
    }

    /* =========================
    BUTTON TAMBAH BARANG
    ========================= */
    .btn-add{
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        border: none;
        color: white !important;
        padding: 10px 22px;
        border-radius: 12px;
        font-weight: 500;
        transition: 0.25s ease;
        font-size: 14px;
    }

    .btn-add:hover{
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(30,60,114,0.18);
    }

    /* =========================
    FILTER CARD
    ========================= */
    .filter-card{
        border-radius: 16px !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        border: none !important;
    }

    /* label */
    .custom-label{
        font-size: 13px;
        font-weight: 600;
        color: #1e3c72;
        margin-bottom: 8px;
    }

    /* input */
    .custom-input{
        border: 1px solid #dde4f0 !important;
        border-radius: 12px !important;
        padding: 11px 14px !important;
        font-size: 14px;
        transition: 0.2s ease;
    }

    .custom-input:focus{
        box-shadow: 0 0 0 3px rgba(42,82,152,0.08) !important;
        border-color: #2a5298 !important;
    }

    /* tombol cari */
    .btn-search{
        background: #2a5298 !important;
        border: none !important;
        color: white !important;
        border-radius: 12px !important;
        padding: 11px;
        font-size: 14px;
        font-weight: 500;
        transition: 0.25s ease;
    }

    .btn-search:hover{
        background: #1e3c72 !important;
        transform: translateY(-1px);
    }

    /* tombol reset */
    .btn-reset{
        background: #eef2f7 !important;
        border: none !important;
        color: #4b5563 !important;
        border-radius: 12px !important;
        padding: 11px;
        font-size: 14px;
        font-weight: 500;
        transition: 0.25s ease;
    }

    .btn-reset:hover{
        background: #dce4ef !important;
    }

    /* =========================
    TABLE HEADER
    ========================= */
    .card-header{
        background: white !important;
        border-bottom: 1px solid #edf2f7 !important;
        padding: 18px 20px !important;
    }

    .card-header h5{
        color: #1e3c72;
        font-weight: 650;
        font-size: 17px;
    }

    /* =========================
    AKSI BUTTON
    ========================= */
    .btn-group .btn{
        border-radius: 8px !important;
        margin-right: 4px;
        padding: 6px 9px;
    }

    /* hover row */
    .table-hover tbody tr:hover{
        background: #f7faff;
    }

    /* footer */
    .card-footer{
        background: #fafbfd !important;
        border-top: 1px solid #edf2f7 !important;
    }
</style>

<?= $this->include('layout/footer') ?>