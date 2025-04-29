<?php
require __DIR__ . '/vendor/autoload.php';
session_start();
ini_set('display_errors', 1);
class Action
{
	private $db;

	public function __construct()
	{
		ob_start();
		include 'db_connect.php';

		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function get_productos_stock_bajo() {
		$qry = $this->db->query("
			SELECT p.* 
			FROM productos p
			WHERE p.cantidad <= p.stock_minimo
			ORDER BY p.cantidad ASC
			LIMIT 10
		");
		
		$productos = array();
		while($row = $qry->fetch_assoc()){
			$productos[] = $row;
		}
		return $productos;
	}

	function contar_productos_stock_bajo() {
		$qry = $this->db->query("SELECT COUNT(*) as total FROM productos WHERE cantidad <= stock_minimo");
		if($qry && $qry->num_rows > 0){
			$result = $qry->fetch_assoc();
			return $result['total'];
		}
		return 0; // Retorna 0 si hay error o no hay registros
	}

	function login()
	{
		extract($_POST);
		$type = array("employee_list", "evaluator_list", "users");
		$qry = $this->db->query("SELECT *,concat(firstname,' ',lastname) as name FROM {$type[$login]} where email = '" . $email . "' and password = '" . md5($password) . "'  ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'password' && !is_numeric($key)) {
					$_SESSION['login_' . $key] = $value;
				}
			}
			$_SESSION['login_type'] = $login;
			return 1;
		} else {
			return 2;
		}
	}
	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function login2()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM students where student_code = '" . $student_code . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'password' && !is_numeric($key)) {
					$_SESSION['rs_' . $key] = $value;
				}
			}
			return 1;
		} else {
			return 3;
		}
	}
	function save_user()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (!empty($password)) {
			$data .= ", password=md5('$password') ";
		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";
		}
		if (empty($id)) {
			if (!isset($_FILES['img']) || (isset($_FILES['img']) && $_FILES['img']['tmp_name'] == '')) {
				$data .= ", avatar = 'no-image-available.png' ";
			}
			$save = $this->db->query("INSERT INTO users set $data");
		} else {
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if ($save) {
			return 1;
		}
	}
	function signup()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass')) && !is_numeric($k)) {
				if ($k == 'password') {
					if (empty($v)) {
						continue;
					}

					$v = md5($v);
				}
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}

		$check = $this->db->query("SELECT * FROM users where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";
		}
		if (empty($id)) {
			if (!isset($_FILES['img']) || (isset($_FILES['img']) && $_FILES['img']['tmp_name'] == '')) {
				$data .= ", avatar = 'no-image-available.png' ";
			}
			$save = $this->db->query("INSERT INTO users set $data");
		} else {
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if ($save) {
			if (empty($id)) {
				$id = $this->db->insert_id;
			}

			foreach ($_POST as $key => $value) {
				if (!in_array($key, array('id', 'cpass', 'password')) && !is_numeric($key)) {
					$_SESSION['login_' . $key] = $value;
				}
			}
			$_SESSION['login_id'] = $id;
			if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name'])) {
				$_SESSION['login_avatar'] = $fname;
			}

			return 1;
		}
	}

	function update_user()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'table', 'password')) && !is_numeric($k)) {

				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$type = array("employee_list", "evaluator_list", "users");
		$check = $this->db->query("SELECT * FROM {$type[$_SESSION['login_type']]} where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";
		}
		if (!empty($password)) {
			$data .= " ,password=md5('$password') ";
		}

		if (empty($id)) {
			if (!isset($_FILES['img']) || (isset($_FILES['img']) && $_FILES['img']['tmp_name'] == '')) {
				$data .= ", avatar = 'no-image-available.png' ";
			}
			$save = $this->db->query("INSERT INTO {$type[$_SESSION['login_type']]} set $data");
		} else {
			$save = $this->db->query("UPDATE {$type[$_SESSION['login_type']]} set $data where id = $id");
		}

		if ($save) {
			foreach ($_POST as $key => $value) {
				if ($key != 'password' && !is_numeric($key)) {
					$_SESSION['login_' . $key] = $value;
				}
			}
			if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name'])) {
				$_SESSION['login_avatar'] = $fname;
			}

			return 1;
		}
	}
	function delete_user()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_system_settings()
	{
		extract($_POST);
		$data = '';
		foreach ($_POST as $k => $v) {
			if (!is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if ($_FILES['cover']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'], '../assets/uploads/' . $fname);
			$data .= ", cover_img = '$fname' ";
		}
		$chk = $this->db->query("SELECT * FROM system_settings");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE system_settings set $data where id =" . $chk->fetch_array()['id']);
		} else {
			$save = $this->db->query("INSERT INTO system_settings set $data");
		}
		if ($save) {
			foreach ($_POST as $k => $v) {
				if (!is_numeric($k)) {
					$_SESSION['system'][$k] = $v;
				}
			}
			if ($_FILES['cover']['tmp_name'] != '') {
				$_SESSION['system']['cover_img'] = $fname;
			}
			return 1;
		}
	}
	function save_image()
	{
		extract($_FILES['file']);
		if (!empty($tmp_name)) {
			$fname = strtotime(date("Y-m-d H:i")) . "_" . (str_replace(" ", "-", $name));
			$move = move_uploaded_file($tmp_name, 'assets/uploads/' . $fname);
			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
			$hostName = $_SERVER['HTTP_HOST'];
			$path = explode('/', $_SERVER['PHP_SELF']);
			$currentPath = '/' . $path[1];
			if ($move) {
				return $protocol . '://' . $hostName . $currentPath . '/assets/uploads/' . $fname;
			}
		}
	}
	function save_department()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM department_list where department = '$department' and id != '{$id}' ")->num_rows;
		if ($chk > 0) {
			return 2;
		}
		if (isset($user_ids)) {
			$data .= ", user_ids='" . implode(',', $user_ids) . "' ";
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO department_list set $data");
		} else {
			$save = $this->db->query("UPDATE department_list set $data where id = $id");
		}
		if ($save) {
			return 1;
		}
	}

	function delete_department()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM department_list where id = $id");
		if ($delete) {
			return 1;
		}
	}

	/*codigo nuevo*/
	function guardar_incidente()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM incidentes where des_incidente = '$des_incidente' and id != '{$id}' ")->num_rows;
		if ($chk > 0) {
			return 2;
		}
		if (isset($user_ids)) {
			$data .= ", user_ids='" . implode(',', $user_ids) . "' ";
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO incidentes set $data");
		} else {
			$save = $this->db->query("UPDATE incidentes set $data where id = $id");
		}
		if ($save) {
			return 1;
		}
	}

	function eliminar_incidente()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM incidentes where id = $id");
		if ($delete) {
			return 1;
		}
	}

	function guardar_maquinaria()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM maquinaria where codigo = '$codigo' and id != '{$id}' ")->num_rows;
		if ($chk > 0) {
			return 2;
		}
		if (isset($user_ids)) {
			$data .= ", user_ids='" . implode(',', $user_ids) . "' ";
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO maquinaria set $data");
		} else {
			$save = $this->db->query("UPDATE maquinaria set $data where id = $id");
		}
		if ($save) {
			return 1;
		}
	}

	function eliminar_maquinaria()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM maquinaria where id = $id");
		if ($delete) {
			return 1;
		}
	}

	function guardar_proveedor()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM proveedor where nit_proveedor = '$nit_proveedor' and id != '{$id}' ")->num_rows;
		if ($chk > 0) {
			return 2;
		}
		if (isset($user_ids)) {
			$data .= ", user_ids='" . implode(',', $user_ids) . "' ";
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO proveedor set $data");
		} else {
			$save = $this->db->query("UPDATE proveedor set $data where id = $id");
		}
		if ($save) {
			return 1;
		}
	}

	function eliminar_proveedor()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM proveedor where id = $id");
		if ($delete) {
			return 1;
		}
	}

	function guardar_producto()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k) && !is_array($v)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM productos where codigo = '$codigo' and id != '{$id}' ")->num_rows;
		if ($chk > 0) {
			return 2;
		}
		if (isset($user_ids)) {
			$data .= ", user_ids='" . implode(',', $user_ids) . "' ";
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO productos set $data");

			if ($save) {
				/* inicio guardar el detalle de insumos*/
				$ultimoIdProducto = $this->db->insert_id;
				if (!empty($_POST["producto_formula_id"]) && is_array($_POST["producto_formula_id"])) {
					for ($i = 0; $i < count($_POST["producto_formula_id"]); ++$i) {

						$productoFormulaId = $_POST["producto_formula_id"][$i];
						$cantidadInsumo = $_POST["cantidad_insumo"][$i];

						$this->db->query("INSERT INTO insumo_formulas (producto_id,producto_formula_id, cantidad) VALUES ('$ultimoIdProducto','$productoFormulaId', '$cantidadInsumo')");
					}
				}
				/* fin guardar el detalle de insumos*/
			}
		} else {
			$save = $this->db->query("UPDATE productos set $data where id = $id");

			if ($save) {
				//antes borramos todos los detalles para volver a insertar
				$this->db->query("DELETE FROM insumo_formulas where producto_id = $id");
				if (!empty($_POST["producto_formula_id"]) && is_array($_POST["producto_formula_id"])) {

					/* inicio guardar el detalle de insumos*/
					for ($i = 0; $i < count($_POST["producto_formula_id"]); ++$i) {
						$productoFormulaId = $_POST["producto_formula_id"][$i];
						$cantidadInsumo = $_POST["cantidad_insumo"][$i];

						$this->db->query("INSERT INTO insumo_formulas (producto_id,producto_formula_id, cantidad) VALUES ('$id','$productoFormulaId', '$cantidadInsumo')");
					}
					/* fin guardar el detalle de insumos*/
				}
			}
		}
		if ($save) {
			return 1;
		}
	}
	function eliminar_producto()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM productos where id = $id");
		if ($delete) {
			return 1;
		}
	}
	/*hasta aqui el codigo nuevo*/

	function save_designation()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM designation_list where designation = '$designation' and id != '{$id}' ")->num_rows;
		if ($chk > 0) {
			return 2;
		}
		if (isset($user_ids)) {
			$data .= ", user_ids='" . implode(',', $user_ids) . "' ";
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO designation_list set $data");
		} else {
			$save = $this->db->query("UPDATE designation_list set $data where id = $id");
		}
		if ($save) {
			return 1;
		}
	}
	function delete_designation()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM designation_list where id = $id");
		if ($delete) {
			return 1;
		}
	}
	function save_employee()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (!empty($password)) {
			$data .= ", password=md5('$password') ";
		}
		$check = $this->db->query("SELECT * FROM employee_list where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";
		}
		if (empty($id)) {
			if (!isset($_FILES['img']) || (isset($_FILES['img']) && $_FILES['img']['tmp_name'] == '')) {
				$data .= ", avatar = 'no-image-available.png' ";
			}
			$save = $this->db->query("INSERT INTO employee_list set $data");
		} else {
			$save = $this->db->query("UPDATE employee_list set $data where id = $id");
		}

		if ($save) {
			return 1;
		}
	}
	function delete_employee()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM employee_list where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_evaluator()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (!empty($password)) {
			$data .= ", password=md5('$password') ";
		}
		$check = $this->db->query("SELECT * FROM evaluator_list where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";
		}
		if (empty($id)) {
			if (!isset($_FILES['img']) || (isset($_FILES['img']) && $_FILES['img']['tmp_name'] == '')) {
				$data .= ", avatar = 'no-image-available.png' ";
			}
			$save = $this->db->query("INSERT INTO evaluator_list set $data");
		} else {
			$save = $this->db->query("UPDATE evaluator_list set $data where id = $id");
		}

		if ($save) {
			return 1;
		}
	}
	function delete_evaluator()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM evaluator_list where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_task()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id')) && !is_numeric($k) && !is_array($v)) {
				if ($k == 'description') {
					$v = htmlentities(str_replace("'", "&#x2019;", $v));
				}

				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (empty($id)) {


			/* inicio - validad stock */
			if (!empty($_POST["insumo_formula_id"]) && is_array($_POST["insumo_formula_id"])) {
				for ($i = 0; $i < count($_POST["insumo_formula_id"]); ++$i) {

					$insumo_formula_id = $_POST["insumo_formula_id"][$i];
					$producto_nombre = $_POST["producto_nombre"][$i];
					$cantidad_base = $_POST["cantidad_base"][$i];
					$cantidad_despachar = $_POST["cantidad_despachar"][$i];

					$queryProducto = $this->db->query("SELECT * FROM productos WHERE nombre_producto='{$producto_nombre}' LIMIT 1");
					$productoBuscar = $queryProducto->fetch_object();

					if ($productoBuscar->cantidad < $cantidad_despachar) {
						return json_encode([
							"error" => true,
							"mensaje" => "El producto {$productoBuscar->nombre_producto} solo cuenta con stock de {$productoBuscar->cantidad}"
						]);
					}
				}
			}
			/* fin - validad stock */



			$save = $this->db->query("INSERT INTO task_list set $data , status=0 ");

			


			if ($save) {


				/* inicio guardar el detalle de insumo preparado*/
				$ultimoIdProceso = $this->db->insert_id;



				if (!empty($_POST["insumo_formula_id"]) && is_array($_POST["insumo_formula_id"])) {
					for ($i = 0; $i < count($_POST["insumo_formula_id"]); ++$i) {

						$insumo_formula_id = $_POST["insumo_formula_id"][$i];
						$producto_nombre = $_POST["producto_nombre"][$i];
						$cantidad_base = $_POST["cantidad_base"][$i];
						$cantidad_despachar = $_POST["cantidad_despachar"][$i];

						$this->db->query("INSERT INTO insumo_preparado (proceso_id,insumo_formula_id, producto_nombre,cantidad_base,cantidad_despachar) VALUES ('$ultimoIdProceso','$insumo_formula_id', '$producto_nombre', '$cantidad_base', '$cantidad_despachar')");

						$this->db->query("UPDATE productos SET cantidad=cantidad-{$cantidad_despachar} WHERE nombre_producto='{$producto_nombre}'");
					}
				}
				/* fin guardar el detalle de insumo preparado*/
			}
		} else {
			$save = $this->db->query("UPDATE task_list set $data where id = $id");


			//antes borramos todos los detalles para volver a insertar
			$this->db->query("DELETE FROM insumo_preparado where proceso_id = $id");
			if (!empty($_POST["insumo_formula_id"]) && is_array($_POST["insumo_formula_id"])) {

				/* inicio guardar el detalle de insumo preparado*/
				for ($i = 0; $i < count($_POST["insumo_formula_id"]); ++$i) {

					$insumo_formula_id = $_POST["insumo_formula_id"][$i];
					$producto_nombre = $_POST["producto_nombre"][$i];
					$cantidad_base = $_POST["cantidad_base"][$i];
					$cantidad_despachar = $_POST["cantidad_despachar"][$i];

					$this->db->query("INSERT INTO insumo_preparado (proceso_id,insumo_formula_id, producto_nombre,cantidad_base,cantidad_despachar) VALUES ('$id','$insumo_formula_id', '$producto_nombre', '$cantidad_base', '$cantidad_despachar')");
				}
				/* fin guardar el detalle de insumo preparado*/
			}
		}
		if ($save) {

			/* NOTICACION */
			$this->enviarNotificacionProcesos($ultimoIdProceso);

			return 1;
		}
	}
	function delete_task()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM task_list where id = $id");
		if ($delete) {
			return 1;
		}
	}
	function save_progress()
	{

		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id')) && !is_numeric($k) && !is_array($v)) {
				if ($k == 'progress') {
					$v = htmlentities(str_replace("'", "&#x2019;", $v));
				}

				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (!isset($is_complete)) {
			$data .= ", is_complete=0 ";
		}

		if (empty($id)) {
			$save = $this->db->query("INSERT INTO task_progress set $data");
			$taskProgress_id = $this->db->insert_id;

			$avanceId = $_POST["task_id"] ?? null;




			/* inicio guardar el detalle de incidencia*/
			if (!empty($_POST["incidencia_id"]) && is_array($_POST["incidencia_id"])) {
				for ($i = 0; $i < count($_POST["incidencia_id"]); ++$i) {

					$incidencia_id = $_POST["incidencia_id"][$i];
					$nombre_incidencia = $_POST["nombre_incidencia"][$i];

					$this->db->query("INSERT INTO avance_incidentes (avance_id,task_progress_id,incidente_id, nombre_incidente) VALUES ('$avanceId','$taskProgress_id','$incidencia_id', '$nombre_incidencia')");
				}
			}
			/* fin guardar el detalle de incidencia*/

			/* inicio guardar el detalle de equipos*/
			if (!empty($_POST["equipo_id"]) && is_array($_POST["equipo_id"])) {
				for ($i = 0; $i < count($_POST["equipo_id"]); ++$i) {

					$equipo_id = $_POST["equipo_id"][$i];
					$nombre_equipo = $_POST["nombre_equipo"][$i];
					$fecha_calibracion = !empty($_POST["fecha_calibraciones"][$i]) ? "'" . $_POST["fecha_calibraciones"][$i] . "'" : "null";
					$n_a = !empty($_POST["n_a"][$i]) ? $_POST["n_a"][$i] : 0;

					$this->db->query("INSERT INTO avance_equipos (avance_id,task_progress_id,equipo_id, nombre_equipo, fecha_calibracion,n_a) VALUES ('$avanceId','$taskProgress_id','$equipo_id', '$nombre_equipo', $fecha_calibracion, '$n_a')");
				}
			}
			/* fin guardar el detalle de equipos*/
		} else {
			$save = $this->db->query("UPDATE task_progress set $data where id = $id");

			//antes borramos todos los detalles para volver a insertar
			$this->db->query("DELETE FROM avance_incidentes where avance_id = $id");
			$this->db->query("DELETE FROM avance_equipos where avance_id = $id");

			$avanceId = $_POST["task_id"] ?? null;

			/* inicio guardar el detalle de incidencia*/
			if (!empty($_POST["incidencia_id"]) && is_array($_POST["incidencia_id"])) {
				for ($i = 0; $i < count($_POST["incidencia_id"]); ++$i) {

					$incidencia_id = $_POST["incidencia_id"][$i];
					$nombre_incidencia = $_POST["nombre_incidencia"][$i];

					$this->db->query("INSERT INTO avance_incidentes (avance_id,incidente_id, nombre_incidente) VALUES ('$avanceId','$incidencia_id', '$nombre_incidencia')");
				}
			}
			/* fin guardar el detalle de incidencia*/

			/* inicio guardar el detalle de equipos*/
			if (!empty($_POST["equipo_id"]) && is_array($_POST["equipo_id"])) {
				for ($i = 0; $i < count($_POST["equipo_id"]); ++$i) {

					$equipo_id = $_POST["equipo_id"][$i];
					$nombre_equipo = $_POST["nombre_equipo"][$i];
					$fecha_calibracion = !empty($_POST["fecha_calibraciones"][$i]) ? $_POST["fecha_calibraciones"][$i] : null;
					$n_a = !empty($_POST["n_a"][$i]) ? $_POST["n_a"][$i] : 0;

					$this->db->query("INSERT INTO avance_equipos (avance_id,equipo_id, nombre_equipo, fecha_calibracion) VALUES ('$avanceId','$equipo_id', '$nombre_equipo', '$fecha_calibracion', '$n_a')");
				}
			}
			/* fin guardar el detalle de equipos*/
		}
		if ($save) {
			if (!isset($is_complete)) {
				$this->db->query("UPDATE task_list set status = 1 where id = $task_id ");
			} else {
				$this->db->query("UPDATE task_list set status = 2 where id = $task_id ");
			}

			/* NOTICACION */
			$this->enviarNotificacionProcesos($avanceId);

			return 1;
		}
	}
	function delete_progress()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM task_progress where id = $id");
		if ($delete) {
			return 1;
		}
	}
	function save_evaluation()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$data .= ", evaluator_id = {$_SESSION['login_id']} ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO ratings set $data");
		} else {
			$save = $this->db->query("UPDATE ratings set $data where id = $id");
		}
		if ($save) {
			if (!isset($is_complete)) {
				return 1;
			}
		}
	}
	function delete_evaluation()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM ratings where id = $id");
		if ($delete) {
			return 1;
		}
	}
	function get_emp_tasks()
	{
		extract($_POST);
		if (!isset($task_id)) {
			$get = $this->db->query("SELECT * FROM task_list where employee_id = $employee_id and status = 2 and id not in (SELECT task_id FROM ratings) ");
		} else {
			$get = $this->db->query("SELECT * FROM task_list where employee_id = $employee_id and status = 2 and id not in (SELECT task_id FROM ratings where task_id !='$task_id') ");
		}

		$data = array();
		while ($row = $get->fetch_assoc()) {
			$data[] = $row;
		}
		return json_encode($data);
	}
	function get_progress()
	{
		extract($_POST);
		$get = $this->db->query("SELECT p.*,concat(u.firstname,' ',u.lastname) as uname,u.avatar FROM task_progress p inner join task_list t on t.id = p.task_id inner join employee_list u on u.id = t.employee_id where p.task_id = $task_id order by unix_timestamp(p.date_created) desc ");
		$data = array();
		while ($row = $get->fetch_assoc()) {
			$row['uname'] = ucwords($row['uname']);
			$row['progress'] = html_entity_decode($row['progress']);
			$row['date_created'] = date("M d, Y H:i:s", strtotime($row['date_created']));
			$data[] = $row;
		}
		return json_encode($data);
	}
	function get_report()
	{
		extract($_POST);
		$data = array();
		$get = $this->db->query("SELECT t.*,p.name as ticket_for FROM ticket_list t inner join pricing p on p.id = t.pricing_id where date(t.date_created) between '$date_from' and '$date_to' order by unix_timestamp(t.date_created) desc ");
		while ($row = $get->fetch_assoc()) {
			$row['date_created'] = date("M d, Y H:i:s", strtotime($row['date_created']));
			$row['name'] = ucwords($row['name']);
			$row['adult_price'] = number_format($row['adult_price'], 2);
			$row['child_price'] = number_format($row['child_price'], 2);
			$row['amount'] = number_format($row['amount'], 2);
			$data[] = $row;
		}
		return json_encode($data);
	}

	function get_insumos_formulas()
	{
		$producto = $_GET["producto"];
		$query = $this->db->query("SELECT ifm.*,(SELECT nombre_producto FROM productos WHERE id = ifm.producto_formula_id LIMIT 1) AS nombre_producto FROM insumo_formulas AS ifm LEFT JOIN productos AS p ON ifm.producto_id = p.id  WHERE p.nombre_producto = '$producto'");
		$data = [];
		if ($query->num_rows > 0) {
			foreach ($query as $key => $value) {
				$data[$key] = $value;
			}
		}
		return json_encode($data);
	}

	public function enviarNotificacionProcesos($procesoId)
	{
		$options = array(
			'cluster' => 'us2',
			'useTLS' => true
		);
		$pusher = new Pusher\Pusher(
			'ef2fee64bd6b5bc6d803',
			'86b4fd2f9a885464454b',
			'1751307',
			$options
		);


		$data = $this->db->query("SELECT * FROM task_list where id = {$procesoId} LIMIT 1");
		$pusher->trigger('my-channel', 'evento-proceso', json_encode($data->fetch_assoc()));
	}
}
