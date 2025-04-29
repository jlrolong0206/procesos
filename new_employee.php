<?php

?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Nuevo Empleado</h1>
          </div><!-- /.col -->

        </div><!-- /.row -->
            <hr class="border-primary">
      </div><!-- /.container-fluid -->
    </div>
<div class="col-lg-12">

	<div class="card">
		<div class="card-body">
			<form action="" id="manage_employee">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">Primer Nombre</label>
							<input type="text" name="firstname" class="form-control form-control-sm" required value="<?php echo isset($firstname) ? $firstname : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Segundo Nombre (opcional)</label>
							<input type="text" name="middlename" class="form-control form-control-sm" value="<?php echo isset($middlename) ? $middlename : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Apellidos</label>
							<input type="text" name="lastname" class="form-control form-control-sm" required value="<?php echo isset($lastname) ? $lastname : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Dependencia</label>
							<select name="department_id" id="department_id" class="form-control form-control-sm select2">
								<option value=""></option>
								<?php 
								$departments = $conn->query("SELECT * FROM department_list order by department asc");
								while($row=$departments->fetch_assoc()):
								?>
								<option value="<?php echo $row['id'] ?>" <?php echo isset($department_id) && $department_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['department'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						<div class="form-group">
							<label for="" class="control-label">Cargo</label>
							<select name="designation_id" id="designation_id" class="form-control form-control-sm select2">
								<option value=""></option>
								<?php 
								$designations = $conn->query("SELECT * FROM designation_list order by designation asc");
								while($row=$designations->fetch_assoc()):
								?>
								<option value="<?php echo $row['id'] ?>" <?php echo isset($designation_id) && $designation_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['designation'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						<div class="form-group">
							<label for="" class="control-label">Supervisor</label>
							<select name="evaluator_id" id="evaluator_id" class="form-control form-control-sm select2">
								<option value=""></option>
								<?php 
								$evaluators = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM evaluator_list order by concat(lastname,', ',firstname,' ',middlename) asc");
								while($row=$evaluators->fetch_assoc()):
								?>
								<option value="<?php echo $row['id'] ?>" <?php echo isset($evaluator_id) && $evaluator_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Cambiar Foto</label>
							<div class="custom-file">
		                      <input type="file" class="custom-file-input" id="customFile" name="img" onchange="displayImg(this,$(this))">
		                      <label class="custom-file-label" for="customFile">Archivo para foto</label>
		                    </div>
						</div>
						<div class="form-group d-flex justify-content-center align-items-center">
							<img src="<?php echo isset($avatar) ? 'assets/uploads/'.$avatar :'' ?>" alt="Usuario" id="cimg" class="img-fluid img-thumbnail ">
						</div>
						<div class="form-group">
							<label class="control-label">Email</label>
							<input type="email" class="form-control form-control-sm" name="email" required value="<?php echo isset($email) ? $email : '' ?>">
							<small id="#msg"></small>
						</div>
						<div class="form-group">
							<label class="control-label">Password</label>
							<input type="password" class="form-control form-control-sm" name="password" <?php echo !isset($id) ? "required":'' ?>>
							<small><i><?php echo isset($id) ? "Leave this blank if you dont want to change you password":'' ?></i></small>
						</div>
						<div class="form-group">
							<label class="label control-label">Confirmar Password</label>
							<input type="password" class="form-control form-control-sm" name="cpass" <?php echo !isset($id) ? 'required' : '' ?>>
							<small id="pass_match" data-status=''></small>
						</div>
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button class="btn btn-primary mr-2">Guardar</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=employee_list'">Cancelar</button>
				</div>
			</form>
		</div>
	</div>
</div>
<style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<script>
	$('[name="password"],[name="cpass"]').keyup(function(){
		var pass = $('[name="password"]').val()
		var cpass = $('[name="cpass"]').val()
		if(cpass == '' ||pass == ''){
			$('#pass_match').attr('data-status','')
		}else{
			if(cpass == pass){
				$('#pass_match').attr('data-status','1').html('<i class="text-success">Password Correcto.</i>')
			}else{
				$('#pass_match').attr('data-status','2').html('<i class="text-danger">Password no coinciden.</i>')
			}
		}
	})
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
	$('#manage_employee').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
		if($('[name="password"]').val() != '' && $('[name="cpass"]').val() != ''){
			if($('#pass_match').attr('data-status') != 1){
				if($("[name='password']").val() !=''){
					$('[name="password"],[name="cpass"]').addClass("border-danger")
					end_load()
					return false;
				}
			}
		}
		$.ajax({
			url:'ajax.php?action=save_employee',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Datos Guardados.',"success");
					setTimeout(function(){
						location.replace('index.php?page=employee_list')
					},750)
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger'>Email ya existe.</div>");
					$('[name="email"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
</script>