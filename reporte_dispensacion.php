<?php
date_default_timezone_set("America/Bogota");
require('fpdf/fpdf.php');



class PDF extends FPDF
{
    public $taskListInfo = null;



    // Cabecera de página
    function Header()
    {

        require('db_connect.php');

        $taskId = $_GET["id"];

        $query = "SELECT tl.*,p.codigo as codigo_producto FROM task_list as tl LEFT JOIN productos AS p ON tl.producto=p.nombre_producto WHERE tl.id={$taskId} ";
        $result = $conn->query($query);
        $taskListInfo  = $result->fetch_object();

        $codicionesAmbientales = [];
        $equiposUtilizados = [];
        $procedimientoDispensacion = [];
        $materiaPrima = [];

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
        $this->SetTitle(utf8_decode('Orden de Dispensación'));
        $this->SetFont('Arial', 'B', 12);
        $this->Image('img/logo ricardo.png', 11, 10, 50);
        $this->Cell(0, 8, 'ORDEN DE DISPENSACION', 0, 0, 'C', 0);
        $this->SetFont('Arial', '', 10);
        $this->SetX(160);
        $this->SetFillColor(91, 169, 138);
        $this->Cell(20, 5, 'Version', 0, 0, 'L', 1);
        $this->SetDrawColor(53, 54, 53);
        $this->Cell(20, 5, '00', 1, 0, 'R', 0);
        $this->Ln(6);
        $this->SetX(160);
        $this->SetFillColor(91, 169, 138);
        $this->Cell(20, 5, 'Codigo', 0, 0, 'L', 1);
        $this->SetDrawColor(53, 54, 53);
        $this->Cell(20, 5, 'F-PRC-024', 1, 0, 'R', 0);
        $this->Ln(6);
        $this->SetX(160);
        $this->SetFillColor(91, 169, 138);
        $this->Cell(20, 5, 'Vigencia', 0, 0, 'L', 1);
        $this->SetDrawColor(53, 54, 53);
        $this->Cell(20, 5, '2023/08/23', 1, 0, 'R', 0);
        $this->Ln(12);
        $this->Cell(0, 0, '', 1, 0, 0);
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 11);
        $this->SetFillColor(91, 169, 138);
        $this->SetDrawColor(255, 255, 255);
        $this->Cell(0, 0, utf8_decode('Información del Producto'), 0, 0, 'C', 0);
        $this->Ln(7);
        $this->Cell(43, 7, utf8_decode('Fecha Dispensación'), 0, 0, 'L', 1);
        $this->SetDrawColor(53, 54, 53);
        $this->Cell(67, 7, utf8_decode(date("M d, Y H:i:s", strtotime($taskListInfo->date_created))), 1, 0, '', 0);
        $this->Ln(9);
        $this->Cell(43, 7, utf8_decode('Nombre del Producto'), 0, 0, 'L', 1);
        $this->SetDrawColor(53, 54, 53);
        $this->Cell(67, 7, utf8_decode($taskListInfo->producto ?? ""), 1, 0, '', 0);
        $this->SetX(120);
        $this->Cell(30, 7, utf8_decode('Lote Asignado'), 0, 0, '', 1);
        $this->SetDrawColor(53, 54, 53);
        $this->Cell(50, 7, $taskListInfo->lote, 1, 0, '', 0);
        $this->Ln(9);
        $this->Cell(43, 7, utf8_decode('Código del Producto'), 0, 0, 'L', 1);
        $this->SetDrawColor(53, 54, 53);
        $this->Cell(67, 7, $taskListInfo->codigo_producto ?? "", 1, 0, '', 0);
        $this->SetX(120);
        $this->Cell(30, 7, utf8_decode('Cantidad'), 0, 0, '', 1);
        $this->SetDrawColor(53, 54, 53);
        $this->Cell(50, 7, $taskListInfo->cant_producto ?? "", 1, 0, '', 0);
        $this->Ln(13);
    }

    // Pie de página
    function Footer()
    {
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

require('db_connect.php');

$taskId = $_GET["id"];

$query = "SELECT tl.*,p.codigo as codigo_producto FROM task_list as tl LEFT JOIN productos AS p ON tl.producto=p.nombre_producto WHERE tl.id={$taskId} ";
$result = $conn->query($query);
$taskListInfo  = $result->fetch_object();

$codicionesAmbientales = [];
$equiposUtilizados = [];
$procedimientoDispensacion = [];
$materiaPrima = [];

$hini = [];

$qrcodicionesAmbientales = $conn->query("SELECT * FROM task_progress where task_id = {$taskListInfo->id}");
//$qravanceIncidentes = $conn->query("SELECT * FROM avance_incidentes where task_progress_id = {$row['id']} and avance_id = {$id}");
$qravanceEquipos = $conn->query("SELECT a.*, m.codigo FROM avance_equipos a, maquinaria m where a.equipo_id = m.id and avance_id = {$taskListInfo->id}");
$qrmateriaPrima = $conn->query("SELECT ifm.*,p.nombre_producto,p.lote,p.codigo,(SELECT nombre_producto FROM productos WHERE id = ifm.producto_formula_id LIMIT 1) AS nombre_producto FROM insumo_formulas AS ifm LEFT JOIN productos AS p ON ifm.producto_id = p.id  WHERE p.nombre_producto = '$taskListInfo->producto'");
//<I> JY 2024.06.06
//"Incio Labor";

$queryInicio = "SELECT  p.date_created as fecha_creacion_tarea FROM task_progress p inner join task_list t on t.id = p.task_id 
				where is_complete = 0 and p.task_id = {$taskListInfo->id}";
$result = $conn->query($queryInicio);
$dshIni  = $result->fetch_object();

$queryFin = "SELECT  p.date_created as fecha_creacion_tarea FROM task_progress p inner join task_list t on t.id = p.task_id 
				where is_complete = 1 and p.task_id = {$taskListInfo->id}";
$result = $conn->query($queryFin);
$dshFin  = $result->fetch_object();
//<F> JY 2024.06.06

foreach ($qrcodicionesAmbientales as $key => $value) {
    $codicionesAmbientales[] = $value;
}

foreach ($qravanceEquipos as $key => $value) {
    $equiposUtilizados[] = $value;
}

foreach ($qrmateriaPrima as $key => $value) {
    $materiaPrima[] = $value;
}

// Creación del objeto de la clase heredada
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 0, utf8_decode('Condiciones Ambientales'), 0, 0, 'C', 0);
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(91, 169, 138);
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetX(40);
$pdf->Cell(45, 8, utf8_decode('Fecha/Hora'), 1, 0, 'C', 1);
$pdf->Cell(45, 8, utf8_decode('Temperatura °C'), 1, 0, 'C', 1);
$pdf->Cell(45, 8, utf8_decode('Humedad Relativa (%)'), 1, 0, 'C', 1);



