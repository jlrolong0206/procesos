<?php include("db_connect.php") ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Incidentes</h1>
          </div><!-- /.col -->

        </div><!-- /.row -->
            <hr class="border-primary">
      </div><!-- /.container-fluid -->
    </div>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-success nuevo_incidente" href="javascript:void(0)"><i class="fa fa-plus"></i>Nuevo Incidente</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="60%">
					
                    <col width="5%">
                   
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">Id</th>
						<th>Incidente</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT * FROM incidentes");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo $row['des_incidente'] ?></b></td>
					
						<td class="text-center">
		                    <div class="btn-group">
		                        <a href="javascript:void(0)" data-id='<?php echo $row['id'] ?>' class="btn btn-primary btn-flat admin_incidentes">
		                          <i class="fas fa-edit"></i>
		                        </a>
		                        <button type="button" class="btn btn-danger btn-flat eliminar_incidente" data-id="<?php echo $row['id'] ?>">
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
		$('.nuevo_incidente').click(function(){
			uni_modal("Nuevo Incidente","admin_incidentes.php")
		})
		$('.admin_incidentes').click(function(){
			uni_modal("Actualizar Incidente","admin_incidentes.php?id="+$(this).attr('data-id'))
		})
	$('.eliminar_incidente').click(function(){
	_conf("Seguro de eliminar Incidente?","eliminar_incidente",[$(this).attr('data-id')])
	})
	})
	function eliminar_incidente($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=eliminar_incidente',
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