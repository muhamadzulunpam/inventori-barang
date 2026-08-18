<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\SatuanModel;
use App\Models\JenisBarangModel;

class BarangController extends BaseController
{
    protected $barangModel;
    protected $satuanModel;
    protected $jenisBarangModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
        $this->satuanModel = new SatuanModel();
        $this->jenisBarangModel = new JenisBarangModel();
    }

    public function index()
{
    if (!session()->get('logged_in')) {
        return redirect()->to('/login');
    }

    // Ambil filter
    $search = $this->request->getGet('search');
    $stok   = $this->request->getGet('stok');

    // Query builder
    $builder = $this->barangModel
        ->select('barang.*, jenis.nama_jenis, satuan.nama_satuan,
            (IFNULL(bm.total_masuk,0) + IFNULL(bk.total_keluar,0)) as total_transaksi
        ')
        ->join('jenis', 'jenis.id_jenis = barang.id_jenis', 'left')
        ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')

        // subquery barang masuk
        ->join('(SELECT id_barang, COUNT(*) as total_masuk FROM barang_masuk GROUP BY id_barang) bm',
            'bm.id_barang = barang.id_barang', 'left')

        // subquery barang keluar
        ->join('(SELECT id_barang, COUNT(*) as total_keluar FROM barang_keluar GROUP BY id_barang) bk',
            'bk.id_barang = barang.id_barang', 'left');

    // Filter pencarian
    if (!empty($search)) {
        $builder->groupStart()
            ->like('barang.id_barang', $search)
            ->orLike('barang.nama_barang', $search)
            ->orLike('barang.warna', $search)
            ->groupEnd();
    }

    // Filter stok
    if ($stok == 'menipis') {
        $builder->where('stok <=', 10)
                ->where('stok >', 0);
    }

    if ($stok == 'habis') {
        $builder->where('stok', 0);
    }

    if ($stok == 'tersedia') {
        $builder->where('stok >', 10);
    }

    // Ambil data hasil filter
    $barang = $builder->findAll();

    // Statistik
    $semuaBarang = $this->barangModel->findAll();

    $totalStok = array_sum(array_column($semuaBarang, 'stok'));

    $stokMenipis = count(array_filter($semuaBarang, function($item) {
        return $item['stok'] <= 10 && $item['stok'] > 0;
    }));

    $stokHabis = count(array_filter($semuaBarang, function($item) {
        return $item['stok'] == 0;
    }));

    $data = [
        'title' => 'Data Barang',
        'page_title' => 'Data Barang',
        'active_menu' => 'barang',
        'barang' => $barang,
        'level' => session()->get('level'),
        'statistik' => [
            'total_barang' => count($semuaBarang),
            'total_stok' => $totalStok,
            'stok_menipis' => $stokMenipis,
            'stok_habis' => $stokHabis
        ]
    ];

    return view('barang/index', $data);
}
    // Form tambah barang
    public function new()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Staff gudang tidak bisa tambah barang
        if (session()->get('level') == 'staff_gudang') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk menambah data barang!');
            return redirect()->to('/barang');
        }

        $data = [
            'title' => 'Tambah Barang',
            'page_title' => 'Form Tambah Barang',
            'active_menu' => 'barang',
            'id_barang' => $this->barangModel->generateKodeBarang(),
            'satuan' => $this->satuanModel->findAll(),
            'jenis' => $this->jenisBarangModel->findAll(),
            'level' => session()->get('level')
        ];

        return view('barang/create', $data);
    }

    // Proses simpan barang
    public function create()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('level') == 'staff_gudang') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk menambah data barang!');
            return redirect()->to('/barang');
        }

        $rules = [
            'id_barang' => 'required|is_unique[barang.id_barang]',
            'nama_barang' => 'required',
            'id_satuan' => 'required',
            'id_jenis' => 'required',
            'stok' => 'required|numeric|greater_than_equal_to[0]',
            'min_stok' => 'permit_empty|numeric|greater_than_equal_to[0]',
            'harga' => 'permit_empty|numeric|greater_than_equal_to[0]'
        ];

        $messages = [
            'id_barang' => [
                'required' => 'ID Barang wajib diisi',
                'is_unique' => 'ID Barang sudah ada'
            ],
            'nama_barang' => [
                'required' => 'Nama barang wajib diisi'
            ],
            'id_satuan' => [
                'required' => 'Satuan wajib dipilih'
            ],
            'id_jenis' => [
                'required' => 'Jenis barang wajib dipilih'
            ],
            'stok' => [
                'required' => 'Stok wajib diisi',
                'numeric' => 'Stok harus berupa angka',
                'greater_than_equal_to' => 'Stok tidak boleh negatif'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Upload foto
        $foto = $this->uploadFoto();

        $data = [
            'id_barang' => $this->request->getPost('id_barang'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'warna' => $this->request->getPost('warna'),
            'stok' => $this->request->getPost('stok'),
            'min_stok' => 11,
            'harga' => $this->request->getPost('harga') ?: 0,
            'id_satuan' => $this->request->getPost('id_satuan'),
            'id_jenis' => $this->request->getPost('id_jenis'),
            'foto' => $foto
        ];

        if ($this->barangModel->insert($data)) {
            session()->setFlashdata('success', 'Data barang berhasil ditambahkan');
            return redirect()->to('/barang');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan data barang: ' . json_encode($this->barangModel->errors()));
            return redirect()->back()->withInput();
        }
    }

    // Halaman edit barang
    public function edit($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('level') == 'staff_gudang') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk mengedit data barang!');
            return redirect()->to('/barang');
        }

        $barang = $this->barangModel->find($id);
        
        if (!$barang) {
            session()->setFlashdata('error', 'Data barang tidak ditemukan');
            return redirect()->to('/barang');
        }

        $data = [
            'title' => 'Edit Barang',
            'page_title' => 'Form Edit Barang',
            'active_menu' => 'barang',
            'barang' => $barang,
            'satuan' => $this->satuanModel->findAll(),
            'jenis' => $this->jenisBarangModel->findAll(),
            'level' => session()->get('level')
        ];

        return view('barang/edit', $data);
    }

    // Proses update barang
    public function update($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('level') == 'staff_gudang') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk mengedit data barang!');
            return redirect()->to('/barang');
        }

        $barangLama = $this->barangModel->find($id);
        
        if (!$barangLama) {
            session()->setFlashdata('error', 'Data barang tidak ditemukan');
            return redirect()->to('/barang');
        }

        $rules = [
            'nama_barang' => 'required',
            'id_satuan' => 'required',
            'id_jenis' => 'required',
            'stok' => 'required|numeric|greater_than_equal_to[0]',
            'min_stok' => 'permit_empty|numeric|greater_than_equal_to[0]',
            'harga' => 'permit_empty|numeric|greater_than_equal_to[0]'
        ];

        // Jika ID barang diubah, cek unique
        if ($this->request->getPost('id_barang') != $barangLama['id_barang']) {
            $rules['id_barang'] = 'required|is_unique[barang.id_barang]';
        }

        $messages = [
            'id_barang' => [
                'required' => 'ID Barang wajib diisi',
                'is_unique' => 'ID Barang sudah ada'
            ],
            'nama_barang' => [
                'required' => 'Nama barang wajib diisi'
            ],
            'id_satuan' => [
                'required' => 'Satuan wajib dipilih'
            ],
            'id_jenis' => [
                'required' => 'Jenis barang wajib dipilih'
            ],
            'stok' => [
                'required' => 'Stok wajib diisi',
                'numeric' => 'Stok harus berupa angka',
                'greater_than_equal_to' => 'Stok tidak boleh negatif'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Upload foto baru jika ada
        $foto = $barangLama['foto'];
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            // Hapus foto lama
            if ($foto && $foto != 'default.png' && file_exists('uploads/barang/' . $foto)) {
                unlink('uploads/barang/' . $foto);
            }
            $foto = $this->uploadFoto();
        }

        $data = [
            'id_barang' => $this->request->getPost('id_barang'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'warna' => $this->request->getPost('warna'),
            'stok' => $this->request->getPost('stok'),
            'min_stok' => 11,
            'harga' => $this->request->getPost('harga') ?: 0,
            'id_satuan' => $this->request->getPost('id_satuan'),
            'id_jenis' => $this->request->getPost('id_jenis'),
            'foto' => $foto
        ];

        if ($this->barangModel->update($id, $data)) {
            session()->setFlashdata('success', 'Data barang berhasil diupdate');
            return redirect()->to('/barang');
        } else {
            session()->setFlashdata('error', 'Gagal mengupdate data barang');
            return redirect()->back()->withInput();
        }
    }

    // Hapus barang
    public function delete($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('level') == 'staff_gudang') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk menghapus data barang!');
            return redirect()->to('/barang');
        }

        // Cek apakah barang memiliki transaksi
        $barangMasukModel = new \App\Models\BarangMasukModel();
        $barangKeluarModel = new \App\Models\BarangKeluarModel();
        
        $transaksiMasuk = $barangMasukModel->where('id_barang', $id)->countAllResults();
        $transaksiKeluar = $barangKeluarModel->where('id_barang', $id)->countAllResults();
        
        if ($transaksiMasuk > 0 || $transaksiKeluar > 0) {
            session()->setFlashdata('error', 'Tidak dapat menghapus barang karena sudah memiliki riwayat transaksi!');
            return redirect()->to('/barang');
        }

        $barang = $this->barangModel->find($id);
        
        if (!$barang) {
            session()->setFlashdata('error', 'Data barang tidak ditemukan');
            return redirect()->to('/barang');
        }

        // Hapus foto
        if ($barang['foto'] && $barang['foto'] != 'default.png' && file_exists('uploads/barang/' . $barang['foto'])) {
            unlink('uploads/barang/' . $barang['foto']);
        }
        
        if ($this->barangModel->delete($id)) {
            session()->setFlashdata('success', 'Data barang berhasil dihapus');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus data barang');
        }
        
        return redirect()->to('/barang');
    }

    // Detail barang
    public function detail($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $barang = $this->barangModel->getBarangWithRelation($id);
        
        if (!$barang) {
            session()->setFlashdata('error', 'Data barang tidak ditemukan');
            return redirect()->to('/barang');
        }

        // Ambil riwayat barang masuk
        $barangMasukModel = new \App\Models\BarangMasukModel();
        $riwayatMasuk = $barangMasukModel
            ->select('barang_masuk.*, supplier.nama_supplier, user.nama as nama_user')
            ->join('supplier', 'supplier.id_supplier = barang_masuk.id_supplier', 'left')
            ->join('user', 'user.id_user = barang_masuk.id_user', 'left')
            ->where('id_barang', $id)
            ->orderBy('tanggal_masuk', 'DESC')
            ->findAll();

        // Ambil riwayat barang keluar
        $barangKeluarModel = new \App\Models\BarangKeluarModel();
        $riwayatKeluar = $barangKeluarModel
            ->select('barang_keluar.*, user.nama as nama_user')
            ->join('user', 'user.id_user = barang_keluar.id_user', 'left')
            ->where('id_barang', $id)
            ->orderBy('tanggal_keluar', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Detail Barang',
            'page_title' => 'Detail Barang',
            'active_menu' => 'barang',
            'barang' => $barang,
            'riwayat_masuk' => $riwayatMasuk,
            'riwayat_keluar' => $riwayatKeluar,
            'level' => session()->get('level')
        ];

        return view('barang/detail', $data);
    }

    // Upload foto
    private function uploadFoto()
    {
        $file = $this->request->getFile('foto');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Buat folder jika belum ada
            if (!is_dir('uploads/barang')) {
                mkdir('uploads/barang', 0777, true);
            }
            
            $namaFile = $file->getRandomName();
            $file->move('uploads/barang', $namaFile);
            return $namaFile;
        }
        
        return 'default.png';
    }

    // Export ke Excel
    public function exportExcel()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $barang = $this->barangModel->getBarangWithRelation();
        
        $filename = 'data_barang_' . date('Ymd_His') . '.xls';
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        
        echo "<table border='1'>";
        echo "<tr>
                <th colspan='8' style='text-align:center; font-size:16px;'>
                    DATA BARANG
                </th>
              </tr>";
        echo "<tr>
                <th colspan='8' style='text-align:center;'>
                    Tanggal Cetak: " . date('d/m/Y H:i:s') . "
                </th>
              </tr>";
        echo "<tr><td colspan='8'></td></tr>";
        echo "<tr>
                <th>No</th>
                <th>ID Barang</th>
                <th>Nama Barang</th>
                <th>Warna</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Jenis</th>
                <th>Stok Minimum</th>
              </tr>";
        
        $no = 1;
        foreach ($barang as $item) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $item['id_barang'] . "</td>";
            echo "<td>" . $item['nama_barang'] . "</td>";
            echo "<td>" . ($item['warna'] ?? '-') . "</td>";
            echo "<td>" . $item['stok'] . "</td>";
            echo "<td>" . ($item['nama_satuan'] ?? '-') . "</td>";
            echo "<td>" . ($item['nama_jenis'] ?? '-') . "</td>";
            echo "<td>" . ($item['stok_minimum'] ?? 0) . "</td>";
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

        $barang = $this->barangModel->getBarangWithRelation();
        
        $data = [
            'title' => 'Laporan Data Barang',
            'barang' => $barang,
            'tanggal_cetak' => date('d/m/Y H:i:s'),
            'total_stok' => array_sum(array_column($barang, 'stok'))
        ];

        return view('barang/print', $data);
    }
}