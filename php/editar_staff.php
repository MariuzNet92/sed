<?php
$conexion = new mysqli("localhost", "root", "", "bd_sed");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$nombres = $data['nombres'];
$carnet_id = $data['carnet_id'];
$correo = $data['correo'];
$telefono = $data['telefono'];
$cargo = $data['cargo'];

$sql = "UPDATE docent_adm SET nombres = ?, carnet_id = ?, correo = ?, telefono = ?, cargo = ? WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssi", $nombres, $carnet_id, $correo, $telefono, $cargo, $id);

if ($stmt->execute()) {
    echo "Datos actualizados correctamente.";
} else {
    echo "Error al actualizar los datos: " . $conexion->error;
}

$stmt->close();
$conexion->close();
?>
