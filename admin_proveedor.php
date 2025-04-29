<?php
include 'db_connect.php';
if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM proveedor where id={$_GET['id']}")->fetch_array();
	foreach ($qry as $k => $v) {
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="admin-proveedor">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="row">
			<div class="col-md-6 border-right">
				<div class="form-group">
					<label for="nit_proveedor" class="control-label">Nit</label>
					<input type="text" class="form-control form-control-sm" name="nit_proveedor" id="nit_proveedor" value="<?php echo isset($nit_proveedor) ? $nit_proveedor : '' ?>">
				</div>
				<div class="form-group">
					<label for="nombre_proveedor" class="control-label">Proveedor</label>
					<input type="text" class="form-control form-control-sm" name="nombre_proveedor" id="nombre_proveedor" value="<?php echo isset($nombre_proveedor) ? $nombre_proveedor : '' ?>">
				</div>
				<div class="form-group">
					<label for="nombre_asesor" class="control-label">Asesor</label>
					<input type="text" class="form-control form-control-sm" name="nombre_asesor" id="nombre_asesor" value="<?php echo isset($nombre_asesor) ? $nombre_asesor : '' ?>">
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<label for="direccion_proveedor" class="control-label">Dirección</label>
					<input type="text" class="form-control form-control-sm" name="direccion_proveedor" id="direccion_proveedor" value="<?php echo isset($direccion_proveedor) ? $direccion_proveedor : '' ?>">
				</div>
				<div class="form-group">
					<label for="contacto_proveedor" class="control-label">Contacto</label>
					<input type="tel" class="form-control form-control-sm" name="contacto_proveedor" id="contacto_proveedor" value="<?php echo isset($contacto_proveedor) ? $contacto_proveedor : '' ?>">
				</div>
				<div class="form-group">
					<label for="correo_proveedor" class="control-label">Correo</label>
					<input type="email" class="form-control form-control-sm" name="correo_proveedor" id="correo_proveedor" value="<?php echo isset($correo_proveedor) ? $correo_proveedor : '' ?>">
				</div>
			</div>
		</div>

	</form>
</div>
<script>
	$(document).ready(function() {
		$('#admin-proveedor').submit(function(e) {
			e.preventDefault();
			start_load()
			$('#msg').html('')
			$.ajax({
				url: 'ajax.php?action=guardar_proveedor',
				method: 'POST',
				data: $(this).serialize(),
				success: function(resp) {
					if (resp == 1) {
						alert_toast("Datos Guardados.", "success");
						setTimeout(function() {
							location.reload()
						}, 1750)
					} else if (resp == 2) {
						$('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Proveedor ya Existe!!.</div>')
						end_load()
					}
				}
			})
		})
	})
</script>