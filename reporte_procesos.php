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
    $this->SetTitle(utf8_decode('Reporte de Procesos'));
    $this->SetFont('Arial','B',13);
    $this->Image('img/logo ricardo.png',11,10,50);
    $this->Cell(190,15,'REPORTE DE PROCESOS',0,0,'C',0);
    $this->Ln(30);
    $this->SetFont('Arial','B',11);
$this->SetFillColor(91, 169, 138);
$this->SetDrawColor(255,255,255);
$this->Cell(10,8,'Id',1,0,'C',1);
$this->Cell(60,8,utf8_decode('Procesos'),1,0,'C',1);
$this->Cell(40,8,utf8_decode('Fecha'),1,0,'C',1);
$this->Cell(50,8,utf8_decode('Asignado a'),1,0,'C',1);
$this->Cell(30,8,utf8_decode('Estado'),1,0,'C',1);
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
$pdf->SetFillColor(227,227,227);
$pdf->SetDrawColor(255,255,255);
// $pdf->SetAutoPageBreak(true,20);


$consulta = "SELECT * FROM task_list order by date_created desc";
$query = mysqli_query($conn,$consulta);


// if($row['status'] == 0){
//       $estado = "Pendinte";
// }elseif($row['status'] == 1){
//     $estado = "En Progreso";
// }elseif($row['status'] == 2){
//     $estado = "Completado";
// }
// if(strtotime($row['due_date']) < strtotime(date('Y-m-d'))){
  // 	echo "<span class='badge badge-danger mx-1'>Cerrado</span>";
// }
//

$i = 1;
while($row= $query->fetch_assoc()){


    $pdf->Ln(8);
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(10,8,$i++,1,0,'C',1);
    $pdf->Cell(60,8,utf8_decode($row['task']),1,0,'L',1);
    $pdf->Cell(40,8,utf8_decode($row['date_created']),1,0,'C',1);
    if($row['status'] == 0){
      $estado = "Pendiente";
    }elseif($row['status'] == 1){
    $estado = "En Progreso";
    }elseif($row['status'] == 2){
    $estado = "Completado";
    }
    $pdf->Cell(50,8,$estado,1,0,'C',1);
    if($row['status'] == 0){
      $estado = "Pendinte";
    }elseif($row['status'] == 1){
    $estado = "En Progreso";
    }elseif($row['status'] == 2){
    $estado = "Completado";
    }
    $pdf->Cell(30,8,$estado,1,0,'L',1);
}



// for($i=1;$i<=20;$i++)
//     $pdf->Cell(60,10,utf8_decode('Imprimiendo linea de código 1 '),1,1);
$pdf->Output();
?>