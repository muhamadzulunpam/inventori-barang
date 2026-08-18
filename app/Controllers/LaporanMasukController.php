<?php

namespace App\Controllers;

use App\Models\LaporanMasukModel;
use App\Models\SupplierModel;
use App\Models\BarangModel;

class LaporanMasukController extends BaseController
{
    protected $laporanMasukModel;
    protected $supplierModel;
    protected $barangModel;

    public function __construct()
    {
        $this->laporanMasukModel = new LaporanMasukModel();
        $this->supplierModel = new SupplierModel();
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
            'id_supplier' => $this->request->getGet('id_supplier'),
            'search' => $this->request->getGet('search')
        ];

        $filter = array_filter($filter);

        $data = [
            'title' => 'Laporan Barang Masuk',
            'page_title' => 'Laporan Barang Masuk',
            'active_menu' => 'laporan_masuk',
            'laporan' => $this->laporanMasukModel->getLaporanBarangMasuk($filter),
            'ringkasan' => $this->laporanMasukModel->getRingkasan($filter),
            'supplier' => $this->supplierModel->findAll(),
            'filter' => $filter,
            'level' => session()->get('level')
        ];

        return view('laporan_masuk/index', $data);
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
            'id_supplier' => $this->request->getGet('id_supplier'),
            'search' => $this->request->getGet('search')
        ];

        $filter = array_filter($filter);
        $laporan = $this->laporanMasukModel->getLaporanBarangMasuk($filter);
        $ringkasan = $this->laporanMasukModel->getRingkasan($filter);

        $filename = 'laporan_barang_masuk_' . date('Ymd_His') . '.xls';

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        echo "<table border='1'>";
        echo "<tr><th colspan='9' style='text-align:center;'>LAPORAN BARANG MASUK</th></tr>";
        echo "<tr><th colspan='9' style='text-align:center;'>Tanggal Cetak: " . date('d/m/Y H:i:s') . "</th></tr>";
        echo "<tr>
                <th>No</th>
                <th>Tanggal Masuk</th>
                <th>Kode Transaksi</th>
                <th>Supplier</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Input Oleh</th>
                <th>Keterangan</th>
              </tr>";

        $no = 1;
        foreach ($laporan as $item) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($item['tanggal_masuk'])) . "</td>";
            echo "<td>" . esc($item['id_barang_masuk']) . "</td>";
            echo "<td>" . esc($item['nama_supplier'] ?? '-') . "</td>";
            echo "<td>" . esc($item['nama_barang']) . "</td>";
            echo "<td style='text-align:right'>" . number_format($item['jumlah_masuk']) . "</td>";
            echo "<td>" . esc($item['nama_satuan'] ?? '-') . "</td>";
            echo "<td>" . esc($item['nama_user'] ?? '-') . "</td>";
            echo "<td>" . esc($item['keterangan'] ?? '-') . "</td>";
            echo "</tr>";
        }

        echo "<tr><td colspan='6' style='text-align:right'><strong>Total Barang Masuk:</strong></td>";
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
            'id_supplier' => $this->request->getGet('id_supplier'),
            'search' => $this->request->getGet('search')
        ];

        $filter = array_filter($filter);

        $supplier_nama = '';
        if (!empty($filter['id_supplier'])) {
            $supplier = $this->supplierModel->find($filter['id_supplier']);
            $supplier_nama = $supplier['nama_supplier'] ?? '';
        }

        $data = [
            'title' => 'Laporan Barang Masuk',
            'laporan' => $this->laporanMasukModel->getLaporanBarangMasuk($filter),
            'ringkasan' => $this->laporanMasukModel->getRingkasan($filter),
            'filter' => $filter,
            'supplier_nama' => $supplier_nama,
            'tanggal_cetak' => date('d/m/Y H:i:s'),
            'level' => session()->get('level')
        ];

        return view('laporan_masuk/laporan_masuk', $data);
    }
}