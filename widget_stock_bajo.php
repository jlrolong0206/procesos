<div class="card card-outline card-danger">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-exclamation-triangle mr-2"></i>Productos con Stock Bajo</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center">Producto</th>
                        <th class="text-center">Código</th>
                        <th class="text-center">Stock Actual</th>
                        <th class="text-center">Stock Mínimo</th>
                        <th class="text-center">Diferencia</th>
                    </tr>
                </thead>
                <tbody id="stock-bajo-list">
                    <tr>
                        <td colspan="5" class="text-center py-3">
                            <div class="spinner-border text-danger" role="status">
                                <span class="sr-only">Cargando...</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer text-right">
        <a href="index.php?page=lista_producto" class="btn btn-sm btn-danger">
            <i class="fas fa-boxes mr-1"></i> Gestionar Productos
        </a>
    </div>
</div>

<script>
$(document).ready(function(){
    cargarProductosStockBajo();
    setInterval(cargarProductosStockBajo, 300000);
});

function cargarProductosStockBajo() {
    $.ajax({
        url: 'ajax.php?action=get_productos_stock_bajo',
        method: 'GET',
        dataType: 'json',
        success: function(resp){
            var html = '';
            if(resp.length > 0){
                resp.forEach(function(producto){
                    var diferencia = producto.cantidad - producto.stock_minimo;
                    var badge = diferencia < 0 ? '<span class="badge badge-danger">CRÍTICO</span>' : '<span class="badge badge-warning">ALERTA</span>';
                    
                    html += `
                    <tr>
                        <td>${producto.nombre_producto}</td>
                        <td class="text-center">${producto.codigo}</td>
                        <td class="text-center font-weight-bold ${producto.cantidad <= producto.stock_minimo ? 'text-danger' : 'text-warning'}">
                            ${producto.cantidad}
                        </td>
                        <td class="text-center">${producto.stock_minimo}</td>
                        <td class="text-center font-weight-bold ${diferencia < 0 ? 'text-danger' : 'text-warning'}">
                            ${diferencia} ${badge}
                        </td>
                    </tr>
                    `;
                });
            } else {
                html = `
                <tr>
                    <td colspan="5" class="text-center py-3 text-success">
                        <i class="fas fa-check-circle mr-2"></i> No hay productos con stock bajo
                    </td>
                </tr>
                `;
            }
            $('#stock-bajo-list').html(html);
        },
        error: function(){
            $('#stock-bajo-list').html(`
            <tr>
                <td colspan="5" class="text-center py-3 text-danger">
                    <i class="fas fa-exclamation-circle mr-2"></i> Error al cargar los datos
                </td>
            </tr>
            `);
        }
    });
}
</script>