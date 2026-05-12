<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: autenticar.php");
    exit();
}

include("conexion.php");

// CONSULTA CORREGIDA con Systema y tabla impresoras (plural)
$sql = "SELECT 
    c.id,
    c.Marca,
    c.Procesador,
    c.Ubicacion,
    c.Tipo,
    c.Systema,           -- COLUMNA CORREGIDA: Systema (no Sistema)
    c.Estado,
    c.fecha_del_ultimo_mantenimiento,
    c.ram,
    c.capacidad,
    r.Carnet,
    r.Nombre_Completo,
    r.Cargo,
    i.Marca AS impresora_marca,
    i.Modelo AS impresora_modelo,
    i.estado AS impresora_estado,
    i.computadora_id AS impresora_pc_id
FROM computadora c
LEFT JOIN responsable r ON c.id = r.computadora_id
LEFT JOIN impresoras i ON c.id = i.computadora_id  -- TABLA CORREGIDA: impresoras (plural)
ORDER BY c.id ASC";

$resultado = mysqli_query($conectar, $sql);

// Verificar error
if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conectar));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe Completo</title>
    <link href="css/bootstrap.css" rel="stylesheet"/>
    <style>
        .container { margin-top: 20px; }
        table { font-size: 13px; }
        th { background-color: #343a40; color: white; }
        .no-data { color: #6c757d; font-style: italic; }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-4">📋 Informe Completo de Computadoras</h2>
    
    <?php 
    $total = mysqli_num_rows($resultado);
    echo "<p><strong>Total de registros: {$total}</strong></p>";
    
    if ($total > 0): 
    ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Marca</th>
                    <th>Procesador</th>
                    <th>Ubicación</th>
                    <th>Tipo</th>
                    <th>Sistema</th>
                    <th>Estado</th>
                    <th>Mantenimiento</th>
                    <th>RAM</th>
                    <th>Capacidad</th>
                    <th>Carnet</th>
                    <th>Nombre</th>
                    <th>Cargo</th>
                    <th>Imp. Marca</th>
                    <th>Imp. Modelo</th>
                    <th>Imp. Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php while($fila = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <!-- Computadora -->
                    <td><strong><?php echo $fila['id']; ?></strong></td>
                    <td><?php echo $fila['Marca']; ?></td>
                    <td><?php echo $fila['Procesador']; ?></td>
                    <td><?php echo $fila['Ubicacion']; ?></td>
                    <td><?php echo $fila['Tipo']; ?></td>
                    <td><?php echo $fila['Systema']; ?></td>
                    <td>
                        <?php 
                        $estado = $fila['Estado'];
                        $clase = '';
                        if ($estado == 'Activo') $clase = 'badge bg-success';
                        elseif ($estado == 'Mantenimiento') $clase = 'badge bg-warning';
                        elseif ($estado == 'Dañado') $clase = 'badge bg-danger';
                        else $clase = 'badge bg-secondary';
                        ?>
                        <span class="<?php echo $clase; ?>"><?php echo $estado; ?></span>
                    </td>
                    <td><?php echo $fila['fecha_del_ultimo_mantenimiento'] ?: '-'; ?></td>
                    <td><?php echo $fila['ram']; ?></td>
                    <td><?php echo $fila['capacidad']; ?></td>
                    
                    <!-- Responsable -->
                    <td>
                        <?php if (!empty($fila['Carnet'])): ?>
                            <span class="badge bg-info"><?php echo $fila['Carnet']; ?></span>
                        <?php else: ?>
                            <span class="no-data">Sin asignar</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $fila['Nombre_Completo'] ?: '<span class="no-data">-</span>'; ?></td>
                    <td><?php echo $fila['Cargo'] ?: '<span class="no-data">-</span>'; ?></td>
                    
                    <!-- Impresora -->
                    <td>
                        <?php if (!empty($fila['impresora_marca'])): ?>
                            <?php echo $fila['impresora_marca']; ?>
                        <?php else: ?>
                            <span class="no-data">No tiene</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($fila['impresora_modelo'])): ?>
                            <?php echo $fila['impresora_modelo']; ?>
                        <?php else: ?>
                            <span class="no-data">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($fila['impresora_estado'])): ?>
                            <?php 
                            $estado_imp = $fila['impresora_estado'];
                            $clase_imp = '';
                            if ($estado_imp == 'Activa') $clase_imp = 'badge bg-success';
                            elseif ($estado_imp == 'Mantenimiento') $clase_imp = 'badge bg-warning';
                            elseif ($estado_imp == 'Dañada') $clase_imp = 'badge bg-danger';
                            else $clase_imp = 'badge bg-info';
                            ?>
                            <span class="<?php echo $clase_imp; ?>"><?php echo $estado_imp; ?></span>
                        <?php else: ?>
                            <span class="no-data">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alert alert-warning">
        No hay registros en el sistema. <a href="agregar.php">Agrega una computadora</a>
    </div>
    <?php endif; ?>
    
    <div class="mt-4">
        <a href="index.php" class="btn btn-secondary">← Volver al inicio</a>
        <a href="agregar.php" class="btn btn-primary">➕ Agregar nueva</a>
    </div>
</div>
</body>
</html>