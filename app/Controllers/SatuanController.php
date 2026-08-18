<?php

namespace App\Controllers;

use App\Models\SatuanModel;
use App\Models\BarangModel;

class SatuanController extends BaseController
{
    protected $satuanModel;
    protected $barangModel;

    public function __construct()
    {
        $this->satuanModel = new SatuanModel();
        $this->barangModel = new BarangModel();
    }

    // Halaman index / daftar satuan
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Data Satuan',
            'page_title' => 'Data Satuan',
            'active_menu' => 'satuan',
            'satuan' => $this->satuanModel->getAllWithCount(),
            'statistik' => $this->satuanModel->getStatistik()
        ];

        return view('satuan/index', $data);
    }

    // Form tambah satuan
    public function new()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Tambah Satuan',
            'page_title' => 'Form Tambah Satuan',
            'active_menu' => 'satuan',
            'id_satuan'  => $this->satuanModel->generateIdSatuan()
        ];

        return view('satuan/create', $data);
    }

    // Proses simpan satuan
    public function create()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $rules = [
            'nama_satuan' => 'required|is_unique[satuan.nama_satuan]',
            'keterangan' => 'permit_empty'
        ];

        $messages = [
            'nama_satuan' => [
                'required' => 'Nama satuan wajib diisi',
                'is_unique' => 'Nama satuan sudah ada'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
        'id_satuan'   => $this->satuanModel->generateIdSatuan(),
        'nama_satuan' => $this->request->getPost('nama_satuan'),
        'keterangan'  => $this->request->getPost('keterangan')
    ];

        if ($this->satuanModel->save($data)) {
            session()->setFlashdata('success', 'Satuan berhasil ditambahkan');
            return redirect()->to('/satuan');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan satuan');
            return redirect()->back()->withInput();
        }
    }

    // Halaman detail satuan
    public function detail($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $satuan = $this->satuanModel->find($id);
        
        if (!$satuan) {
            session()->setFlashdata('error', 'Satuan tidak ditemukan');
            return redirect()->to('/satuan');
        }

        // Ambil daftar barang dengan satuan ini
        $barangModel = new \App\Models\BarangModel();
        $daftar_barang = $barangModel->where('id_satuan', $id)->findAll();
        
        // Hitung total stok
        $total_stok = 0;
        foreach ($daftar_barang as $item) {
            $total_stok += $item['stok'];
        }

        $data = [
            'title' => 'Detail Satuan',
            'page_title' => 'Detail Satuan',
            'active_menu' => 'satuan',
            'satuan' => $satuan,
            'daftar_barang' => $daftar_barang,
            'total_barang' => count($daftar_barang),
            'total_stok' => $total_stok
        ];

        return view('satuan/detail', $data);
    }

    // Form edit satuan
    public function edit($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $satuan = $this->satuanModel->find($id);
        
        if (!$satuan) {
            session()->setFlashdata('error', 'Satuan tidak ditemukan');
            return redirect()->to('/satuan');
        }

        $data = [
            'title' => 'Edit Satuan',
            'page_title' => 'Form Edit Satuan',
            'active_menu' => 'satuan',
            'satuan' => $satuan
        ];

        return view('satuan/edit', $data);
    }

    // Proses update satuan
    public function update($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $satuanLama = $this->satuanModel->find($id);
        
        if (!$satuanLama) {
            session()->setFlashdata('error', 'Satuan tidak ditemukan');
            return redirect()->to('/satuan');
        }

        $rules = [
            'nama_satuan' => 'required',
            'keterangan' => 'permit_empty'
        ];

        // Jika nama satuan diubah, cek unique
        if ($this->request->getPost('nama_satuan') != $satuanLama['nama_satuan']) {
            $rules['nama_satuan'] = 'required|is_unique[satuan.nama_satuan]';
        }

        $messages = [
            'nama_satuan' => [
                'required' => 'Nama satuan wajib diisi',
                'is_unique' => 'Nama satuan sudah ada'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_satuan' => $this->request->getPost('nama_satuan'),
            'keterangan' => $this->request->getPost('keterangan')
        ];

        if ($this->satuanModel->update($id, $data)) {
            session()->setFlashdata('success', 'Satuan berhasil diupdate');
            return redirect()->to('/satuan');
        } else {
            session()->setFlashdata('error', 'Gagal mengupdate satuan');
            return redirect()->back()->withInput();
        }
    }

    // Proses hapus satuan
    public function delete($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $satuan = $this->satuanModel->find($id);
        
        if (!$satuan) {
            session()->setFlashdata('error', 'Satuan tidak ditemukan');
            return redirect()->to('/satuan');
        }

        // Cek apakah satuan sudah memiliki barang
        $hasBarang = $this->barangModel->where('id_satuan', $id)->countAllResults();
        
        if ($hasBarang > 0) {
            session()->setFlashdata('error', 'Satuan tidak dapat dihapus karena masih memiliki ' . $hasBarang . ' barang!');
            return redirect()->to('/satuan');
        }

        if ($this->satuanModel->delete($id)) {
            session()->setFlashdata('success', 'Satuan berhasil dihapus');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus satuan');
        }
        
        return redirect()->to('/satuan');
    }
    public function print()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $satuan = $this->satuanModel->findAll();
        
        $data = [
            'title' => 'Laporan Data Satuan',
            'satuan' => $satuan,
            'tanggal_cetak' => date('d/m/Y H:i:s'),
            'total_satuan' => count($satuan)
        ];
        
        return view('satuan/print', $data);
    }
}