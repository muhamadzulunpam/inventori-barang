<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Halaman index / daftar user
    public function index()
    {
        // Cek login dan level super admin
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        if (session()->get('level') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak!');
        }

        $data = [
            'title' => 'Manajemen User',
            'page_title' => 'Manajemen User',
            'active_menu' => 'user',
            'users' => $this->userModel->orderBy('created_at', 'DESC')->findAll(),
            'statistik' => $this->userModel->getStatistik()
        ];

        return view('user/index', $data);
    }

    // Form tambah user
    public function new()
    {
        // Cek login dan level super admin
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        if (session()->get('level') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak!');
        }

        $data = [
            'title' => 'Tambah User',
            'page_title' => 'Form Tambah User',
            'active_menu' => 'user',
            'id_user' => $this->userModel->generateIdUser()
        ];

        return view('user/create', $data);
    }

    // Proses simpan user
    public function create()
    {
        // Cek login dan level super admin
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        if (session()->get('level') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak!');
        }

        $rules = [
            'id_user' => 'required|is_unique[user.id_user]',
            'nama' => 'required|min_length[3]',
            'username' => 'required|min_length[3]|is_unique[user.username]',
            'email' => 'required|valid_email|is_unique[user.email]',
            'no_hp' => 'permit_empty|min_length[10]|max_length[15]',
            'level' => 'required|in_list[owner,admin,staff_gudang]',
            'password' => 'required|min_length[4]',
            'status' => 'required|in_list[aktif,nonaktif]'
        ];

        $messages = [
            'id_user' => [
                'required' => 'ID User wajib diisi',
                'is_unique' => 'ID User sudah ada'
            ],
            'nama' => [
                'required' => 'Nama lengkap wajib diisi',
                'min_length' => 'Nama lengkap minimal 3 karakter'
            ],
            'username' => [
                'required' => 'Username wajib diisi',
                'min_length' => 'Username minimal 3 karakter',
                'is_unique' => 'Username sudah digunakan'
            ],
            'email' => [
                'required' => 'Email wajib diisi',
                'valid_email' => 'Email tidak valid',
                'is_unique' => 'Email sudah terdaftar'
            ],
            'level' => [
                'required' => 'Level wajib dipilih'
            ],
            'password' => [
                'required' => 'Password wajib diisi',
                'min_length' => 'Password minimal 4 karakter'
            ],
            'status' => [
                'required' => 'Status wajib dipilih'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_user' => $this->userModel->generateIdUser(),
            'nama' => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'no_hp' => $this->request->getPost('no_hp'),
            'level' => $this->request->getPost('level'),
            'password' => $this->request->getPost('password'),
            'foto' => 'default.png',
            'status' => $this->request->getPost('status')
        ];

        if ($this->userModel->save($data)) {
            session()->setFlashdata('success', 'User berhasil ditambahkan');
            return redirect()->to('/user');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan user');
            return redirect()->back()->withInput();
        }
    }

    // Halaman detail user
    public function detail($id = null)
    {
        // Cek login dan level super admin
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        if (session()->get('level') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak!');
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan');
            return redirect()->to('/user');
        }

        $data = [
            'title' => 'Detail User',
            'page_title' => 'Detail User',
            'active_menu' => 'user',
            'user' => $user
        ];

        return view('user/detail', $data);
    }

    // Form edit user
    public function edit($id = null)
    {
        // Cek login dan level super admin
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        if (session()->get('level') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak!');
        }

        // Cari user berdasarkan ID
        $user = $this->userModel->find($id);
        
        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan');
            return redirect()->to('/user');
        }

        $data = [
            'title' => 'Edit User',
            'page_title' => 'Form Edit User',
            'active_menu' => 'user',
            'user' => $user
        ];

        return view('user/edit', $data);
    }

    // Proses update user
    public function update($id = null)
    {
        // Cek login dan level super admin
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        if (session()->get('level') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak!');
        }

        $userLama = $this->userModel->find($id);
        
        if (!$userLama) {
            session()->setFlashdata('error', 'User tidak ditemukan');
            return redirect()->to('/user');
        }

        // Ambil data dari form
        $nama = $this->request->getPost('nama');
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $no_hp = $this->request->getPost('no_hp');
        $level = $this->request->getPost('level');
        $status = $this->request->getPost('status');
        $password = $this->request->getPost('password');

        // Validasi sederhana
        if (empty($nama) || empty($username) || empty($email) || empty($level) || empty($status)) {
            session()->setFlashdata('error', 'Semua field wajib diisi!');
            return redirect()->back()->withInput();
        }

        // Cek unique username
        if ($username != $userLama['username']) {
            $cekUsername = $this->userModel->where('username', $username)->first();
            if ($cekUsername) {
                session()->setFlashdata('error', 'Username sudah digunakan!');
                return redirect()->back()->withInput();
            }
        }

        // Cek unique email
        if ($email != $userLama['email']) {
            $cekEmail = $this->userModel->where('email', $email)->first();
            if ($cekEmail) {
                session()->setFlashdata('error', 'Email sudah terdaftar!');
                return redirect()->back()->withInput();
            }
        }

        // Siapkan data update
        $updateData = [
            'nama' => $nama,
            'username' => $username,
            'email' => $email,
            'no_hp' => $no_hp,
            'level' => $level,
            'status' => $status
        ];

        // Update password jika diisi
        if (!empty($password)) {
            if (strlen($password) < 4) {
                session()->setFlashdata('error', 'Password minimal 4 karakter!');
                return redirect()->back()->withInput();
            }
            $updateData['password'] = md5($password);
        }

        // Update data menggunakan query builder langsung
        $db = \Config\Database::connect();
        $builder = $db->table('user');
        $builder->where('id_user', $id);
        
        if ($builder->update($updateData)) {
            session()->setFlashdata('success', 'User berhasil diupdate');
            return redirect()->to('/user');
        } else {
            session()->setFlashdata('error', 'Gagal mengupdate user');
            return redirect()->back()->withInput();
        }
    }
    // Halaman detail user (method show untuk resource route)
    public function show($id = null)
    {
        // Cek login dan level super admin
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        if (session()->get('level') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak!');
        }

        // Debug: cek ID yang diterima
        echo "ID yang dicari: " . $id;
        
        $user = $this->userModel->find($id);
        
        // Debug: cek hasil query
        echo "<pre>";
        print_r($user);
        echo "</pre>";
        die();
        
        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan');
            return redirect()->to('/user');
        }

        $data = [
            'title' => 'Detail User',
            'page_title' => 'Detail User',
            'active_menu' => 'user',
            'user' => $user
        ];

        return view('user/detail', $data);
    }

    // Proses hapus user
    public function delete($id = null)
    {
        // Cek login dan level super admin
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        if (session()->get('level') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak!');
        }

        // Cek apakah user ada
        $user = $this->userModel->find($id);
        
        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan');
            return redirect()->to('/user');
        }

        // Cek jangan sampai menghapus diri sendiri
        if ($id == session()->get('id_user')) {
            session()->setFlashdata('error', 'Anda tidak dapat menghapus akun sendiri!');
            return redirect()->to('/user');
        }

        // Hapus user
        if ($this->userModel->delete($id)) {
            session()->setFlashdata('success', 'User berhasil dihapus');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus user');
        }
        
        return redirect()->to('/user');
    }

    // Reset password user
    public function resetPassword($id = null)
    {
        // Cek login dan level super admin
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        if (session()->get('level') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak!');
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan');
            return redirect()->to('/user');
        }

        // Reset password ke default '123456'
        $newPassword = md5('123456');
        
        // Update manual
        $sql = "UPDATE user SET password = ? WHERE id_user = ?";
        $db = \Config\Database::connect();
        $query = $db->query($sql, [$newPassword, $id]);
        
        if ($db->affectedRows() > 0) {
            session()->setFlashdata('success', 'Password user berhasil direset menjadi 123456');
        } else {
            session()->setFlashdata('error', 'Gagal mereset password');
        }
        
        return redirect()->to('/user');
    }

    // Ubah status user (aktif/nonaktif)
    public function toggleStatus($id = null)
    {
        // Cek login dan level super admin
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        if (session()->get('level') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak!');
        }

        // Cari user
        $user = $this->userModel->find($id);
        
        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan');
            return redirect()->to('/user');
        }

        // Cek jangan sampai menonaktifkan diri sendiri
        if ($id == session()->get('id_user')) {
            session()->setFlashdata('error', 'Anda tidak dapat menonaktifkan akun sendiri!');
            return redirect()->to('/user');
        }

        // Tentukan status baru
        $status_baru = $user['status'] == 'aktif' ? 'nonaktif' : 'aktif';
        
        // Gunakan query builder langsung
        $db = \Config\Database::connect();
        $builder = $db->table('user');
        $builder->where('id_user', $id);
        
        if ($builder->update(['status' => $status_baru])) {
            session()->setFlashdata('success', 'Status user berhasil diubah menjadi ' . $status_baru);
        } else {
            session()->setFlashdata('error', 'Gagal mengubah status user');
        }
        
        return redirect()->to('/user');
    }
}