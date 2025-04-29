<?php

session_start();

include 'db_connect.php';
include 'helpers.php';
$id = $_GET['id'];

?>
<div class="container-fluid">
	<div id="post-field">
		<?php

		$progress = $conn->query("SELECT p.*,concat(u.firstname,' ',u.lastname) as uname,u.avatar,t.date_created as fecha_creacion_tarea FROM task_progress p inner join task_list t on t.id = p.task_id inner join employee_list u on u.id = t.employee_id  where p.task_id = $id order by unix_timestamp(p.date_created) asc ");
		$cantidadRegistros = $progress->num_rows;
		if ($cantidadRegistros > 0) :
			$fechaInicio = "";
			$contador = 0;
			while ($row = $progress->fetch_assoc()) :

				if ($contador == 0) {
					$fechaInicio = $row["date_created"];
				}

				if ($row['is_complete'] == 0) {
					$inicio = "Incio Labor";
				} elseif ($row['is_complete'] == 1) {

					$inicio = "Finalizo Labor";
				}
		?>

				<?php
				$qravanceIncidentes = $conn->query("SELECT * FROM avance_incidentes where task_progress_id = {$row['id']} and avance_id = {$id}");
				$qravanceEquipos = $conn->query("SELECT * FROM avance_equipos where task_progress_id = {$row['id']} and avance_id = {$id}");

				$avanceIncidentes = [];
				foreach ($qravanceIncidentes as $fila) {
					$avanceIncidentes[] = $fila;
				}

				$avanceEquipos = [];
				foreach ($qravanceEquipos as $fila) {
					$avanceEquipos[] = $fila;
				}



				?>
				<div class="post">
					<div class="user-block">
						<?php if ($_SESSION['login_type'] == 1) : ?>
							<span class="btn-group dropleft float-right">
								<span class="btndropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;">
									<i class="fa fa-ellipsis-v"></i>
								</span>
								<div class="dropdown-menu">
									<a class="dropdown-item manage_progress" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-task="<?php echo $row['task'] ?>">Editar</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item delete_progress" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Eliminar</a>
								</div>
							</span>
						<?php endif; ?>
						<img class="img-circle img-bordered-sm" src="assets/uploads/<?php echo $row['avatar'] ?>" alt="Usuario">
						<span class="username">
							<a href="#"><?php echo ucwords($row['uname']) ?></a>
						</span>
						<!-- <span class="description">
                	<span class="fa fa-calendar-day"></span>
                	
					<?php /*echo date('M d, Y',strtotime($row['date_created'])) */ ?>
            	</span> -->
					</div>

					<div class="container-fluid">
						<div class="col-lg-12">
							<div class="row">
								<div class="col-md-6">
									<dl>
										<dt><b class="border-bottom border-primary">Accion</b>

										</dt>
										<dd><?php echo $inicio ?></dd>
									</dl>
									<dl>
										<dt><b class="border-bottom border-primary">Fecha y Hora</b></dt>
										<dd><?php echo date("M d, Y H:i:s", strtotime($row['date_created'])) ?></dd>
									</dl>

									<dl>
										<dt><b class="border-bottom border-primary">Humedad Relativa</b></dt>
										<dd><?php echo ucwords($row['humedad']) ?></dd>
									</dl>


									<dl>
										<dt><b class="border-bottom border-primary">Incidentes</b></dt>
										<dd>

											<div class="table-responsive">
												<table class="table table-sm table-bordered table-hover" id="tblIncidencias">
													<thead>
														<tr>
															<th>NOMBRE</th>
														</tr>
													</thead>
													<tbody>
														<?php if (!empty($avanceIncidentes)) : ?>
															<?php foreach ($avanceIncidentes as $item) : ?>
																<tr>
																	<td>
																		<?php echo $item['nombre_incidente'] ?>
																	</td>
																</tr>
															<?php endforeach ?>
														<?php endif ?>
													</tbody>
												</table>
											</div>
										</dd>

									</dl>



								</div>
								<div class="col-md-6">
									<dl>
										<dt><b class="border-bottom border-primary">Temperatura</b></dt>
										<dd><?php echo ucwords($row['temperatura']) ?></dd>
									</dl>
									<dl>

										<?php if ($row["is_complete"]) : ?>
											<dt><b class="border-bottom border-primary">Tiempo invertido</b></dt>
											<dd><?php echo restarFechas($row['date_created'], $fechaInicio)  ?></dd>
										<?php else : ?>
											<dt><b class="border-bottom border-primary">Tiempo retraso</b></dt>
											<dd><?php echo restarFechas($row["fecha_creacion_tarea"], $row['date_created'])  ?></dd>
										<?php endif ?>

									</dl>


									<dl>
										<dt><b class="border-bottom border-primary">Equipos Utilizados</b></dt>
										<dd>

											<div class="table-responsive">
												<table class="table table-sm table-bordered table-hover" id="tblEquipos">
													<thead>
														<tr>
															<th>NOMBRE</th>
															<th>FECHA CALIBRACIÓN</th>
															<th>N.A</th>
														</tr>
													</thead>
													<tbody>
														<?php if (!empty($avanceEquipos)) : ?>
															<?php foreach ($avanceEquipos as $item) : ?>
																<tr>
																	<td>
																		<?php echo $item['nombre_equipo'] ?>
																	</td>
																	<td><?php echo $item['fecha_calibracion'] ?></td>
																	<td class="text-center"><?php echo $item['n_a'] ? 'SI' : 'NO' ?></td>
																</tr>
															<?php endforeach ?>
														<?php endif ?>
													</tbody>
												</table>
											</div>

										</dd>

									</dl>

								</div>
							</div>
						</div>
					</div>


					<div>
						<b><label for="">OBSERVACION</label></b>
						<?php echo html_entity_decode($row['progress']) ?>
					</div>

					<p>

					</p>
				</div>

				<?php
				$contador++;
				?>
			<?php endwhile; ?>
		<?php else : ?>
			<div class="mb-2">
				<center><i>No hay Avances</i></center>

			</div>
		<?php endif; ?>
	</div>
</div>
<style>
	.users-list>li img {
		border-radius: 50%;
		height: 67px;
		width: 67px;
		object-fit: cover;
	}

	.users-list>li {
		width: 33.33% !important
	}

	.truncate {
		-webkit-line-clamp: 1 !important;
	}
</style>


<div class="modal-footer display p-0 m-0">

	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
</div>
<script>
	$('.manage_progress').click(function() {
		uni_modal("<i class='fa fa-edit'></i> Editar Avance", "manage_progress.php?tid=<?php echo $id ?>&id=" + $(this).attr('data-id'), 'large')
	})
	$('.delete_progress').click(function() {
		_conf("Seguro de Eliminar Avance?", "delete_progress", [$(this).attr('data-id')])
	})

	function delete_progress($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_progress',
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
</script>
<style>
	#uni_modal .modal-footer {
		display: none
	}

	#uni_modal .modal-footer.display {
		display: flex
	}

	#post-field {
		max-height: 70vh;
		overflow: auto;
	}
</style>