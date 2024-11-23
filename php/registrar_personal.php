<?php
require_once 'conexion.php';

$nombres = $_POST['nombres'] ?? null;
$apellido_pat = $_POST['apellido_pat'] ?? null;
$apellido_mat = $_POST['apellido_mat'] ?? null;
$carnet_id = $_POST['carnet_id'] ?? null;
$fecha_nac = $_POST['fecha_nac'] ?? null;
$correo = $_POST['correo'] ?? null;
$telefono = $_POST['telefono'] ?? null;
$especialidad = $_POST['especialidad'] ?? null;
$cargo = $_POST['cargo'] ?? null;
$genero = $_POST['genero'] ?? null;

if (!$nombres || !$apellido_pat || !$apellido_mat || !$carnet_id || !$fecha_nac || !$correo || !$cargo || !$genero) {
    echo json_encode(["success" => false, "message" => "Todos los campos obligatorios deben ser completados."]);
    exit;
}

$query = "INSERT INTO docent_adm (nombres, apellido_pat, apellido_mat, carnet_id, fecha_nac, correo, telefono, especialidad, cargo, genero)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($query);
$stmt->bind_param(
    "ssssssssss",
    $nombres,
    $apellido_pat,
    $apellido_mat,
    $carnet_id,
    $fecha_nac,
    $correo,
    $telefono,
    $especialidad,
    $cargo,
    $genero
);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Personal registrado con éxito."]);
} else {
    echo json_encode(["success" => false, "message" => "Error al registrar el personal: " . $conexion->error]);
}

$stmt->close();
$conexion->close();
?>
