<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PendaftaranModel;
use App\Models\TahunAjaranModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Laporan extends BaseController
{
    protected $pendaftaranModel;
    protected $tahunAjaranModel;

    public function __construct()
    {
        $this->pendaftaranModel = new PendaftaranModel();
        $this->tahunAjaranModel = new TahunAjaranModel();
        helper(['auth', 'form']);
    }

    /**
     * Page filter dan view laporan
     */
    public function index()
    {
        // Cek role
        if (!is_admin()) {
            return redirect()->to('/user/dashboard');
        }

        // Get tahun ajaran
        $tahunAjaran = $this->tahunAjaranModel->findAll();

        $data = [
            'title' => 'Laporan Pendaftaran',
            'tahun_ajaran' => $tahunAjaran
        ];

        return view('admin/laporan/index', $data);
    }

    /**
     * Export data ke PDF
     */
    public function exportPdf()
    {
        // Cek role
        if (!is_admin()) {
            return redirect()->to('/user/dashboard');
        }

        // Get filters
        $filters = [
            'tahun_ajaran_id' => $this->request->getGet('tahun_ajaran_id') ?? '',
            'status_pendaftaran' => $this->request->getGet('status_pendaftaran') ?? '',
            'jenis_kelamin' => $this->request->getGet('jenis_kelamin') ?? '',
            'agama' => $this->request->getGet('agama') ?? '',
            'search' => $this->request->getGet('search') ?? '',
            'date_from' => $this->request->getGet('date_from') ?? '',
            'date_to' => $this->request->getGet('date_to') ?? '',
            'sort_by' => 'created_at',
            'sort_order' => 'DESC'
        ];

        // Get data
        $data = $this->pendaftaranModel->getFiltered($filters, null, 0)->find();

        // Load dompdf
        $dompdf = new \Dompdf\Dompdf();

        // Get HTML dari view
        $html = view('admin/laporan/pdf', [
            'pendaftaran' => $data,
            'filters' => $filters,
            'tahun_ajaran' => $this->tahunAjaranModel->find($filters['tahun_ajaran_id'] ?? null),
            'tanggal_cetak' => date('d-m-Y H:i:s')
        ]);

        // Load HTML ke dompdf
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Output file
        $dompdf->stream('Laporan_PPDB_' . date('Ymd_His') . '.pdf', ['Attachment' => 0]);
    }

    /**
     * Export data ke Excel
     */
    public function exportExcel()
    {
        // Cek role
        if (!is_admin()) {
            return redirect()->to('/user/dashboard');
        }

        // Get filters
        $filters = [
            'tahun_ajaran_id' => $this->request->getGet('tahun_ajaran_id') ?? '',
            'status_pendaftaran' => $this->request->getGet('status_pendaftaran') ?? '',
            'jenis_kelamin' => $this->request->getGet('jenis_kelamin') ?? '',
            'agama' => $this->request->getGet('agama') ?? '',
            'search' => $this->request->getGet('search') ?? '',
            'date_from' => $this->request->getGet('date_from') ?? '',
            'date_to' => $this->request->getGet('date_to') ?? '',
            'sort_by' => 'nomor_pendaftaran',
            'sort_order' => 'ASC'
        ];

        // Get data
        $pendaftaran = $this->pendaftaranModel->getFiltered($filters, null, 0)->find();

        // Create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set title
        $sheet->setTitle('Laporan PPDB');

        // Header styling
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '366092']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];

        // Headers
        $headers = [
            'No',
            'Nomor Pendaftaran',
            'Nama Lengkap',
            'NIK',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Agama',
            'Alamat',
            'Email Orang Tua',
            'Status Pendaftaran',
            'Tanggal Pendaftaran'
        ];

        // Set headers
        foreach ($headers as $index => $header) {
            $cell = $sheet->getCellByColumnAndRow($index + 1, 1);
            $cell->setValue($header);
            $cell->setStyle($headerStyle);
        }

        // Set column widths
        $widths = [5, 18, 20, 16, 15, 12, 12, 30, 20, 18, 18];
        foreach ($widths as $index => $width) {
            $sheet->getColumnByIndex($index + 1)->setWidth($width);
        }

        // Data rows
        $row = 2;
        foreach ($pendaftaran as $index => $p) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $p->nomor_pendaftaran);
            $sheet->setCellValue('C' . $row, $p->nama_lengkap);
            $sheet->setCellValue('D' . $row, $p->nik);
            $sheet->setCellValue('E' . $row, date('d-m-Y', strtotime($p->tanggal_lahir)));
            $sheet->setCellValue('F' . $row, $p->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan');
            $sheet->setCellValue('G' . $row, $p->agama);
            $sheet->setCellValue('H' . $row, $p->alamat);
            $sheet->setCellValue('I' . $row, $p->email ?? '-');
            $sheet->setCellValue('J' . $row, ucfirst(str_replace('_', ' ', $p->status_pendaftaran)));
            $sheet->setCellValue('K' . $row, date('d-m-Y H:i', strtotime($p->created_at)));

            // Style data rows
            $sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP, 'wrapText' => true]
            ]);

            $row++;
        }

        // Freeze header row
        $sheet->freezePane('A2');

        // Create writer
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        $filename = 'Laporan_PPDB_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Output to browser
        $writer->save('php://output');
        exit;
    }

    /**
     * Get laporan statistik
     */
    public function getStatistik()
    {
        // Cek role
        if (!is_admin()) {
            return redirect()->to('/user/dashboard');
        }

        $tahunAjaranAktif = $this->tahunAjaranModel->getActive();

        if (!$tahunAjaranAktif) {
            return [
                'total_pendaftar' => 0,
                'laki_laki' => 0,
                'perempuan' => 0,
                'by_status' => []
            ];
        }

        $pendaftaran = $this->pendaftaranModel
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->findAll();

        $stats = [
            'total_pendaftar' => count($pendaftaran),
            'laki_laki' => 0,
            'perempuan' => 0,
            'by_status' => [],
            'by_agama' => []
        ];

        foreach ($pendaftaran as $p) {
            if ($p->jenis_kelamin === 'L') {
                $stats['laki_laki']++;
            } else {
                $stats['perempuan']++;
            }

            if (!isset($stats['by_status'][$p->status_pendaftaran])) {
                $stats['by_status'][$p->status_pendaftaran] = 0;
            }
            $stats['by_status'][$p->status_pendaftaran]++;

            if (!isset($stats['by_agama'][$p->agama])) {
                $stats['by_agama'][$p->agama] = 0;
            }
            $stats['by_agama'][$p->agama]++;
        }

        return $stats;
    }
}
