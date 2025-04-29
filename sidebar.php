  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="dropdown">
   	<a href="./" class="brand-link">
        <?php if($_SESSION['login_type'] == 2): ?>
        <h3 class="text-center p-0 m-0">Administrador</h3> 
        <?php elseif($_SESSION['login_type'] == 1): ?>
        <h3 class="text-center p-0 m-0">Supervisor</h3>
        <!-- <img src="img/logo ricardo.png" alt="" width="230px"> -->
         <?php else: ?>
        <h3 class="text-center p-0 m-0">Operario</h3>
        <!-- <img src="img/logo ricardo.png" alt="" width="230px"> -->
        <?php endif; ?>

    </a>
      
    </div>
    <div class="sidebar pb-4 mb-4">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
         <li class="nav-item dropdown">
            <a href="./" class="nav-link nav-home">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li> 
          <li class="nav-item dropdown">
            <a href="./index.php?page=task_list" class="nav-link nav-task_list">
              <i class="nav-icon fas fa-tasks"></i>
              <p>
                Procesos
              </p>
            </a>
          </li> 
          
          <?php if($_SESSION['login_type'] != 0): ?>
          <li class="nav-item dropdown">
            <a href="./index.php?page=evaluation" class="nav-link nav-evaluation">
              <i class="nav-icon far fa-edit"></i>
              <p>
                Supervición
              </p>
            </a>
          </li>
          
        <?php endif; ?>
        <?php if($_SESSION['login_type'] == 2): ?>
          
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_user">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>
                Gestión
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="./index.php?page=designation" class="nav-link  nav-designation tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Cargos</p>
                </a>
              </li>
            <li class="nav-item">
                <a href="./index.php?page=department" class="nav-link  nav-department tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Dependecias</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=proveedor" class="nav-link  nav-proveedor tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Proveedor</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=maquinaria" class="nav-link nav-maquinaria tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Maquinaria</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=incidentes" class="nav-link nav-incidentes tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Incidentes</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_user">
              <i class="nav-icon fas fa-boxes"></i>
              <p>
                Productos
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=nuevo_producto" class="nav-link  nav-nuevo_producto tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Nuevo Producto</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=lista_producto" class="nav-link nav-lista_producto tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Lista Productos</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=dispensacion" class="nav-link nav-dispensacion tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Dispensación</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_employee">
              <i class="nav-icon fas fa-user-friends"></i>
              <p>
                Empleados
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_employee" class="nav-link nav-new_employee tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Nuevo</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=employee_list" class="nav-link nav-employee_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Lista</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_evaluator">
              <i class="nav-icon fas fa-user-check"></i>
              <p>
                Supervisor
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_evaluator" class="nav-link nav-new_evaluator tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Nuevo</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=evaluator_list" class="nav-link nav-evaluator_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Lista</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_user">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Usuarios
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_user" class="nav-link nav-new_user tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Nuevo</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=user_list" class="nav-link nav-user_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Lista</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_user">
              <i class="nav-icon fas fa-list-alt"></i>
              <p>
                Reportes
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=reporte_proceso" class="nav-link  nav-reporte_proceso tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Reporte Procesos</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=reporte_dependencia" class="nav-link nav-reporte_dependencia tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Reporte Dependencia</p>
                </a>
              </li>
            </ul>
          </li>
        <?php endif; ?>
        </ul>
      </nav>
    </div>
  </aside>
  <script>
  	$(document).ready(function(){
      var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'Inicio' ?>';
  		var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
      if(s!='')
        page = page+'_'+s;
  		if($('.nav-link.nav-'+page).length > 0){
             $('.nav-link.nav-'+page).addClass('active')
  			if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
            $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
  				$('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
  			}
        if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
          $('.nav-link.nav-'+page).parent().addClass('menu-open')
        }

  		}
     
  	})
  </script>