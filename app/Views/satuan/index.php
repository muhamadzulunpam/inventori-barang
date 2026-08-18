<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Data Satuan</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item active">Data Satuan</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="<?= base_url('/satuan/new') ?>" class="btn btn-add">
                        <i class="fas fa-plus"></i> Tambah Satuan
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
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Satuan</h6>
                            <h3 class="mb-0"><?= $statistik['total_satuan'] ?? 0 ?></h3>
                        </div>
                        <i class="fas fa-ruler fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
<div class="card filter-card mb-4">
    <div class="card-body">
        <div class="row align-items-end g-3">

            <div class="col-md-5">
                <label class="form-label custom-label">
                    <i class="fas fa-search"></i> Cari Satuan
                </label>
                <input type="text" id="searchInput" class="form-control custom-input"
                    placeholder="Cari ID, nama satuan, atau keterangan...">
            </div>

            <div class="col-md-3">
                <label class="form-label custom-label">
                    <i class="fas fa-filter"></i> Filter Status
                </label>
                <select id="filterStatus" class="form-select custom-input">
                    <option value="">Semua</option>
                    <option value="memiliki_barang">Memiliki Barang</option>
                    <option value="tidak_memiliki_barang">Tidak Memiliki Barang</option>
                    <option value="ada_keterangan">Ada Keterangan</option>
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-search w-100" id="searchBtn">
                    <i class="fas fa-search"></i> Cari
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
            <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Satuan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">ID Satuan</th>
                            <th width="20%">Nama Satuan</th>
                            <th width="35%">Keterangan</th>
                            <th width="15%">Total Barang</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($satuan) > 0): ?>
                            <?php $no = 1; foreach($satuan as $item): ?>
                                <?php 
                                    $total_barang = $item['total_barang'] ?? 0;
                                    $total_stok = $item['total_stok'] ?? 0;
                                    $memiliki_barang = $total_barang > 0;
                                    $ada_keterangan = !empty($item['keterangan']);
                                ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><span class="badge bg-info"><?= $item['id_satuan'] ?></span></td>
                                <td><strong><?= $item['nama_satuan'] ?></strong></td>
                                <td>
                                    <?php if($ada_keterangan): ?>
                                        <?= $item['keterangan'] ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $memiliki_barang ? 'bg-primary' : 'bg-secondary' ?>">
                                        <?= number_format($total_barang) ?>
                                    </span>
                                    <?php if($total_stok > 0): ?>
                                        <small class="text-muted d-block">(<?= number_format($total_stok) ?> stok)</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('/satuan/detail/' . $item['id_satuan']) ?>" 
                                           class="btn btn-info" title="Detail Satuan">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('/satuan/edit/' . $item['id_satuan']) ?>" 
                                           class="btn btn-warning" title="Edit Satuan">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($total_barang > 0): ?>
                                            <button type="button"
                                                    class="btn btn-secondary"
                                                    title="Tidak dapat dihapus karena masih memiliki barang"
                                                    disabled>
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        <?php else: ?>

                                            <button type="button"
                                                    class="btn btn-danger"
                                                    title="Hapus"
                                                    onclick="confirmDelete(
                                                        '<?= $item['id_satuan'] ?>',
                                                        '<?= addslashes($item['nama_satuan']) ?>',
                                                        false
                                                    )">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        <?php endif; ?>
                                    </div>
                                 </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-ruler fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted">Belum ada data satuan</p>
                                    <a href="<?= base_url('/satuan/new') ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Satuan
                                    </a>
                                 </div>
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
                        <i class="fas fa-database"></i> Total Data: <?= count($satuan) ?>
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
                <p>Apakah Anda yakin ingin menghapus satuan <strong id="deleteNama"></strong>?</p>
                <p class="text-danger" id="warningMessage" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i> Satuan ini sedang digunakan oleh beberapa barang dan tidak dapat dihapus!
                </p>
                <p class="text-danger" id="confirmMessage">
                    <small>Data yang dihapus tidak dapat dikembalikan!</small>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="post" style="display: inline-block;">
                    <?= csrf_field() ?>
                    <button type="submit" id="deleteButton" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Konfirmasi Delete dengan pengecekan memiliki barang
    function confirmDelete(id, nama, hasBarang) {
        document.getElementById('deleteNama').innerText = nama;
        let deleteForm = document.getElementById('deleteForm');
        let deleteButton = document.getElementById('deleteButton');
        let warningMessage = document.getElementById('warningMessage');
        let confirmMessage = document.getElementById('confirmMessage');
        
        deleteForm.action = '<?= base_url('/satuan/delete/') ?>' + id;
        
        if (hasBarang) {
            // Jika masih memiliki barang, disable tombol hapus
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
        let filterStatus = document.getElementById('filterStatus').value;
        let rows = document.querySelectorAll('#dataTable tbody tr');
        let hasData = false;
        
        rows.forEach(row => {
            // Skip jika row adalah pesan "Tidak ada data"
            if (row.cells.length === 1) return;
            
            let idSatuan = row.cells[1].innerText.toLowerCase();
            let namaSatuan = row.cells[2].innerText.toLowerCase();
            let keterangan = row.cells[3].innerText.toLowerCase();
            let totalBarangText = row.cells[4].innerText;
            
            // Parse total barang (angka)
            let totalBarang = 0;
            let match = totalBarangText.match(/(\d+)/);
            if (match) {
                totalBarang = parseInt(match[0]);
            }
            
            let memilikiBarang = totalBarang > 0;
            let adaKeterangan = keterangan !== '' && keterangan !== '-';
            
            let showBySearch = true;
            let showByStatus = true;
            
            if (searchText) {
                showBySearch = idSatuan.includes(searchText) || 
                               namaSatuan.includes(searchText) || 
                               keterangan.includes(searchText);
            }
            
            if (filterStatus) {
                if (filterStatus === 'memiliki_barang') {
                    showByStatus = memilikiBarang;
                } else if (filterStatus === 'tidak_memiliki_barang') {
                    showByStatus = !memilikiBarang;
                } else if (filterStatus === 'ada_keterangan') {
                    showByStatus = adaKeterangan;
                }
            }
            
            if (showBySearch && showByStatus) {
                row.style.display = '';
                hasData = true;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Cek apakah baris yang ditampilkan adalah baris "Tidak ada data"
        let noDataRow = document.querySelector('#dataTable tbody tr td[colspan="6"]');
        if (noDataRow) {
            noDataRow.parentElement.style.display = hasData ? 'none' : '';
        }
        
        if (!hasData && rows.length > 0 && !noDataRow) {
            alert('Data tidak ditemukan!');
        }
    });

    // Reset filter
    document.getElementById('resetBtn').addEventListener('click', function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('filterStatus').value = '';
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
    /* =========================
    BUTTON TAMBAH
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

    /* STAT CARD */
    .card.bg-primary,
    .card.bg-success,
    .card.bg-warning,
    .card.bg-danger{
        border-radius: 14px !important;
        box-shadow: 0 6px 18px rgba(0,0,0,0.08) !important;
        transition: all 0.25s ease;
    }

    .card.bg-primary:hover,
    .card.bg-success:hover,
    .card.bg-warning:hover,
    .card.bg-danger:hover{
        transform: translateY(-3px);
    }

    /* body */
    .card-body{
        padding: 14px 16px !important;
    }

    .card h6{
        font-size: 13px !important;
        font-weight: 600;
        margin-bottom: 6px !important;
    }

    .card h2,
    .card h3{
        font-size: 22px !important;
        font-weight: 700;
    }

    /* icon */
    .card i{
        opacity: 1 !important;
        filter: drop-shadow(0 2px 2px rgba(0,0,0,0.25));
    }

    /* FILTER CARD */
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

    /* TABLE */
    .table{
        font-size: 14px;
    }

    .table th{
        background: #f8f9fc;
        font-weight: 600;
        border: none !important;
        padding: 12px !important;
    }

    .table td{
        padding: 10px !important;
        vertical-align: middle;
        border-color: #f1f1f1;
    }

    .table-hover tbody tr:hover{
        background: #f7faff;
    }

    /* badge */
    .badge.bg-info{
        background: #e7f1ff !important;
        color: #2f80ed !important;
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 8px;
    }

    /* card */
    .card{
        border: none !important;
        border-radius: 14px !important;
    }

    /* card header */
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

    /* footer */
    .card-footer{
        background: #fafbfd !important;
        border-top: 1px solid #edf2f7 !important;
    }

    /* tombol aksi */
    .btn-group .btn{
        border-radius: 8px !important;
        margin-right: 4px;
        padding: 6px 9px;
    }

    /* breadcrumb */
    .breadcrumb{
        background: transparent;
        padding: 0;
        font-size: 13px;
    }

    .page-title{
        font-weight: 700;
    }

</style>

<?= $this->include('layout/footer') ?>