<?php include 'db_connect.php' ?>
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Lista de Productos</h1>
			</div><!-- /.col -->

		</div><!-- /.row -->
		<hr class="border-primary">
	</div><!-- /.container-fluid -->
</div>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header d-flex">
			<a id="btnGenerarReportePdf" href="reporte_productos.php" target="_blank"><img src="img/pdf.png" alt="" style="height: 40px;"></a>
			<div class="form-inline ml-3">
				<label>Tipo producto</label>
				<select class="form-control" id="cmbTipoProducto">
					<option <?php echo !empty($_GET['tipo_producto']) && $_GET['tipo_producto'] == 'producto' ? 'selected' : '' ?> value="producto">Producto</option>
					<option <?php echo !empty($_GET['tipo_producto']) && $_GET['tipo_producto'] == 'formula' ? 'selected' : '' ?> value="formula">Formula</option>
				</select>
			</div>
			<div class="card-tools ml-auto">
				<a class="btn btn-success" href="./index.php?page=nuevo_producto"><i class="fa fa-plus"></i> Nuevo Producto</a>


			</div>

		</div>
		<style>
			.badge-stock {
				font-size: 0.7em;
				padding: 3px 5px;
			}
		</style>

		<div class="card-body">

			<div class="table-responsive">
				<table class="table table-sm tabe-hover table-bordered" id="list">
					<!-- <colgroup>
					<col width="5%">
					<col width="30%">
					<col width="45%">
					<col width="20%">
				</colgroup> -->
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th>Código</th>
							<th>Lote</th>
							<th>Nombre Producto</th>
							<th>Tipo</th>
							<th>Proveedor</th>
							<th>Stock</th>
							<th>UM</th>
							<th>Bodega</th>
							<th>Fecha Creación</th>
							<th>Acción</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$adicionalCondicionales = "";
						if (!empty($_GET['tipo_producto'])) {
							$tipo_producto = $_GET['tipo_producto'];
							$adicionalCondicionales .= "WHERE tipo_producto = '$tipo_producto' ";
						}

						$qry = $conn->query("SELECT * FROM productos " . $adicionalCondicionales . " order by fecha desc");
						while ($row = $qry->fetch_assoc()):
						?>
							<tr>
								<th class="text-center"><?php echo $i++ ?></th>
								<td><?php echo $row['codigo'] ?></td>
								<td><?php echo $row['lote'] ?></td>
								<td><?php echo $row['nombre_producto'] ?></td>
								<td><?php echo $row['tipo_producto'] ?></td>
								<td><?php echo $row['nombre_proveedor'] ?></td>
								<td style="color: <?php echo ($row['cantidad'] <= $row['stock_minimo']) ? 'red' : 'green' ?>; font-weight: bold;">
									<?php echo $row['cantidad'] ?>
									<?php if ($row['cantidad'] <= $row['stock_minimo']): ?>
										<span class="badge badge-danger">STOCK BAJO</span>
									<?php endif; ?>
								</td>
								<td><?php echo $row['unidad_medida'] ?></td>
								<td><?php echo $row['bodega'] ?></td>
								<td><?php echo $row['fecha'] ?></td>
								<td class="text-center">
									<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
										Accion
									</button>
									<div class="dropdown-menu">
										<a class="dropdown-item" href="./index.php?page=editar_producto&id=<?php echo $row['id'] ?>">Editar</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item eliminar_producto" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Eliminar</a>
									</div>
								</td>
							</tr>
						<?php endwhile; ?>
					</tbody>

				</table>
			</div>

		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		var initDatatable = $('#list').dataTable()
		$('.eliminar_producto').click(function() {
			_conf("Seguro de eliminar Producto?", "eliminar_producto", [$(this).attr('data-id')])
		})

		aplicarFiltros();
		genererReportePdf();

	});

	function eliminar_producto($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=eliminar_producto',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Datos Eliminados", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}

	const genererReportePdf = () => {
		$("#btnGenerarReportePdf").on('click', function(e) {
			e.preventDefault();
			const tipoProducto = $("#cmbTipoProducto").val() || "";
			window.open(`reporte_productos.php?tipo_producto=${tipoProducto}`, "_blank");
		});
	}

	const aplicarFiltros = () => {
		$("#cmbTipoProducto").on('change', function() {
			window.location.href = 'index.php?page=lista_producto&tipo_producto=' + $(this).val()
		});
	}
</script>