<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Data Jenis Barang</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item active">Jenis Barang</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="<?= base_url('/jenis/new') ?>" class="btn btn-add">
                        <i class="fas fa-plus"></i> Tambah Jenis
                    </a>
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

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Jenis</h6>
                            <h2 class="mb-0"><?= $statistik['total_jenis'] ?? count($jenis) ?></h2>
                        </div>
                        <i class="fas fa-tags fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Barang</h6>
                            <h2 class="mb-0"><?= $statistik['total_barang'] ?? array_sum(array_column($jenis, 'total_barang')) ?></h2>
                        </div>
                        <i class="fas fa-box fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
<div class="card filter-card mb-4">
    <div class="card-body">
        <div class="row align-items-end g-3">

            <div class="col-md-7">
                <label class="form-label custom-label">
                    <i class="fas fa-search"></i> Cari Jenis
                </label>
                <input type="text"
                    id="searchInput"
                    class="form-control custom-input"
                    placeholder="Cari ID jenis, nama jenis...">
            </div>

            <div class="col-md-3">
                <button class="btn btn-search w-100" id="searchBtn">
                    <i class="fas fa-search"></i> Cari Data
                </button>
            </div>

            <div class="col-md-2">
                <button class="btn btn-reset w-100" id="resetBtn">
                    <i class="fas fa-rotate-right"></i> Reset
                </button>
            </div>

        </div>
    </div>
</div>

    <!-- Card Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Jenis Barang</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">ID Jenis</th>
                            <th width="20%">Nama Jenis</th>
                            <th width="25%">Keterangan</th>
                            <th width="15%">Total Barang</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($jenis) > 0): ?>
                            <?php $no = 1; foreach($jenis as $item): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><span class="badge bg-info"><?= $item['id_jenis'] ?></span></td>
                                <td><strong><?= $item['nama_jenis'] ?></strong></td>
                                <td>
                                    <?php if(!empty($item['keterangan'])): ?>
                                        <span>
                                            <?= substr($item['keterangan'], 0, 50) . (strlen($item['keterangan']) > 50 ? '...' : '') ?>
                                        </span>
                                    <?php else: ?>
                                        <span>-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <span class="badge bg-primary"><?= $item['total_barang'] ?? 0 ?></span>
                                        <?php if(($item['total_stok'] ?? 0) > 0): ?>
                                            <small class="text-muted d-block">(<?= number_format($item['total_stok'] ?? 0) ?> stok)</small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('/jenis/detail/' . $item['id_jenis']) ?>" 
                                           class="btn btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('/jenis/edit/' . $item['id_jenis']) ?>" 
                                           class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if(($item['total_barang'] ?? 0) == 0): ?>
                                            <button type="button" 
                                                    class="btn btn-danger" 
                                                    title="Hapus Jenis"
                                                    onclick="confirmDelete('<?= $item['id_jenis'] ?>', '<?= addslashes($item['nama_jenis']) ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-secondary" title="Tidak bisa dihapus (masih ada <?= $item['total_barang'] ?> barang)" disabled>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                 </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-tags fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted">Belum ada data jenis barang</p>
                                    <a href="<?= base_url('/jenis/new') ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Jenis
                                    </a>
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
                        <i class="fas fa-database"></i> Total Data: <?= count($jenis) ?>
                    </small>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        <i class="fas fa-box"></i> Total Barang: <?= $statistik['total_barang'] ?? array_sum(array_column($jenis, 'total_barang')) ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus jenis barang <strong id="deleteNama"></strong>?</p>
                <p class="text-danger">
                    <small>Data yang dihapus tidak dapat dikembalikan!</small>
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>

                <form id="deleteForm" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    // Konfirmasi Delete
    function confirmDelete(id, nama) {
        document.getElementById('deleteNama').innerText = nama;
        document.getElementById('deleteForm').action = '<?= base_url('/jenis/delete/') ?>' + id;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    // Search dan Filter - pastikan elemen ada sebelum diakses
    document.addEventListener('DOMContentLoaded', function() {
        const searchBtn = document.getElementById('searchBtn');
        const resetBtn = document.getElementById('resetBtn');
        const searchInput = document.getElementById('searchInput');
        const filterKeterangan = document.getElementById('filterKeterangan');
        const filterBarang = document.getElementById('filterBarang');
        
        // Fungsi filter
        function filterData() {
            let searchText = searchInput ? searchInput.value.toLowerCase() : '';
            let filterKet = filterKeterangan ? filterKeterangan.value : '';
            let filterBrg = filterBarang ? filterBarang.value : '';
            let rows = document.querySelectorAll('#dataTable tbody tr');
            let hasData = false;
            
            rows.forEach(row => {
                // Skip jika row adalah "tidak ada data"
                if (row.querySelector('td[colspan]')) return;
                
                let cells = row.querySelectorAll('td');
                if (cells.length < 5) return;
                
                let idJenis = cells[1]?.innerText.toLowerCase() || '';
                let namaJenis = cells[2]?.innerText.toLowerCase() || '';
                let keterangan = cells[3]?.innerText.toLowerCase() || '';
                let totalBarangText = cells[4]?.innerText || '0';
                let totalBarang = parseInt(totalBarangText) || 0;
                
                let showBySearch = true;
                let showByKeterangan = true;
                let showByBarang = true;
                
                if (searchText) {
                    showBySearch = idJenis.includes(searchText) || namaJenis.includes(searchText) || keterangan.includes(searchText);
                }
                
                if (filterKet) {
                    if (filterKet === 'ada') showByKeterangan = keterangan !== '-' && keterangan !== '';
                    else if (filterKet === 'kosong') showByKeterangan = keterangan === '-' || keterangan === '';
                }
                
                if (filterBrg) {
                    if (filterBrg === 'ada') showByBarang = totalBarang > 0;
                    else if (filterBrg === 'kosong') showByBarang = totalBarang === 0;
                }
                
                if (showBySearch && showByKeterangan && showByBarang) {
                    row.style.display = '';
                    hasData = true;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Tampilkan pesan jika tidak ada data
            let noDataRow = document.querySelector('#dataTable tbody tr td[colspan]');
            if (!hasData && rows.length > 0 && !noDataRow) {
                let tbody = document.querySelector('#dataTable tbody');
                if (tbody && !document.getElementById('noDataRow')) {
                    let tr = document.createElement('tr');
                    tr.id = 'noDataRow';
                    tr.innerHTML = '<td colspan="6" class="text-center py-4"><i class="fas fa-search fa-3x text-muted mb-3 d-block"></i><p class="text-muted">Data tidak ditemukan</p></td>';
                    tbody.appendChild(tr);
                }
            } else if (hasData && document.getElementById('noDataRow')) {
                document.getElementById('noDataRow').remove();
            }
        }
        
        // Event listeners
        if (searchBtn) {
            searchBtn.addEventListener('click', filterData);
        }
        
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                if (searchInput) searchInput.value = '';
                if (filterKeterangan) filterKeterangan.value = '';
                if (filterBarang) filterBarang.value = '';
                
                let rows = document.querySelectorAll('#dataTable tbody tr');
                rows.forEach(row => {
                    row.style.display = '';
                });
                
                // Hapus pesan no data
                if (document.getElementById('noDataRow')) {
                    document.getElementById('noDataRow').remove();
                }
            });
        }
        
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    filterData();
                }
            });
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