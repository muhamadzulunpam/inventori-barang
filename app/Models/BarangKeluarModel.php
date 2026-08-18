<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangKeluarModel extends Model
{
    protected $table = 'barang_keluar';
    protected $primaryKey = 'id_barang_keluar';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_barang_keluar',
        'id_barang',   
        'id_user',
        'jumlah_keluar',
        'tanggal_keluar',
        'keterangan'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $deletedField = null;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Matikan validasi untuk sementara
    protected $skipValidation = false;
    
    protected $validationRules = [
        'id_barang' => 'required',
        'jumlah_keluar' => 'required|numeric|greater_than[0]',
        'tanggal_keluar' => 'required|valid_date'
    ];

    protected $validationMessages = [
        'id_barang' => ['required' => 'Barang wajib dipilih'],
        'jumlah_keluar' => [
            'required' => 'Jumlah wajib diisi',
            'numeric' => 'Jumlah harus berupa angka',
            'greater_than' => 'Jumlah harus lebih dari 0'
        ],
        'tanggal_keluar' => [
            'required' => 'Tanggal wajib diisi',
            'valid_date' => 'Tanggal tidak valid'
        ]
    ];

    // Get semua barang keluar dengan relasi
    public function getBarangKeluarWithRelations($id = null)
{
    $builder = $this->select('barang_keluar.*,
                              barang.nama_barang,
                              barang.stok as stok_sekarang,
                              satuan.nama_satuan,
                              user.nama as nama_user')
                    ->join('barang', 'barang.id_barang = barang_keluar.id_barang', 'left')
                    ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
                    ->join('user', 'user.id_user = barang_keluar.id_user', 'left');

    if ($id) {
        return $builder->where('barang_keluar.id_barang_keluar', $id)
                       ->first();
    }

    return $builder->findAll();
}


    public function getBarangKeluarWithRelationFilter($filter = [])
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            barang_keluar.*,
            barang.nama_barang,
            barang.warna,
            barang.stok as stok_sekarang,
            satuan.nama_satuan,
            user.nama as nama_user
        ');
        $builder->join('barang', 'barang.id_barang = barang_keluar.id_barang', 'left');
        $builder->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left');
        $builder->join('user', 'user.id_user = barang_keluar.id_user', 'left');
        
        // Filter bulan
        if (!empty($filter['bulan'])) {
            $builder->where('MONTH(barang_keluar.tanggal_keluar)', $filter['bulan']);
        }
        
        // Filter tahun
        if (!empty($filter['tahun'])) {
            $builder->where('YEAR(barang_keluar.tanggal_keluar)', $filter['tahun']);
        }
        
        // Filter pencarian
        if (!empty($filter['search'])) {
            $builder->groupStart()
                    ->like('barang_keluar.id_barang_keluar', $filter['search'])
                    ->orLike('barang.nama_barang', $filter['search'])
                    ->groupEnd();
        }
        
        $builder->orderBy('barang_keluar.tanggal_keluar', 'DESC');
        $builder->orderBy('barang_keluar.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }

    // Get laporan per periode
    public function getLaporanPeriode($start_date, $end_date)
    {
        return $this->db->table($this->table)
            ->select('
                barang_keluar.*, 
                barang.nama_barang,
                barang.warna,
                satuan.nama_satuan,
                user.nama as nama_user
            ')
            ->join('barang', 'barang.id_barang = barang_keluar.id_barang', 'left')
            ->join('satuan', 'satuan.id_satuan = barang.id_satuan', 'left')
            ->join('user', 'user.id_user = barang_keluar.id_user', 'left')
            ->where('barang_keluar.tanggal_keluar >=', $start_date)
            ->where('barang_keluar.tanggal_keluar <=', $end_date)
            ->orderBy('barang_keluar.tanggal_keluar', 'DESC')
            ->get()
            ->getResultArray();
    }

    // Get total per bulan
    public function getTotalPerBulan($bulan = null, $tahun = null)
    {
        $bulan = $bulan ?? date('m');
        $tahun = $tahun ?? date('Y');
        
        $result = $this->selectSum('jumlah_keluar')
                    ->where('MONTH(tanggal_keluar)', $bulan)
                    ->where('YEAR(tanggal_keluar)', $tahun)
                    ->first();
        
        return $result['jumlah_keluar'] ?? 0;
    }

    // Get total per hari
    public function getTotalPerHari($tanggal = null)
    {
        $tanggal = $tanggal ?? date('Y-m-d');
        
        $result = $this->selectSum('jumlah_keluar')
                    ->where('DATE(tanggal_keluar)', $tanggal)
                    ->first();
        
        return $result['jumlah_keluar'] ?? 0;
    }

    // Get ringkasan untuk dashboard
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
            'total_keseluruhan' => $this->selectSum('jumlah_keluar')->first()['jumlah_keluar'] ?? 0
        ];
    }
    public function generateIdBarangKeluar()
{
    // Format:
    // TYYMM####
    // Contoh:
    // 626050001
    // 6  = barang keluar
    // 26 = tahun
    // 05 = bulan
    // 0001 = urutan

    $tipe  = '6';
    $tahun = date('y');
    $bulan = date('m');

    $prefix = $tipe . $tahun . $bulan;

    // Ambil ID terakhir sesuai prefix
    $lastBarangKeluar = $this->like('id_barang_keluar', $prefix, 'after')
                            ->orderBy('id_barang_keluar', 'DESC')
                            ->first();

    if ($lastBarangKeluar && isset($lastBarangKeluar['id_barang_keluar'])) {

        // Ambil 4 digit terakhir
        $lastNumber = (int) substr($lastBarangKeluar['id_barang_keluar'], -4);

        $newNumber = $lastNumber + 1;

    } else {

        $newNumber = 1;
    }

    // Generate ID baru
    $idBarangKeluar = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

    return $idBarangKeluar;
}
}   