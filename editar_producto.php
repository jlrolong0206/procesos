<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * FROM productos where id = " . $_GET['id'])->fetch_array();
foreach ($qry as $k => $v) {
	$$k = $v;
}

$qryInsumoFormulas = $conn->query(
	"SELECT inf.*,p.nombre_producto FROM insumo_formulas AS inf
	  LEFT JOIN productos AS p ON p.id = inf.producto_formula_id WHERE producto_id = {$_GET['id']}"
);

$listadoInsumoFormulas = [];
foreach ($qryInsumoFormulas as $row) {
	$listadoInsumoFormulas[] = $row;
}

include 'nuevo_producto.php';
