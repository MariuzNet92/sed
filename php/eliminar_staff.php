<?php
$conexion = new mysqli("localhost", "root", "", "bd_sed");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];

$sql = "DELETE FROM docent_adm WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Registro eliminado correctamente.";
} else {
    echo "Error al eliminar el registro: " . $conexion->error;
}

$stmt->close();
$conexion->close();
?>
