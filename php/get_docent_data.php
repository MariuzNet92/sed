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

// Validar que el ID del docente se haya proporcionado y sea válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Consulta principal para obtener datos del personal y sus archivos
    $sql = "SELECT 
                e.id, 
                e.nombres, 
                e.apellido_pat, 
                e.apellido_mat, 
                e.carnet_id, 
                e.fecha_nac, 
                e.correo, 
                e.telefono, 
                e.genero, 
                ds.foto, 
                ds.carnet_id AS carnet_pdf, 
                ds.certif_nac, 
                ds.doc_1, 
                ds.doc_2, 
                ds.doc_3, 
                ds.doc_4, 
                ds.doc_5, 
                ds.doc_6, 
                ds.doc_7, 
                ds.doc_8, 
                ds.doc_9, 
                ds.doc_10
            FROM docent_adm e
            LEFT JOIN datos_docent ds ON e.id = ds.id
            WHERE e.id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["success" => false, "error" => "Error al preparar la consulta: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontraron resultados
    if ($result->num_rows > 0) {
        $docent = $result->fetch_assoc();

        // Generar rutas completas para los archivos
        $base_url = "http://localhost/sed/bd/docentes";
        $files = [];

        // Agregar archivos si existen en la base de datos
        foreach ($docent as $key => $value) {
            if (strpos($key, 'doc_') === 0 && $value) {
                $files[] = ["name" => ucfirst(str_replace('_', ' ', $key)), "path" => "$base_url/documents/$value"];
            }
        }
        if ($docent['carnet_pdf']) {
            $files[] = ["name" => "Carnet de Identidad", "path" => "$base_url/documents/" . $docent['carnet_pdf']];
        }
        if ($docent['certif_nac']) {
            $files[] = ["name" => "Certificado de Nacimiento", "path" => "$base_url/documents/" . $docent['certif_nac']];
        }
        if ($docent['foto']) {
            $photo_path = "$base_url/img/" . $docent['foto'];
        } else {
            $photo_path = "$base_url/img/default.jpg"; // Foto por defecto si no hay imagen
        }

        // Devolver datos en formato JSON
        echo json_encode([
            "success" => true,
            "id" => $docent['id'],
            "name" => $docent['nombres'] . " " . $docent['apellido_pat'] . " " . $docent['apellido_mat'],
            "carnet_id" => $docent['carnet_id'],
            "birth_date" => $docent['fecha_nac'],
            "email" => $docent['correo'],
            "phone" => $docent['telefono'],
            "gender" => $docent['genero'],
            "photo" => $photo_path,
            "files" => $files
        ]);
    } else {
        echo json_encode(["success" => false, "error" => "Personal no encontrado."]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "ID no válido o no proporcionado."]);
}

$conn->close();
?>
