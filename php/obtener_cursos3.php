<?php
require_once 'conexion.php';

$query = "SELECT id, nombre, nivel, grado, paralelo, descripcion FROM cursos";
$resultado = $conexion->query($query);

if ($resultado) {
    $cursos = [];
    while ($fila = $resultado->fetch_assoc()) {
        $cursos[] = $fila;
    }
    echo json_encode(["success" => true, "cursos" => $cursos]);
} else {
    echo json_encode(["success" => false, "message" => "Error al obtener cursos."]);
}

$conexion->close();
?>
