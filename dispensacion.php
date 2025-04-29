<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dispensación</h1>
            </div><!-- /.col -->

        </div><!-- /.row -->
        <hr class="border-primary">
    </div><!-- /.container-fluid -->
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <form action="" id="lista_producto">
                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                <div class="row">
                    <div class="col-md-6 border-right">
                        <div class="form-group">
                            <label for="" class="control-label">Código</label>
                            <input type="text" name="codigo" class="form-control form-control-sm" required value="<?php echo isset($codigo) ? $codigo : '' ?>">
                        </div>

                        <div class="form-group">
                            <label for="" class="control-label">Nombre Producto</label>
                            <input type="text" name="nombre_producto" class="form-control form-control-sm" required value="<?php echo isset($nombre_producto) ? $nombre_producto : '' ?>">
                        </div>


                        <div class="form-group">
                            <label for="" class="control-label">Cantidad Inicial (Kg)</label>
                            <input type="text" name="cant_inicial" class="form-control form-control-sm" required value="<?php echo isset($cant_inicial) ? $cant_inicial : '' ?>">
                        </div>
                    </div>

                    <div class="col-md-6 border-right">

                        <div class="form-group">
                            <label for="">Materia Prima</label>
                            <input list="producto" name="producto" placeholder="Seleccione Producto" class="form-control form-control-sm">
                            <datalist id="producto">
                                <?php
                                $producto = $conn->query("SELECT * FROM productos");
                                while ($row = $producto->fetch_assoc()) :
                                ?>
                                    <option value="<?php echo $row['nombre_producto'] ?>"></option>
                                    <?php endwhile; ?>

                            </datalist>
                        </div>


                        <div class="form-group">
                            <label for="" class="control-label">Unidad de Medida</label>
                            <input type="text" name="cantidad" class="form-control form-control-sm" required value="<?php echo isset($cantidad) ? $cantidad : '' ?>">
                        </div>



                        <div class="form-group">
                            <label for="" class="control-label">Cantidad Sugerida</label>
                            <input type="text" name="cantidad" class="form-control form-control-sm" required value="<?php echo isset($cantidad) ? $cantidad : '' ?>">
                        </div>
                    </div>
                </div>

                <hr>
                <div class="col-lg-12 text-right justify-content-center d-flex">
                    <button class="btn btn-primary mr-2">Agregar</button>
                    <button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=lista_producto'">Cancelar</button>
                </div>

            </form>
        </div>
    </div>
</div>