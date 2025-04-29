<?php
include 'db_connect.php';
if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM productos where id={$_GET['id']}")->fetch_array();
	foreach ($qry as $k => $v) {
		$$k = $v;
	}
}
?>


<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Nuevo Producto</h1>
			</div><!-- /.col -->

		</div><!-- /.row -->
		<hr class="border-primary">
	</div><!-- /.container-fluid -->
</div>

<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="lista_producto">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div id="msg" class="form-group"></div>
				<div class="row">
					<div class="col-md-6 border-right">

						<div class="form-group">
							<label for="" class="control-label">Código</label>
							<input type="text" name="codigo" class="form-control form-control-sm" required value="<?php echo isset($codigo) ? $codigo : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Lote</label>
							<input type="text" name="lote" class="form-control form-control-sm" required value="<?php echo isset($lote) ? $lote : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Nombre Producto</label>
							<input type="text" name="nombre_producto" style="text-transform:uppercase" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control form-control-sm" required value="<?php echo isset($nombre_producto) ? $nombre_producto : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Tipo Producto</label>
							<div class="d-flex justify-content-center " style="gap:30px">
								<label>
									<input type="radio" <?php echo !empty($tipo_producto) && $tipo_producto == "producto" ? 'checked' : '' ?> value="producto" name="tipo_producto" class="form-check-input" required> PRODUCTO
								</label>
								<label><input type="radio" <?php echo !empty($tipo_producto) && $tipo_producto == "formula" ? 'checked' : '' ?> value="formula" name="tipo_producto" class="form-check-input" required> FORMULA</label>

							</div>
						</div>



						<?php if (!empty($tipo_producto)) : ?>
							<div id="contentTblInsumo" style="display:<?php echo $tipo_producto == "producto" ? 'none' : 'block' ?>">
							<?php else : ?>
								<div id="contentTblInsumo" style="display:none;">
								<?php endif ?>

								<div class="form-group">
									<label for="">Materia Prima</label>
									<select id="formula_id" class="form-control form-control-sm select2">
										<option value=""></option>
										<?php
										$nombre_proveedor = $conn->query("SELECT * FROM productos WHERE tipo_producto = 'producto'");
										while ($row = $nombre_proveedor->fetch_assoc()) :
										?>
											<option data-producto='<?php echo json_encode($row) ?>' value="<?php echo $row['id'] ?>"><?php echo $row['nombre_producto'] ?></option>
										<?php endwhile; ?>
									</select>
									<label for="">Cantidad</label>
									<input id='cantidad_insumo' type='number' min='0' step='any' class="form-control form-control-sm" placeholder='0'>
								</div>

								<button id="btnAgregarFilaInsumo" type="button" class="btn btn-info btn-sm mb-1"><i class="fa fa-plus"></i> AGREGAR FILA</button>
								<p class="font-weight-bold">FORMULA DE: <span id="txtFormulaDe" class="text-primary"></span></p>

								<div class="table-responsive">
									<table class="table table-sm table-bordered table-hover" id="tblInsumo">
										<thead>
											<tr>
												<th>NOMBRE</th>
												<th>CANTIDAD</th>
												<th>ACCIONES</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($listadoInsumoFormulas) && count($listadoInsumoFormulas) >= 1) : ?>
												<?php foreach ($listadoInsumoFormulas as $item) : ?>
													<tr>
														<td>
															<input type="hidden" name="producto_formula_id[]" value="<?php echo $item['producto_formula_id'] ?>">
															<input value="<?php echo $item['nombre_producto'] ?>" readonly class='form-control' placeholder='Nombre...'>
														</td>
														<td><input value="<?php echo $item['cantidad'] ?>" name='cantidad_insumo[]' type='number' min='0' step='any' class='form-control' placeholder='0'></td>
														<td class='text-center'><button type='button' class='btn btn-danger btn-sm btnEliminarFilaInsumo'><i class='fa fa-trash'></i></button></td>
													</tr>
												<?php endforeach ?>
											<?php endif ?>
										</tbody>
									</table>
								</div>

								</div>

							</div>

							<div class="col-md-6 border-right">

								<div class="form-group">
									<label for="">Proveedor</label>
									<select id="nombre_proveedor" name="nombre_proveedor" class="form-control form-control-sm select2">
										<option value=""></option>
										<?php
										$proveedores = $conn->query("SELECT * FROM proveedor");
										while ($row = $proveedores->fetch_assoc()) :
										?>
											<option value="<?php echo $row['nombre_proveedor'] ?>" <?php echo isset($nombre_proveedor) && $nombre_proveedor == $row['nombre_proveedor'] ? 'selected' : '' ?>>
												<?php echo $row['nombre_proveedor'] ?>
											</option>
										<?php endwhile; ?>
									</select>
								</div>

								<div class="form-group">
									<label for="" class="control-label">Cantidad</label>
									<input type="text" name="cantidad" class="form-control form-control-sm" required value="<?php echo isset($cantidad) ? $cantidad : '' ?>">
								</div>

								<!-- codigo nuevo para stock bajo -->
								<div class="form-group">
									<label for="" class="control-label">Stock Mínimo</label>
									<input type="number" name="stock_minimo" class="form-control form-control-sm" required value="<?php echo isset($stock_minimo) ? $stock_minimo : '0' ?>" min="0" step="0.01">
								</div>
								<!-- fin -->

								<div class="form-group">
									<label for="">Unidad de Medida</label>
									<select id="unidad_medida" name="unidad_medida" class="form-control form-control-sm select2">
										<option value="<?php echo isset($unidad_medida) ? $unidad_medida : '' ?>"><?php echo isset($unidad_medida) ? $unidad_medida : '' ?></option>
										<option value="Undidades">Unidades</option>
										<option value="Gramos">Gramos</option>
										<option value="Kilogramos">Kilogramos</option>
										<option value="Litros">Litros</option>
										<option value="Militros">Mililitros</option>
									</select>
								</div>

								<div class="form-group">
									<label for="">Bodega</label>
									<select name="bodega" id="bodega" class="form-control form-control-sm select2" required="">

										<option value="<?php echo isset($bodega) ? $bodega : '' ?>"><?php echo isset($bodega) ? $bodega : '' ?></option>
										<option value="Bodega Granel">Bodega Granel</option>
										<option value="Bodega de Empaque">Bodega de Empaque</option>
										<option value="Bodega de Materias Prima">Bodega de Materias Prima</option>
										<option value="Bodega de Producto Terminado">Bodega de Producto Terminado</option>
									</select>
								</div>
							</div>
					</div>

					<hr>
					<div class="col-lg-12 text-right justify-content-center d-flex">
						<button class="btn btn-primary mr-2">Guardar</button>
						<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=lista_producto'">Cancelar</button>
					</div>

			</form>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('#lista_producto').submit(function(e) {
			e.preventDefault();
			start_load()
			$('#msg').html('')
			$.ajax({
				url: 'ajax.php?action=guardar_producto',
				method: 'POST',
				data: $(this).serialize(),
				success: function(resp) {
					if (resp == 1) {
						alert_toast("Datos Guardados.", "success");
						setTimeout(function() {
							location.replace('index.php?page=lista_producto')
						}, 1750)
					} else if (resp == 2) {
						$('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Producto ya Existe.</div>')
						end_load()
					}
				}
			})
		})
	});

	$(function() {
		ocultarMostrarTipoProducto();
		btnAgregarFilaInsumo();
		btnEliminarFilaInsumo();
		changeMateriaPrima();
	});


	const ocultarMostrarTipoProducto = () => {
		$("input[name='tipo_producto']").on('change', function() {
			const valor = $(this).val();
			if (valor == "formula") {
				$("#contentTblInsumo").show();
			} else {
				$("#contentTblInsumo").hide();
			}
		});
	}

	const changeMateriaPrima = () => {
		$("#formula_id").on('change', function() {
			const producto = $(this).find(':selected').data('producto');
			$("#txtFormulaDe").text(producto.nombre_producto || "");
		});
	}

	const btnAgregarFilaInsumo = () => {
		$("#btnAgregarFilaInsumo").on('click', function() {
			var cantidad_i = $('#cantidad_insumo').val();


			const producto = $("#formula_id").find(':selected').data('producto');

			let existeProducto = false;
			const buscarProductos = $("#tblInsumo tbody tr").each(function(index, el) {
				const idExistente = $(this).find("td").first("td").find("input[name='producto_formula_id[]']").val();

				if (producto.id == idExistente) {
					existeProducto = true;
				}

			});


			if (existeProducto) {
				alert_toast("El insumo ya se encuentra en la lista.", "error");
				return;
			}

			let html = "";
			html += "<tr>";
			html += `<td><input type='hidden' name='producto_formula_id[]' value='${producto.id}'><input class='form-control form-control-sm' placeholder='Nombre...' readonly value='${producto.nombre_producto || ""}'></td>`;
			html += `<td><input name='cantidad_insumo[]' type='number' min='0' step='any' class='form-control form-control-sm' value="${cantidad_i}"></td>`;
			html += "<td class='text-center'><button type='button' class='btn btn-danger btn-sm btnEliminarFilaInsumo'><i class='fa fa-trash'></i></button></td>";
			html += "</tr>";
			$("#tblInsumo tbody").append(html);
		});
	}

	const btnEliminarFilaInsumo = () => {
		$(document).on('click', ".btnEliminarFilaInsumo", function() {
			$(this).parent("td").parent("tr").remove();
		});
	};

	$(document).ready(function() {
		// Inicializar Select2 en todos los select
		$('#formula_id').select2({
			placeholder: "Seleccione una materia prima",
			allowClear: true
		});

		$('#nombre_proveedor').select2({
			placeholder: "Seleccione un proveedor",
			allowClear: true
		});

		$('#unidad_medida').select2({
			placeholder: "Seleccione una unidad de medida",
			allowClear: true
		});

		$('#bodega').select2({
			placeholder: "Seleccione una bodega",
			allowClear: true
		});
	});
</script>