document.addEventListener("DOMContentLoaded", () => {
    const tablaStaff = document.querySelector("#tabla-staff tbody");

    // Cargar datos del personal
    cargarStaff();

    function cargarStaff() {
        fetch("php/obtener_staff.php")
            .then(response => response.json())
            .then(staff => {
                tablaStaff.innerHTML = ""; // Limpiar la tabla
                staff.forEach(personal => {
                    const fila = document.createElement("tr");

                    fila.innerHTML = `
                        <td>${personal.id}</td>
                        <td contenteditable="true" data-campo="nombres">${personal.nombres} ${personal.apellido_pat || ""} ${personal.apellido_mat || ""}</td>
                        <td contenteditable="true" data-campo="carnet_id">${personal.carnet_id}</td>
                        <td contenteditable="true" data-campo="correo">${personal.correo}</td>
                        <td contenteditable="true" data-campo="telefono">${personal.telefono}</td>
                        <td contenteditable="true" data-campo="cargo">${personal.cargo}</td>
                        <td class="actions">
                            <button class="btn-save" data-id="${personal.id}">Guardar</button>
                            <button class="btn-delete" data-id="${personal.id}">Eliminar</button>
                        </td>
                    `;

                    tablaStaff.appendChild(fila);
                });

                agregarEventos();
            })
            .catch(error => console.error("Error al cargar el personal:", error));
    }

    function agregarEventos() {
        const botonesGuardar = document.querySelectorAll(".btn-save");
        const botonesEliminar = document.querySelectorAll(".btn-delete");

        botonesGuardar.forEach(btn => {
            btn.addEventListener("click", guardarCambios);
        });

        botonesEliminar.forEach(btn => {
            btn.addEventListener("click", eliminarPersonal);
        });
    }

    function guardarCambios(event) {
        const id = event.target.dataset.id;
        const fila = event.target.closest("tr");
        const datos = {};

        fila.querySelectorAll("[contenteditable]").forEach(celda => {
            const campo = celda.dataset.campo;
            datos[campo] = celda.textContent.trim();
        });

        fetch("php/editar_staff.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id, ...datos })
        })
            .then(response => response.text())
            .then(resultado => {
                alert(resultado);
                cargarStaff(); // Recargar la tabla
            })
            .catch(error => console.error("Error al guardar cambios:", error));
    }

    function eliminarPersonal(event) {
        const id = event.target.dataset.id;

        if (confirm("¿Estás seguro de que deseas eliminar este registro?")) {
            fetch("php/eliminar_staff.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id })
            })
                .then(response => response.text())
                .then(resultado => {
                    alert(resultado);
                    cargarStaff(); // Recargar la tabla
                })
                .catch(error => console.error("Error al eliminar personal:", error));
        }
    }
});
