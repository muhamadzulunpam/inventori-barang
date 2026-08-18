<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id_user';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_user',
        'nama',
        'username',
        'email',
        'no_hp',
        'level',
        'password',
        'foto',
        'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = null;

    protected $validationRules = [
        'id_user' => 'required|is_unique[user.id_user]',
        'username' => 'required|is_unique[user.username]|min_length[3]',
        'nama' => 'required|min_length[3]',
        'email' => 'required|valid_email|is_unique[user.email]',
        'no_hp' => 'permit_empty|min_length[10]|max_length[15]',
        'level' => 'required|in_list[admin,owner,staff_gudang]',
        'password' => 'required|min_length[4]',
        'status' => 'required|in_list[aktif,nonaktif]'
    ];

    protected $validationMessages = [
        'id_user' => [
            'required' => 'ID User wajib diisi',
            'is_unique' => 'ID User sudah digunakan'
        ],
        'username' => [
            'required' => 'Username wajib diisi',
            'is_unique' => 'Username sudah digunakan',
            'min_length' => 'Username minimal 3 karakter'
        ],
        'nama' => [
            'required' => 'Nama lengkap wajib diisi',
            'min_length' => 'Nama lengkap minimal 3 karakter'
        ],
        'email' => [
            'required' => 'Email wajib diisi',
            'valid_email' => 'Email tidak valid',
            'is_unique' => 'Email sudah terdaftar'
        ],
        'no_hp' => [
            'min_length' => 'Nomor HP minimal 10 digit',
            'max_length' => 'Nomor HP maksimal 15 digit'
        ],
        'level' => [
            'required' => 'Level wajib dipilih',
            'in_list' => 'Level tidak valid'
        ],
        'password' => [
            'required' => 'Password wajib diisi',
            'min_length' => 'Password minimal 4 karakter'
        ],
        'status' => [
            'required' => 'Status wajib dipilih',
            'in_list' => 'Status tidak valid'
        ]
    ];

    // Hash password sebelum insert
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = md5($data['data']['password']);
        }
        return $data;
    }

    // Untuk login
    public function login($username, $password)
    {
        return $this->where('username', $username)
                    ->where('password', md5($password))
                    ->where('status', 'aktif')
                    ->first();
    }

    // Generate ID User otomatis
    public function generateIdUser()
    {
        $prefix = '7';
        $tahun  = date('y');
        $bulan  = date('m');

        $kodePrefix = $prefix . $tahun . $bulan;

        $lastUser = $this->like('id_user', $kodePrefix, 'after')
                        ->orderBy('id_user', 'DESC')
                        ->first();

        if ($lastUser) {
            $lastNumber = (int) substr((string)$lastUser['id_user'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return (int) ($kodePrefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT));
    }

    // Cari user
    public function search($keyword)
    {
        return $this->like('username', $keyword)
                    ->orLike('nama', $keyword)
                    ->orLike('email', $keyword)
                    ->orLike('level', $keyword)
                    ->findAll();
    }

    // Get user by level
    public function getByLevel($level)
    {
        return $this->where('level', $level)
                    ->where('status', 'aktif')
                    ->orderBy('nama', 'ASC')
                    ->findAll();
    }

    // Get user aktif
    public function getAktif()
    {
        return $this->where('status', 'aktif')
                    ->orderBy('nama', 'ASC')
                    ->findAll();
    }

    // Get user nonaktif
    public function getNonaktif()
    {
        return $this->where('status', 'nonaktif')
                    ->orderBy('nama', 'ASC')
                    ->findAll();
    }

    // Get statistik user
    public function getStatistik()
    {
        $total = $this->countAll();
        $aktif = $this->where('status', 'aktif')->countAllResults();
        $nonaktif = $this->where('status', 'nonaktif')->countAllResults();
        $admin = $this->where('level', 'admin')->countAllResults();
        $owner = $this->where('level', 'owner')->countAllResults();
        $staff_gudang = $this->where('level', 'staff_gudang')->countAllResults();

        return [
            'total' => $total,
            'aktif' => $aktif,
            'nonaktif' => $nonaktif,
            'admin' => $admin,
            'owner' => $owner,
            'staff_gudang' => $staff_gudang
        ];
    }

    // Update status user
    public function updateStatus($id_user, $status)
    {
        return $this->update($id_user, ['status' => $status]);
    }

    // Reset password
    public function resetPassword($id_user, $password_baru)
    {
        return $this->update($id_user, ['password' => md5($password_baru)]);
    }

    // Cek username exists
    public function usernameExists($username, $exceptId = null)
    {
        $builder = $this->where('username', $username);
        if ($exceptId) {
            $builder->where('id_user !=', $exceptId);
        }
        return $builder->countAllResults() > 0;
    }

    // Cek email exists
    public function emailExists($email, $exceptId = null)
    {
        $builder = $this->where('email', $email);
        if ($exceptId) {
            $builder->where('id_user !=', $exceptId);
        }
        return $builder->countAllResults() > 0;
    }

    // Get user dengan pagination
    public function getPaginated($limit = 10, $offset = 0)
    {
        return $this->orderBy('created_at', 'DESC')
                    ->findAll($limit, $offset);
    }
}