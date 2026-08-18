<?php

namespace App\Controllers;

use App\Models\LaporanStokModel;
use App\Models\JenisBarangModel;

class LaporanStokController extends BaseController
{
    protected $laporanStokModel;
    protected $jenisBarangModel;

    public function __construct()
    {
        $this->laporanStokModel = new LaporanStokModel();
        $this->jenisBarangModel = new JenisBarangModel();
    }

    // Halaman utama laporan stok
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $filter = [
            'id_jenis' => $this->request->getGet('id_jenis'),
            'status' => $this->request->getGet('status'),
            'search' => $this->request->getGet('search')
        ];

        // Hapus filter yang kosong
        $filter = array_filter($filter);

        $data = [
            'title' => 'Laporan Stok Barang',
            'page_title' => 'Laporan Stok Barang',
            'active_menu' => 'laporan_stok',
            'stok_barang' => $this->laporanStokModel->getStokBarang($filter),
            'statistik' => $this->laporanStokModel->getRingkasanStok(),
            'stok_per_jenis' => $this->laporanStokModel->getStokPerJenis(),
            'total_nilai' => $this->laporanStokModel->getTotalNilaiStok($filter),
            'jenis_barang' => $this->jenisBarangModel->findAll(),
            'filter' => $filter,
            'level' => session()->get('level')
        ];

        return view('laporan_stok/index', $data);
    }

    // Export ke Excel
    public function exportExcel()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $filter = [
            'id_jenis' => $this->request->getGet('id_jenis'),
            'status' => $this->request->getGet('status'),
            'search' => $this->request->getGet('search')
        ];

        $filter = array_filter($filter);
        $stok_barang = $this->laporanStokModel->getStokBarang($filter);
        
        $filename = 'laporan_stok_' . date('Ymd_His') . '.xls';
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        
        echo "<table border='1'>";
        echo "</tr>
                <th colspan='10' style='text-align:center; font-size:16px;'>
                    LAPORAN STOK BARANG
                </th>
              </tr>";
        echo "<tr>
                <th colspan='10' style='text-align:center;'>
                    Tanggal Cetak: " . date('d/m/Y H:i:s') . "
                </th>
              </tr>";
        echo "<tr><td colspan='10'></td></tr>";
        echo "<tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Warna</th>
                <th>Jenis</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Min Stok</th>
                <th>Status</th>
                <th>Nilai Stok</th>
              </tr>";
        
        $no = 1;
        foreach ($stok_barang as $item) {
            // Tentukan status
            $stok_minimum = $item['stok_minimum'] ?? 0;
            if ($item['stok'] <= $stok_minimum && $item['stok'] > 0) {
                $status = 'Stok Menipis';
            } elseif ($item['stok'] == 0) {
                $status = 'Habis';
            } else {
                $status = 'Aman';
            }
            
            // Gunakan kolom 'harga' bukan 'harga_beli'
            $nilai_stok = $item['stok'] * ($item['harga'] ?? 0);
            
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $item['id_barang'] . "</td>";
            echo "<td>" . $item['nama_barang'] . "</td>";
            echo "<td>" . ($item['warna'] ?? '-') . "</td>";
            echo "<td>" . ($item['nama_jenis'] ?? '-') . "</td>";
            echo "<td>" . number_format($item['stok']) . "</td>";
            echo "<td>" . ($item['nama_satuan'] ?? '-') . "</td>";
            echo "<td>" . number_format($stok_minimum) . "</td>";
            echo "<td>" . $status . "</td>";
            echo "<td>Rp " . number_format($nilai_stok, 0, ',', '.') . "</td>";
            echo "</tr>";
        }
        
        echo "<tr><td colspan='10'></td></tr>";
        echo "<tr>
                <td colspan='9' style='text-align:right;'><strong>Total Nilai Stok:</strong></td>
                <td><strong>Rp " . number_format($this->laporanStokModel->getTotalNilaiStok($filter), 0, ',', '.') . "</strong></td>
              </tr>";
        
        echo "</table>";
        exit();
    }

    // Print laporan
    public function print()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $filter = [
            'id_jenis' => $this->request->getGet('id_jenis'),
            'status' => $this->request->getGet('status'),
            'search' => $this->request->getGet('search')
        ];

        $filter = array_filter($filter);

        // Ambil nama filter untuk ditampilkan
        $jenis_nama = '';
        if (!empty($filter['id_jenis'])) {
            $jenis = $this->jenisBarangModel->find($filter['id_jenis']);
            $jenis_nama = $jenis['nama_jenis'] ?? '';
        }
        
        $status_label = '';
        if (!empty($filter['status'])) {
            if ($filter['status'] == 'menipis') $status_label = 'Stok Menipis';
            elseif ($filter['status'] == 'habis') $status_label = 'Stok Habis';
            elseif ($filter['status'] == 'aman') $status_label = 'Stok Aman';
        }

        $data = [
            'title' => 'Laporan Stok Barang',
            'stok_barang' => $this->laporanStokModel->getStokBarang($filter),
            'ringkasan' => $this->laporanStokModel->getRingkasanStok(),
            'total_nilai' => $this->laporanStokModel->getTotalNilaiStok($filter),
            'filter' => $filter,
            'jenis_nama' => $jenis_nama,
            'status_label' => $status_label,
            'tanggal_cetak' => date('d/m/Y H:i:s'),
            'level' => session()->get('level')
        ];

        return view('laporan_stok/print', $data);
    }

    // API untuk chart data
    public function getChartData()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $stok_per_jenis = $this->laporanStokModel->getStokPerJenis();
        
        return $this->response->setJSON([
            'stok_per_jenis' => $stok_per_jenis,
        ]);
    }
}