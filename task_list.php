<?php include 'db_connect.php' ?>
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Procesos </h1>
			</div><!-- /.col -->

		</div><!-- /.row -->
		<hr class="border-primary">
	</div><!-- /.container-fluid -->
</div>
<div class="col-lg-12">

	<div class="form">
		<div class="card-header">
			<a href="reporte_procesos.php" target="_blank"><img src="img/pdf.png" alt="" style="height: 40px;"></a>
			<label for="">Reporte Procesos</label>
			<!-- <a href="reporte_dispensacion.php" target="_blank"><img src="img/pdf.png" alt="" style="height: 40px;"></a>
			<label for="">Orden Dispensación</label>
			<a href="reporte_fabricacion.php" target="_blank"><img src="img/pdf.png" alt="" style="height: 40px;"></a>
			<label for="">Orden Fabricación</label>
			<a href="reporte_llenado.php" target="_blank"><img src="img/pdf.png" alt="" style="height: 40px;"></a>
			<label for="">Orden Llenado</label> -->


			<?php if ($_SESSION['login_type'] == 2) : ?>
				<div class="card-tools">
					<button class="btn btn-success" id="new_task"><i class="fa fa-plus"></i> Nuevo Proceso</button>
				</div>
			<?php endif; ?>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-condensed" id="list">

				<thead>
					<tr>
						<th class="text-center">Id</th>
						<th width="30%">Procesos</th>
						<th>Fecha</th>
						<!-- <th>Hora</th> -->
						<?php if ($_SESSION['login_type'] != 0) : ?>
							<th>Asignado a</th>
						<?php endif; ?>

						<th>Estado</th>
						<th>Accion</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$where = "";
					if ($_SESSION['login_type'] == 0)
						$where = " where t.employee_id = '{$_SESSION['login_id']}' ";
					elseif ($_SESSION['login_type'] == 1)
						$where = " where e.evaluator_id = {$_SESSION['login_id']} ";


					$qry = $conn->query("SELECT t.*,concat(e.firstname,' ',e.lastname,' ',e.middlename) as name FROM task_list t inner join employee_list e on e.id = t.employee_id $where order by unix_timestamp(t.date_created) desc");
					while ($row = $qry->fetch_assoc()) :
						$trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
						unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
						$desc = strtr(html_entity_decode($row['description']), $trans);
						$desc = str_replace(array("<li>", "</li>"), array("", ", "), $desc);


					?>
						<tr>
							<td class="text-center"><?php echo $i++ ?></td>
							<td>
								<p><b><?php echo ucwords($row['task']) ?></b></p>
								<p class="truncate"><?php echo strip_tags($desc) ?></p>
							</td>
							<td><b><?php echo date("M d, Y H:i:s", strtotime($row['date_created'])) ?></b></td>
							<?php if ($_SESSION['login_type'] != 0) : ?>
								<td>
									<p><b><?php echo ucwords($row['name']) ?></b></p>
								</td>
							<?php endif; ?>
							<td>
								<?php
								if ($row['status'] == 0) {
									echo "<span class='badge badge-danger'>Pendiente</span>";
									$estado = "Pendiente";
								} elseif ($row['status'] == 1) {
									echo "<span class='badge badge-warning'>En Progreso</span>";
									$estado = "En Progreso";
								} elseif ($row['status'] == 2) {
									echo "<span class='badge badge-success'>Completado</span>";
									$estado = "Completado";
								}
								// if(strtotime($row['date_created']) < strtotime(date('Y-m-d'))){
								// 	echo "<span class='badge badge-danger mx-1'>Cerrado</span>";
								// }
								?>
							</td>
							<td class="text-center">
								<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
									Accion
								</button>
								<div class="dropdown-menu">
									<a class="dropdown-item view_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Ver Proceso</a>
									<div class="dropdown-divider"></div>
									<?php if ($_SESSION['login_type'] == 2) : ?>
										<a class="dropdown-item manage_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Editar</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item delete_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Eliminar</a>
										<div class="dropdown-divider"></div>
									<?php endif; ?>
									<?php if ($_SESSION['login_type'] == 0) : ?>
										<?php if ($row['status'] != 2) : ?>
											<a class="dropdown-item new_progress" data-pid='<?php echo $row['pid'] ?>' data-tid='<?php echo $row['id'] ?>' data-task='<?php echo ucwords($row['task']) ?>' href="javascript:void(0)">Actualizar Avance</a>
											<div class="dropdown-divider"></div>
										<?php endif; ?>
									<?php endif; ?>
									<a class="dropdown-item view_progress" data-pid='<?php echo $row['pid'] ?>' data-tid='<?php echo $row['id'] ?>' data-task='<?php echo ucwords($row['task']) ?>' href="javascript:void(0)">Ver Avance</a>
									<a class="dropdown-item" target="_blank" href="reporte_dispensacion.php?id=<?php echo $row['id'] ?>"><i class="fa fa-file-pdf text-danger"></i> Orden Dispensación (PDF)</a>
								</div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<style>
	table p {
		margin: unset !important;
	}

	table td {
		vertical-align: middle !important
	}
