<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanMasukModel extends Model
{
    protected $table = 'barang_masuk';
    protected $primaryKey = 'id_barang_masuk';
    protected $allowedFields = [
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

    // Get laporan barang masuk
    public function getLaporanBarangMasuk($filter = [])
    {
        $builder = $this->db->table('barang_masuk');
        $builder->select('
            barang_masuk.*,
            supplier.nama_supplier,
            barang.nama_barang,
            barang.warna,
            satuan.nama_satuan,
            user.nama as nama_user
        ');
        $builder->join('supplier', 'supplier.id_supplier = barang_masuk.id_supplier', 'left');
        $builder->join('barang', 'barang.id_barang = barang_masuk.id_barang', 'left');
        $builder->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left');
        $builder->join('user', 'user.id_user = barang_masuk.id_user', 'left');
        
        // Filter tanggal
        if (!empty($filter['start_date']) && !empty($filter['end_date'])) {
            $builder->where('barang_masuk.tanggal_masuk >=', $filter['start_date']);
            $builder->where('barang_masuk.tanggal_masuk <=', $filter['end_date']);
        }
        
        // Filter supplier
        if (!empty($filter['id_supplier'])) {
            $builder->where('barang_masuk.id_supplier', $filter['id_supplier']);
        }
        
        // Pencarian
        if (!empty($filter['search'])) {
            $builder->groupStart()
                    ->like('barang_masuk.id_barang_masuk', $filter['search'])
                    ->orLike('supplier.nama_supplier', $filter['search'])
                    ->orLike('barang.nama_barang', $filter['search'])
                    ->groupEnd();
        }
        
        $builder->orderBy('barang_masuk.tanggal_masuk', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    // Get ringkasan
    public function getRingkasan($filter = [])
    {
        $builder = $this->db->table('barang_masuk');
        
        if (!empty($filter['start_date']) && !empty($filter['end_date'])) {
            $builder->where('tanggal_masuk >=', $filter['start_date']);
            $builder->where('tanggal_masuk <=', $filter['end_date']);
        }
        
        if (!empty($filter['id_supplier'])) {
            $builder->where('id_supplier', $filter['id_supplier']);
        }
        
        $total_transaksi = $builder->countAllResults(false);
        $total_jumlah = $builder->selectSum('jumlah_masuk')->get()->getRowArray();
        
        return [
            'total_transaksi' => $total_transaksi,
            'total_jumlah' => $total_jumlah['jumlah_masuk'] ?? 0
        ];
    }
}