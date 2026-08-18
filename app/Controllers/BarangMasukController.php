<?php

namespace App\Controllers;

use App\Models\BarangMasukModel;
use App\Models\BarangModel;
use App\Models\SupplierModel;
use App\Models\UserModel;

class BarangMasukController extends BaseController
{
    protected $barangMasukModel;
    protected $barangModel;
    protected $supplierModel;
    protected $userModel;

    public function __construct()
    {
        $this->barangMasukModel = new BarangMasukModel();
        $this->barangModel = new BarangModel();
        $this->supplierModel = new SupplierModel();
        $this->userModel = new UserModel();
    }

    // ============================================================
    // INDEX / DAFTAR BARANG MASUK
    // ============================================================
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Ambil parameter filter
        $bulan  = $this->request->getGet('bulan');
        $tahun  = $this->request->getGet('tahun');
        $search = $this->request->getGet('search');

        // Query builder
        $builder = $this->barangMasukModel
        ->select('barang_masuk.*, 
                  barang.nama_barang, 
                  supplier.nama_supplier, 
                  satuan.nama_satuan, 
                  user.nama as nama_user')  // PERBAIKAN: tambahkan AS nama_user
        ->join('barang', 'barang.id_barang = barang_masuk.id_barang')
        ->join('supplier', 'supplier.id_supplier = barang_masuk.id_supplier')
        ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
        ->join('user', 'user.id_user = barang_masuk.id_user', 'left');

        // Filter bulan
        if (!empty($bulan)) {
            $builder->where('MONTH(tanggal_masuk)', $bulan);
        }

        // Filter tahun
        if (!empty($tahun)) {
            $builder->where('YEAR(tanggal_masuk)', $tahun);
        }

        // Filter pencarian
        if (!empty($search)) {
            $builder->groupStart()
                ->like('barang_masuk.id_barang_masuk', $search)
                ->orLike('barang.nama_barang', $search)
                ->orLike('supplier.nama_supplier', $search)
                ->groupEnd();
        }

        // Ambil data~
        $barang_masuk = $builder
            ->orderBy('updated_at', 'DESC')
            ->orderBy('id_barang_masuk', 'DESC')
            ->findAll();

        // Hitung total
        $total_masuk = array_sum(array_column($barang_masuk, 'jumlah_masuk'));

        $total_nilai = 0;
        foreach ($barang_masuk as $item) {
            $total_nilai += $item['jumlah_masuk'] * ($item['harga'] ?? 0);
        }

        $data = [
            'title'            => 'Barang Masuk',
            'page_title'       => 'Data Barang Masuk',
            'active_menu'      => 'barang_masuk',
            'barang_masuk'     => $barang_masuk,
            'total_masuk' => $this->barangMasukModel->selectSum('jumlah_masuk')->first()['jumlah_masuk'] ?? 0,
            'total_transaksi' => $this->barangMasukModel->countAll(),
            'total_nilai'      => $total_nilai,
            'level'            => session()->get('level')
        ];

        return view('barang_masuk/index', $data);
    }

