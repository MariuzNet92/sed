document.getElementById("buscar_docente").addEventListener("click", async () => {
    const docenteId = document.getElementById("docente_id").value;

    if (!docenteId) {
        alert("Por favor, ingresa un ID de docente.");
        return;
    }

    try {
        const response = await fetch("php/obtener_staff.php");
        const data = await response.json();

        const docente = data.find(d => d.id === parseInt(docenteId));

        const docenteInfoDiv = document.getElementById("docente_info");
        docenteInfoDiv.innerHTML = "";

        if (docente) {
            docenteInfoDiv.innerHTML = `
                <strong>Nombre:</strong> ${docente.nombres} ${docente.apellido_pat} ${docente.apellido_mat}<br>
                <strong>Cargo:</strong> ${docente.cargo}<br>
                <strong>Correo:</strong> ${docente.correo}<br>
                <strong>Teléfono:</strong> ${docente.telefono}
            `;
        } else {
            docenteInfoDiv.innerHTML = "<p style='color: red;'>Docente no encontrado.</p>";
        }
    } catch (error) {
        console.error("Error:", error);
        alert("Error al buscar el docente.");
    }
});
