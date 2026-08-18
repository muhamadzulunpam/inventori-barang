<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Detail User</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('/user') ?>">Manajemen User</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="<?= base_url('/user') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-circle"></i> Foto Profil</h5>
                </div>
                <div class="card-body text-center">
                    <?php if($user['foto'] && file_exists('assets/images/users/' . $user['foto'])): ?>
                        <img src="<?= base_url('assets/images/users/' . $user['foto']) ?>" 
                             alt="Foto" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    <?php else: ?>
                        <div class="avatar-placeholder rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center" 
                             style="width: 150px; height: 150px; font-size: 48px;">
                            <?= strtoupper(substr($user['nama'], 0, 2)) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi User</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">ID User</th>
                            <td><?= $user['id_user'] ?></td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td><?= $user['nama'] ?></td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td><?= $user['username'] ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?= $user['email'] ?></td>
                        </tr>
                        <tr>
                            <th>No. HP</th>
                            <td><?= $user['no_hp'] ?: '-' ?></td>
                        </tr>
                        <tr>
                            <th>Level</th>
                            <td>
                                <?php if($user['level'] == 'superadmin'): ?>
                                    <span class="badge bg-danger">Admin</span>
                                <?php elseif($user['level'] == 'staff_gudang'): ?>
                                    <span class="badge bg-danger">Staff Gudang</span>
                                <?php else: ?>
                                    <span class="badge bg-info">Owner</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <?php if($user['status'] == 'aktif'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Terdaftar Sejak</th>
                            <td><?= date('d/m/Y H:i:s', strtotime($user['created_at'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-placeholder {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
    }
</style>

<?= $this->include('layout/footer') ?>