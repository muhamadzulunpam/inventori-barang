<?= $this->include('layout/sidebar') ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Edit User</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('/user') ?>">Manajemen User</a></li>
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

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-edit"></i> Form Edit User</h5>
        </div>
        <div class="card-body">
            <form action="<?= base_url('/user/update/' . $user['id_user']) ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="id_user">ID User</label>
                            <input type="text" class="form-control" id="id_user" name="id_user" 
                                   value="<?= old('id_user', $user['id_user'] ?? '') ?>" readonly>
                            <small class="text-muted">ID User tidak dapat diubah</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="aktif" <?= (old('status', $user['status'] ?? '') == 'aktif') ? 'selected' : '' ?>>Aktif</option>
                                <option value="nonaktif" <?= (old('status', $user['status'] ?? '') == 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama" name="nama" 
                                   value="<?= old('nama', $user['nama'] ?? '') ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?= old('username', $user['username'] ?? '') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= old('email', $user['email'] ?? '') ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="no_hp">No. HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" 
                                   value="<?= old('no_hp', $user['no_hp'] ?? '') ?>" placeholder="Masukkan nomor HP">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="level">Level <span class="text-danger">*</span></label>
                            <select class="form-select" id="level" name="level" required>
                                <option value="owner" <?= (old('level', $user['level'] ?? '') == 'owner') ? 'selected' : '' ?>>Owner</option>
                                <option value="staff_gudang" <?= (old('level', $user['level'] ?? '') == 'staff_gudang') ? 'selected' : '' ?>>Staff Gudang</option>
                                <option value="admin" <?= (old('level', $user['level'] ?? '') == 'admin') ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="password">Password (Kosongkan jika tidak diubah)</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Minimal 4 karakter">
                            <small class="text-muted">Isi hanya jika ingin mengganti password</small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Perhatian:</strong> Jika password dikosongkan, maka password tidak akan berubah.
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="<?= base_url('/user') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-primary-custom {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        border: none;
        padding: 10px 25px;
        border-radius: 8px;
        color: white;
    }
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(30,60,114,0.3);
        color: white;
    }
    .form-group label {
        font-weight: 600;
        margin-bottom: 8px;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 10px 12px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #2a5298;
        box-shadow: 0 0 0 0.2rem rgba(42,82,152,0.25);
    }
</style>

<?= $this->include('layout/footer') ?>