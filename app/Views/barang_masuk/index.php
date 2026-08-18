<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Data Barang Masuk</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item active">Barang Masuk</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="<?= base_url('/barang_masuk/create') ?>" class="btn btn-primary-custom">
                        <i class="fas fa-plus"></i> Tambah Barang Masuk
                    </a>
                </div>
            </div>
        </div>
    </div>

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
                            <h6 class="mb-0">Total Transaksi</h6>
                            <h3 class="mb-0"><?= number_format($total_transaksi ?? 0) ?></h3>
                        </div>
                        <i class="fas fa-exchange-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Barang Masuk</h6>
                            <h3 class="mb-0"><?= number_format($total_masuk ?? 0) ?></h3>
                        </div>
                        <i class="fas fa-boxes fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4 rounded-4">
        <div class="card-header bg-white border-0 py-3 rounded-top-4">
            <h5 class="mb-0 fw-semibold text-dark">
                <i class="fas fa-filter text-primary me-2"></i>Filter Data
            </h5>
        </div>
        <div class="card-body pt-2">
            <form method="GET" action="<?= base_url('/barang_masuk') ?>" id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-3 col-md-6">
                        <label for="bulan" class="form-label fw-semibold small text-muted">Bulan</label>
                        <select name="bulan" id="bulan" class="form-select rounded-3 shadow-sm">
                            <option value="">Semua Bulan</option>
                            <option value="1" <?= ($_GET['bulan'] ?? '') == '1' ? 'selected' : '' ?>>Januari</option>
                            <option value="2" <?= ($_GET['bulan'] ?? '') == '2' ? 'selected' : '' ?>>Februari</option>
                            <option value="3" <?= ($_GET['bulan'] ?? '') == '3' ? 'selected' : '' ?>>Maret</option>
                            <option value="4" <?= ($_GET['bulan'] ?? '') == '4' ? 'selected' : '' ?>>April</option>
                            <option value="5" <?= ($_GET['bulan'] ?? '') == '5' ? 'selected' : '' ?>>Mei</option>
                            <option value="6" <?= ($_GET['bulan'] ?? '') == '6' ? 'selected' : '' ?>>Juni</option>
                            <option value="7" <?= ($_GET['bulan'] ?? '') == '7' ? 'selected' : '' ?>>Juli</option>
                            <option value="8" <?= ($_GET['bulan'] ?? '') == '8' ? 'selected' : '' ?>>Agustus</option>
                            <option value="9" <?= ($_GET['bulan'] ?? '') == '9' ? 'selected' : '' ?>>September</option>
                            <option value="10" <?= ($_GET['bulan'] ?? '') == '10' ? 'selected' : '' ?>>Oktober</option>
                            <option value="11" <?= ($_GET['bulan'] ?? '') == '11' ? 'selected' : '' ?>>November</option>
                            <option value="12" <?= ($_GET['bulan'] ?? '') == '12' ? 'selected' : '' ?>>Desember</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="tahun" class="form-label fw-semibold small text-muted">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select rounded-3 shadow-sm">
                            <option value="">Semua Tahun</option>
                            <?php for($t = date('Y') - 2; $t <= date('Y') + 1; $t++): ?>
                                <option value="<?= $t ?>" <?= (($_GET['tahun'] ?? '') == $t) ? 'selected' : '' ?>><?= $t ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <label for="search" class="form-label fw-semibold small text-muted">Pencarian</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-end-0 rounded-start-3">
                                <i class="fas fa-search text-secondary"></i>
                            </span>
                            <input type="text" name="search" id="search" class="form-control border-start-0 rounded-end-3"
                                placeholder="Cari kode transaksi, supplier, atau barang..."
                                value="<?= $_GET['search'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary" style="background: #1e3c72; border: none;">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            <a href="<?= base_url('/laporan_masuk') ?>" class="btn btn-secondary">
                                <i class="fas fa-sync"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Card Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Barang Masuk</h5>
                <div>
                    <small class="text-muted">
                        <i class="fas fa-database"></i> Total Data: <?= count($barang_masuk) ?> transaksi
                    </small>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Kode Transaksi</th>
                            <th width="12%">Tanggal</th>
                            <th width="15%">Supplier</th>
                            <th width="15%">Barang</th>
                            <th width="8%">Jumlah</th>
                            <th width="8%">Satuan</th>
                            <th width="10%">Harga</th>
                            <th width="10%">Total</th>
                            <th width="10%">Input Oleh</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($barang_masuk) > 0): ?>
                            <?php $no = 1; foreach($barang_masuk as $item): ?>
                            <?php 
                                $total_harga = $item['jumlah_masuk'] * ($item['harga'] ?? 0);
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><span class="badge bg-success"><?= $item['id_barang_masuk'] ?></span></td>
                                <td><?= date('d/m/Y', strtotime($item['tanggal_masuk'])) ?></td>
                                <td><?= $item['nama_supplier'] ?></td>
                                <td><strong><?= $item['nama_barang'] ?></strong></td>
                                <td class="text-end fw-bold"><?= number_format($item['jumlah_masuk']) ?></td>
                                <td><?= $item['nama_satuan'] ?></td>
                                <td class="text-end">Rp <?= number_format($item['harga'] ?? 0, 0, ',', '.') ?></td>
                                <td class="text-end">Rp <?= number_format($total_harga, 0, ',', '.') ?></td>
                                <td><small><?= $item['nama_user'] ?? '-' ?></small></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('/barang_masuk/detail/' . $item['id_barang_masuk']) ?>" 
                                           class="btn btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- PERBAIKAN: Gunakan data-id dan data-kode -->
                                        <button type="button" 
                                                class="btn btn-danger" 
                                                title="Hapus"
                                                data-id="<?= $item['id_barang_masuk'] ?>"
                                                data-kode="<?= $item['id_barang_masuk'] ?>"
                                                onclick="confirmDelete(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted">Belum ada data barang masuk</p>
                                    <a href="<?= base_url('/barang_masuk/create') ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Barang Masuk
                                    </a>
                                </table>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-active">
                            <th colspan="5" class="text-end">Total Keseluruhan:</th>
                            <th class="text-end"><?= number_format(array_sum(array_column($barang_masuk, 'jumlah_masuk'))) ?></th>
                            <th colspan="2"></th>
                            <th class="text-end">Rp <?= number_format($total_nilai ?? 0, 0, ',', '.') ?></th>
                            <th colspan="1"></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Menampilkan <?= count($barang_masuk) ?> dari <?= $total_transaksi ?? 0 ?> transaksi
                    </small>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        <i class="fas fa-clock"></i> 
                        Last update: <?= date('d/m/Y H:i:s') ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus - PERBAIKAN -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-trash-alt fa-4x text-danger mb-3"></i>
                </div>
                <p class="text-center">Apakah Anda yakin ingin menghapus transaksi:</p>
                <p class="text-center fw-bold fs-5" id="deleteKode"></p>
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Perhatian!</strong>
                    <ul class="mb-0 mt-2">
                        <li>Data yang dihapus <strong class="text-danger">TIDAK DAPAT DIKEMBALIKAN</strong></li>
                        <li>Stok barang akan <strong>dikurangi</strong> secara otomatis</li>
                        <li>Pastikan Anda yakin sebelum menghapus</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Batal
                </button>
                <form id="deleteForm" method="POST">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="_method" value="DELETE">

                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Ya, Hapus!
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // PERBAIKAN: Fungsi confirmDelete yang benar
    function confirmDelete(button) {
        // Ambil data dari attribute button
        const id = button.getAttribute('data-id');
        const kode = button.getAttribute('data-kode');
        
        // Set ke modal
        document.getElementById('deleteKode').innerText = kode;
        document.getElementById('deleteForm').action = '/barang_masuk/delete/' + id;
        
        // Tampilkan modal
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    // Auto submit filter when pressing Enter
    document.querySelectorAll('#filterForm input, #filterForm select').forEach(el => {
        el.addEventListener('change', () => document.getElementById('filterForm').submit());
    });
</script>

<style>
    /* =========================
       BUTTON CUSTOM
    ========================== */
    .btn-primary-custom {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        border: none;
        color: white;
        padding: 7px 18px;
        font-size: 14px;
        border-radius: 10px;
        transition: all 0.25s ease;
        font-weight: 500;
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(30,60,114,0.25);
        color: white;
    }

    /* tombol biasa */
    .btn {
        border-radius: 8px !important;
        font-size: 14px;
        padding: 7px 14px;
    }

    /* =========================
       CARD GLOBAL
    ========================== */
    .card {
        border: none !important;
        border-radius: 14px !important;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    }

    .card-header {
        background: #ffffff !important;
        border-bottom: 1px solid #f1f1f1 !important;
        padding: 14px 18px;
        font-weight: 600;
    }

    .card-body {
        padding: 16px !important;
    }

    /* =========================
       STAT CARD
    ========================== */
    .card.bg-primary,
    .card.bg-danger {
        border-radius: 14px !important;
        box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        transition: all 0.25s ease;
    }

    .card.bg-primary:hover,
    .card.bg-danger:hover {
        transform: translateY(-3px);
    }

    .card h6 {
        font-size: 13px !important;
        font-weight: 600;
        margin-bottom: 5px !important;
    }

    .card h3 {
        font-size: 22px !important;
        font-weight: 700;
    }

    .card i {
        opacity: 1 !important;
        filter: drop-shadow(0 2px 2px rgba(0,0,0,0.25));
    }

    /* =========================
       FILTER SECTION
    ========================== */
    .form-label {
        font-size: 13px;
        font-weight: 600 !important;
        color: #666 !important;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        border-radius: 10px !important;
        border: 1px solid #ddd !important;
        padding: 9px 12px !important;
        font-size: 14px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #2a5298 !important;
        box-shadow: 0 0 0 0.2rem rgba(42,82,152,0.18);
    }

    .input-group-text {
        border-radius: 10px 0 0 10px !important;
        background: #fff !important;
    }

    /* tombol filter */
    .btn-primary {
        padding: 8px 18px !important;
        font-size: 14px;
    }

    .btn-outline-secondary {
        padding: 8px 18px !important;
        font-size: 14px;
    }

    /* =========================
       TABLE
    ========================== */
    .table {
        font-size: 14px;
    }

    .table th {
        background: #f8f9fc !important;
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
        background: #f6f9ff;
    }

    /* badge */
    .badge.bg-danger {
        border-radius: 8px;
        padding: 6px 10px;
        font-weight: 600;
    }

    /* =========================
       MODAL
    ========================== */
    .modal-content {
        border: none;
        border-radius: 14px;
    }

    .modal-header {
        border-bottom: 1px solid #eee;
        padding: 14px 18px;
    }

    .modal-footer {
        border-top: 1px solid #eee;
    }

    /* =========================
       PAGE TITLE
    ========================== */
    .page-title {
        font-weight: 700;
        margin-bottom: 2px;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        font-size: 13px;
        margin-bottom: 0;
    }

    /* footer kecil */
    .card-footer {
        background: #fff !important;
        border-top: 1px solid #f1f1f1 !important;
        font-size: 13px;
        padding: 12px 18px;
    }
</style>
<?= $this->include('layout/footer') ?>