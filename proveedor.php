<?php include("db_connect.php") ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Proveedor</h1>
          </div><!-- /.col -->

        </div><!-- /.row -->
            <hr class="border-primary">
      </div><!-- /.container-fluid -->
    </div>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-success nuevo_proveedor" href="javascript:void(0)"><i class="fa fa-plus"></i>Nuevo Proveedor</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="10%">
					<col width="20%">
					<col width="20%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">Id</th>
						<th>Nit Proveedor</th>
						<th>Proveedor</th>
						<th>Asesor</th>
						<th>Dirección</th>
                        <th>Contacto</th>
						<th>Correo</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT * FROM proveedor order by nombre_proveedor asc ");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo $row['nit_proveedor'] ?></b></td>
						<td><b><?php echo $row['nombre_proveedor'] ?></b></td>
						<td><b><?php echo $row['nombre_asesor'] ?></b></td>
						<td><b><?php echo $row['direccion_proveedor'] ?></b></td>
                        <td><b><?php echo $row['contacto_proveedor'] ?></b></td>
						<td><b><?php echo $row['correo_proveedor'] ?></b></td>
						<td class="text-center">
		                    <div class="btn-group">
		                        <a href="javascript:void(0)" data-id='<?php echo $row['id'] ?>' class="btn btn-primary btn-flat admin_proveedor">
		                          <i class="fas fa-edit"></i>
		                        </a>
		                        <button type="button" class="btn btn-danger btn-flat eliminar_proveedor" data-id="<?php echo $row['id'] ?>">
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
		$('.nuevo_proveedor').click(function(){
			uni_modal("Nuevo Proveedor","admin_proveedor.php")
		})
		$('.admin_proveedor').click(function(){
			uni_modal("Actualizar Proveedor","admin_proveedor.php?id="+$(this).attr('data-id'))
		})
	$('.eliminar_proveedor').click(function(){
	_conf("Seguro de eliminar Proveedor?","eliminar_proveedor",[$(this).attr('data-id')])
	})
	})
	function eliminar_proveedor($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=eliminar_proveedor',
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