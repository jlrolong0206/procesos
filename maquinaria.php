<?php include("db_connect.php") ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Maquinaria</h1>
          </div><!-- /.col -->

        </div><!-- /.row -->
            <hr class="border-primary">
      </div><!-- /.container-fluid -->
    </div>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-success nueva_maquinaria" href="javascript:void(0)"><i class="fa fa-plus"></i>Nueva Maquinaria</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="10%">
					<col width="30%">
					<col width="20%">
                    <col width="5%">
                    <col width="5%">
                   
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">Id</th>
						<th>Código</th>
						<th>Descripción</th>
                        <th>Categoria</th>
                        <th>Stock</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT * FROM maquinaria order by descripcion asc ");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo $row['codigo'] ?></b></td>
						<td><b><?php echo $row['descripcion'] ?></b></td>
                        <td><b><?php echo $row['categoria'] ?></b></td>
                        <td><b><?php echo $row['stock'] ?></b></td>
						<td class="text-center">
		                    <div class="btn-group">
		                        <a href="javascript:void(0)" data-id='<?php echo $row['id'] ?>' class="btn btn-primary btn-flat admin_maquinaria">
		                          <i class="fas fa-edit"></i>
		                        </a>
		                        <button type="button" class="btn btn-danger btn-flat eliminar_maquinaria" data-id="<?php echo $row['id'] ?>">
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
		$('.nueva_maquinaria').click(function(){
			uni_modal("Nueva Maquinaria","admin_maquinaria.php")
		})
		$('.admin_maquinaria').click(function(){
			uni_modal("Actualizar Maquinaria","admin_maquinaria.php?id="+$(this).attr('data-id'))
		})
	$('.eliminar_maquinaria').click(function(){
	_conf("Seguro de eliminar Maquinaria?","eliminar_maquinaria",[$(this).attr('data-id')])
	})
	})
	function eliminar_maquinaria($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=eliminar_maquinaria',
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