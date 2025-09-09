<?php
require_once '../../config/database.php';
require_once '../../includes/auth-check.php';
require_once '../../vendor/autoload.php'; // Pastikan TCPDF sudah diinstall via composer

use TCPDF as TCPDF;

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

// Create new PDF document
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistem E-Book');
$pdf->SetTitle('Laporan E-Book');
$pdf->SetSubject('Laporan Daftar E-Book');

// Set default header data
$pdf->SetHeaderData('', 0, 'LAPORAN DAFTAR E-BOOK', 'Dicetak pada: ' . date('d/m/Y H:i:s'));

// Set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(15, 25, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a page
$pdf->AddPage();

// Set content
$html = '<h3 style="text-align:center;">LAPORAN DAFTAR E-BOOK</h3>';
if (!empty($search)) {
    $html .= '<p style="text-align:center;">Hasil pencarian: <b>' . htmlspecialchars($search) . '</b></p>';
}

// Table header
$html .= '<table border="1" cellpadding="5" style="border-collapse:collapse;">';
$html .= '<tr style="background-color:#f2f2f2;">';
$html .= '<th width="5%"><b>No</b></th>';
$html .= '<th width="15%"><b>Judul</b></th>';
$html .= '<th width="12%"><b>Penulis</b></th>';
$html .= '<th width="15%"><b>Kategori</b></th>';
$html .= '<th width="8%"><b>Tahun</b></th>';
$html .= '<th width="15%"><b>Penerbit</b></th>';
$html .= '<th width="15%"><b>ISBN</b></th>';
$html .= '<th width="15%"><b>Tanggal Upload</b></th>';
$html .= '</tr>';

// Table content
$no = 1;
foreach ($ebooks as $ebook) {
    $html .= '<tr>';
    $html .= '<td>' . $no++ . '</td>';
    $html .= '<td>' . htmlspecialchars($ebook['judul']) . '</td>';
    $html .= '<td>' . htmlspecialchars($ebook['penulis']) . '</td>';
    $html .= '<td>' . ($ebook['kategori_nama'] ?? '-') . '</td>';
    $html .= '<td>' . $ebook['tahun_terbit'] . '</td>';
    $html .= '<td>' . htmlspecialchars($ebook['penerbit'] ?? '-') . '</td>';
    $html .= '<td>' . htmlspecialchars($ebook['isbn'] ?? '-') . '</td>';
    $html .= '<td>' . date('d/m/Y', strtotime($ebook['created_at'])) . '</td>';
    $html .= '</tr>';
}

$html .= '</table>';

// Print text using writeHTMLCell()
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('laporan_ebook_' . date('Ymd_His') . '.pdf', 'I');
