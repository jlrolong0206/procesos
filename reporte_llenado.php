<?php
date_default_timezone_set("America/Bogota");
require('fpdf/fpdf.php');
require('db_connect.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
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
    $this->SetTitle(utf8_decode('Orden de Llenado'));
    $this->SetFont('Arial','B',12);
    $this->Image('img/logo ricardo.png',11,10,50);
    $this->Cell(0,8,'ORDEN DE LLENADO',0,0,'C',0);
    $this->SetX(175);
    $this->SetFillColor(91, 169, 138);
    $this->Cell(10,8,'No',0,0,'L',1);
    $this->SetDrawColor(53, 54, 53);
    $this->Cell(14,8,'0001',1,0,'',0);
    $this->Ln(20);
    $this->Cell(0,0,'',1,0,0);
    $this->Ln(5);
    $this->SetFont('Arial','B',11);
    $this->SetFillColor(91, 169, 138);
    $this->SetDrawColor(255,255,255);
    $this->Cell(0,0,utf8_decode('Información del Producto'),0,0,'C',0);
    $this->Ln(7);
    $this->Cell(43,7,utf8_decode('Fecha Dispensación'),0,0,'L',1);
    $this->SetDrawColor(53, 54, 53);
    $this->Cell(67,7,'Nov 23, 2023 00:23:44',1,0,'',0);
    $this->Ln(9);
    $this->Cell(43,7,utf8_decode('Nombre del Producto'),0,0,'L',1);
    $this->SetDrawColor(53, 54, 53);
    $this->Cell(67,7,'TRATAMIENTO DE COCO',1,0,'',0);
    $this->SetX(120);
    $this->Cell(30,7,utf8_decode('Lote Asignado'),0,0,'',1);
    $this->SetDrawColor(53, 54, 53);
    $this->Cell(50,7,'2311001',1,0,'',0);
    $this->Ln(9);
    $this->Cell(43,7,utf8_decode('Código del Producto'),0,0,'L',1);
    $this->SetDrawColor(53, 54, 53);
    $this->Cell(67,7,'3000-001',1,0,'',0);
    $this->SetX(120);
    $this->Cell(30,7,utf8_decode('Cantidad'),0,0,'',1);
    $this->SetDrawColor(53, 54, 53);
    $this->Cell(50,7,'25000',1,0,'',0);
    $this->Ln(13);
  
}

// Pie de página
function Footer()
{
    // // Posición: a 1,5 cm del final
     $this->SetY(-15);
    // // Arial italic 8
     $this->SetFont('Arial','',8);
    // // Número de página
      
    $this->Cell(0,10,date('d/m/Y H:i:s'),0,0,'L');
    $this->SetY(-15);
    $this->Cell(0,10,utf8_decode('Gestión de Procesos'),0,0,'C');
    $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'R');
}
}


// Creación del objeto de la clase heredada
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,0,utf8_decode('Condiciones Ambientales'),0,0,'C',0);
$pdf->Ln(6);
$pdf->SetFont('Arial','B',11);
$pdf->SetFillColor(91, 169, 138);
$pdf->SetDrawColor(255,255,255);
$pdf->SetX(40);
$pdf->Cell(45,8,utf8_decode('Fecha/Hora'),1,0,'C',1);
$pdf->Cell(45,8,utf8_decode('Temperatura °C'),1,0,'C',1);
$pdf->Cell(45,8,utf8_decode('Humedad Relativa (%)'),1,0,'C',1);



$pdf->SetFillColor(227,227,227);
$pdf->SetDrawColor(255,255,255);
// $pdf->SetAutoPageBreak(true,20);



  
    $pdf->Ln(8);
    $pdf->SetFont('Arial','',10);
    $pdf->SetX(40);
    $pdf->Cell(45,8,utf8_decode('2023/11/28/8:10'),1,0,'C',1);
    $pdf->Cell(45,8,utf8_decode('25.2 °C'),1,0,'C',1);
    $pdf->Cell(45,8,utf8_decode('65%'),1,0,'C',1);

    $pdf->Ln(13);

    $pdf->SetFont('Arial','B',11);
$pdf->Cell(0,0,utf8_decode('Equipos Utilizados'),0,0,'C',0);
$pdf->Ln(6);
$pdf->SetFont('Arial','B',11);
$pdf->SetFillColor(91, 169, 138);
$pdf->SetDrawColor(255,255,255);
$pdf->SetX(40);
$pdf->Cell(45,8,utf8_decode('Descripción del Equipo'),1,0,'C',1);
$pdf->Cell(45,8,utf8_decode('Código'),1,0,'C',1);
$pdf->Cell(45,8,utf8_decode('Fecha de Calibración'),1,0,'C',1);

$pdf->SetFillColor(227,227,227);
$pdf->SetDrawColor(255,255,255);
// $pdf->SetAutoPageBreak(true,20);

    $pdf->Ln(8);
    $pdf->SetFont('Arial','',10);
    $pdf->SetX(40);
    $pdf->Cell(45,8,utf8_decode('LLENADORA 1'),1,0,'C',1);
    $pdf->Cell(45,8,utf8_decode('EQP-011'),1,0,'C',1);
    $pdf->Cell(45,8,utf8_decode('N/A'),1,0,'C',1);
    
    
    $pdf->Ln(13);
    $pdf->SetFont('Arial','B',11);
