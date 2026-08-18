<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangMasukModel extends Model
{
    protected $table = 'barang_masuk';
    protected $primaryKey = 'id_barang_masuk';
    protected $useAutoIncrement = false; // ID manual, bukan auto increment
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_barang_masuk',  // TAMBAHKAN INI!
        'id_supplier',
        'id_barang',
        'id_user',
        'jumlah_masuk',
        'tanggal_masuk',
        'harga',
        'keterangan'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // ============================================
    // GENERATE ID TRANSAKSI OTOMATIS (BM00001, BM00002, ...)
    // ============================================
        public function generateIdTransaksi()
    {
        // Format:
        // TYYMM####
        // Contoh:
        // 526050001
        // 5  = barang masuk
        // 26 = tahun
        // 05 = bulan
        // 0001 = urutan

        $tipe  = '5';
        $tahun = date('y');
        $bulan = date('m');

        $prefix = $tipe . $tahun . $bulan;

        // Ambil transaksi terakhir sesuai prefix
        $lastTransaksi = $this->like('id_barang_masuk', $prefix, 'after')
                            ->orderBy('id_barang_masuk', 'DESC')
                            ->first();

        if ($lastTransaksi && isset($lastTransaksi['id_barang_masuk'])) {

            // Ambil 4 digit terakhir
            $lastNumber = (int) substr($lastTransaksi['id_barang_masuk'], -4);

            $newNumber = $lastNumber + 1;

        } else {

            $newNumber = 1;
        }

        // Generate ID baru
        $idTransaksi = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        return $idTransaksi;
    }

    // ============================================
    // GET DATA WITH RELATION (Join semua tabel)
    // ============================================
    public function getBarangMasukWithRelation($id = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            barang_masuk.*,
            supplier.nama_supplier,
            supplier.no_hp as supplier_no_hp,
            supplier.alamat as supplier_alamat,
            barang.nama_barang,
            barang.warna,
            barang.stok as stok_sekarang,
            satuan.nama_satuan,
            user.nama as nama_user
        ');
        $builder->join('supplier', 'supplier.id_supplier = barang_masuk.id_supplier', 'left');
        $builder->join('barang', 'barang.id_barang = barang_masuk.id_barang', 'left');
        $builder->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left');
        $builder->join('user', 'user.id_user = barang_masuk.id_user', 'left');
        
        if ($id !== null) {
            $builder->where('barang_masuk.id_barang_masuk', $id);
            return $builder->get()->getRowArray();
        }
        
        $builder->orderBy('barang_masuk.tanggal_masuk', 'DESC');
        $builder->orderBy('barang_masuk.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }

    // ============================================
    // GET TOTAL NILAI BARANG MASUK
    // ============================================
    public function getTotalNilai()
    {
        $builder = $this->db->table($this->table);
        $builder->select('SUM(jumlah_masuk * harga) as total');
        $result = $builder->get()->getRowArray();
        
        return $result['total'] ?? 0;
    }

    // ============================================
    // GET TOTAL PER BULAN
    // ============================================
    public function getTotalPerBulan($bulan = null, $tahun = null)
    {
        $bulan = $bulan ?? date('m');
        $tahun = $tahun ?? date('Y');
        
        $result = $this->selectSum('jumlah_masuk')
                    ->where('MONTH(tanggal_masuk)', $bulan)
                    ->where('YEAR(tanggal_masuk)', $tahun)
                    ->first();
        
        return $result['jumlah_masuk'] ?? 0;
    }

    // ============================================
    // GET TOTAL PER HARI
    // ============================================
    public function getTotalPerHari($tanggal = null)
    {
        $tanggal = $tanggal ?? date('Y-m-d');
        
        $result = $this->selectSum('jumlah_masuk')
                    ->where('DATE(tanggal_masuk)', $tanggal)
                    ->first();
        
        return $result['jumlah_masuk'] ?? 0;
    }

    // ============================================
    // GET RINGKASAN UNTUK DASHBOARD
    // ============================================
    public function getRingkasan()
    {
        $total_bulan_ini = $this->getTotalPerBulan(date('m'), date('Y'));
        
        $bulanLalu = date('m') - 1;
        $tahunBulanLalu = date('Y');
        if ($bulanLalu == 0) {
            $bulanLalu = 12;
            $tahunBulanLalu = date('Y') - 1;
        }
        $total_bulan_lalu = $this->getTotalPerBulan($bulanLalu, $tahunBulanLalu);
        
        $persentase = 0;
        if ($total_bulan_lalu > 0) {
            $persentase = (($total_bulan_ini - $total_bulan_lalu) / $total_bulan_lalu) * 100;
        } elseif ($total_bulan_ini > 0) {
            $persentase = 100;
        }
        
        return [
            'total_bulan_ini' => $total_bulan_ini,
            'total_bulan_lalu' => $total_bulan_lalu,
            'persentase' => round($persentase, 2),
            'total_keseluruhan' => $this->selectSum('jumlah_masuk')->first()['jumlah_masuk'] ?? 0
        ];
    }

    // ============================================
    // GET BARANG MASUK BY SUPPLIER
    // ============================================
    public function getBarangMasukWithRelations()
    {
        return $this->select('barang_masuk.*, 
                              supplier.nama_supplier, 
                              barang.nama_barang, 
                              satuan.nama_satuan,
                              user.nama as nama_user')
                    ->join('supplier', 'supplier.id_supplier = barang_masuk.id_supplier', 'left')
                    ->join('barang', 'barang.id_barang = barang_masuk.id_barang', 'left')
                    ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
                    ->join('user', 'user.id_user = barang_masuk.id_user', 'left')
                    ->orderBy('tanggal_masuk', 'DESC')
                    ->findAll();
    }
    public function getBySupplier($id_supplier, $limit = 10)
    {
        return $this->db->table($this->table)
            ->select('barang_masuk.*, barang.nama_barang, satuan.nama_satuan, user.nama as nama_user')
            ->join('barang', 'barang.id_barang = barang_masuk.id_barang')
            ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
            ->join('user', 'user.id_user = barang_masuk.id_user', 'left')
            ->where('id_supplier', $id_supplier)
            ->orderBy('tanggal_masuk', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    // ============================================
    // GET BARANG MASUK BY BARANG
    // ============================================
    public function getByBarang($id_barang, $limit = 10)
    {
        return $this->db->table($this->table)
            ->select('barang_masuk.*, supplier.nama_supplier, user.nama as nama_user')
            ->join('supplier', 'supplier.id_supplier = barang_masuk.id_supplier', 'left')
            ->join('user', 'user.id_user = barang_masuk.id_user', 'left')
            ->where('id_barang', $id_barang)
            ->orderBy('tanggal_masuk', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}