    // ============================================================
    // FORM TAMBAH BARANG MASUK (GET /barang_masuk/create)
    // ============================================================
    public function create()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('level') == 'staff_gudang') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses!');
            return redirect()->to('/barang_masuk');
        }

        // Ambil data barang dengan satuan
        $barang = $this->barangModel->db->table('barang')
            ->select('barang.*, satuan.nama_satuan')
            ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
            ->orderBy('barang.nama_barang', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Tambah Barang Masuk',
            'page_title' => 'Form Tambah Barang Masuk',
            'active_menu' => 'barang_masuk',
            'id_transaksi' => $this->barangMasukModel->generateIdTransaksi(),
            'barang' => $barang,
            'supplier' => $this->supplierModel->orderBy('nama_supplier', 'ASC')->findAll(),
            'level' => session()->get('level')
        ];

        return view('barang_masuk/create', $data);
    }

    // ============================================================
    // PROSES SIMPAN BARANG MASUK (POST /barang_masuk/store)
    // ============================================================
    public function store()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // 🔥 TAMBAHKAN INI (DI SINI)
        if (!session()->get('id_user')) {
            dd('SESSION ID USER KOSONG');
        }

        if (session()->get('level') == 'staff_gudang') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses!');
            return redirect()->to('/barang_masuk');
        }

        $rules = [
            'id_supplier'   => 'required',
            'id_barang'     => 'required',
            'jumlah_masuk'  => 'required|numeric|greater_than[0]',
            'tanggal_masuk' => 'required|valid_date',
            'harga'         => 'required|numeric|greater_than[0]'
        ];
        

        $messages = [
            'id_supplier' => ['required' => 'Supplier wajib dipilih'],
            'id_barang' => ['required' => 'Barang wajib dipilih'],
            'jumlah_masuk' => [
                'required' => 'Jumlah wajib diisi',
                'numeric' => 'Jumlah harus berupa angka',
                'greater_than' => 'Jumlah harus lebih dari 0'
            ],
            'tanggal_masuk' => [
                'required' => 'Tanggal wajib diisi',
                'valid_date' => 'Tanggal tidak valid'
            ],
            'harga' => [
                'required' => 'Harga beli wajib diisi',
                'numeric' => 'Harga harus berupa angka',
                'greater_than' => 'Harga harus lebih dari 0'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $id_barang = $this->request->getPost('id_barang');
        $jumlah = $this->request->getPost('jumlah_masuk');
        $harga = str_replace('.', '', $this->request->getPost('harga'));

        $barang = $this->barangModel->find($id_barang);
        if (!$barang) {
            session()->setFlashdata('error', 'Barang tidak ditemukan');
            return redirect()->back()->withInput();
        }

        $id_transaksi = $this->barangMasukModel->generateIdTransaksi();

        $data = [
            'id_barang_masuk' => $id_transaksi,
            'id_supplier'     => $this->request->getPost('id_supplier'),
            'id_barang'       => $id_barang,
            'id_user' => (int) session()->get('id_user'),
            'jumlah_masuk'    => $jumlah,
            'tanggal_masuk'   => $this->request->getPost('tanggal_masuk'),
            'harga'           => $harga,
            'keterangan'      => $this->request->getPost('keterangan')
        ];


        $inserted = $this->barangMasukModel->insert($data);
        
        if ($inserted) {
            $stok_baru = $barang['stok'] + $jumlah;
            $this->barangModel->update($id_barang, ['stok' => $stok_baru]);
            
            session()->setFlashdata('success', 'Barang masuk berhasil disimpan! (ID: ' . $id_transaksi . ')');
            return redirect()->to('/barang_masuk');
        } else {
            $errors = $this->barangMasukModel->errors();
            $errorMsg = !empty($errors) ? implode(', ', $errors) : 'Terjadi kesalahan saat menyimpan data';
            session()->setFlashdata('error', 'Gagal menyimpan: ' . $errorMsg);
            return redirect()->back()->withInput();
        }
    }

    // ============================================================
    // DETAIL BARANG MASUK (GET /barang_masuk/detail/:id)
    // ============================================================
    public function detail($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $barang_masuk = $this->barangMasukModel->getBarangMasukWithRelation($id);
        
        if (!$barang_masuk) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/barang_masuk');
        }

        $data = [
            'title' => 'Detail Barang Masuk',
            'page_title' => 'Detail Barang Masuk',
            'active_menu' => 'barang_masuk',
            'barang_masuk' => $barang_masuk,
            'level' => session()->get('level')
        ];

        return view('barang_masuk/detail', $data);
    }

    // ============================================================
    // FORM EDIT BARANG MASUK (GET /barang_masuk/edit/:id)
    // ============================================================
    public function edit($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('level') == 'staff_gudang') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses!');
            return redirect()->to('/barang_masuk');
        }

        // Cari data dengan ID
        $barang_masuk = $this->barangMasukModel->find($id);
        
        if (!$barang_masuk) {
            session()->setFlashdata('error', 'Data barang masuk tidak ditemukan dengan ID: ' . $id);
            return redirect()->to('/barang_masuk');
        }

        // Ambil data barang dengan satuan
        $barang = $this->barangModel->db->table('barang')
            ->select('barang.*, satuan.nama_satuan')
            ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
            ->orderBy('barang.nama_barang', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Edit Barang Masuk',
            'page_title' => 'Form Edit Barang Masuk',
            'active_menu' => 'barang_masuk',
            'barang_masuk' => $barang_masuk,
            'barang' => $barang,
            'supplier' => $this->supplierModel->orderBy('nama_supplier', 'ASC')->findAll(),
            'level' => session()->get('level')
        ];

        return view('barang_masuk/edit', $data);
    }

    // ============================================================
    // PROSES UPDATE BARANG MASUK (POST /barang_masuk/update/:id)
    // ============================================================
    public function update($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('level') == 'staff_gudang') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses!');
            return redirect()->to('/barang_masuk');
        }

        $oldData = $this->barangMasukModel->find($id);
        
        if (!$oldData) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/barang_masuk');
        }

        $rules = [
            'id_supplier'   => 'required',
            'id_barang'     => 'required',
            'jumlah_masuk'  => 'required|numeric|greater_than[0]',
            'tanggal_masuk' => 'required|valid_date',
            'harga'         => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $harga = str_replace('.', '', $this->request->getPost('harga'));
        
        $newData = [
            'id_supplier'   => $this->request->getPost('id_supplier'),
            'id_barang'     => $this->request->getPost('id_barang'),
            'jumlah_masuk'  => $this->request->getPost('jumlah_masuk'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'harga'         => $harga,
            'keterangan'    => $this->request->getPost('keterangan')
        ];

        // Update stok
        $barangLama = $this->barangModel->find($oldData['id_barang']);
        $barangBaru = $this->barangModel->find($newData['id_barang']);
        
        if ($barangLama && $barangBaru) {
            $stok_lama_baru = $barangLama['stok'] - $oldData['jumlah_masuk'];
            if ($stok_lama_baru < 0) $stok_lama_baru = 0;
            $this->barangModel->update($oldData['id_barang'], ['stok' => $stok_lama_baru]);
            
            $stok_baru_baru = $barangBaru['stok'] + $newData['jumlah_masuk'];
            $this->barangModel->update($newData['id_barang'], ['stok' => $stok_baru_baru]);
        }

        $updated = $this->barangMasukModel->update($id, $newData);

        if ($updated) {
            session()->setFlashdata('success', 'Barang masuk berhasil diupdate');
            return redirect()->to('/barang_masuk');
        } else {
            session()->setFlashdata('error', 'Gagal mengupdate barang masuk');
            return redirect()->back()->withInput();
        }
    }

    // ============================================================
    // PROSES HAPUS BARANG MASUK (GET /barang_masuk/delete/:id)
    // ============================================================
    public function delete($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('level') == 'staff_gudang') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses!');
            return redirect()->to('/barang_masuk');
        }

        $barang_masuk = $this->barangMasukModel->find($id);
        
        if (!$barang_masuk) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/barang_masuk');
        }

        // Kembalikan stok barang
        $barang = $this->barangModel->find($barang_masuk['id_barang']);
        if ($barang) {
            $stok_baru = $barang['stok'] - $barang_masuk['jumlah_masuk'];
            if ($stok_baru < 0) $stok_baru = 0;
            $this->barangModel->update($barang_masuk['id_barang'], ['stok' => $stok_baru]);
        }

        $deleted = $this->barangMasukModel->delete($id);

        if ($deleted) {
            session()->setFlashdata('success', 'Barang masuk berhasil dihapus');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus barang masuk');
        }
        
        return redirect()->to('/barang_masuk');
    }

    // ============================================================
    // EXPORT KE EXCEL
    // ============================================================
    public function exportExcel()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $barang_masuk = $this->barangMasukModel->getBarangMasukWithRelation();
        
        $filename = 'laporan_barang_masuk_' . date('Ymd_His') . '.xls';
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        
        echo "<table border='1'>";
        echo "<tr><th colspan='11'>LAPORAN BARANG MASUK</th></tr>";
        echo "<tr><th colspan='11'>Tanggal Cetak: " . date('d/m/Y H:i:s') . "</th></tr>";
        echo "<tr><th>No</th><th>ID Transaksi</th><th>Tanggal</th><th>Supplier</th><th>Barang</th><th>Jumlah</th><th>Satuan</th><th>Harga</th><th>Total</th><th>Input Oleh</th><th>Keterangan</th></tr>";
        
        $no = 1;
        foreach ($barang_masuk as $item) {
            $total = $item['jumlah_masuk'] * ($item['harga'] ?? 0);
            echo "<td>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $item['id_barang_masuk'] . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($item['tanggal_masuk'])) . "</td>";
            echo "<td>" . ($item['nama_supplier'] ?? '-') . "</td>";
            echo "<td>" . $item['nama_barang'] . "</td>";
            echo "<td>" . number_format($item['jumlah_masuk']) . "</td>";
            echo "<td>" . ($item['nama_satuan'] ?? '-') . "</td>";
            echo "<td>Rp " . number_format($item['harga'] ?? 0, 0, ',', '.') . "</td>";
            echo "<td>Rp " . number_format($total, 0, ',', '.') . "</td>";
            echo "<td>" . ($item['nama_user'] ?? '-') . "</td>";
            echo "<td>" . ($item['keterangan'] ?? '-') . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        exit();
    }

    // ============================================================
    // PRINT LAPORAN
    // ============================================================
    public function print()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $barang_masuk = $this->barangMasukModel->getBarangMasukWithRelations();
        
        $data = [
            'title' => 'Laporan Barang Masuk',
            'barang_masuk' => $barang_masuk,
            'tanggal_cetak' => date('d/m/Y H:i:s'),
            'total_nilai' => $this->barangMasukModel->getTotalNilai()
        ];
        
        return view('barang_masuk/print', $data);
    }

}