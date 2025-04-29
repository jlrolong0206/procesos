<?php include'db_connect.php' ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Lista de Empleados</h1>
          </div><!-- /.col -->

        </div><!-- /.row -->
            <hr class="border-primary">
      </div><!-- /.container-fluid -->
    </div>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-success" href="./index.php?page=new_employee"><i class="fa fa-plus"></i> Nuevo Empleado</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">Id</th>
						<th>Nombre</th>
						<th>Email</th>
						<th>Dependencia</th>
						<th>Cargo</th>
						<th>Accion</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$designations = $conn->query("SELECT * FROM designation_list ");
					$design_arr[0]= "Unset";
					while($row=$designations->fetch_assoc()){
						$design_arr[$row['id']] =$row['designation'];
					}
					$departments = $conn->query("SELECT * FROM department_list ");
					$dept_arr[0]= "Unset";
					while($row=$departments->fetch_assoc()){
						$dept_arr[$row['id']] =$row['department'];
					}
					$qry = $conn->query("SELECT *,concat(firstname,' ',lastname,' ',middlename) as name FROM employee_list order by concat(firstname,', ',lastname,' ',middlename) asc");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo ucwords($row['name']) ?></b></td>
						<td><b><?php echo $row['email'] ?></b></td>
						<td><b><?php echo isset($dept_arr[$row['department_id']]) ? $dept_arr[$row['department_id']] : 'Unknow Department' ?></b></td>
						<td><b><?php echo isset($design_arr[$row['designation_id']]) ? $design_arr[$row['designation_id']] : 'Unknow Designation' ?></b></td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Accion
		                    </button>
		                    <div class="dropdown-menu">
		                      <a class="dropdown-item view_employee" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Ver</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item" href="./index.php?page=edit_employee&id=<?php echo $row['id'] ?>">Editar</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item delete_employee" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Eliminar</a>
		                    </div>
						</td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#list').dataTable()
	$('.view_employee').click(function(){
		uni_modal("<i class='fa fa-id-card'></i> Detalle de Empleado","view_employee.php?id="+$(this).attr('data-id'))
	})
	$('.delete_employee').click(function(){
	_conf("Seguro de eliminar Empleado?","delete_employee",[$(this).attr('data-id')])
	})
	})
	function delete_employee($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_employee',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Datos Eliminados",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>