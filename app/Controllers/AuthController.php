<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function index()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        
        $data = [
            'title' => 'Login - Sistem Inventori'
        ];
        
        return view('auth/login', $data);
    }

    public function login()
    {
        $session = session();
        $model = new UserModel();
        
        // Validasi input
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        
        if (empty($username) || empty($password)) {
            $session->setFlashdata('msg', 'Username dan password harus diisi!');
            return redirect()->to('/login');
        }
        
        // Cari user
        $data = $model->where('username', $username)->first();
        
        if ($data) {
            // Verifikasi password (MD5)
            if (md5($password) == $data['password']) {
                // Cek status user (aktif/nonaktif)
                if ($data['status'] == 'nonaktif') {
                    $session->setFlashdata('msg', 'Akun Anda sedang nonaktif! Hubungi administrator.');
                    return redirect()->to('/login');
                }
                
                // Tentukan level display name
                $levelDisplay = '';
                switch ($data['level']) {
                    case 'admin':
                        $levelDisplay = 'Administrator';
                        break;
                    case 'owner':
                        $levelDisplay = 'owner';
                        break;
                    case 'staff_gudang':
                        $levelDisplay = 'Staff Gudang';
                        break;
                    default:
                        $levelDisplay = 'User';
                }
                
                $ses_data = [
                    'id_user'       => $data['id_user'],
                    'username'      => $data['username'],
                    'nama'          => $data['nama'],
                    'email'         => $data['email'],
                    'no_hp'         => $data['no_hp'],
                    'level'         => $data['level'],
                    'level_display' => $levelDisplay,
                    'foto'          => $data['foto'],
                    'status'        => $data['status'],
                    'logged_in'     => TRUE
                ];
                $session->set($ses_data);
                
                // Log success
                log_message('info', 'User ' . $username . ' login success with level: ' . $data['level']);
                
                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('msg', 'Password salah!');
                log_message('error', 'User ' . $username . ' wrong password');
            }
        } else {
            $session->setFlashdata('msg', 'Username tidak ditemukan!');
            log_message('error', 'Username ' . $username . ' not found');
        }
        
        return redirect()->to('/login');
    }

    public function logout()
    {
        $session = session();
        $username = $session->get('username');
        
        $session->destroy();
        
        log_message('info', 'User ' . $username . ' logged out');
        
        session()->setFlashdata('success', 'Anda telah berhasil logout');
        
        return redirect()->to('/login');
    }
}