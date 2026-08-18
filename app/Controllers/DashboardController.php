<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\BarangMasukModel;
use App\Models\BarangKeluarModel;
use App\Models\JenisBarangModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use App\Models\SatuanModel;

class DashboardController extends BaseController
{
    protected $barangModel;
    protected $barangMasukModel;
    protected $barangKeluarModel;
    protected $jenisBarangModel;
    protected $supplierModel;
    protected $userModel;
    protected $satuanModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
        $this->barangMasukModel = new BarangMasukModel();
        $this->barangKeluarModel = new BarangKeluarModel();
        $this->jenisBarangModel = new JenisBarangModel();
        $this->supplierModel = new SupplierModel();
        $this->userModel = new UserModel();
        $this->satuanModel = new SatuanModel();
    }

    public function index()
    {
        
        // Cek apakah sudah login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Data statistik utama
        $data = [
            'title' => 'Dashboard',
            'page_title' => 'Dashboard',
            'active_menu' => 'dashboard',
            
            // Statistik utama
            'total_barang' => $this->barangModel->countAll(),
            'total_jenis_barang' => $this->jenisBarangModel->countAll(),
            'total_supplier' => $this->supplierModel->countAll(),
            'total_user' => $this->userModel->where('status', 'aktif')->countAllResults(),
            'total_satuan' => $this->satuanModel->countAll(),
            
            // Statistik barang masuk (bulan ini)
            'barang_masuk_bulan_ini' => $this->barangMasukModel->where('MONTH(tanggal_masuk)', date('m'))->where('YEAR(tanggal_masuk)', date('Y'))->countAllResults(),
            'total_barang_masuk' => $this->barangMasukModel->selectSum('jumlah_masuk')->where('MONTH(tanggal_masuk)', date('m'))->where('YEAR(tanggal_masuk)', date('Y'))->first()['jumlah_masuk'] ?? 0,
            
            // Statistik barang keluar (bulan ini)
            'barang_keluar_bulan_ini' => $this->barangKeluarModel->where('MONTH(tanggal_keluar)', date('m'))->where('YEAR(tanggal_keluar)', date('Y'))->countAllResults(),
            'total_barang_keluar' => $this->barangKeluarModel->selectSum('jumlah_keluar')->where('MONTH(tanggal_keluar)', date('m'))->where('YEAR(tanggal_keluar)', date('Y'))->first()['jumlah_keluar'] ?? 0,
            
            // Stok menipis (stok <= 10)
            'stok_menipis' => $this->barangModel->getStokMenipis(10),
            'total_stok_menipis' => count($this->barangModel->getStokMenipis()),
            
            // Stok habis (stok = 0)
            'stok_habis' => $this->barangModel->getStokHabis(),
            'total_stok_habis' => count($this->barangModel->getStokHabis()),
            
            // Transaksi terbaru
            'transaksi_terbaru' => $this->getTransaksiTerbaru(),
            
            // Data untuk grafik (7 hari terakhir)
            'grafik_masuk' => $this->getGrafikBarangMasuk(),
            'grafik_keluar' => $this->getGrafikBarangKeluar(),
            
            // Data per jenis barang
            'data_per_jenis' => $this->jenisBarangModel->getAllWithCount(),
            
            // 5 barang terbanyak stoknya
            'barang_terbanyak' => $this->barangModel->getTopStok(5),
            
            // 5 barang tersedikit stoknya
            'barang_tersedikit' => $this->barangModel->getBottomStok(5),
            
            // Statistik user
            'statistik_user' => $this->userModel->getStatistik(),
        ];
        
        return view('dashboard', $data);
    }

    private function getTransaksiTerbaru()
    {
        $masuk = $this->barangMasukModel
            ->select('
                barang_masuk.id_barang_masuk as kode_transaksi,
                barang_masuk.tanggal_masuk as tanggal,
                barang_masuk.jumlah_masuk as jumlah,
                barang.nama_barang,
                "Masuk" as jenis_transaksi,
                barang_masuk.created_at
            ')
            ->join('barang', 'barang.id_barang = barang_masuk.id_barang', 'left')
            ->orderBy('barang_masuk.tanggal_masuk', 'DESC')
            ->orderBy('barang_masuk.created_at', 'DESC')
            ->limit(5)
            ->findAll();
        
        $keluar = $this->barangKeluarModel
            ->select('
                barang_keluar.id_barang_keluar as kode_transaksi,
                barang_keluar.tanggal_keluar as tanggal,
                barang_keluar.jumlah_keluar as jumlah,
                barang.nama_barang,
                "Keluar" as jenis_transaksi,
                barang_keluar.created_at
            ')
            ->join('barang', 'barang.id_barang = barang_keluar.id_barang', 'left')
            ->orderBy('barang_keluar.tanggal_keluar', 'DESC')
            ->orderBy('barang_keluar.created_at', 'DESC')
            ->limit(5)
            ->findAll();
        
        // Gabungkan dan urutkan
        $transaksi = array_merge($masuk, $keluar);
        usort($transaksi, function($a, $b) {
            $tglA = strtotime($a['tanggal'] . ' ' . ($a['created_at'] ?? '00:00:00'));
            $tglB = strtotime($b['tanggal'] . ' ' . ($b['created_at'] ?? '00:00:00'));
            return $tglB - $tglA;
        });
        
        return array_slice($transaksi, 0, 10);
    }

    private function getGrafikBarangMasuk()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = date('Y-m-d', strtotime("-$i days"));
            $total = $this->barangMasukModel
                ->selectSum('jumlah_masuk')
                ->where('DATE(tanggal_masuk)', $tanggal)
                ->first()['jumlah_masuk'] ?? 0;
            
            $data[] = [
                'tanggal' => date('d/m', strtotime($tanggal)),
                'total' => (int)$total
            ];
        }
        return $data;
    }

    private function getGrafikBarangKeluar()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = date('Y-m-d', strtotime("-$i days"));
            $total = $this->barangKeluarModel
                ->selectSum('jumlah_keluar')
                ->where('DATE(tanggal_keluar)', $tanggal)
                ->first()['jumlah_keluar'] ?? 0;
            
            $data[] = [
                'tanggal' => date('d/m', strtotime($tanggal)),
                'total' => (int)$total
            ];
        }
        return $data;
    }

    // API untuk dashboard (AJAX)
    public function getStats()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'total_barang' => $this->barangModel->countAll(),
            'total_barang_masuk' => $this->barangMasukModel->selectSum('jumlah_masuk')->first()['jumlah_masuk'] ?? 0,
            'total_barang_keluar' => $this->barangKeluarModel->selectSum('jumlah_keluar')->first()['jumlah_keluar'] ?? 0,
            'total_supplier' => $this->supplierModel->countAll(),
            'total_user' => $this->userModel->countAll(),
        ];

        return $this->response->setJSON($data);
    }

    // Laporan stok
    public function laporanStok()
    {
        $data = [
            'title' => 'Laporan Stok Barang',
            'page_title' => 'Laporan Stok Barang',
            'active_menu' => 'laporan',
            'barang' => $this->barangModel->getBarangWithRelation(),
            'stok_menipis' => $this->barangModel->getStokMenipis(),
            'stok_habis' => $this->barangModel->getStokHabis(),
        ];

        return view('laporan/stok', $data);
    }

    // Laporan barang masuk
    public function laporanMasuk()
    {
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-d');

        $data = [
            'title' => 'Laporan Barang Masuk',
            'page_title' => 'Laporan Barang Masuk',
            'active_menu' => 'laporan',
            'laporan' => $this->barangMasukModel->getLaporanPeriode($start_date, $end_date),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'total_jumlah' => $this->barangMasukModel->selectSum('jumlah_masuk')->where('tanggal_masuk >=', $start_date)->where('tanggal_masuk <=', $end_date)->first()['jumlah_masuk'] ?? 0,
        ];

        return view('laporan/masuk', $data);
    }

    // Laporan barang keluar
    public function laporanKeluar()
    {
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-d');

        $data = [
            'title' => 'Laporan Barang Keluar',
            'page_title' => 'Laporan Barang Keluar',
            'active_menu' => 'laporan',
            'laporan' => $this->barangKeluarModel->getLaporanPeriode($start_date, $end_date),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'total_jumlah' => $this->barangKeluarModel->selectSum('jumlah_keluar')->where('tanggal_keluar >=', $start_date)->where('tanggal_keluar <=', $end_date)->first()['jumlah_keluar'] ?? 0,
        ];

        return view('laporan/keluar', $data);
    }
}

