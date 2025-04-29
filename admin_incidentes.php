<?php
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM incidentes where id={$_GET['id']}")->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="admin-incidentes">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg" class="form-group"></div>
        <div class="form-group">
			<label for="des_incidente" class="control-label">Incidente</label>
			<input type="text" class="form-control form-control-sm" name="des_incidente" id="des_incidente" value="<?php echo isset($des_incidente) ? $des_incidente : '' ?>">
		</div>
	</form>
</div>
<script>
	$(document).ready(function(){
		$('#admin-incidentes').submit(function(e){
			e.preventDefault();
			start_load()
			$('#msg').html('')
			$.ajax({
				url:'ajax.php?action=guardar_incidente',
				method:'POST',
				data:$(this).serialize(),
				success:function(resp){
					if(resp == 1){
						alert_toast("Datos Guardados.","success");
						setTimeout(function(){
							location.reload()	
						},1750)
					}else if(resp == 2){
						$('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Incidente ya Existe!!.</div>')
						end_load()
					}
				}
			})
		})
	})

</script>