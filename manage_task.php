<?php

include 'db_connect.php';
if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM task_list where id = " . $_GET['id'])->fetch_array();
	foreach ($qry as $k => $v) {
		$$k = $v;
	}

	$qryInsumoPreparado = $conn->query(
		"SELECT * FROM insumo_preparado WHERE proceso_id = {$_GET['id']}"
	);

	$listadoInsumoPreparado = [];
	foreach ($qryInsumoPreparado as $row) {
		$listadoInsumoPreparado[] = $row;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-task">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-md-5">
					<div class="form-group">
						<label for="">Proceso</label>
						<input type="text" class="form-control form-control-sm" name="task" value="<?php echo isset($task) ? $task : '' ?>" required>
					</div>

					<div class="form-group">
						<label for="">Asignar a</label>
						<select name="employee_id" id="employee_id" class="form-control form-control-sm" required>
							<option value=""></option>
							<?php
							$employees = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM employee_list order by concat(lastname,', ',firstname,' ',middlename) asc");
							while ($row = $employees->fetch_assoc()) :
							?>
								<option value="<?php echo $row['id'] ?>" <?php echo isset($employee_id) && $employee_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
							<?php endwhile; ?>
						</select>
					</div>

					<div class="form-group">
						<label for="">Fecha</label>
						<input type="date" class="form-control form-control-sm" name="due_date" value="<?php echo isset($due_date) ? $due_date : date("Y-m-d") ?>" required>
					</div>

					<div class="form-group">
						<label for="">Lote</label>
						<input type="text" class="form-control form-control-sm" name="lote" value="<?php echo isset($lote) ? $lote : '' ?>" required>
					</div>

					<div class="form-group">
						<label for="">Productos</label>
						<select id="producto" name="producto" class="form-control form-control-sm select2">
						
                            <option value=""></option>
							<?php
							$producto = $conn->query("SELECT * FROM productos");
							while ($row = $producto->fetch_assoc()) :
							?>
								<option value="<?php echo $row['nombre_producto'] ?>"><?php echo $row['nombre_producto'] ?></option>
							<?php endwhile; ?>


						</select>
					</div>
					
					<div class="form-group">
						<label for="">Cantidad</label>
						<input type="number" min="0" step="any" class="form-control form-control-sm" name="cant_producto" id="cant_producto" value="<?php echo isset($cant_producto) ? $cant_producto : '' ?>" required>
					</div>
				</div>

				

				<div class="col-md-7">
					<div class="form-group">
						<label for="">Descripción</label>
						<textarea name="description" id="" cols="30" rows="10" class="summernote form-control">
							<?php echo isset($description) ? $description : '' ?>
						</textarea>
					</div>
				</div>

				<div class="col-md-12" id="tblProductosFormula" style="display: none;">
					<div class="form-group">
						<p>Lista de insumos de: <span id="txtNombreInsumo" class="text-primary"></span></p>
						<div class="table-responsive">
							<table class="table table-sm table-bordered table-hover" id="tblInsumo">
								<thead>
									<tr>
										<th>NOMBRE</th>
										<th>CANTIDAD BASE</th>
										<th>CANT. A DESPACHAR</th>
										<th>ACCIÓN</th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($listadoInsumoPreparado) && count($listadoInsumoPreparado) >= 1) : ?>
										<?php foreach ($listadoInsumoPreparado as $item) : ?>
											<tr>
												<td>
													<input type='hidden' name='insumo_formula_id[]' value="<?php echo $item["insumo_formula_id"] ?>">
													<input class='form-control' name='producto_nombre[]' placeholder='Nombre...' readonly value="<?php echo $item["producto_nombre"] ?>">
												</td>
												<td><input name='cantidad_base[]' value='<?php echo floatval($item["cantidad_base"]) ?>' type='number' min='0' step='any' class='form-control' placeholder='0' readonly></td>
												<td><input name='cantidad_despachar[]' value='<?php echo floatval($item["cantidad_despachar"]) ?>' type='number' min='0' step='any' class='form-control' placeholder='0' readonly></td>
												<td class='text-center'><button type='button' class='btn btn-danger btn-sm btnEliminarFilaInsumo'><i class='fa fa-trash'></i></button></td>
											</tr>
										<?php endforeach ?>
									<?php endif ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>




					<!-- Campos condicionales para la dependencia específica -->
					<div id="camposProductos" style="display: none;">
						
						<button type="button" id="agregarProducto" class="btn btn-primary btn-sm">Agregar Producto</button>
					</div>
				</div>
				

				<!-- Tabla de productos seleccionados (solo para la dependencia específica) -->
				<div class="col-md-12" id="tblProductosContainer" style="display: none;">
					<div class="form-group">
						<p>Lista de productos seleccionados:</p>
						<div class="table-responsive">
							<table class="table table-sm table-bordered table-hover" id="tblProductos">
								<thead>
									<tr>
										<th>Producto</th>
										<th>Cantidad</th>
										<th>Acción</th>
									</tr>
								</thead>
								<tbody>
									<!-- Aquí se agregarán los productos dinámicamente -->
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<script>
	$(document).ready(function() {
		$('#employee_id').select2({
			placeholder: 'Seleccione Dependencia',
			width: '100%'
		});

		$('#producto').select2({
			placeholder: 'Seleccione Producto',
			width: '100%'
		});

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
		});

		listarInsumosFormulas();
		btnEliminarFilaInsumo();
		changeCantidadProducto();
	})

		// Mostrar u ocultar campos según la dependencia seleccionada
		$('#employee_id').change(function() {
			var dependenciaId = $(this).val();
			if (dependenciaId == 6) { // Cambia "1" por el ID de la dependencia específica
				$('#camposProductos').show();
				$('#tblProductosContainer').show();
			} else {
				$('#camposProductos').hide();
				$('#tblProductosContainer').hide();
			}
			if (dependenciaId == 3 || dependenciaId == 4 || dependenciaId == 8) { // Cambia "1" por el ID de la dependencia específica
				$('#tblProductosFormula').show();
			} else {
				$('#tblProductosFormula').hide();
			}
		});

	

		// Agregar producto a la tabla
		$('#agregarProducto').click(function() {
			var productoId = $('#producto').val();
			var productoNombre = $('#producto option:selected').text();
			var cantidad = $('#cant_producto').val();

			if (productoId && cantidad) {
				var row = `<tr>
								<td><input type="hidden" name="producto_id[]" value="${productoId}">${productoNombre}</td>
								<td><input type="number" name="cantidad[]" value="${cantidad}" class="form-control" readonly></td>
								<td class='text-center'><button type='button' class='btn btn-danger btn-sm btnEliminarFilaInsumo'><i class='fa fa-trash'></i></button></td>
							</tr>`;
				$('#tblProductos tbody').append(row);
				$('#producto').val('').trigger('change');
				$('#cant_producto').val('');
			} else {
				alert('Por favor, seleccione un producto y una cantidad.');
			}
		});

		// Eliminar producto de la tabla
		$(document).on('click', '.btnEliminarProducto', function() {
			$(this).closest('tr').remove();
		});
	
	$('#manage-task').submit(function(e) {
		e.preventDefault();

		const formData = new FormData($(this)[0]);

		$.ajax({
			url: 'ajax.php?action=save_task',
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			beforeSend: function() {
				start_load();
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast('Datos Guardados', "success");
					setTimeout(function() {
						location.reload();
					}, 1500);
				} else {
					const jsonData = JSON.parse(resp);
					if (jsonData.error) {
						alert_toast(jsonData.mensaje, "error");
					}
				}
			},
			complete: function() {
				end_load();
			}
		});
	});


	const listarInsumosFormulas = () => {
		$(document).on("change", "select[name='producto']", function() {
			$.ajax({
				url: 'ajax.php?action=get_insumos_formulas',
				data: {
					producto: $(this).val()
				},
				success: function(data) {

					$("#txtNombreInsumo").text($("select[name='producto']").val());
					const insumos = JSON.parse(data);
					let html = "";
					insumos.forEach(item => {
						html += "<tr>";
						html += `<td><input type='hidden' name='insumo_formula_id[]' value='${item.id}'>
						<input class='form-control' name='producto_nombre[]' placeholder='Nombre...' readonly value='${item.nombre_producto || ""}'></td>`;
						html += `<td><input name='cantidad_base[]' value='${Number(item.cantidad)}' type='number' min='0' step='any' class='form-control' placeholder='0' readonly></td>`;
						html += `<td><input name='cantidad_despachar[]' type='number' min='0' step='any' class='form-control' placeholder='0' readonly></td>`;
						html += "<td class='text-center'><button type='button' class='btn btn-danger btn-sm btnEliminarFilaInsumo'><i class='fa fa-trash'></i></button></td>";
						html += "</tr>";
					});

					$("#tblInsumo tbody").html(html);

					realizarCalculoPorFila();
				}
			})
		});
	};

	const btnEliminarFilaInsumo = () => {
		$(document).on('click', ".btnEliminarFilaInsumo", function() {
			$(this).parent("td").parent("tr").remove();
		});
	};

	const changeCantidadProducto = () => {
		$(document).on("input", "#cant_producto", function() {
			realizarCalculoPorFila();
		})
	}

	const realizarCalculoPorFila = () => {

		Array.from($("input[name='cantidad_base[]']")).forEach(function(item, index) {

			const cantidadBaseFormula = 1000;
			const cantidadProducto = Number($("#cant_producto").val());
			const total = (Number(item.value) * cantidadProducto) / cantidadBaseFormula;

			$("input[name='cantidad_despachar[]']")[index].value = total;
		});

	};
</script>