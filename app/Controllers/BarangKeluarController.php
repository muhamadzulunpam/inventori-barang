<?php

namespace App\Controllers;

use App\Models\BarangKeluarModel;
use App\Models\BarangModel;

class BarangKeluarController extends BaseController
{
    protected $barangKeluarModel;
    protected $barangModel;

    public function __construct()
    {
        $this->barangKeluarModel = new BarangKeluarModel();
        $this->barangModel = new BarangModel();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Ambil filter dari GET request
        $filter = [
            'bulan' => $this->request->getGet('bulan'),
            'tahun' => $this->request->getGet('tahun'),
            'search' => $this->request->getGet('search')
        ];
        
        // Hapus filter yang kosong
        $filter = array_filter($filter);

        // Ambil data dengan filter (gunakan method yang sudah ada filter)
        $barang_keluar = $this->barangKeluarModel->getBarangKeluarWithRelationFilter($filter);

        $data = [
            'title' => 'Barang Keluar',
            'page_title' => 'Data Barang Keluar',
            'active_menu' => 'barang_keluar',
            'barang_keluar' => $barang_keluar,
            'total_keluar' => $this->barangKeluarModel->selectSum('jumlah_keluar')->first()['jumlah_keluar'] ?? 0,
            'total_transaksi' => $this->barangKeluarModel->countAll(),
            'filter' => $filter,  // Kirim filter ke view untuk mempertahankan nilai
            'level' => session()->get('level')
        ];

        return view('barang_keluar/index', $data);
    }
    
    // Form tambah barang keluar
    public function create()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // PERBAIKAN: Gunakan query builder dengan join ke satuan
        $barang = $this->barangModel->db->table('barang')
            ->select('barang.*, satuan.nama_satuan')
            ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
            ->where('barang.stok >', 0)
            ->orderBy('barang.nama_barang', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Tambah Barang Keluar',
            'page_title' => 'Form Tambah Barang Keluar',
            'active_menu' => 'barang_keluar',
            'barang' => $barang,
            'level' => session()->get('level'),
            'id_barang_keluar' => $this->barangKeluarModel->generateIdBarangKeluar()
        ];

