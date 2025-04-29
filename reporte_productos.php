<?php
date_default_timezone_set("America/Bogota");
require 'fpdf/fpdf.php';
require 'db_connect.php';

class PDF extends FPDF {
// Cabecera de página
	function Header() {
		// // Logo
		// $this->Image('logo.png',10,8,33);
		// // Arial bold 15
		// $this->SetFont('Arial','B',15);
		// // Movernos a la derecha
		// $this->Cell(80);
		// // Título
		// $this->Cell(30,10,'Title',1,0,'C');
		// // Salto de línea
		// $this->Ln(20);
		$this->SetTitle(utf8_decode('Reporte de Productos'));
		$this->SetFont('Arial', 'B', 13);
		$this->Image('img/logo ricardo.png', 11, 10, 50);
		$this->Cell(190, 15, 'REPORTE DE PRODUCTOS', 0, 0, 'C', 0);
		$this->Ln(30);
		$this->SetFont('Arial', 'B', 11);
		$this->SetFillColor(91, 169, 138);
		$this->SetDrawColor(255, 255, 255);
		$this->Cell(6, 8, '#', 1, 0, 'C', 1);
		$this->Cell(22, 8, utf8_decode('Código'), 1, 0, 'C', 1);
		$this->Cell(84, 8, utf8_decode('Nombre Producto'), 1, 0, 'C', 1);
		//$this->Cell(22, 8, utf8_decode('Tipo Producto'), 1, 0, 'C', 1);
		$this->Cell(15, 8, utf8_decode('stock'), 1, 0, 'C', 1);
		$this->Cell(8, 8, utf8_decode('um'), 1, 0, 'C', 1);
		$this->Cell(55, 8, utf8_decode('Bodega'), 1, 0, 'C', 1);
	}

// Pie de página
	function Footer() {
		// // Posición: a 1,5 cm del final
		$this->SetY(-15);
		// // Arial italic 8
		$this->SetFont('Arial', '', 8);
		// // Número de página

		$this->Cell(0, 10, date('d/m/Y H:i:s'), 0, 0, 'L');
		$this->SetY(-15);
		$this->Cell(0, 10, utf8_decode('Gestión de Procesos'), 0, 0, 'C');
		$this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
	}
}

// Creación del objeto de la clase heredada
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFillColor(227, 227, 227);
$pdf->SetDrawColor(255, 255, 255);
// $pdf->SetAutoPageBreak(true,20);

$adicionalCondicionales = "";

if (!empty($_GET['tipo_producto'])) {
	$tipo_producto = $_GET['tipo_producto'];
	$adicionalCondicionales .= "WHERE tipo_producto = '$tipo_producto' ";
}

$consulta = "SELECT * FROM productos " . $adicionalCondicionales . " order by fecha desc";

$query = mysqli_query($conn, $consulta);

$i = 1;
while ($row = $query->fetch_assoc()) {

	$pdf->Ln(8);
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(6, 8, $i++, 1, 0, 'C', 1);
	$pdf->Cell(22, 8, utf8_decode($row['codigo']), 1, 0, 'C', 1);
	$pdf->Cell(84, 8, utf8_decode($row['nombre_producto']), 1, 0, 'L', 1);
	//$pdf->Cell(22, 8, utf8_decode($row['tipo_producto'] ?? ""), 1, 0, 'L', 1);
	$pdf->Cell(15, 8, utf8_decode($row['cantidad']), 1, 0, 'C', 1);
	$pdf->Cell(8, 8, utf8_decode($row['unidad_medida']), 1, 0, 'C', 1);
	$pdf->Cell(55, 8, utf8_decode($row['bodega']), 1, 0, 'L', 1);
}

// for($i=1;$i<=20;$i++)
//     $pdf->Cell(60,10,utf8_decode('Imprimiendo linea de código 1 '),1,1);
$pdf->Output();
?>