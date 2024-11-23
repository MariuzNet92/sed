<?php
$conexion = new mysqli("localhost", "root", "", "bd_sed");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$curso = $data['curso'];
$paralelo = $data['paralelo'];

$sql = "UPDATE inscripciones 
        JOIN cursos ON inscripciones.id_curso = cursos.id
        SET inscripciones.paralelo = ?
        WHERE inscripciones.id = ? AND cursos.nombre = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sis", $paralelo, $id, $curso);

if ($stmt->execute()) {
    echo "Inscripción actualizada correctamente.";
} else {
    echo "Error al actualizar la inscripción: " . $conexion->error;
}

$stmt->close();
$conexion->close();
?>
