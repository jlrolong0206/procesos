<?php
include 'db_connect.php';
if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM task_progress where id = " . $_GET['id'])->fetch_array();
	foreach ($qry as $k => $v) {
		$$k = $v;
	}
	$avanceIncidentes = $conn->query("SELECT * FROM avance_incidentes where avance_id = " . $_GET['id']);
	$avanceEquipos = $conn->query("SELECT * FROM avance_equipos where avance_id = " . $_GET['id']);
}
?>
<div class="container-fluid">
	<form id="manage-progress" onsubmit="return false;">
		<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
		<input type="hidden" name="task_id" value="<?php echo isset($_GET['tid']) ? $_GET['tid'] : '' ?>">
		<div class="col-lg-12">

			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Temperatura °C</label>
						<select name="temperatura" id="" class="form-control form-control-sm">
							<option selected disabled>Seleccione</option>
							<option value="15°C">15°C</option>
							<option value="16°C">16°C</option>
							<option value="17°C">17°C</option>
							<option value="18°C">18°C</option>
							<option value="19°C">19°C</option>
							<option value="20°C">20°C</option>
							<option value="21°C">21°C</option>
							<option value="22°C">22°C</option>
							<option value="23°C">23°C</option>
							<option value="24°C">24°C</option>
							<option value="25°C">25°C</option>
							<option value="26°C">26°C</option>
							<option value="27°C">27°C</option>
							<option value="28°C">28°C</option>
							<option value="29°C">29°C</option>
							<option value="30°C">30°C</option>
						</select>
					</div>

					<div class="form-group">
						<label for="">Humedad Relativa (%)</label>
						<select name="humedad" id="" class="form-control form-control-sm">
							<option selected disabled>Seleccione</option>
							<option value="50%">50%</option>
							<option value="51%">51%</option>
							<option value="52%">52%</option>
							<option value="53%">53%</option>
							<option value="54%">54%</option>
							<option value="55%">55%</option>
							<option value="56%">56%</option>
							<option value="57%">57%</option>
							<option value="58%">58%</option>
							<option value="59%">59%</option>
							<option value="60%">60%</option>
							<option value="61%">61%</option>
							<option value="62%">62%</option>
							<option value="63%">63%</option>
							<option value="64%">64%</option>
							<option value="65%">65%</option>
							<option value="66%">66%</option>
							<option value="67%">67%</option>
							<option value="68%">68%</option>
							<option value="69%">69%</option>
							<option value="70%">70%</option>
						</select>
					</div>


				</div>


				<div class="col-md-8">
					<div class="form-group">
						<label for="">Avances</label>
						<textarea name="progress" id="progress" cols="30" rows="10" class="summernote form-control"><?php echo isset($progress) ? $progress : '' ?></textarea>
					</div>
					<div class="form-group clearfix">
						<div class="icheck-primary d-inline">
							<input type="checkbox" name="is_complete" value="1" <?php echo isset($is_complete) && $is_complete == 1 ? 'checked' : '' ?> id="is_complete">
							<label for="is_complete">
								Proceso Completado
							</label>
						</div>
					</div>

				</div>
				<div class="col-md-12 d-flex ">
					<div class="form-group flex-grow-1">
						<label for="" class="control-label">ASIGNAR INCIDENTES</label>

						<select id="asignar_incidencias" class="form-control">
							<option value="">[--Seleccione--]</option>
							<?php
							$des_incidente = $conn->query("SELECT * FROM incidentes");
							while ($row = $des_incidente->fetch_assoc()) :
							?>
								<option value='<?php echo json_encode($row) ?>'><?php echo $row['des_incidente'] ?></option>
							<?php endwhile; ?>

						</select>

					</div>
					<button id="btnAgregarIncidencia" type="button" class="btn btn-info btn-sm my-auto mx-1"><i class="fa fa-plus"></i> AGREGAR FILA INCIDENCIA</button>

				</div>
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-sm table-bordered table-hover" id="tblIncidencias">
							<thead>
								<tr>
									<th>NOMBRE</th>
									<th>ACCIONES</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($avanceIncidentes)) : ?>
									<?php foreach ($avanceIncidentes as $item) : ?>
										<tr>
											<td>
												<input type="hidden" name="incidencia_id[]" value="<?php echo $item['id'] ?>">
												<input name='nombre_incidencia[]' value="<?php echo $item['nombre_incidente'] ?>" readonly class='form-control' placeholder='Nombre...'>
											</td>
											<td class='text-center'><button type='button' class='btn btn-danger btn-sm btnEliminarIncidencia'><i class='fa fa-trash'></i></button></td>
										</tr>
									<?php endforeach ?>
								<?php endif ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-12">
					<hr>
				</div>
				<div class="col-md-12 d-flex ">
					<div class="form-group flex-grow-1">
						<label for="" class="control-label">EQUIPOS UTILIZADOS</label>


						<select id="equipos_utilizados" class="form-control">
							<option value="">[--Seleccione--]</option>
							<?php
							$descripcion = $conn->query("SELECT * FROM maquinaria");
							while ($row = $descripcion->fetch_assoc()) :
							?>
								<option value='<?php echo json_encode($row) ?>'><?php echo $row['descripcion'] ?></option>
							<?php endwhile; ?>

						</select>

					</div>
					<button id="btnAgregarEquipo" type="button" class="btn btn-info btn-sm my-auto mx-1"><i class="fa fa-plus"></i> AGREGAR FILA EQUIPO</button>

				</div>
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-sm table-bordered table-hover" id="tblEquipos">
							<thead>
								<tr>
									<th>NOMBRE</th>
									<th>FECHA CALIBRACIÓN</th>
									<th>N.A</th>
									<th>ACCIONES</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($avanceEquipos)) : ?>
									<?php foreach ($avanceEquipos as $item) : ?>
										<tr>
											<td>
												<input type="hidden" name="avance_id[]" value="<?php echo $item['id'] ?>">
												<input name='nombre_equipo[]' value="<?php echo $item['nombre_equipo'] ?>" readonly class='form-control' placeholder='Nombre...'>
											</td>
											<td><input type='date' name='fecha_calibraciones[]' value="<?php echo $item['fecha_calibracion'] ?>" class='form-control'></td>
											<td class="text-center"><input class="chckNAFila" type='checkbox' name='n_a' value="1" <?php echo $item['n_a'] ? 'checked' : '' ?>></td>
											<td class='text-center'><button type='button' class='btn btn-danger btn-sm btnEliminarIncidencia'><i class='fa fa-trash'></i></button></td>
										</tr>
									<?php endforeach ?>
								<?php endif ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<script>
	$(document).ready(function() {
		$('.summernote').summernote({
			height: 200,
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
				['fontname', ['fontname']],
				['fontsize', ['fontsize']],
				['color', ['color']],
				['para', ['ol', 'ul', 'paragraph', 'height']],
				['table', ['table']],
				['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
			]
		})
		$('.select2').select2({
			placeholder: "Seleccione",
			width: "100%"
		});

	})
	$('#manage-progress').submit(function(e) {
		e.preventDefault();
		start_load();

		const formData = new FormData($(this)[0]);
		formData.delete("n_a[]");
		$(".chckNAFila").each(function(index) {
			formData.append("n_a[]", $(this).is(":checked") ? 1 : 0);
		});

		$.ajax({
			url: 'ajax.php?action=save_progress',
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				if (resp == 1) {
					alert_toast('Datos Guardados', "success");
					setTimeout(function() {
						location.reload()
					}, 1500)
				}
			}
		})
	})
</script>