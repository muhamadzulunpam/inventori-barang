<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class StaffGudang implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $level = session()->get('level');
        
        // Staff gudang hanya bisa akses barang keluar
        if ($level == 'staff_gudang') {
            $currentUri = $request->getUri()->getPath();
            
            // Staff gudang hanya boleh akses barang_keluar dan dashboard
            $allowedUris = ['barang_keluar', 'dashboard', 'auth/logout'];
            
            $isAllowed = false;
            foreach ($allowedUris as $uri) {
                if (strpos($currentUri, $uri) !== false) {
                    $isAllowed = true;
                    break;
                }
            }
            
            if (!$isAllowed) {
                return redirect()->to('/dashboard')->with('error', 'Akses ditolak! Anda hanya dapat mengakses halaman Barang Keluar.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}