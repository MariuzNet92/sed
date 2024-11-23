<?php
$conexion = new mysqli("localhost", "root", "", "bd_sed");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$curso_id = isset($_GET['curso_id']) && !empty($_GET['curso_id']) ? intval($_GET['curso_id']) : null;

// Consulta para obtener las inscripciones con datos de estudiantes y cursos
$sql = "SELECT 
            inscripciones.id, 
            inscripciones.rude, 
            CONCAT(estudiantes.apellido_pat, ' ', estudiantes.apellido_mat, ' ', estudiantes.nombres) AS estudiante,
            CONCAT(cursos.nombre, ' (', cursos.nivel, ')') AS curso,
            inscripciones.paralelo,
            inscripciones.fecha_inscripcion
        FROM inscripciones
        JOIN estudiantes ON inscripciones.rude = estudiantes.carnet_id
        JOIN cursos ON inscripciones.id_curso = cursos.id";

if ($curso_id) {
    $sql .= " WHERE cursos.id = $curso_id";
}

$sql .= " ORDER BY estudiante"; // Ordenar alfabéticamente por estudiante

$resultado = $conexion->query($sql);

$inscripciones = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $inscripciones[] = $fila;
    }
}

echo json_encode($inscripciones);

$conexion->close();
?>
