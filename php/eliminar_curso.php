<?php
$conexion = new mysqli("localhost", "root", "", "bd_sed");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener los datos enviados en la solicitud
$data = json_decode(file_get_contents("php://input"), true);
$id = isset($data['id']) ? $data['id'] : null;

// Validar que se haya recibido un ID
if (!$id) {
    echo "ID no proporcionado.";
    exit;
}

// Preparar y ejecutar la consulta para eliminar el curso
$sql = "DELETE FROM cursos WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Curso eliminado correctamente.";
} else {
    echo "Error al eliminar el curso: " . $conexion->error;
}

// Cerrar la conexión
$stmt->close();
$conexion->close();
?>
