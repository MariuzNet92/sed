<?php
$conexion = new mysqli("localhost", "root", "", "bd_sed");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];

$sql = "DELETE FROM inscripciones WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Inscripción eliminada correctamente.";
} else {
    echo "Error al eliminar la inscripción: " . $conexion->error;
}

$stmt->close();
$conexion->close();
?>
