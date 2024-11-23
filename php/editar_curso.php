<?php
$conexion = new mysqli("localhost", "root", "", "bd_sed");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$nombre = $data['nombre'];
$nivel = $data['nivel'];
$grado = $data['grado'];
$paralelo = $data['paralelo']; // Nuevo campo
$descripcion = $data['descripcion'];

$sql = "UPDATE cursos SET nombre = ?, nivel = ?, grado = ?, paralelo = ?, descripcion = ? WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssi", $nombre, $nivel, $grado, $paralelo, $descripcion, $id);

if ($stmt->execute()) {
    echo "Curso actualizado correctamente.";
} else {
    echo "Error al actualizar el curso: " . $conexion->error;
}

$stmt->close();
$conexion->close();
?>
