<?php include('db_connect.php') ?>
<?php
$twhere ="";
if($_SESSION['login_type'] != 1)
  $twhere = "  ";
?>
<!-- Info boxes -->
<?php if($_SESSION['login_type'] == 2): ?>
  <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Inicio</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
        <hr class="border-primary">
      </div><!-- /.container-fluid -->
    </div>
    
    <!-- Primera fila: Widgets de resumen -->
    <div class="row">
      <div class="col-12 col-sm-6 col-md-4">
        <a href="./index.php?page=department">
          <div class="small-box bg-light shadow-sm border">
            <div class="inner">
              <h3><?php echo $conn->query("SELECT * FROM department_list ")->num_rows; ?></h3>
              <h5>Dependencias</h5>
            </div>
            <div class="icon">
              <i class="fa fa-th-list"></i>
            </div>
          </div>
        </a>
      </div>
      <div class="col-12 col-sm-6 col-md-4">
        <a href="./index.php?page=lista_producto">
          <div class="small-box bg-light shadow-sm border">
            <div class="inner">
              <h3><?php echo $conn->query("SELECT * FROM productos")->num_rows; ?></h3>
              <h5>Productos</h5>
            </div>
            <div class="icon">
              <i class="fa fa-boxes"></i>
            </div>
          </div>
        </a>
      </div>
      <div class="col-12 col-sm-6 col-md-4">
        <a href="./index.php?page=user_list">
          <div class="small-box bg-light shadow-sm border">
            <div class="inner">
              <h3><?php echo $conn->query("SELECT * FROM users")->num_rows; ?></h3>
              <h5>Usuarios</h5>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
          </div>
        </a>
      </div>
    </div>
    
    <!-- Segunda fila: Widgets de resumen -->
    <div class="row">
      <div class="col-12 col-sm-6 col-md-4">
        <a href="./index.php?page=employee_list">
          <div class="small-box bg-light shadow-sm border">
            <div class="inner">
              <h3><?php echo $conn->query("SELECT * FROM employee_list")->num_rows; ?></h3>
              <h5>Empleados</h5>
            </div>
            <div class="icon">
              <i class="fa fa-user-friends"></i>
            </div>
          </div>
        </a>
      </div>
      <div class="col-12 col-sm-6 col-md-4">
    <a href="./index.php?page=lista_producto">
        <div class="small-box bg-light shadow-sm border" id="stock-bajo-widget">
            <div class="inner">
                <h3 id="productos-stock-bajo-count">
                    <?php 
                    // Mostrar valor inicial mientras carga AJAX
                    $qry = $conn->query("SELECT COUNT(*) as total FROM productos WHERE cantidad <= stock_minimo");
                    $result = $qry->fetch_assoc();
                    echo $result['total'];
                    ?>
                </h3>
                <h5>Productos con Stock Bajo</h5>
            </div>
            <div class="icon">
                <i class="fa fa-exclamation-triangle text-danger"></i>
            </div>
        </div>
    </a>
</div>
      <div class="col-12 col-sm-6 col-md-4">
        <a href="./index.php?page=task_list">
          <div class="small-box bg-light shadow-sm border">
            <div class="inner">
              <h3><?php echo $conn->query("SELECT * FROM task_list")->num_rows; ?></h3>
              <h5>Procesos</h5>
            </div>
            <div class="icon">
              <i class="fa fa-tasks"></i>
            </div>
          </div>
        </a>
      </div>
    </div>
    
    <!-- Tercera fila: Widget de stock bajo (ancho completo) -->
    <div class="row">
      <div class="col-md-12">
        <?php include 'widget_stock_bajo.php'; ?>
      </div>
    </div>

<?php else: ?>
  <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Inicio</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
        <hr class="border-primary">
      </div><!-- /.container-fluid -->
    </div>
   <div class="col-12">
      <div class="card">
        <div class="card-body">
          Bienvenido <?php echo $_SESSION['login_name'] ?>!
        </div>
      </div>
    </div>

    <script>
$(document).ready(function(){
    // Actualizar cada 2 minutos (opcional)
    setInterval(actualizarStockBajo, 120000);
});

function actualizarStockBajo() {
    $.get('ajax.php?action=contar_productos_stock_bajo', function(resp) {
        $('#productos-stock-bajo-count').text(resp);
        
        // Resaltar si hay productos con stock bajo
        if(resp > 0) {
            $('#stock-bajo-widget').addClass('border-danger');
            $('#productos-stock-bajo-count').addClass('text-danger');
        } else {
            $('#stock-bajo-widget').removeClass('border-danger');
            $('#productos-stock-bajo-count').removeClass('text-danger');
        }
    }).fail(function() {
        console.error("Error al actualizar stock bajo");
    });
}
</script>

<style>
.blink {
    animation: blink-animation 1s steps(5, start) infinite;
}
@keyframes blink-animation {
    to { visibility: hidden; }
}
</style>
<?php endif; ?>