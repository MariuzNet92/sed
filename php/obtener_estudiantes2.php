<?php
$conexion = new mysqli("localhost", "root", "", "bd_sed");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "SELECT carnet_id, nombres, apellido_pat, apellido_mat FROM estudiantes";
$resultado = $conexion->query($sql);

$estudiantes = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $estudiantes[] = $fila;
    }
}

echo json_encode($estudiantes);
$conexion->close();
?>
