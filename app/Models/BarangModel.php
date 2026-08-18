<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_barang',
        'nama_barang',
        'warna',
        'stok',
        'id_satuan',
        'id_jenis',
        'min_stok',
        'foto'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = null;


    // Join dengan satuan dan jenis
    public function getBarangWithRelation($id = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            barang.*, 
            satuan.nama_satuan, 
            jenis.nama_jenis,
            jenis.keterangan as jenis_keterangan
        ');
        $builder->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left');
        $builder->join('jenis', 'jenis.id_jenis = barang.id_jenis', 'left');
        
        if ($id !== null) {
            $builder->where('barang.id_barang', $id);
            return $builder->get()->getRowArray();
        }
        
        $builder->orderBy('barang.id_barang', 'ASC');
        return $builder->get()->getResultArray();
    }

    // Get barang dengan stok menipis (stok < 10)
    public function getStokMenipis($limit = 10)
    {
        return $this->select('barang.*, satuan.nama_satuan, jenis.nama_jenis')
                    ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
                    ->join('jenis', 'jenis.id_jenis = barang.id_jenis', 'left')
                    ->where('barang.stok <=', 10)
                    ->orderBy('barang.stok', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }

    // Get barang dengan stok habis (stok = 0)
    public function getStokHabis()
    {
        return $this->select('barang.*, satuan.nama_satuan, jenis.nama_jenis')
                    ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
                    ->join('jenis', 'jenis.id_jenis = barang.id_jenis', 'left')
                    ->where('barang.stok', 0)
                    ->orderBy('barang.id_barang', 'ASC')
                    ->findAll();
    }

    // Update stok barang
    public function updateStok($id_barang, $jumlah, $tipe = 'masuk')
    {
        $barang = $this->find($id_barang);
        if ($barang) {
            if ($tipe == 'masuk') {
                $stok_baru = $barang['stok'] + $jumlah;
            } else {
                $stok_baru = $barang['stok'] - $jumlah;
            }
            
            // Pastikan stok tidak negatif
            if ($stok_baru < 0) {
                return false;
            }
            
            return $this->update($id_barang, ['stok' => $stok_baru]);
        }
        return false;
    }

    // Cek stok mencukupi
    public function cekStok($id_barang, $jumlah)
    {
        $barang = $this->find($id_barang);
        if ($barang) {
            return $barang['stok'] >= $jumlah;
        }
        return false;
    }

    // Generate kode barang otomatis
    public function generateKodeBarang()
{
    // Format:
    // TYYMM####
    // Contoh:
    // 126050001
    // 1  = tipe barang
    // 26 = tahun
    // 05 = bulan
    // 0001 = urutan

    $tipe  = '1';
    $tahun = date('y'); // 2 digit tahun
    $bulan = date('m'); // 2 digit bulan

    $prefix = $tipe . $tahun . $bulan;

    // Ambil kode terakhir berdasarkan prefix sekarang
    $lastBarang = $this->like('id_barang', $prefix, 'after')
                    ->orderBy('id_barang', 'DESC')
                    ->first();

    if ($lastBarang) {
        // Ambil 4 digit terakhir
        $lastNumber = (int) substr($lastBarang['id_barang'], -4);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }

    // Gabungkan jadi kode baru
    $kodeBarang = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

    return $kodeBarang;
}

    // Cari barang
    public function search($keyword)
    {
        return $this->select('barang.*, satuan.nama_satuan, jenis.nama_jenis')
                    ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
                    ->join('jenis', 'jenis.id_jenis = barang.id_jenis', 'left')
                    ->groupStart()
                        ->like('barang.id_barang', $keyword)
                        ->orLike('barang.nama_barang', $keyword)
                        ->orLike('barang.warna', $keyword)
                    ->groupEnd()
                    ->findAll();
    }

    // Get barang by jenis
    public function getByJenis($id_jenis)
    {
        return $this->select('barang.*, satuan.nama_satuan')
                    ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
                    ->where('id_jenis', $id_jenis)
                    ->orderBy('barang.nama_barang', 'ASC')
                    ->findAll();
    }

    // Get barang by satuan
    public function getBySatuan($id_satuan)
    {
        return $this->where('id_satuan', $id_satuan)
                    ->orderBy('nama_barang', 'ASC')
                    ->findAll();
    }

    // Get total stok semua barang
    public function getTotalStok()
    {
        return $this->selectSum('stok')->first()['stok'] ?? 0;
    }

    // Get 5 barang dengan stok terbanyak
    public function getTopStok($limit = 5)
    {
        return $this->select('barang.*, satuan.nama_satuan')
                    ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
                    ->orderBy('stok', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    // Get 5 barang dengan stok tersedikit
    public function getBottomStok($limit = 5)
    {
        return $this->select('barang.*, satuan.nama_satuan')
                    ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
                    ->where('stok >', 0)
                    ->orderBy('stok', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }

    // Get statistik stok per jenis
    public function getStatistikPerJenis()
    {
        return $this->select('
                jenis.id_jenis,
                jenis.nama_jenis,
                COUNT(barang.id_barang) as jumlah_barang,
                SUM(barang.stok) as total_stok
            ')
            ->join('jenis', 'jenis.id_jenis = barang.id_jenis', 'left')
            ->groupBy('jenis.id_jenis')
            ->orderBy('total_stok', 'DESC')
            ->findAll();
    }

    // Get statistik stok per satuan
    public function getStatistikPerSatuan()
    {
        return $this->select('
                satuan.id_satuan,
                satuan.nama_satuan,
                COUNT(barang.id_barang) as jumlah_barang,
                SUM(barang.stok) as total_stok
            ')
            ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
            ->groupBy('satuan.id_satuan')
            ->orderBy('total_stok', 'DESC')
            ->findAll();
    }

    // Get barang by warna
    public function getByWarna($warna)
    {
        return $this->select('barang.*, satuan.nama_satuan, jenis.nama_jenis')
                    ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
                    ->join('jenis', 'jenis.id_jenis = barang.id_jenis', 'left')
                    ->where('barang.warna', $warna)
                    ->findAll();
    }

    // Hitung jumlah barang per warna
    public function getJumlahPerWarna()
    {
        return $this->select('warna, COUNT(*) as jumlah')
                    ->groupBy('warna')
                    ->orderBy('jumlah', 'DESC')
                    ->findAll();
    }
}