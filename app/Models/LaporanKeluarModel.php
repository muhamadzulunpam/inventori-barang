<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanKeluarModel extends Model
{
    protected $table = 'barang_keluar';
    protected $primaryKey = 'id_barang_keluar';
    protected $allowedFields = [
        'id_barang',
        'id_user',
        'jumlah_keluar',
        'tanggal_keluar',
        'keterangan'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get laporan barang keluar
    public function getLaporanBarangKeluar($filter = [])
    {
        $builder = $this->db->table('barang_keluar');
        $builder->select('
            barang_keluar.*,
            barang.nama_barang,
            barang.warna,
            satuan.nama_satuan,
            user.nama as nama_user
        ');
        $builder->join('barang', 'barang.id_barang = barang_keluar.id_barang', 'left');
        $builder->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left');
        $builder->join('user', 'user.id_user = barang_keluar.id_user', 'left');
        
        // Filter tanggal
        if (!empty($filter['start_date']) && !empty($filter['end_date'])) {
            $builder->where('barang_keluar.tanggal_keluar >=', $filter['start_date']);
            $builder->where('barang_keluar.tanggal_keluar <=', $filter['end_date']);
        }
        
        // Filter barang
        if (!empty($filter['id_barang'])) {
            $builder->where('barang_keluar.id_barang', $filter['id_barang']);
        }
        
        // Pencarian
        if (!empty($filter['search'])) {
            $builder->groupStart()
                    ->like('barang_keluar.id_barang_keluar', $filter['search'])
                    ->orLike('barang.nama_barang', $filter['search'])
                    ->groupEnd();
        }
        
        $builder->orderBy('barang_keluar.tanggal_keluar', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    // Get ringkasan
    public function getRingkasan($filter = [])
    {
        $builder = $this->db->table('barang_keluar');
        
        if (!empty($filter['start_date']) && !empty($filter['end_date'])) {
            $builder->where('tanggal_keluar >=', $filter['start_date']);
            $builder->where('tanggal_keluar <=', $filter['end_date']);
        }
        
        if (!empty($filter['id_barang'])) {
            $builder->where('id_barang', $filter['id_barang']);
        }
        
        $total_transaksi = $builder->countAllResults(false);
        $total_jumlah = $builder->selectSum('jumlah_keluar')->get()->getRowArray();
        
        return [
            'total_transaksi' => $total_transaksi,
            'total_jumlah' => $total_jumlah['jumlah_keluar'] ?? 0
        ];
    }
}