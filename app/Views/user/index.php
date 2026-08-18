<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Manajemen User</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item active">Manajemen User</li>
                        </ol>
                    </nav>
                </div>
                <a href="<?= base_url('/user/new') ?>" class="btn btn-primary-custom">
                    <i class="fas fa-plus"></i> Tambah User
                </a>
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
                            <h6 class="mb-0">Total User</h6>
                            <h3 class="mb-0"><?= $statistik['total'] ?? 0 ?></h3>
                        </div>
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">User Aktif</h6>
                            <h3 class="mb-0"><?= $statistik['aktif'] ?? 0 ?></h3>
                        </div>
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">User Nonaktif</h6>
                            <h3 class="mb-0"><?= $statistik['nonaktif'] ?? 0 ?></h3>
                        </div>
                        <i class="fas fa-user-slash fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Super Admin</h6>
                            <h3 class="mb-0"><?= $statistik['admin'] ?? 0 ?></h3>
                        </div>
                        <i class="fas fa-user-shield fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list"></i> Daftar User
                </h5>
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari user...">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">ID User</th>
                            <th width="15%">Foto</th>
                            <th width="15%">Nama</th>
                            <th width="15%">Username</th>
                            <th width="20%">Email</th>
                            <th width="10%">Level</th>
                            <th width="10%">Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($users) > 0): ?>
                            <?php $no = 1; foreach($users as $item): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $item['id_user'] ?></td>
                                <td class="text-center">
                                    <?php if($item['foto'] && file_exists('assets/images/users/' . $item['foto'])): ?>
                                        <img src="<?= base_url('assets/images/users/' . $item['foto']) ?>" 
                                             alt="Foto" width="40" height="40" class="rounded-circle">
                                    <?php else: ?>
                                        <div class="avatar-placeholder rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px; font-size: 16px;">
                                            <?= strtoupper(substr($item['nama'], 0, 2)) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= $item['nama'] ?></strong></td>
                                <td><?= $item['username'] ?></td>
                                <td><?= $item['email'] ?></td>
                                <td>
                                    <?php if($item['level'] == 'admin'): ?>
                                        <span class="badge bg-danger">Admin</span>
                                    <?php elseif($item['level'] == 'staff_gudang'): ?>
                                        <span class="badge bg-warning text-dark">Staff Gudang</span>
                                    <?php else: ?>
                                        <span class="badge bg-info">Owner</span>
                                    <?php endif; ?>
                                 </td>
                                <td>
                                    <?php if($item['status'] == 'aktif'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                 </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('/user/detail/' . $item['id_user']) ?>" 
                                           class="btn btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('/user/edit/' . $item['id_user']) ?>" 
                                           class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('/user/toggleStatus/' . $item['id_user']) ?>" 
                                           class="btn <?= $item['status'] == 'aktif' ? 'btn-danger' : 'btn-success' ?>" 
                                           title="<?= $item['status'] == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>"
                                           onclick="return confirm('Yakin ingin mengubah status user ini?')">
                                            <i class="fas <?= $item['status'] == 'aktif' ? 'fa-user-slash' : 'fa-user-check' ?>"></i>
                                        </a>
                                        <?php if(session()->get('id_user') != $item['id_user']): ?>
                                            <button type="button"
                                                    class="btn btn-danger"
                                                    title="Hapus"
                                                    onclick="confirmDelete('<?= $item['id_user'] ?>', '<?= addslashes($item['nama']) ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                 </td>
                             </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted">Belum ada data user</p>
                                    <a href="<?= base_url('/user/new') ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus"></i> Tambah User
                                    </a>
                                </td>
                             </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal Konfirmasi Hapus User -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-trash"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p>
                    Apakah Anda yakin ingin menghapus user
                    <strong id="deleteNama"></strong>?
                </p>

                <div class="alert alert-danger mb-0">
                    <i class="fas fa-exclamation-triangle"></i>
                    Data yang dihapus tidak dapat dikembalikan.
                </div>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Batal
                </button>

                <form id="deleteForm" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    
    
    
    function confirmDelete(id, nama)
    {
        document.getElementById('deleteNama').innerText = nama;

        document.getElementById('deleteForm').action =
            '<?= base_url('/user/delete/') ?>' + id;

        const modal = new bootstrap.Modal(
            document.getElementById('deleteModal')
        );

        modal.show();
    }



    // Search functionality
    document.getElementById('searchBtn').addEventListener('click', function() {
        let searchText = document.getElementById('searchInput').value.toLowerCase();
        let rows = document.querySelectorAll('#dataTable tbody tr');
        
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(searchText) ? '' : 'none';
        });
    });

    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('searchBtn').click();
        }
    });
</script>

<style>
    .btn-primary-custom {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        border: none;
        color: white;
        padding: 8px 20px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(30,60,114,0.3);
        color: white;
    }
    .avatar-placeholder {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
    }
    .table th {
        background: #f8f9fa;
        font-weight: 600;
    }
</style>

<?= $this->include('layout/footer') ?>