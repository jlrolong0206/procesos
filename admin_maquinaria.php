<?php
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM maquinaria where id={$_GET['id']}")->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="admin-maquinaria">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg" class="form-group"></div>
        <div class="form-group">
			<label for="codigo" class="control-label">Código</label>
			<input type="text" class="form-control form-control-sm" name="codigo" id="codigo" value="<?php echo isset($codigo) ? $codigo : '' ?>">
		</div>
		<div class="form-group">
			<label for="descripcion" class="control-label">Descripción</label>
			<input type="text" class="form-control form-control-sm" name="descripcion" id="descripcion" value="<?php echo isset($descripcion) ? $descripcion : '' ?>">
		</div>
        <div class="form-group">
			<label for="categoria" class="control-label">Categoria</label>
			<input type="tel" class="form-control form-control-sm" name="categoria" id="categoria" value="<?php echo isset($categoria) ? $categoria : '' ?>">
		</div>
        <div class="form-group">
			<label for="stock" class="control-label">Stock</label>
			<input type="tel" class="form-control form-control-sm" name="stock" id="stock" value="<?php echo isset($stock) ? $stock : '' ?>">
		</div>
	</form>
</div>
<script>
	$(document).ready(function(){
		$('#admin-maquinaria').submit(function(e){
			e.preventDefault();
			start_load()
			$('#msg').html('')
			$.ajax({
				url:'ajax.php?action=guardar_maquinaria',
				method:'POST',
				data:$(this).serialize(),
				success:function(resp){
					if(resp == 1){
						alert_toast("Datos Guardados.","success");
						setTimeout(function(){
							location.reload()	
						},1750)
					}else if(resp == 2){
						$('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Maquinaria ya existe!!.</div>')
						end_load()
					}
				}
			})
		})
	})

</script>