$pdf->SetFillColor(227, 227, 227);
$pdf->SetDrawColor(255, 255, 255);
// $pdf->SetAutoPageBreak(true,20);





foreach ($codicionesAmbientales as $key => $ca) {
    $pdf->Ln(8);
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetX(40);
    $pdf->Cell(45, 8, utf8_decode($ca["date_created"] ?? ""), 1, 0, 'C', 1);
    $pdf->Cell(45, 8, utf8_decode($ca["temperatura"] ?? ""), 1, 0, 'C', 1);
    $pdf->Cell(45, 8, utf8_decode($ca["humedad"] ?? ""), 1, 0, 'C', 1);
}

$pdf->Ln(13);



$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 0, utf8_decode('Equipos Utilizados'), 0, 0, 'C', 0);
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(91, 169, 138);
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetX(40);
$pdf->Cell(45, 8, utf8_decode('Descripción del Equipo'), 1, 0, 'C', 1);
$pdf->Cell(45, 8, utf8_decode('Código'), 1, 0, 'C', 1);
$pdf->Cell(45, 8, utf8_decode('Fecha de Calibración'), 1, 0, 'C', 1);

$pdf->SetFillColor(227, 227, 227);
$pdf->SetDrawColor(255, 255, 255);
// $pdf->SetAutoPageBreak(true,20);

foreach ($equiposUtilizados as $key => $eu) {
    $pdf->Ln(8);
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetX(40);
    $pdf->Cell(45, 8, utf8_decode($eu["nombre_equipo"] ?? ""), 1, 0, 'C', 1);
    $pdf->Cell(45, 8, utf8_decode($eu["codigo"] ?? ""), 1, 0, 'C', 1);
    $pdf->Cell(45, 8, utf8_decode($eu["fecha_calibracion"] ?? ""), 1, 0, 'C', 1);
}


$pdf->Ln(13);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 0, utf8_decode('Procedimiento de Dispensación'), 0, 0, 'C', 0);
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(91, 169, 138);
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetX(40);
$pdf->Cell(45, 8, utf8_decode('Hora Inicio'), 1, 0, 'C', 1);
$pdf->Cell(45, 8, utf8_decode('Hora Fin'), 1, 0, 'C', 1);
$pdf->Cell(45, 8, utf8_decode('Duración Min'), 1, 0, 'C', 1);



$pdf->SetFillColor(227, 227, 227);
$pdf->SetDrawColor(255, 255, 255);
// $pdf->SetAutoPageBreak(true,20);

