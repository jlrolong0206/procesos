<?php
ob_start();
date_default_timezone_set("America/Bogota");

$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if ($action == 'login') {
	$login = $crud->login();
	if ($login)
		echo $login;
}
if ($action == 'login2') {
	$login = $crud->login2();
	if ($login)
		echo $login;
}
if ($action == 'logout') {
	$logout = $crud->logout();
	if ($logout)
		echo $logout;
}
if ($action == 'logout2') {
	$logout = $crud->logout2();
	if ($logout)
		echo $logout;
}

if ($action == 'signup') {
	$save = $crud->signup();
	if ($save)
		echo $save;
}
if ($action == 'save_user') {
	$save = $crud->save_user();
	if ($save)
		echo $save;
}
if ($action == 'update_user') {
	$save = $crud->update_user();
	if ($save)
		echo $save;
}
if ($action == 'delete_user') {
	$save = $crud->delete_user();
	if ($save)
		echo $save;
}
if ($action == 'save_department') {
	$save = $crud->save_department();
	if ($save)
		echo $save;
}
if ($action == 'delete_department') {
	$save = $crud->delete_department();
	if ($save)
		echo $save;
}
if ($action == 'guardar_incidente') {
	$save = $crud->guardar_incidente();
	if ($save)
		echo $save;
}
if ($action == 'eliminar_incidente') {
	$save = $crud->eliminar_incidente();
	if ($save)
		echo $save;
}
if ($action == 'guardar_maquinaria') {
	$save = $crud->guardar_maquinaria();
	if ($save)
		echo $save;
}
if ($action == 'eliminar_maquinaria') {
	$save = $crud->eliminar_maquinaria();
	if ($save)
		echo $save;
}
if ($action == 'guardar_proveedor') {
	$save = $crud->guardar_proveedor();
	if ($save)
		echo $save;
}
if ($action == 'eliminar_proveedor') {
	$save = $crud->eliminar_proveedor();
	if ($save)
		echo $save;
}
if ($action == 'guardar_producto') {
	$save = $crud->guardar_producto();
	if ($save)
		echo $save;
}
if ($action == 'eliminar_producto') {
	$save = $crud->eliminar_producto();
	if ($save)
		echo $save;
}
if ($action == 'save_designation') {
	$save = $crud->save_designation();
	if ($save)
		echo $save;
}
if ($action == 'delete_designation') {
	$save = $crud->delete_designation();
	if ($save)
		echo $save;
}
if ($action == 'save_employee') {
	$save = $crud->save_employee();
	if ($save)
		echo $save;
}
if ($action == 'delete_employee') {
	$save = $crud->delete_employee();
	if ($save)
		echo $save;
}
if ($action == 'save_evaluator') {
	$save = $crud->save_evaluator();
	if ($save)
		echo $save;
}
if ($action == 'delete_evaluator') {
	$save = $crud->delete_evaluator();
	if ($save)
		echo $save;
}

if ($action == 'save_task') {
	$save = $crud->save_task();
	if ($save)
		echo $save;
}



if ($action == 'delete_task') {
	$save = $crud->delete_task();
	if ($save)
		echo $save;
}
if ($action == 'save_progress') {
	$save = $crud->save_progress();
	if ($save)
		echo $save;
}
if ($action == 'delete_progress') {
	$save = $crud->delete_progress();
	if ($save)
		echo $save;
}
if ($action == 'save_evaluation') {
	$save = $crud->save_evaluation();
	if ($save)
		echo $save;
}
if ($action == 'delete_evaluation') {
	$save = $crud->delete_evaluation();
	if ($save)
		echo $save;
}
if ($action == 'get_emp_tasks') {
	$get = $crud->get_emp_tasks();
	if ($get)
		echo $get;
}
if ($action == 'get_progress') {
	$get = $crud->get_progress();
	if ($get)
		echo $get;
}
if ($action == 'get_report') {
	$get = $crud->get_report();
	if ($get)
		echo $get;
}
if ($action == 'get_insumos_formulas') {
	$get = $crud->get_insumos_formulas();
	echo $get;
}

if ($action == 'get_productos_stock_bajo') {
    $get = $crud->get_productos_stock_bajo();
    echo json_encode($get);
}

if ($action == 'contar_productos_stock_bajo') {
    $count = $crud->contar_productos_stock_bajo();
    echo $count;
    exit; // Añade esto para evitar salida adicional
}

ob_end_flush();