$pdf->Cell(0,0,utf8_decode('Procedimiento de Llenado'),0,0,'C',0);
$pdf->Ln(6);
$pdf->SetFont('Arial','B',11);
$pdf->SetFillColor(91, 169, 138);
$pdf->SetDrawColor(255,255,255);
$pdf->SetX(40);
$pdf->Cell(45,8,utf8_decode('Hora Inicio'),1,0,'C',1);
$pdf->Cell(45,8,utf8_decode('Hora Fin'),1,0,'C',1);
$pdf->Cell(45,8,utf8_decode('Duración'),1,0,'C',1);



$pdf->SetFillColor(227,227,227);
$pdf->SetDrawColor(255,255,255);
// $pdf->SetAutoPageBreak(true,20);
  
    $pdf->Ln(8);
    $pdf->SetFont('Arial','',10);
    $pdf->SetX(40);
    $pdf->Cell(45,8,utf8_decode('13:00'),1,0,'C',1);
    $pdf->Cell(45,8,utf8_decode('0.56'),1,0,'C',1);
    $pdf->Cell(45,8,utf8_decode('11.56'),1,0,'C',1);

    $pdf->Ln(15);
    $pdf->SetDrawColor(0,0,0);
    $pdf->Cell(0,0,'',1,0,'L',0);
    $pdf->Ln(4);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,0,utf8_decode('Recomendaciones'),0,0,'L',0);
    $pdf->Ln(6);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,0,utf8_decode('5.1 Verificar que el granel, envases y tapas correspondan con la orden a llenar'),0,0,'L',0);
    $pdf->Ln(4);
    $pdf->Cell(0,0,utf8_decode('5.2 Realizar montaje y alistamiento de la maquina'),0,0,'L',0);
    $pdf->Ln(4);
    $pdf->Cell(0,0,utf8_decode('5.4 Tomar una muestra de 500 g de producto terminado y llevar a laboratorio de control de calidad'),0,0,'L',0);
    $pdf->Ln(4);
    $pdf->Cell(0,0,utf8_decode('5.5 Una vez finalizado el proceso de llenado, enviar los productos llenos al area de acondicionamiento'),0,0,'L',0);
    $pdf->Ln(4);
    $pdf->Cell(0,0,utf8_decode('para etiquetado y codificado'),0,0,'L',0);
    $pdf->Ln(4);
    $pdf->Cell(0,0,'',1,0,'L',0);

    $pdf->Ln(7);

    $pdf->SetFont('Arial','B',11);
$pdf->Cell(0,0,utf8_decode('Materiales'),0,0,'C',0);
$pdf->Ln(6);
$pdf->SetFont('Arial','B',11);
$pdf->SetFillColor(91, 169, 138);
$pdf->SetDrawColor(255,255,255);
$pdf->SetX(11.5);
$pdf->Cell(8,8,utf8_decode('#'),1,0,'C',1);
$pdf->Cell(35,8,utf8_decode('Código Material'),1,0,'C',1);
$pdf->Cell(60,8,utf8_decode('Descripción Material'),1,0,'C',1);
$pdf->Cell(45,8,utf8_decode('Cantidad a Dispensar'),1,0,'C',1);
$pdf->Cell(40,8,utf8_decode('Lote de Material'),1,0,'C',1);



$pdf->SetFillColor(227,227,227);
$pdf->SetDrawColor(255,255,255);
// $pdf->SetAutoPageBreak(true,20);




  
    $pdf->Ln(8);
    $pdf->SetFont('Arial','',10);
    $pdf->SetX(11.5);
    $pdf->Cell(8,8,utf8_decode('1'),1,0,'C',1);
    $pdf->Cell(35,8,utf8_decode('2000-019'),1,0,'C',1);
    $pdf->Cell(60,8,utf8_decode('ENVASES 500 CC '),1,0,'C',1);
    $pdf->Cell(45,8,utf8_decode('50'),1,0,'C',1);
    $pdf->Cell(40,8,utf8_decode('073217-23'),1,0,'C',1);

    $pdf->Ln(8);
    $pdf->SetFont('Arial','',10);
    $pdf->SetX(11.5);
    $pdf->Cell(8,8,utf8_decode('2'),1,0,'C',1);
    $pdf->Cell(35,8,utf8_decode('2000-020'),1,0,'C',1);
    $pdf->Cell(60,8,utf8_decode('TAPA ROSCA N° 86MM'),1,0,'C',1);
    $pdf->Cell(45,8,utf8_decode('50'),1,0,'C',1);
    $pdf->Cell(40,8,utf8_decode('3212-321'),1,0,'C',1);

   




// for($i=1;$i<=20;$i++)
//     $pdf->Cell(60,10,utf8_decode('Imprimiendo linea de código 1 '),1,1);
$pdf->Output();
?>