<?php

namespace App\Controllers;

use App\Models\SupplierModel;
use App\Models\BarangMasukModel;

class SupplierController extends BaseController
{
    protected $supplierModel;
    protected $barangMasukModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
        $this->barangMasukModel = new BarangMasukModel();
    }

    public function index()
{
    if (!session()->get('logged_in')) {
        return redirect()->to('/login');
    }

    // Ambil filter
    $search = $this->request->getGet('search');
    $status = $this->request->getGet('status');

    // Query builder (WAJIB konsisten)
    $builder = $this->supplierModel
        ->select('supplier.*, COUNT(barang_masuk.id_barang_masuk) as total_transaksi')
        ->join('barang_masuk', 'barang_masuk.id_supplier = supplier.id_supplier', 'left')
        ->groupBy('supplier.id_supplier')
        ->orderBy('supplier.id_supplier', 'ASC');

    // =========================
    // FILTER SEARCH
    // =========================
    if (!empty($search)) {
        $builder = $builder->groupStart()
            ->like('supplier.id_supplier', $search)
            ->orLike('supplier.nama_supplier', $search)
            ->orLike('supplier.alamat', $search)
            ->groupEnd();
    }

    // =========================
    // FILTER STATUS
    // =========================
    if ($status == 'pernah_transaksi') {
        $builder = $builder->whereIn(
            'supplier.id_supplier',
            function ($subquery) {
                return $subquery->select('id_supplier')
                    ->from('barang_masuk');
            }
        );
    }

    if ($status == 'belum_transaksi') {
        $builder = $builder->whereNotIn(
            'supplier.id_supplier',
            function ($subquery) {
                return $subquery->select('id_supplier')
                    ->from('barang_masuk');
            }
        );
    }

    // =========================
    // EXECUTE QUERY
    // =========================
    $suppliers = $builder->findAll();

    $data = [
        'title' => 'Data Supplier',
        'page_title' => 'Data Supplier',
        'active_menu' => 'supplier',
        'suppliers' => $suppliers,
        'statistik' => $this->supplierModel->getStatistik()
    ];

    return view('supplier/index', $data);
}

    // Form tambah supplier
    public function new()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Tambah Supplier',
            'page_title' => 'Form Tambah Supplier',
            'active_menu' => 'supplier',
            'id_supplier' => $this->supplierModel->generateIdSupplier()
        ];

        return view('supplier/create', $data);
    }

    // Proses simpan supplier
    public function create()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $rules = [
            'id_supplier' => 'required|is_unique[supplier.id_supplier]',
            'nama_supplier' => 'required|min_length[3]',
            'no_hp' => 'permit_empty|min_length[10]|max_length[15]',
            'alamat' => 'permit_empty'
        ];

        $messages = [
            'id_supplier' => [
                'required' => 'ID Supplier wajib diisi',
                'is_unique' => 'ID Supplier sudah ada'
            ],
            'nama_supplier' => [
                'required' => 'Nama supplier wajib diisi',
                'min_length' => 'Nama supplier minimal 3 karakter'
            ],
            'no_hp' => [
                'min_length' => 'Nomor HP minimal 10 digit',
                'max_length' => 'Nomor HP maksimal 15 digit'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_supplier' => $this->request->getPost('id_supplier'),
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'no_hp' => $this->request->getPost('no_hp'),
            'alamat' => $this->request->getPost('alamat')
        ];

        if ($this->supplierModel->save($data)) {
            session()->setFlashdata('success', 'Supplier berhasil ditambahkan');
            return redirect()->to('/supplier');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan supplier');
            return redirect()->back()->withInput();
        }
    }

    // Halaman detail supplier
    public function detail($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $supplier = $this->supplierModel->find($id);
        
        if (!$supplier) {
            session()->setFlashdata('error', 'Supplier tidak ditemukan');
            return redirect()->to('/supplier');
        }

        // Ambil riwayat barang masuk dari supplier ini
        $riwayat = $this->barangMasukModel
            ->select('barang_masuk.*, barang.nama_barang, satuan.nama_satuan')
            ->join('barang', 'barang.id_barang = barang_masuk.id_barang', 'left')
            ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
            ->where('id_supplier', $id)
            ->orderBy('tanggal_masuk', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Detail Supplier',
            'page_title' => 'Detail Supplier',
            'active_menu' => 'supplier',
            'supplier' => $supplier,
            'riwayat' => $riwayat,
            'total_barang_masuk' => $this->barangMasukModel->where('id_supplier', $id)->selectSum('jumlah_masuk')->first()['jumlah_masuk'] ?? 0,
            'total_transaksi' => $this->barangMasukModel->where('id_supplier', $id)->countAllResults()
        ];

        return view('supplier/detail', $data);
    }

    // Form edit supplier
    public function edit($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $supplier = $this->supplierModel->find($id);
        
        if (!$supplier) {
            session()->setFlashdata('error', 'Supplier tidak ditemukan');
            return redirect()->to('/supplier');
        }

        $data = [
            'title' => 'Edit Supplier',
            'page_title' => 'Form Edit Supplier',
            'active_menu' => 'supplier',
            'supplier' => $supplier
        ];

        return view('supplier/edit', $data);
    }

    // Proses update supplier
    public function update($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $supplierLama = $this->supplierModel->find($id);
        
        if (!$supplierLama) {
            session()->setFlashdata('error', 'Supplier tidak ditemukan');
            return redirect()->to('/supplier');
        }

        $rules = [
            'nama_supplier' => 'required|min_length[3]',
            'no_hp' => 'permit_empty|min_length[10]|max_length[15]',
            'alamat' => 'permit_empty'
        ];

        $messages = [
            'nama_supplier' => [
                'required' => 'Nama supplier wajib diisi',
                'min_length' => 'Nama supplier minimal 3 karakter'
            ],
            'no_hp' => [
                'min_length' => 'Nomor HP minimal 10 digit',
                'max_length' => 'Nomor HP maksimal 15 digit'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'no_hp' => $this->request->getPost('no_hp'),
            'alamat' => $this->request->getPost('alamat')
        ];

        if ($this->supplierModel->update($id, $data)) {
            session()->setFlashdata('success', 'Supplier berhasil diupdate');
            return redirect()->to('/supplier');
        } else {
            session()->setFlashdata('error', 'Gagal mengupdate supplier');
            return redirect()->back()->withInput();
        }
    }

    // Proses hapus supplier
    public function delete($id = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $supplier = $this->supplierModel->find($id);
        
        if (!$supplier) {
            session()->setFlashdata('error', 'Supplier tidak ditemukan');
            return redirect()->to('/supplier');
        }

        // Cek apakah supplier sudah memiliki transaksi barang masuk
        $hasTransaksi = $this->barangMasukModel->where('id_supplier', $id)->countAllResults();
        
        if ($hasTransaksi > 0) {
            session()->setFlashdata('error', 'Supplier tidak dapat dihapus karena sudah memiliki riwayat transaksi!');
            return redirect()->to('/supplier');
        }

        if ($this->supplierModel->delete($id)) {
            session()->setFlashdata('success', 'Supplier berhasil dihapus');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus supplier');
        }
        
        return redirect()->to('/supplier');
    }
    // Print laporan supplier
    public function print()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $supplier = $this->supplierModel->findAll();
        
        $data = [
            'title' => 'Laporan Data Supplier',
            'supplier' => $supplier,
            'tanggal_cetak' => date('d/m/Y H:i:s'),
            'total_supplier' => count($supplier)
        ];
        
        return view('supplier/print', $data);
    }
}