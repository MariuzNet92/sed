<?php
$conexion = new mysqli("localhost", "root", "", "bd_sed");

if ($conexion->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión: " . $conexion->connect_error]));
}

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$nivel = $_POST['nivel'];
$grado = $_POST['grado'];
$descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : null;
$paralelo = $_POST['paralelo'];

// Validar si el curso ya existe
$sql_verificar = "SELECT COUNT(*) AS total FROM cursos WHERE nombre = ? AND grado = ? AND paralelo = ?";
$stmt_verificar = $conexion->prepare($sql_verificar);
$stmt_verificar->bind_param("sss", $nombre, $grado, $paralelo);
$stmt_verificar->execute();
$resultado_verificar = $stmt_verificar->get_result();
$fila = $resultado_verificar->fetch_assoc();

if ($fila['total'] > 0) {
    echo json_encode(["success" => false, "message" => "Ya existe un curso con el mismo nombre, grado y paralelo."]);
} else {
    // Insertar datos en la tabla `cursos`
    $sql_insertar = "INSERT INTO cursos (nombre, nivel, grado, descripcion, paralelo) VALUES (?, ?, ?, ?, ?)";
    $stmt_insertar = $conexion->prepare($sql_insertar);
    $stmt_insertar->bind_param("sssss", $nombre, $nivel, $grado, $descripcion, $paralelo);

    if ($stmt_insertar->execute()) {
        echo json_encode(["success" => true, "message" => "Curso creado exitosamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al crear el curso: " . $conexion->error]);
    }
    $stmt_insertar->close();
}

$stmt_verificar->close();
$conexion->close();
?>
