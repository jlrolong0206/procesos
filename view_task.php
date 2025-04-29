<?php 
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT t.*,concat(e.lastname,', ',e.firstname,' ',e.middlename) as name FROM task_list t inner join employee_list e on e.id = t.employee_id  where t.id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
 
	$qryInsumoPreparado = $conn->query(
		"SELECT * FROM insumo_preparado WHERE proceso_id = {$_GET['id']}"
	);
	
	$listadoInsumoPreparado = [];
	
	
	$qrmateriaPrima = $conn->query("select t.id as task_id, ip.insumo_formula_id,  p.codigo as producto_codigo, p.nombre_producto as producto_nombre, p.lote as producto_lote, ip.cantidad_despachar from task_list t, insumo_preparado ip, insumo_formulas ifor, productos p where t.id = ip.proceso_id and ip.insumo_formula_id = ifor.id and ifor.producto_formula_id = p.id and t.id = ".$_GET['id']);
    
    foreach ($qrmateriaPrima as $row) {
		$listadoInsumoPreparado[] = $row;
	}
}
?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-6">
				<dl>
					<dt><b class="border-bottom border-primary">Proceso</b></dt>
					<dd><?php echo ucwords($task) ?></dd>
				</dl>
				<dl>
					<dt><b class="border-bottom border-primary">Asignado a</b></dt>
					<dd><?php echo ucwords($name) ?></dd>
				</dl>
				<dl>
					<dt><b class="border-bottom border-primary">Fecha de Asignación</b></dt>
					<dd><?php echo date("M d, Y H:i:s",strtotime($date_created)) ?></dd>
				</dl>
				
				
			</div>
			<div class="col-md-6">
			<dl>
					<dt><b class="border-bottom border-primary">Lote</b></dt>
					<dd><?php echo ucwords($lote) ?></dd>
				</dl>
			<dl>
					<dt><b class="border-bottom border-primary">Producto</b></dt>
					<dd><?php echo ucwords($producto) ?></dd>
				</dl>
				<dl>
					<dt><b class="border-bottom border-primary">Cantidad</b></dt>
					<dd><?php echo ucwords($cant_producto) ?></dd>
				</dl>
				
				<dl>
					<dt><b class="border-bottom border-primary">Estado</b></dt>
					<dd>
						<?php 
			        	if($status == 0){
					  		echo "<span class='badge badge-danger'>Pendiente</span>";
			        	}elseif($status == 1){
					  		echo "<span class='badge badge-warning'>En Progreso</span>";
			        	}elseif($status == 2){
					  		echo "<span class='badge badge-success'>Completado</span>";
			        	}
			        	// if(strtotime($due_date) < strtotime(date('Y-m-d'))){
					  	// 	echo "<span class='badge badge-danger mx-1'>Atrasado</span>";
			        	// }
			        	?>
					</dd>
				</dl>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<dl>
				<dt><b class="border-bottom border-primary">Descripción</b></dt>
				<dd><?php echo html_entity_decode($description) ?></dd>
			</dl>
			</div>
		</div> 
		
    	 <div class="col-md-12">
					<div class="form-group">
						<p>Lista de insumos de: <span id="txtNombreInsumo" class="text-primary"></span></p>
						<div class="table-responsive">
							<table class="table table-sm table-bordered table-hover" id="tblInsumo">
								<thead>
									<tr>
									    <th>CODIGO</th>
										<th>NOMBRE</th>
									    <th>LOTE</th>
										<th>CANT. A DESPACHAR</th>
									 
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($listadoInsumoPreparado) && count($listadoInsumoPreparado) >= 1) : ?>
										<?php foreach ($listadoInsumoPreparado as $item) : ?>
											<tr>
												<td>
													<input type='hidden' name='producto_codigo[]' value="<?php echo $item["producto_codigo"] ?>">
													<input class='form-control' name='producto_codigo[]' placeholder='Nombre...' readonly value="<?php echo $item["producto_codigo"] ?>">
												</td>
												
												<td>
													<input type='hidden' name='producto_nombre[]' value="<?php echo $item["producto_nombre"] ?>">
													<input class='form-control' name='producto_nombre[]' placeholder='Nombre...' readonly value="<?php echo $item["producto_nombre"] ?>">
												</td>
												
												<td>
													<input type='hidden' name='producto_lote[]' value="<?php echo $item["producto_lote"] ?>">
													<input   name='producto_lote[]' class='form-control' style="font-size: 5; text-align:right;" placeholder='Nombre...' readonly value="<?php echo $item["producto_lote"] ?>">
												</td>
											 
												<td><input name='cantidad_despachar[]' value='<?php echo floatval($item["cantidad_despachar"]) ?>' class='form-control' type='number' size="4" min='0' step='any' style="font-size: 5; text-align:right;" readonly></td>
												 
											</tr>
										<?php endforeach ?>
									<?php endif ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>

	</div>
</div>
<style>
	#uni_modal .modal-footer{
		display: none
	}
	#uni_modal .modal-footer.display{
		display: flex
	}
	#post-field{
		max-height: 70vh;
		overflow: auto;
	}
</style>
<div class="modal-footer display p-0 m-0">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
</div>