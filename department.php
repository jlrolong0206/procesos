<?php include'db_connect.php' ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dependencias</h1>
          </div><!-- /.col -->

        </div><!-- /.row -->
            <hr class="border-primary">
      </div><!-- /.container-fluid -->
    </div>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-success new_department" href="javascript:void(0)"><i class="fa fa-plus"></i>Nueva Dependencia</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="30%">
					<col width="45%">
					<col width="20%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">Id</th>
						<th>Dependencia</th>
						<th>Descripción</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT * FROM department_list order by department asc ");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo $row['department'] ?></b></td>
						<td><b><?php echo $row['description'] ?></b></td>
						<td class="text-center">
		                    <div class="btn-group">
		                        <a href="javascript:void(0)" data-id='<?php echo $row['id'] ?>' class="btn btn-primary btn-flat manage_department">
		                          <i class="fas fa-edit"></i>
		                        </a>
		                        <button type="button" class="btn btn-danger btn-flat delete_department" data-id="<?php echo $row['id'] ?>">
		                          <i class="fas fa-trash"></i>
		                        </button>
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
		$('.new_department').click(function(){
			uni_modal("Nueva Dependencia","manage_department.php")
		})
		$('.manage_department').click(function(){
			uni_modal("Actualizar Dependencia","manage_department.php?id="+$(this).attr('data-id'))
		})
	$('.delete_department').click(function(){
	_conf("Seguro de eliminar Dependencia?","delete_department",[$(this).attr('data-id')])
	})
	})
	function delete_department($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_department',
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