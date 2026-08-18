<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'id_supplier';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_supplier',
        'nama_supplier',
        'no_hp',
        'alamat'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = null;
    protected $updatedField = null;
    protected $deletedField = null;

    protected $validationRules = [
        'id_supplier' => 'required|is_unique[supplier.id_supplier]',
        'nama_supplier' => 'required',
        'no_hp' => 'permit_empty|min_length[10]|max_length[15]',
        'alamat' => 'permit_empty'
    ];

    protected $validationMessages = [
        'id_supplier' => [
            'required' => 'ID Supplier wajib diisi',
            'is_unique' => 'ID Supplier sudah ada'
        ],
        'nama_supplier' => [
            'required' => 'Nama supplier wajib diisi'
        ],
        'no_hp' => [
            'min_length' => 'Nomor HP minimal 10 digit',
            'max_length' => 'Nomor HP maksimal 15 digit'
        ]
    ];

    // Generate ID Supplier otomatis
    public function generateIdSupplier()
    {
        // Format:
        // TYYMM####
        // Contoh:
        // 226050001
        // 2  = supplier
        // 26 = tahun
        // 05 = bulan
        // 0001 = urutan

        $tipe  = '2';
        $tahun = date('y');
        $bulan = date('m');

        $prefix = $tipe . $tahun . $bulan;

        // Ambil ID terakhir sesuai prefix
        $lastSupplier = $this->like('id_supplier', $prefix, 'after')
                            ->orderBy('id_supplier', 'DESC')
                            ->first();

        if ($lastSupplier) {
            // Ambil 4 digit terakhir
            $lastNumber = (int) substr($lastSupplier['id_supplier'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Generate ID baru
        $idSupplier = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        return $idSupplier;
    }

    // Cari supplier
    public function search($keyword)
    {
        return $this->like('id_supplier', $keyword)
                    ->orLike('nama_supplier', $keyword)
                    ->orLike('no_hp', $keyword)
                    ->orLike('alamat', $keyword)
                    ->findAll();
    }

    // Get semua supplier dengan jumlah barang masuk
    public function getAllWithCount()
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            supplier.*, 
            COUNT(barang_masuk.id_barang_masuk) as jumlah_transaksi,
            COALESCE(SUM(barang_masuk.jumlah_masuk), 0) as total_barang_masuk
        ');
        $builder->join('barang_masuk', 'barang_masuk.id_supplier = supplier.id_supplier', 'left');
        $builder->groupBy('supplier.id_supplier');
       $builder->orderBy('supplier.id_supplier', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    // Get top supplier (paling sering melakukan pengiriman)
    public function getTopSupplier($limit = 5)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            supplier.id_supplier,
            supplier.nama_supplier,
            COUNT(barang_masuk.id_barang_masuk) as jumlah_transaksi,
            COALESCE(SUM(barang_masuk.jumlah_masuk), 0) as total_barang
        ');
        $builder->join('barang_masuk', 'barang_masuk.id_supplier = supplier.id_supplier', 'left');
        $builder->groupBy('supplier.id_supplier');
        $builder->orderBy('jumlah_transaksi', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }

    // Get supplier yang pernah melakukan transaksi
    public function getPernahTransaksi()
    {
        $builder = $this->db->table($this->table);
        $builder->select('supplier.*');
        $builder->distinct();
        $builder->join('barang_masuk', 'barang_masuk.id_supplier = supplier.id_supplier');
        $builder->orderBy('supplier.id_supplier', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    // Get supplier yang tidak pernah transaksi
    public function getTidakPernahTransaksi()
    {
        $builder = $this->db->table($this->table);
        $builder->select('supplier.*');
        $builder->join('barang_masuk', 'barang_masuk.id_supplier = supplier.id_supplier', 'left');
        $builder->where('barang_masuk.id_supplier IS NULL');
        $builder->orderBy('supplier.id_supplier', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    // Cek apakah supplier memiliki transaksi
    public function hasTransaksi($id_supplier)
    {
        $barangMasukModel = new \App\Models\BarangMasukModel();
        return $barangMasukModel->where('id_supplier', $id_supplier)->countAllResults() > 0;
    }

    // Get statistik supplier
    public function getStatistik()
    {
        $total = $this->countAll();
        $pernahTransaksi = count($this->getPernahTransaksi());
        
        return [
            'total' => $total,
            'pernah_transaksi' => $pernahTransaksi,
            'tidak_pernah_transaksi' => $total - $pernahTransaksi
        ];
    }
    public function getActiveSupplier()
    {
        return $this->where('status', 'Aktif')->findAll();
    }
}