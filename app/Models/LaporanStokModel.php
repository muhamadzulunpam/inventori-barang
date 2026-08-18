<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanStokModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    // Get semua data stok barang dengan join
    public function getStokBarang($filter = [])
    {
        $builder = $this->db->table('barang');
        $builder->select('
            barang.*, 
            satuan.nama_satuan, 
            jenis.nama_jenis
        ');
        $builder->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left');
        $builder->join('jenis', 'jenis.id_jenis = barang.id_jenis', 'left');
        
        // Filter berdasarkan jenis
        if (!empty($filter['id_jenis'])) {
            $builder->where('barang.id_jenis', $filter['id_jenis']);
        }
        
        // Filter status stok (gunakan min_stok)
        if (!empty($filter['status'])) {
            if ($filter['status'] == 'menipis') {
                $builder->where('barang.stok <= barang.min_stok');
                $builder->where('barang.stok >', 0);
            } elseif ($filter['status'] == 'habis') {
                $builder->where('barang.stok =', 0);
            } elseif ($filter['status'] == 'aman') {
                $builder->where('barang.stok > barang.min_stok');
            }
        }
        
        // Filter pencarian
        if (!empty($filter['search'])) {
            $builder->groupStart()
                    ->like('barang.id_barang', $filter['search'])
                    ->orLike('barang.nama_barang', $filter['search'])
                    ->groupEnd();
        }
        
        $builder->orderBy('barang.id_barang', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    // Get ringkasan laporan
    public function getRingkasan($filter = [])
    {
        $builder = $this->db->table('barang_masuk');
        
        // Apply filter yang sama
        if (!empty($filter['start_date']) && !empty($filter['end_date'])) {
            $builder->where('tanggal_masuk >=', $filter['start_date']);
            $builder->where('tanggal_masuk <=', $filter['end_date']);
        } elseif (!empty($filter['start_date'])) {
            $builder->where('tanggal_masuk >=', $filter['start_date']);
        } elseif (!empty($filter['end_date'])) {
            $builder->where('tanggal_masuk <=', $filter['end_date']);
        }
        
        if (!empty($filter['id_supplier'])) {
            $builder->where('id_supplier', $filter['id_supplier']);
        }
        
        if (!empty($filter['search'])) {
            $builder->groupStart()
                    ->like('id_barang_masuk', $filter['search'])
                    ->orLike('keterangan', $filter['search'])
                    ->groupEnd();
        }
        
        $total_transaksi = $builder->countAllResults(false);
        $total_jumlah = $builder->selectSum('jumlah_masuk')->get()->getRowArray();
        
        return [
            'total_transaksi' => $total_transaksi,
            'total_jumlah' => $total_jumlah['jumlah_masuk'] ?? 0
        ];
    }
    // Get total nilai stok (gunakan kolom 'harga')
    public function getTotalNilaiStok($filter = [])
    {
        $builder = $this->db->table('barang');
        $builder->select('SUM(barang.stok * barang.harga) as total_nilai');
        
        if (!empty($filter['id_jenis'])) {
            $builder->where('barang.id_jenis', $filter['id_jenis']);
        }
        
        if (!empty($filter['status'])) {
            if ($filter['status'] == 'menipis') {
                $builder->where('barang.stok <= barang.min_stok');
                $builder->where('barang.stok >', 0);
            } elseif ($filter['status'] == 'habis') {
                $builder->where('barang.stok =', 0);
            } elseif ($filter['status'] == 'aman') {
                $builder->where('barang.stok > barang.min_stok');
            }
        }
        
        if (!empty($filter['search'])) {
            $builder->groupStart()
                    ->like('barang.id_barang', $filter['search'])
                    ->orLike('barang.nama_barang', $filter['search'])
                    ->groupEnd();
        }
        
        $result = $builder->get()->getRowArray();
        return $result['total_nilai'] ?? 0;
    }

    // Get ringkasan stok
    public function getRingkasanStok()
    {
        $builder = $this->db->table('barang');
        
        // Total barang
        $total_barang = $builder->countAllResults();
        
        // Total stok keseluruhan
        $total_stok = $builder->selectSum('stok')->get()->getRowArray();
        
        // Stok menipis (gunakan min_stok)
        $stok_menipis = $this->db->table('barang')
            ->where('stok <= min_stok')
            ->where('stok >', 0)
            ->countAllResults();
        
        // Stok habis
        $stok_habis = $this->db->table('barang')
            ->where('stok =', 0)
            ->countAllResults();
        
        // Stok aman
        $stok_aman = $this->db->table('barang')
            ->where('stok > min_stok')
            ->countAllResults();
        
        return [
            'total_barang' => $total_barang,
            'total_stok' => $total_stok['stok'] ?? 0,
            'stok_menipis' => $stok_menipis,
            'stok_habis' => $stok_habis,
            'stok_aman' => $stok_aman
        ];
    }

    // Get stok per jenis barang
    public function getStokPerJenis()
    {
        return $this->db->table('barang')
            ->select('jenis.nama_jenis, COUNT(barang.id_barang) as jumlah_barang, SUM(barang.stok) as total_stok')
            ->join('jenis', 'jenis.id_jenis = barang.id_jenis', 'left')
            ->groupBy('jenis.id_jenis')
            ->orderBy('jenis.nama_jenis', 'ASC')
            ->get()
            ->getResultArray();
    }
}