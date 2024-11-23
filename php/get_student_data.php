<?php
header('Content-Type: application/json');

// Configuración de conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$dbname = "bd_sed";

$conn = new mysqli($host, $user, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Error de conexión a la base de datos."]);
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Consulta principal para obtener datos del estudiante
    $sql = "SELECT e.id, e.nombres, e.apellido_pat, e.apellido_mat, e.carnet_id, e.fecha_nac, e.correo, e.telefono, e.direccion, e.genero, ds.foto, ds.carnet_id AS carnet_pdf, ds.certif_nac, ds.doc_1, ds.doc_2
            FROM estudiantes e
            LEFT JOIN datos_student ds ON e.id = ds.id
            WHERE e.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();

        // Consulta adicional para obtener el curso del estudiante
        $course_sql = "SELECT c.nombre AS curso
                       FROM inscripciones i
                       INNER JOIN cursos c ON i.id_curso = c.id
                       WHERE i.rude = ?";
        $course_stmt = $conn->prepare($course_sql);
        $course_stmt->bind_param("s", $student['carnet_id']);
        $course_stmt->execute();
        $course_result = $course_stmt->get_result();

        $course = "Estudiante aún no matriculado";
        if ($course_result->num_rows > 0) {
            $course_data = $course_result->fetch_assoc();
            $course = $course_data['curso'];
        }

        $course_stmt->close();

        // Generar rutas completas para los archivos
        $base_url = "http://localhost/sed/bd/estudiantes";
        $files = [];
        if ($student['carnet_pdf']) {
            $files[] = ["name" => "Carnet de Identidad", "path" => $base_url . "/documents/" . $student['carnet_pdf']];
        }
        if ($student['certif_nac']) {
            $files[] = ["name" => "Certificado de Nacimiento", "path" => $base_url . "/documents/" . $student['certif_nac']];
        }
        if ($student['doc_1']) {
            $files[] = ["name" => "Documento 1", "path" => $base_url . "/documents/" . $student['doc_1']];
        }
        if ($student['doc_2']) {
            $files[] = ["name" => "Documento 2", "path" => $base_url . "/documents/" . $student['doc_2']];
        }

        echo json_encode([
            "success" => true,
            "id" => $student['id'],
            "name" => $student['nombres'] . " " . $student['apellido_pat'] . " " . $student['apellido_mat'],
            "carnet_id" => $student['carnet_id'],
            "birth_date" => $student['fecha_nac'],
            "email" => $student['correo'],
            "phone" => $student['telefono'],
            "address" => $student['direccion'],
            "gender" => $student['genero'],
            "photo" => $base_url . "/img/" . $student['foto'],
            "course" => $course,
            "files" => $files
        ]);
    } else {
        echo json_encode(["success" => false, "error" => "Estudiante no encontrado."]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "ID no proporcionado."]);
}

$conn->close();
?>
