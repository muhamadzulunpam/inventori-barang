<?php

namespace App\Models;

use CodeIgniter\Model;

class SatuanModel extends Model
{
    protected $table = 'satuan';
    protected $primaryKey = 'id_satuan';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_satuan',
        'nama_satuan',
        'keterangan'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = null;
    protected $updatedField = null;
    protected $deletedField = null;

    // Hitung total barang per satuan
    public function getTotalBarang($id_satuan)
    {
        $barangModel = new BarangModel();
        return $barangModel->where('id_satuan', $id_satuan)->countAllResults();
    }

    // Hitung total stok barang per satuan
    public function getTotalStok($id_satuan)
    {
        $barangModel = new BarangModel();
        return $barangModel->selectSum('stok')->where('id_satuan', $id_satuan)->first()['stok'] ?? 0;
    }

    // Get semua satuan dengan jumlah barang
    public function getAllWithCount()
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            satuan.*, 
            COUNT(barang.id_barang) as total_barang,
            COALESCE(SUM(barang.stok), 0) as total_stok
        ');
        $builder->join('barang', 'barang.id_satuan = satuan.id_satuan', 'left');
        $builder->groupBy('satuan.id_satuan');
        $builder->orderBy('satuan.id_satuan', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    // Get satuan dengan barang terbanyak
    public function getTopSatuan($limit = 5)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            satuan.*, 
            COUNT(barang.id_barang) as total_barang
        ');
        $builder->join('barang', 'barang.id_satuan = satuan.id_satuan', 'left');
        $builder->groupBy('satuan.id_satuan');
        $builder->orderBy('total_barang', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }

    // Cari satuan
    public function search($keyword)
    {
        return $this->like('nama_satuan', $keyword)
                    ->orLike('keterangan', $keyword)
                    ->findAll();
    }

    // Get statistik satuan
    public function getStatistik()
    {
        return $this->select('
                COUNT(*) as total_satuan,
                SUM(CASE WHEN keterangan IS NOT NULL THEN 1 ELSE 0 END) as ada_keterangan
            ')->first();
    }

    // Cek apakah satuan memiliki barang
    public function hasBarang($id_satuan)
    {
        $barangModel = new BarangModel();
        return $barangModel->where('id_satuan', $id_satuan)->countAllResults() > 0;
    }

    // Get satuan tanpa barang
    public function getSatuanTanpaBarang()
    {
        $builder = $this->db->table($this->table);
        $builder->select('satuan.*');
        $builder->join('barang', 'barang.id_satuan = satuan.id_satuan', 'left');
        $builder->where('barang.id_barang IS NULL');
        $builder->orderBy('satuan.id_satuan', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    // Get semua satuan (simple)
    public function getAllSatuan()
    {
        return $this->orderBy('id_satuan', 'ASC')->findAll();
    }
    public function generateIdSatuan()
    {
        // Format:
        // TYYMM####
        // Contoh:
        // 326050001
        // 3  = satuan
        // 26 = tahun
        // 05 = bulan
        // 0001 = urutan

        $tipe  = '3';
        $tahun = date('y');
        $bulan = date('m');

        $prefix = $tipe . $tahun . $bulan;

        // Ambil ID terakhir sesuai prefix
        $lastSatuan = $this->like('id_satuan', $prefix, 'after')
                        ->orderBy('id_satuan', 'DESC')
                        ->first();

        if ($lastSatuan) {
            // Ambil 4 digit terakhir
            $lastNumber = (int) substr($lastSatuan['id_satuan'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Generate ID baru
        $idSatuan = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        return $idSatuan;
    }
}