<?php
$conexion = new mysqli("localhost", "root", "", "bd_sed");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Actualizamos la consulta para incluir todos los campos necesarios
$sql = "SELECT id, nombre, nivel, grado, paralelo, descripcion FROM cursos";
$resultado = $conexion->query($sql);

$cursos = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $cursos[] = $fila;
    }
}

echo json_encode($cursos);
$conexion->close();
?>
