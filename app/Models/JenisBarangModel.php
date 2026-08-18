<?php

namespace App\Models;

use CodeIgniter\Model;

class JenisBarangModel extends Model
{
    protected $table = 'jenis';
    protected $primaryKey = 'id_jenis';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_jenis',
        'nama_jenis',
        'keterangan'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = null;
    protected $updatedField = null;
    protected $deletedField = null;

    // Hitung total barang per jenis
    public function getTotalBarang($id_jenis)
    {
        $barangModel = new BarangModel();
        return $barangModel->where('id_jenis', $id_jenis)->countAllResults();
    }

    // Hitung total stok barang per jenis
    public function getTotalStok($id_jenis)
    {
        $barangModel = new BarangModel();
        return $barangModel->selectSum('stok')->where('id_jenis', $id_jenis)->first()['stok'] ?? 0;
    }

    // Get semua jenis dengan jumlah barang
    public function getAllWithCount()
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            jenis.*, 
            COUNT(barang.id_barang) as total_barang,
            COALESCE(SUM(barang.stok), 0) as total_stok
        ');
        $builder->join('barang', 'barang.id_jenis = jenis.id_jenis', 'left');
        $builder->groupBy('jenis.id_jenis');
        $builder->orderBy('jenis.id_jenis', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    // Get jenis dengan barang terbanyak
    public function getTopJenis($limit = 5)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            jenis.*, 
            COUNT(barang.id_barang) as total_barang
        ');
        $builder->join('barang', 'barang.id_jenis = jenis.id_jenis', 'left');
        $builder->groupBy('jenis.id_jenis');
        $builder->orderBy('total_barang', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }

    // Cari jenis
    public function search($keyword)
    {
        return $this->like('nama_jenis', $keyword)
                    ->orLike('keterangan', $keyword)
                    ->findAll();
    }

    // Get statistik jenis
    public function getStatistik()
    {
        return $this->select('
                COUNT(*) as total_jenis,
                SUM(CASE WHEN keterangan IS NOT NULL THEN 1 ELSE 0 END) as ada_keterangan
            ')->first();
    }

    // Cek apakah jenis memiliki barang
    public function hasBarang($id_jenis)
    {
        $barangModel = new BarangModel();
        return $barangModel->where('id_jenis', $id_jenis)->countAllResults() > 0;
    }

    // Get jenis tanpa barang
    public function getJenisTanpaBarang()
    {
        $builder = $this->db->table($this->table);
        $builder->select('jenis.*');
        $builder->join('barang', 'barang.id_jenis = jenis.id_jenis', 'left');
        $builder->where('barang.id_barang IS NULL');
        
        return $builder->get()->getResultArray();
    }

    // Get semua jenis (simple)
    public function getAllJenis()
    {
        return $this->orderBy('nama_jenis', 'ASC')->findAll();
    }
    public function generateIdJenis()
{
    // Format:
    // TYYMM####
    // Contoh:
    // 426050001
    // 4  = jenis barang
    // 26 = tahun
    // 05 = bulan
    // 0001 = urutan

    $tipe  = '4';
    $tahun = date('y');
    $bulan = date('m');

    $prefix = $tipe . $tahun . $bulan;

    // Ambil ID terakhir sesuai prefix
    $lastJenis = $this->like('id_jenis', $prefix, 'after')
                    ->orderBy('id_jenis', 'DESC')
                    ->first();

    if ($lastJenis) {
        // Ambil 4 digit terakhir
        $lastNumber = (int) substr($lastJenis['id_jenis'], -4);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }

    // Generate ID baru
    $idJenis = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

    return $idJenis;
}
}