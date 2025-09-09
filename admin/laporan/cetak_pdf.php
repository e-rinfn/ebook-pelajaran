<?php
require_once '../../config/database.php';
require_once '../../includes/auth-check.php';
require_once '../../vendor/autoload.php'; // Pastikan TCPDF sudah diinstall via composer

use TCPDF as TCPDF;

function dateIndo($tanggal)
{
    $bulanIndo = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];
    $tanggal = date('Y-m-d', strtotime($tanggal));
    $pecah = explode('-', $tanggal);
    return $pecah[2] . ' ' . $bulanIndo[(int)$pecah[1]] . ' ' . $pecah[0];
}


$search = $_GET['search'] ?? '';

// Query data ebook
$query = "SELECT ebook.*, GROUP_CONCAT(kategori.nama SEPARATOR ', ') as kategori_nama 
          FROM ebook 
          LEFT JOIN ebook_kategori ON ebook.id = ebook_kategori.ebook_id 
          LEFT JOIN kategori ON ebook_kategori.kategori_id = kategori.id 
          WHERE ebook.judul LIKE ? OR ebook.penulis LIKE ?
          GROUP BY ebook.id";
$stmt = $pdo->prepare($query);
$stmt->execute(["%$search%", "%$search%"]);
$ebooks = $stmt->fetchAll(PDO::FETCH_ASSOC);

class InventoryPDF extends TCPDF
{
    public function Header()
    {
        $this->SetFont('times', '', 10);
        $image_file = '../../assets/images/Logo.png';
        if (file_exists($image_file)) {
            $this->Image($image_file, 15, 10, 20, '', 'PNG');
        }

        $this->SetXY(40, 10);
        $this->SetFont('times', 'B', 12);
        $this->SetX(66);
        $this->Cell(0, 5, 'PEMERINTAH KABUPATEN TASIKMALAYA', 0, 1, 'L');
        $this->Cell(0, 5, 'DINAS PENDIDIKAN', 0, 1, 'C');
        $this->SetFont('times', 'B', 14);
        $this->Cell(0, 6, 'SMP FDS AL-MANSHURIYAH CIMANGGU', 0, 1, 'C');
        $this->SetFont('times', '', 10);
        $this->Cell(0, 5, 'Jl. Raya Sukaresik No. 123, Kabupaten Tasikmalaya, Jawa Barat', 0, 1, 'C');
        $this->Cell(0, 5, 'Telp. (0265) 7654321 | Email: smpfdsmanshuriyahtasikmalaya@example.com', 0, 1, 'C');
        $this->Line(10, 42, 200, 42);
        $this->Ln(5);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('times', 'I', 8);
        $this->Cell(0, 10, 'Halaman ' . $this->getAliasNumPage() . ' dari ' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

$pdf = new InventoryPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Laporan Transaksi Barang');
$pdf->SetMargins(15, 50, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->AddPage();

// Report title
$pdf->SetFont('times', 'B', 14);
$pdf->Cell(0, 10, 'LAPORAN DAFTAR EBOOK', 0, 1, 'C');
$pdf->SetFont('times', '', 11);
// $pdf->Cell(0, 6, 'Periode: ' . date('d F Y', strtotime($tanggal_awal)) . ' - ' . date('d F Y', strtotime($tanggal_akhir)), 0, 1, 'C');

// Filter information
// $filterText = '';
// if ($jenis) {
//     $filterText .= 'Jenis: ' . ucfirst($jenis) . '; ';
// }
// if ($barang > 0) {
//     $barang_name = '';
//     foreach ($barang_options as $item) {
//         if ($item['id_barang'] == $barang) {
//             $barang_name = $item['nama_barang'];
//             break;
//         }
//     }
//     $filterText .= 'Barang: ' . $barang_name . '; ';
// }

if (!empty($filterText)) {
    $pdf->Ln(2);
    $pdf->SetFont('times', '', 10);
    $pdf->writeHTML('<p style="font-style:italic;">' . rtrim($filterText, '; ') . '</p>', true, false, true, false, '');
}

// Transaction table
$pdf->Ln(3);
$pdf->SetFont('times', '', 10);

$html = '<table border="1" cellpadding="4" width="100%">
    <thead>
        <tr style="background-color:#f2f2f2;text-align:center;font-weight:bold;">
            <th width="5%">No</th>
            <th width="35%">Judul</th>
            <th width="20%">Penulis</th>
            <th width="15%">Kategori</th>
            <th width="10%">Tahun</th>
            <th width="15%">Tanggal Upload</th>
        </tr>
    </thead>
    <tbody>';

if (empty($ebooks)) {
    $html .= '<tr><td colspan="9" style="text-align:center;">Tidak ada data ebooks</td></tr>';
} else {
    $no = 1;
    foreach ($ebooks as $ebook) {
        $html .= '<tr>
                <td width="5%" style="text-align:center;">' . ($no++) . '</td>
                <td width="35%">' . htmlspecialchars($ebook['judul']) . '</td>
                <td width="20%">' . htmlspecialchars($ebook['penulis']) . '</td>
                <td width="15%">' . $ebook['kategori_nama'] . '</td>
                <td width="10%">' . htmlspecialchars($ebook['tahun_terbit']) . '</td>
                <td width="15%">' . dateIndo($ebook['created_at']) . '</td>
            </tr>';
    }
}

$html .= '</tbody></table>';
$pdf->writeHTML($html, true, false, true, false, '');

// Signature section
$pdf->Ln(10);
$pdf->Cell(0, 0, 'Tasikmalaya, ' . dateIndo('now'), 0, 1, 'R');
$pdf->Ln(10);
$pdf->SetFont('times', 'B', 10);
$pdf->Cell(0, 0, 'Kepala Sekolah', 0, 1, 'R');
$pdf->Ln(15);
$pdf->SetFont('times', 'BU', 10);
$pdf->Cell(0, 0, 'Nama Kepala Sekolah', 0, 1, 'R');
$pdf->Ln(5);
$pdf->SetFont('times', '', 10);
$pdf->Cell(0, 0, 'NIP. 1234567890', 0, 1, 'R');

// Output PDF
$filename = "Laporan_Transaksi_Barang_" . date('Ymd_His') . ".pdf";
$pdf->Output($filename, 'I');
