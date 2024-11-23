<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $db_conn = new mysqli('localhost', 'root', '', 'bd_sed');

    if ($db_conn->connect_error) {
        echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos']);
        exit;
    }

    // Consultar datos del estudiante
    $stmt = $db_conn->prepare("SELECT * FROM estudiantes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();

        // Consultar datos adicionales (foto y documentos)
        $stmt_files = $db_conn->prepare("SELECT * FROM datos_student WHERE id = ?");
        $stmt_files->bind_param("i", $id);
        $stmt_files->execute();
        $result_files = $stmt_files->get_result();
        $files = [];

        if ($result_files->num_rows > 0) {
            $files_data = $result_files->fetch_assoc();
            if ($files_data['foto']) $files[] = ['name' => 'Foto', 'path' => "C:/xampp/htdocs/sed/bd/estudiantes/img/" . $files_data['foto']];
            if ($files_data['carnet_id']) $files[] = ['name' => 'Carnet de Identidad', 'path' => "C:/xampp/htdocs/sed/bd/estudiantes/documents/" . $files_data['carnet_id']];
            if ($files_data['certif_nac']) $files[] = ['name' => 'Certificado de Nacimiento', 'path' => "C:/xampp/htdocs/sed/bd/estudiantes/documents/" . $files_data['certif_nac']];
            if ($files_data['doc_1']) $files[] = ['name' => 'Documento 1', 'path' => "C:/xampp/htdocs/sed/bd/estudiantes/documents/" . $files_data['doc_1']];
            if ($files_data['doc_2']) $files[] = ['name' => 'Documento 2', 'path' => "C:/xampp/htdocs/sed/bd/estudiantes/documents/" . $files_data['doc_2']];
        }

        echo json_encode([
            'success' => true,
            'id' => $student['id'],
            'name' => $student['nombres'] . ' ' . $student['apellido_pat'] . ' ' . $student['apellido_mat'],
            'carnet_id' => $student['carnet_id'],
            'birth_date' => $student['fecha_nac'],
            'email' => $student['correo'],
            'phone' => $student['telefono'],
            'address' => $student['direccion'],
            'gender' => $student['genero'],
            'photo' => $files_data['foto'] ? "C:/xampp/htdocs/sed/bd/estudiantes/img/" . $files_data['foto'] : '',
            'files' => $files
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Estudiante no encontrado']);
    }

    $stmt->close();
    $db_conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'ID no especificado']);
}
?>
