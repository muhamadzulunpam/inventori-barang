<?php

namespace App\Controllers;

use App\Models\JenisBarangModel;
use App\Models\BarangModel;

class JenisController extends BaseController
{
    protected $jenisModel;
    protected $barangModel;

    public function __construct()
    {
        $this->jenisModel = new JenisBarangModel();
        $this->barangModel = new BarangModel();
    }

    // Halaman index / daftar jenis
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Data Jenis Barang',
            'page_title' => 'Data Jenis Barang',
            'active_menu' => 'jenis',
            'jenis' => $this->jenisModel->getAllWithCount(),
            'statistik' => $this->jenisModel->getStatistik()
        ];

        return view('jenis/index', $data);
    }

    // Form tambah jenis
    public function new()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Tambah Jenis Barang',
            'page_title' => 'Form Tambah Jenis Barang',
            'active_menu' => 'jenis',
            'id_jenis' => $this->jenisModel->generateIdJenis()
        ];

        return view('jenis/create', $data);
    }

    // Proses simpan jenis
    public function create()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $rules = [
            'nama_jenis' => 'required|is_unique[jenis.nama_jenis]',
            'keterangan' => 'permit_empty'
        ];

        $messages = [
            'nama_jenis' => [
                'required' => 'Nama jenis barang wajib diisi',
                'is_unique' => 'Nama jenis barang sudah ada'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_jenis'   => $this->jenisModel->generateIdJenis(),
            'nama_jenis' => $this->request->getPost('nama_jenis'),
            'keterangan' => $this->request->getPost('keterangan')
        ];

        if ($this->jenisModel->save($data)) {
            session()->setFlashdata('success', 'Jenis barang berhasil ditambahkan');
            return redirect()->to('/jenis');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan jenis barang');
            return redirect()->back()->withInput();
        }
    }

    // Halaman detail jenis
    public function detail($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $jenis = $this->jenisModel->find($id);
        
        if (!$jenis) {
            session()->setFlashdata('error', 'Jenis barang tidak ditemukan');
            return redirect()->to('/jenis');
        }

        // Ambil barang berdasarkan jenis ini
        $barang = $this->barangModel
            ->select('barang.*, satuan.nama_satuan')
            ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
            ->where('id_jenis', $id)
            ->orderBy('nama_barang', 'ASC')
            ->findAll();

        $data = [
            'title' => 'Detail Jenis Barang',
            'page_title' => 'Detail Jenis Barang',
            'active_menu' => 'jenis',
            'jenis' => $jenis,
            'barang' => $barang,
            'total_barang' => count($barang),
            'total_stok' => $this->barangModel->where('id_jenis', $id)->selectSum('stok')->first()['stok'] ?? 0
        ];

        return view('jenis/detail', $data);
    }

    // Form edit jenis
    public function edit($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $jenis = $this->jenisModel->find($id);
        
        if (!$jenis) {
            session()->setFlashdata('error', 'Jenis barang tidak ditemukan');
            return redirect()->to('/jenis');
        }

        $data = [
            'title' => 'Edit Jenis Barang',
            'page_title' => 'Form Edit Jenis Barang',
            'active_menu' => 'jenis',
            'jenis' => $jenis
        ];

        return view('jenis/edit', $data);
    }

    // Proses update jenis
    public function update($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $jenisLama = $this->jenisModel->find($id);
        
        if (!$jenisLama) {
            session()->setFlashdata('error', 'Jenis barang tidak ditemukan');
            return redirect()->to('/jenis');
        }

        $rules = [
            'nama_jenis' => 'required',
            'keterangan' => 'permit_empty'
        ];

        // Jika nama jenis diubah, cek unique
        if ($this->request->getPost('nama_jenis') != $jenisLama['nama_jenis']) {
            $rules['nama_jenis'] = 'required|is_unique[jenis.nama_jenis]';
        }

        $messages = [
            'nama_jenis' => [
                'required' => 'Nama jenis barang wajib diisi',
                'is_unique' => 'Nama jenis barang sudah ada'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_jenis' => $this->request->getPost('nama_jenis'),
            'keterangan' => $this->request->getPost('keterangan')
        ];

        if ($this->jenisModel->update($id, $data)) {
            session()->setFlashdata('success', 'Jenis barang berhasil diupdate');
            return redirect()->to('/jenis');
        } else {
            session()->setFlashdata('error', 'Gagal mengupdate jenis barang');
            return redirect()->back()->withInput();
        }
    }

    // Proses hapus jenis
    public function delete($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Cek apakah jenis memiliki barang
        $barangModel = new \App\Models\BarangModel();
        $barangCount = $barangModel->where('id_jenis', $id)->countAllResults();
        
        if ($barangCount > 0) {
            session()->setFlashdata('error', 'Tidak dapat menghapus jenis barang karena masih memiliki ' . $barangCount . ' data barang!');
            return redirect()->to('/jenis');
        }

        $jenis = $this->jenisModel->find($id);
        
        if (!$jenis) {
            session()->setFlashdata('error', 'Data jenis barang tidak ditemukan');
            return redirect()->to('/jenis');
        }
        
        if ($this->jenisModel->delete($id)) {
            session()->setFlashdata('success', 'Data jenis barang berhasil dihapus');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus data jenis barang');
        }
        
        return redirect()->to('/jenis');
    }
     public function print()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $jenis = $this->jenisModel->findAll();
        
        $data = [
            'title' => 'Laporan Data Jenis Barang',
            'jenis' => $jenis,
            'tanggal_cetak' => date('d/m/Y H:i:s'),
            'total_jenis' => count($jenis)
        ];
        
        return view('jenis/print', $data);
    }
    
}