        return view('barang_keluar/create', $data);
    }

    // Proses simpan barang keluar
    public function store()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $rules = [
            'id_barang' => 'required',
            'jumlah_keluar' => 'required|numeric|greater_than[0]',
            'tanggal_keluar' => 'required|valid_date'
        ];

        $messages = [
            'id_barang' => ['required' => 'Barang wajib dipilih'],
            'jumlah_keluar' => [
                'required' => 'Jumlah wajib diisi',
                'numeric' => 'Jumlah harus berupa angka',
                'greater_than' => 'Jumlah harus lebih dari 0'
            ],
            'tanggal_keluar' => [
                'required' => 'Tanggal wajib diisi',
                'valid_date' => 'Tanggal tidak valid'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $id_barang = $this->request->getPost('id_barang');
        $jumlah = $this->request->getPost('jumlah_keluar');

        // Cek stok barang
        $barang = $this->barangModel->find($id_barang);
        if (!$barang) {
            session()->setFlashdata('error', 'Barang tidak ditemukan');
            return redirect()->back()->withInput();
        }

        if ($barang['stok'] < $jumlah) {
            session()->setFlashdata('error', 'Stok tidak mencukupi! Stok tersedia: ' . $barang['stok']);
            return redirect()->back()->withInput();
        }

        $data = [
            'id_barang_keluar' => $this->barangKeluarModel->generateIdBarangKeluar(),
            'id_barang' => $id_barang,
            'id_user' => session()->get('id_user'),
            'jumlah_keluar' => $jumlah,
            'tanggal_keluar' => $this->request->getPost('tanggal_keluar'),
            'keterangan' => $this->request->getPost('keterangan')
        ];

        // Debug: log data yang akan disimpan
        log_message('debug', 'Data yang akan disimpan: ' . json_encode($data));

        if ($this->barangKeluarModel->insert($data)) {
            // Update stok barang
            $stok_baru = $barang['stok'] - $jumlah;
            $this->barangModel->update($id_barang, ['stok' => $stok_baru]);
            
            session()->setFlashdata('success', 'Data barang keluar berhasil disimpan');
            return redirect()->to('/barang_keluar');
        } else {
            // Debug error
            $errors = $this->barangKeluarModel->errors();
            log_message('error', 'Error insert: ' . json_encode($errors));
            session()->setFlashdata('error', 'Gagal menyimpan data: ' . json_encode($errors));
            return redirect()->back()->withInput();
        }
    }

    // Detail barang keluar
    public function detail($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $barang_keluar = $this->barangKeluarModel->getBarangKeluarWithRelations($id);
        
        if (!$barang_keluar) {
            session()->setFlashdata('error', 'Data tidak ditemukan untuk ID: ' . $id);
            return redirect()->to('/barang_keluar');
        }

        $data = [
            'title' => 'Detail Barang Keluar',
            'page_title' => 'Detail Barang Keluar',
            'active_menu' => 'barang_keluar',
            'barang_keluar' => $barang_keluar,
            'level' => session()->get('level')
        ];

        return view('barang_keluar/detail', $data);
    }


    // Form edit barang keluar (hanya admin & super admin)
    public function edit($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('level') == 'staff_gudang') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk mengedit data!');
            return redirect()->to('/barang_keluar');
        }

        $barang_keluar = $this->barangKeluarModel->find($id);
        
        if (!$barang_keluar) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/barang_keluar');
        }

        // PERBAIKAN: Ambil semua barang dengan join satuan
        $barang = $this->barangModel->db->table('barang')
            ->select('barang.*, satuan.nama_satuan')
            ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
            ->orderBy('barang.nama_barang', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Edit Barang Keluar',
            'page_title' => 'Form Edit Barang Keluar',
            'active_menu' => 'barang_keluar',
            'barang_keluar' => $barang_keluar,
            'barang' => $barang,
            'level' => session()->get('level')
        ];

        return view('barang_keluar/edit', $data);
    }

    // Proses update barang keluar (hanya admin & super admin)
    public function update($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('level') == 'staff_gudang') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk mengupdate data!');
            return redirect()->to('/barang_keluar');
        }

        $oldData = $this->barangKeluarModel->find($id);
        
        if (!$oldData) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/barang_keluar');
        }

        $rules = [
            'id_barang' => 'required',
            'jumlah_keluar' => 'required|numeric|greater_than[0]',
            'tanggal_keluar' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $newData = [
            'id_barang' => $this->request->getPost('id_barang'),
            'jumlah_keluar' => $this->request->getPost('jumlah_keluar'),
            'tanggal_keluar' => $this->request->getPost('tanggal_keluar'),
            'keterangan' => $this->request->getPost('keterangan')
        ];

        // Update stok
        $barangLama = $this->barangModel->find($oldData['id_barang']);
        $barangBaru = $this->barangModel->find($newData['id_barang']);
        
        if ($barangLama && $barangBaru) {
            // Kembalikan stok lama
            $stok_lama_baru = $barangLama['stok'] + $oldData['jumlah_keluar'];
            $this->barangModel->update($oldData['id_barang'], ['stok' => $stok_lama_baru]);
            
            // Kurangi stok baru
            $stok_baru_baru = $barangBaru['stok'] - $newData['jumlah_keluar'];
            $this->barangModel->update($newData['id_barang'], ['stok' => $stok_baru_baru]);
        }

        if ($this->barangKeluarModel->update($id, $newData)) {
            session()->setFlashdata('success', 'Data berhasil diupdate');
            return redirect()->to('/barang_keluar');
        } else {
            session()->setFlashdata('error', 'Gagal mengupdate数据');
            return redirect()->back()->withInput();
        }
    }

    // Hapus barang keluar (hanya admin & super admin)
    public function delete($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('level') == 'staff_gudang') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk menghapus data!');
            return redirect()->to('/barang_keluar');
        }

        $barang_keluar = $this->barangKeluarModel->find($id);
        
        if (!$barang_keluar) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/barang_keluar');
        }

        // Kembalikan stok barang
        $barang = $this->barangModel->find($barang_keluar['id_barang']);
        if ($barang) {
            $stok_baru = $barang['stok'] + $barang_keluar['jumlah_keluar'];
            $this->barangModel->update($barang_keluar['id_barang'], ['stok' => $stok_baru]);
        }

        if ($this->barangKeluarModel->delete($id)) {
            session()->setFlashdata('success', 'Data berhasil dihapus');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus data');
        }
        
        return redirect()->to('/barang_keluar');
    }

    // Export ke Excel
    public function exportExcel()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $barang_keluar = $this->barangKeluarModel->getBarangKeluarWithRelations();
        
        $filename = 'laporan_barang_keluar_' . date('Ymd_His') . '.xls';
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        
        echo "<table border='1'>";
        echo "<tr><th colspan='8' style='text-align:center;'>LAPORAN BARANG KELUAR</th></tr>";
        echo "<tr><th colspan='8' style='text-align:center;'>Tanggal Cetak: " . date('d/m/Y H:i:s') . "</th></tr>";
        echo "<tr><th>No</th><th>ID Transaksi</th><th>Tanggal Keluar</th><th>Nama Barang</th><th>Warna</th><th>Jumlah</th><th>Satuan</th><th>Diinput Oleh</th></tr>";
        
        $no = 1;
        foreach ($barang_keluar as $item) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $item['id_barang_keluar'] . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($item['tanggal_keluar'])) . "</td>";
            echo "<td>" . $item['nama_barang'] . "</td>";
            echo "<td>" . ($item['warna'] ?? '-') . "</td>";
            echo "<td>" . number_format($item['jumlah_keluar']) . "</td>";
            echo "<td>" . ($item['nama_satuan'] ?? '-') . "</td>";
            echo "<td>" . ($item['nama_user'] ?? '-') . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        exit();
    }

    // Print laporan
    public function print()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $barang_keluar = $this->barangKeluarModel->getBarangKeluarWithRelations();
        
        $data = [
            'title' => 'Laporan Barang Keluar',
            'barang_keluar' => $barang_keluar,
            'tanggal_cetak' => date('d/m/Y H:i:s'),
        ];
        
        return view('barang_keluar/print', $data);
    }
}