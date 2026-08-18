<?php

namespace App\Controllers;

use App\Models\LaporanKeluarModel;
use App\Models\BarangModel;

class LaporanKeluarController extends BaseController
{
    protected $laporanKeluarModel;
    protected $barangModel;

    public function __construct()
    {
        $this->laporanKeluarModel = new LaporanKeluarModel();
        $this->barangModel = new BarangModel();
    }

    // Halaman utama laporan
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $filter = [
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
            'id_barang' => $this->request->getGet('id_barang'),
            'search' => $this->request->getGet('search')
        ];

        $filter = array_filter($filter);

        $data = [
            'title' => 'Laporan Barang Keluar',
            'page_title' => 'Laporan Barang Keluar',
            'active_menu' => 'laporan_keluar',
            'laporan' => $this->laporanKeluarModel->getLaporanBarangKeluar($filter),
            'ringkasan' => $this->laporanKeluarModel->getRingkasan($filter),
            'barang' => $this->barangModel->findAll(),
            'filter' => $filter,
            'level' => session()->get('level')
        ];

        return view('laporan_keluar/index', $data);
    }

    // Export ke Excel
    public function exportExcel()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $filter = [
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
            'id_barang' => $this->request->getGet('id_barang'),
            'search' => $this->request->getGet('search')
        ];

        $filter = array_filter($filter);
        $laporan = $this->laporanKeluarModel->getLaporanBarangKeluar($filter);
        $ringkasan = $this->laporanKeluarModel->getRingkasan($filter);

        $filename = 'laporan_barang_keluar_' . date('Ymd_His') . '.xls';

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        echo "<table border='1'>";
        echo "<tr><th colspan='8' style='text-align:center;'>LAPORAN BARANG KELUAR</th></tr>";
        echo "<tr><th colspan='8' style='text-align:center;'>Tanggal Cetak: " . date('d/m/Y H:i:s') . "</th></tr>";
        echo "<tr>
                <th>No</th>
                <th>Tanggal Keluar</th>
                <th>Kode Transaksi</th>
                <th>Nama Barang</th>
                <th>Warna</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Input Oleh</th>
              <tr>";

        $no = 1;
        foreach ($laporan as $item) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($item['tanggal_keluar'])) . "</td>";
            echo "<td>" . esc($item['id_barang_keluar']) . "</td>";
            echo "<td>" . esc($item['nama_barang']) . "</td>";
            echo "<td>" . esc($item['warna'] ?? '-') . "</td>";
            echo "<td style='text-align:right'>" . number_format($item['jumlah_keluar']) . "</td>";
            echo "<td>" . esc($item['nama_satuan'] ?? '-') . "</td>";
            echo "<td>" . esc($item['nama_user'] ?? '-') . "</td>";
            echo "</tr>";
        }

        echo "<tr><td colspan='5' style='text-align:right'><strong>Total Barang Keluar:</strong></td>";
        echo "<td colspan='2'><strong>" . number_format($ringkasan['total_jumlah']) . "</strong></td>";
        echo "<tr>";

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
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
            'id_barang' => $this->request->getGet('id_barang'),
            'search' => $this->request->getGet('search')
        ];

        $filter = array_filter($filter);

        $barang_nama = '';
        if (!empty($filter['id_barang'])) {
            $barang = $this->barangModel->find($filter['id_barang']);
            $barang_nama = $barang['nama_barang'] ?? '';
        }

        $data = [
            'title' => 'Laporan Barang Keluar',
            'laporan' => $this->laporanKeluarModel->getLaporanBarangKeluar($filter),
            'ringkasan' => $this->laporanKeluarModel->getRingkasan($filter),
            'filter' => $filter,
            'barang_nama' => $barang_nama,
            'tanggal_cetak' => date('d/m/Y H:i:s'),
            'level' => session()->get('level')
        ];

        return view('laporan_keluar/print', $data);
    }
}