</style>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

<script>
	$(document).ready(function() {
		$('#list').dataTable()
		$('#new_task').click(function() {
			uni_modal("<i class='fa fa-plus'></i> Nuevo Proceso", "manage_task.php", 'mid-large')
		})
		$('.view_task').click(function() {
			uni_modal("Ver Proceso", "view_task.php?id=" + $(this).attr('data-id'), 'mid-large')
		})
		$('.manage_task').click(function() {
			uni_modal("<i class='fa fa-edit'></i> Editar Procesos", "manage_task.php?id=" + $(this).attr('data-id'), 'mid-large')
		})
		$('.new_progress').click(function() {
			uni_modal("<i class='fa fa-plus'></i> Nuevo Avance: " + $(this).attr('data-task'), `manage_progress.php?tid=${$(this).attr('data-tid')}`, 'mid-large')
		})
		$('.view_progress').click(function() {
			uni_modal("Proceso: " + $(this).attr('data-task'), `view_progress.php?id=${$(this).attr('data-tid')}`, 'mid-large')
		})
		$('.delete_task').click(function() {
			_conf("Seguro de Eliminar Proceso?", "delete_employee", [$(this).attr('data-id')])
		});

		btnAgregarFilaIncidencia();
		btnEliminarFilaIncidencia();
		btnAgregarFilaEquipo();
		btnEliminarFilaEquipo();
	})

	function delete_task($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_task',
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


	const btnAgregarFilaIncidencia = () => {
		$(document).on('click', "#btnAgregarIncidencia", function() {

			const incidencia = JSON.parse($("#asignar_incidencias").val());
			let html = "";
			html += "<tr>";
			html += `<td><input type='hidden' name='incidencia_id[]' value='${incidencia.id}'><input name='nombre_incidencia[]' class='form-control' placeholder='Nombre...' readonly value='${incidencia.des_incidente || ""}'></td>`;
			html += "<td class='text-center'><button type='button' class='btn btn-danger btn-sm btnEliminarIncidencia'><i class='fa fa-trash'></i></button></td>";
			html += "</tr>";
			$("#tblIncidencias tbody").append(html);
		});
	};

	const btnEliminarFilaIncidencia = () => {
		$(document).on('click', ".btnEliminarIncidencia", function() {
			$(this).parent("td").parent("tr").remove();
		});
	};

	const btnAgregarFilaEquipo = () => {
		$(document).on('click', "#btnAgregarEquipo", function() {

			const equipo = JSON.parse($("#equipos_utilizados").val());
			let html = "";
			html += "<tr>";
			html += `<td><input type='hidden' name='equipo_id[]' value='${equipo.id}'><input name='nombre_equipo[]' class='form-control' placeholder='Nombre...' readonly value='${equipo.descripcion || ""}'></td>`;
			html += `<td><input type='date' name='fecha_calibraciones[]' class='form-control'></td>`;
			html += `<td class='text-center'><input class='chckNAFila' type='checkbox' name='n_a[]' value='1'></td>`;
			html += "<td class='text-center'><button type='button' class='btn btn-danger btn-sm btnEliminarEquipo'><i class='fa fa-trash'></i></button></td>";
			html += "</tr>";
			$("#tblEquipos tbody").append(html);
		});
	};

	const btnEliminarFilaEquipo = () => {
		$(document).on('click', ".btnEliminarEquipo", function() {
			$(this).parent("td").parent("tr").remove();
		});
	};

	// Enable pusher logging - don't include this in production
	//Pusher.logToConsole = true;

	var pusher = new Pusher('ef2fee64bd6b5bc6d803', {
		cluster: 'us2'
	});

	var channel = pusher.subscribe('my-channel');
	channel.bind('evento-proceso', function(data) {
		const proceso = JSON.parse(data);
		if (proceso.employee_id == "<?php echo $_SESSION['login_id'] ?>") {
			location.reload();
		}
		if ("<?php echo $_SESSION['login_type'] ?>" == 2) {
			location.reload();
		}
	});
</script>