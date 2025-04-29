<?php 
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT r.*,concat(e.lastname,', ',e.firstname,' ',e.middlename) as name,t.task,concat(ev.lastname,', ',ev.firstname,' ',ev.middlename) as ename,((((r.efficiency + r.timeliness + r.quality + r.accuracy)/4)/5) * 100) as pa FROM ratings r inner join employee_list e on e.id = r.employee_id inner join task_list t on t.id = r.task_id inner join evaluator_list ev on ev.id = r.evaluator_id  where r.id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
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
					<dt><b class="border-bottom border-primary">Supervisor</b></dt>
					<dd><?php echo ucwords($ename) ?></dd>
				</dl>
				<dl>
					<dt><b class="border-bottom border-primary">Fecha de Supervision</b></dt>
					<dd><?php echo date("M d, Y H:i:s",strtotime($date_created)) ?></dd>
				</dl>
				<dl>
				<dt><b class="border-bottom border-primary">Observaciones</b></dt>
				<dd><?php echo $remarks ?></dd>
				</dl>
			</div>
			<div class="col-md-6">
				<b>Observaciones:</b>
				<dl>
					<dt><b class="border-bottom border-primary">Eficiencia</b></dt>
					<dd><?php echo $efficiency ?></dd>
				</dl>
				<dl>
					<dt><b class="border-bottom border-primary">Tiempo</b></dt>
					<dd><?php echo $timeliness ?></dd>
				</dl>
				<dl>
					<dt><b class="border-bottom border-primary">Calidad</b></dt>
					<dd><?php echo $quality ?></dd>
				</dl>
				<dl>
					<dt><b class="border-bottom border-primary">Exactitud</b></dt>
					<dd><?php echo $accuracy ?></dd>
				</dl>
				<dl>
					<dt><b class="border-bottom border-primary">Promedio de rendimiento</b></dt>
					<dd><?php echo number_format($pa,2).'%' ?></dd>
				</dl>
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