<?php
class Computadora {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Agregar computadora
    public function agregar($datos) {
        // Si el ID viene del formulario, inclúyelo en la consulta
        if (isset($datos['id']) && !empty($datos['id'])) {
            $sql = "INSERT INTO computadora (id, Marca, Procesador, Ubicacion, Tipo, Systema, Estado, fecha_del_ultimo_mantenimiento, ram, capacidad)
                    VALUES ({$datos['id']}, '{$datos['Marca']}', '{$datos['Procesador']}', '{$datos['Ubicacion']}', '{$datos['Tipo']}', '{$datos['Sistema']}', '{$datos['Estado']}', '{$datos['fecha_del_ultimo_mantenimiento']}', '{$datos['ram']}', '{$datos['capacidad']}')";
        } else {
            // Si no viene ID, que MySQL genere uno automático
            $sql = "INSERT INTO computadora (Marca, Procesador, Ubicacion, Tipo, Systema, Estado, fecha_del_ultimo_mantenimiento, ram, capacidad)
                    VALUES ('{$datos['Marca']}', '{$datos['Procesador']}', '{$datos['Ubicacion']}', '{$datos['Tipo']}', '{$datos['Sistema']}', '{$datos['Estado']}', '{$datos['fecha_del_ultimo_mantenimiento']}', '{$datos['ram']}', '{$datos['capacidad']}')";
        }
        
        if (mysqli_query($this->conexion, $sql)) {
            return ['success' => true, 'id' => mysqli_insert_id($this->conexion)];
        } else {
            return ['success' => false, 'message' => mysqli_error($this->conexion)];
        }
    }

    // Asignar responsable
    public function asignarResponsable($pc_id, $carnet, $nombre, $cargo) {
        $sql = "INSERT INTO responsable (Carnet, Nombre_Completo, Cargo, computadora_id)
                VALUES ('$carnet', '$nombre', '$cargo', '$pc_id')";
        mysqli_query($this->conexion, $sql);
    }

    // Asignar impresora
    public function asignarImpresora($pc_id, $marca, $modelo, $estado) {
        $sql = "INSERT INTO impresoras (Marca, Modelo, estado, computadora_id)
                VALUES ('$marca', '$modelo', '$estado', '$pc_id')";
        mysqli_query($this->conexion, $sql);
    }
}
?>
