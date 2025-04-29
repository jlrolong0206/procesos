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
    $this->SetTitle(utf8_decode('Orden de Fabricación'));
    $this->SetFont('Arial','B',12);
    $this->Image('img/logo ricardo.png',11,10,50);
    $this->Cell(0,8,'ORDEN DE FABRICACION',0,0,'C',0);
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
    $this->Cell(30,7,utf8_decode('Emp a Granel'),0,0,'L',1);
    $this->SetDrawColor(53, 54, 53);
    $this->Cell(50,7,'',1,0,'',0);
    
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
    $pdf->Cell(45,8,utf8_decode('MARMITA 1'),1,0,'C',1);
    $pdf->Cell(45,8,utf8_decode('EQP-001'),1,0,'C',1);
    $pdf->Cell(45,8,utf8_decode('N/A'),1,0,'C',1);
    $pdf->Ln(8);
    $pdf->SetX(40);
    $pdf->Cell(45,8,utf8_decode('MARMITA 2'),1,0,'C',1);
    $pdf->Cell(45,8,utf8_decode('BL-002'),1,0,'C',1);
    $pdf->Cell(45,8,utf8_decode('N/A'),1,0,'C',1);
    $pdf->Ln(8);
    $pdf->SetX(40);
    $pdf->Cell(45,8,utf8_decode('TERMOMETRO'),1,0,'C',1);
    $pdf->Cell(45,8,utf8_decode('TM-001'),1,0,'C',1);
    $pdf->Cell(45,8,utf8_decode('10/08/2023'),1,0,'C',1);
    $pdf->Ln(13);
    $pdf->SetFont('Arial','B',11);
$pdf->Cell(0,0,utf8_decode('Procedimiento de Fabricación'),0,0,'C',0);
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
    $pdf->Cell(45,8,utf8_decode('10:40'),1,0,'C',1);
    $pdf->Cell(45,8,utf8_decode('0.53'),1,0,'C',1);
    $pdf->Cell(45,8,utf8_decode('14.13'),1,0,'C',1);

    $pdf->Ln(15);
    $pdf->SetDrawColor(0,0,0);
    $pdf->Cell(0,0,'',1,0,'L',0);
    $pdf->Ln(4);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,0,utf8_decode('Recomendaciones'),0,0,'L',0);
    $pdf->Ln(6);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,0,utf8_decode('5.1 Verificar el estado de limpieza de los equipos a utilizar'),0,0,'L',0);
    $pdf->Ln(4);
    $pdf->Cell(0,0,utf8_decode('5.2 Verificar que los materiales correspondan con la orden a fabricar'),0,0,'L',0);
    $pdf->Ln(4);
    $pdf->Cell(0,0,utf8_decode('5.3 Seguir las intrucciones de preparación del Batch récord F-PRC-024'),0,0,'L',0);
    $pdf->Ln(4);
    $pdf->Cell(0,0,utf8_decode('5.4 Tomar una muestra de 500 g de producto terminado y llevar a laboratorio de control de calidad'),0,0,'L',0);
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
$pdf->Cell(35,8,utf8_decode('1000-016'),1,0,'C',1);
$pdf->Cell(60,8,utf8_decode('ALCOHOL CETOESTERILICO'),1,0,'C',1);
$pdf->Cell(45,8,utf8_decode('15.000g'),1,0,'C',1);
$pdf->Cell(40,8,utf8_decode('073217-23'),1,0,'C',1);

$pdf->Ln(8);
$pdf->SetFont('Arial','',10);
$pdf->SetX(11.5);
$pdf->Cell(8,8,utf8_decode('2'),1,0,'C',1);
$pdf->Cell(35,8,utf8_decode('1000-027'),1,0,'C',1);
$pdf->Cell(60,8,utf8_decode('INCIDE H55'),1,0,'C',1);
$pdf->Cell(45,8,utf8_decode('12.000g'),1,0,'C',1);
$pdf->Cell(40,8,utf8_decode('DMD-23-24'),1,0,'C',1);

$pdf->Ln(8);
$pdf->SetFont('Arial','',10);
$pdf->SetX(11.5);
$pdf->Cell(8,8,utf8_decode('3'),1,0,'C',1);
$pdf->Cell(35,8,utf8_decode('1000-072'),1,0,'C',1);
$pdf->Cell(60,8,utf8_decode('ACEITE MINERAL USP'),1,0,'C',1);
$pdf->Cell(45,8,utf8_decode('4.000g'),1,0,'C',1);
$pdf->Cell(40,8,utf8_decode('2023 0095'),1,0,'C',1);

$pdf->Ln(8);
$pdf->SetFont('Arial','',10);
$pdf->SetX(11.5);
$pdf->Cell(8,8,utf8_decode('4'),1,0,'C',1);
$pdf->Cell(35,8,utf8_decode('1000-023'),1,0,'C',1);
$pdf->Cell(60,8,utf8_decode('PROZOL-OH'),1,0,'C',1);
$pdf->Cell(45,8,utf8_decode('4.000g'),1,0,'C',1);
$pdf->Cell(40,8,utf8_decode('043606-23'),1,0,'C',1);

$pdf->Ln(8);
$pdf->SetFont('Arial','',10);
$pdf->SetX(11.5);
$pdf->Cell(8,8,utf8_decode('5'),1,0,'C',1);
$pdf->Cell(35,8,utf8_decode('1000-044'),1,0,'C',1);
$pdf->Cell(60,8,utf8_decode('FRAGANCIA SENXUAL'),1,0,'C',1);
$pdf->Cell(45,8,utf8_decode('3.000g'),1,0,'C',1);
$pdf->Cell(40,8,utf8_decode('6-159699'),1,0,'C',1);

$pdf->Ln(8);
$pdf->SetFont('Arial','',10);
$pdf->SetX(11.5);
$pdf->Cell(8,8,utf8_decode('6'),1,0,'C',1);
$pdf->Cell(35,8,utf8_decode('N/A'),1,0,'C',1);
$pdf->Cell(60,8,utf8_decode('AGUA DESIONIZADA'),1,0,'C',1);
$pdf->Cell(45,8,utf8_decode('190L'),1,0,'C',1);
$pdf->Cell(40,8,utf8_decode('23/11/2028'),1,0,'C',1);
  
    

   




// for($i=1;$i<=20;$i++)
//     $pdf->Cell(60,10,utf8_decode('Imprimiendo linea de código 1 '),1,1);
$pdf->Output();
?>