$pdf->Ln(8);
$pdf->SetFont('Arial', '', 10);
$pdf->SetX(40);
$pdf->Cell(45, 8, utf8_decode($dshIni->fecha_creacion_tarea), 1, 0, 'C', 1);
$pdf->Cell(45, 8, utf8_decode($dshFin->fecha_creacion_tarea), 1, 0, 'C', 1);
// Creamos objetos DateTime para las fechas
$inicio = new DateTime($dshIni->fecha_creacion_tarea);
// new DateTime($fechaInicio);
$fin = new DateTime($dshFin->fecha_creacion_tarea);
//new DateTime($fechaFin);

// Obtenemos la diferencia entre las fechas
$diferencia = $inicio->diff($fin);

// Mostramos la diferencia en el formato que deseas
$msg = "{$diferencia->h} hr, {$diferencia->i} min y {$diferencia->s} seg";
$pdf->Cell(45, 8, utf8_decode($msg), 1, 0, 'C', 1);

$pdf->Ln(15);
$pdf->SetDrawColor(0, 0, 0);
$pdf->Cell(0, 0, '', 1, 0, 'L', 0);
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 0, utf8_decode('Procedimientos'), 0, 0, 'L', 0);
$pdf->Ln(6);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 0, utf8_decode('5.1 Verificar el estado de limpieza de los contenedores'), 0, 0, 'L', 0);
$pdf->Ln(4);
$pdf->Cell(0, 0, utf8_decode('5.2 Verificar que la matyeria prima a dispensar tenga etiqueta de aprobado'), 0, 0, 'L', 0);
$pdf->Ln(4);
$pdf->Cell(0, 0, utf8_decode('5.3 Alistar las materias primas según orden de producción y pasar a través del passthrough al área de dispensación'), 0, 0, 'L', 0);
$pdf->Ln(4);
$pdf->Cell(0, 0, utf8_decode('5.4 Dispensar cada una de las materias primas según orden de producción'), 0, 0, 'L', 0);
$pdf->Ln(4);
$pdf->Cell(0, 0, utf8_decode('5.5 Identificar cada una de las materias primas con la etiqueta "Identificación de materias primas" F-PRC-024-1 '), 0, 0, 'L', 0);
$pdf->Ln(4);
$pdf->Cell(0, 0, utf8_decode('5.6 Diligenciar las cantidades de las materias primas y lotes dispensados en la orden de producción'), 0, 0, 'L', 0);
$pdf->Ln(4);
$pdf->Cell(0, 0, utf8_decode('5.7 Colocar las materias primas sobre una estiba o carro transportador y trasladar a fabricación a través del ascensor'), 0, 0, 'L', 0);
$pdf->Ln(4);
$pdf->Cell(0, 0, '', 1, 0, 'L', 0);

$pdf->Ln(7);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 0, utf8_decode('Materia Prima'), 0, 0, 'C', 0);
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(91, 169, 138);
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetX(11.5);
$pdf->Cell(8, 8, utf8_decode('#'), 1, 0, 'C', 1);
$pdf->Cell(35, 8, utf8_decode('Código Material'), 1, 0, 'C', 1);
$pdf->Cell(60, 8, utf8_decode('Descripción Material'), 1, 0, 'C', 1);
$pdf->Cell(45, 8, utf8_decode('Cantidad a Dispensar'), 1, 0, 'C', 1);
$pdf->Cell(40, 8, utf8_decode('Lote de Material'), 1, 0, 'C', 1);



$pdf->SetFillColor(227, 227, 227);
$pdf->SetDrawColor(255, 255, 255);
// $pdf->SetAutoPageBreak(true,20);



foreach ($materiaPrima as $key => $value) {
    $pdf->Ln(8);
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetX(11.5);
    $pdf->Cell(8, 8, utf8_decode($key  + 1), 1, 0, 'C', 1);
    $pdf->Cell(35, 8, utf8_decode($value["codigo"] ?? ""), 1, 0, 'C', 1);
    $pdf->Cell(60, 8, utf8_decode($value["nombre_producto"] ?? ""), 1, 0, 'C', 1);
    $pdf->Cell(45, 8, utf8_decode($value["cantidad"] ?? ""), 1, 0, 'C', 1);
    $pdf->Cell(40, 8, utf8_decode($value["lote"] ?? ""), 1, 0, 'C', 1);
}



// for($i=1;$i<=20;$i++)
//     $pdf->Cell(60,10,utf8_decode('Imprimiendo linea de código 1 '),1,1);
$pdf->Output('I', 'Orden de Dispensacion.pdf');
