document.addEventListener("DOMContentLoaded", () => {
    const tablaInscripciones = document.querySelector("#tabla-inscripciones tbody");
    const filtroCurso = document.getElementById("filter-curso");

    // Cargar inscripciones al iniciar la página
    cargarInscripciones();

    // Cargar cursos en el filtro
    fetch("php/obtener_cursos.php")
        .then(response => response.json())
        .then(cursos => {
            cursos.forEach(curso => {
                const option = document.createElement("option");
                option.value = curso.id;
                option.textContent = curso.nombre;
                filtroCurso.appendChild(option);
            });
        })
        .catch(error => console.error("Error al cargar cursos:", error));

    // Filtrar por curso
    filtroCurso.addEventListener("change", cargarInscripciones);

    // Función para cargar inscripciones desde el servidor
    function cargarInscripciones() {
        const cursoId = filtroCurso.value;

        fetch(`php/obtener_inscripciones.php?curso_id=${cursoId}`)
            .then(response => response.json())
            .then(inscripciones => {
                tablaInscripciones.innerHTML = ""; // Limpiar la tabla
                inscripciones.sort((a, b) => a.estudiante.localeCompare(b.estudiante)); // Ordenar por estudiante

                inscripciones.forEach(inscripcion => {
                    const fila = document.createElement("tr");

                    fila.innerHTML = `
                        <td>${inscripcion.rude}</td>
                        <td>${inscripcion.estudiante}</td>
                        <td contenteditable="true" data-campo="curso">${inscripcion.curso}</td>
                        <td contenteditable="true" data-campo="paralelo">${inscripcion.paralelo}</td>
                        <td>${inscripcion.fecha_inscripcion}</td>
                        <td class="actions">
                            <button class="btn-save" data-id="${inscripcion.id}">Guardar</button>
                            <button class="btn-delete" data-id="${inscripcion.id}">Eliminar</button>
                        </td>
                    `;

                    tablaInscripciones.appendChild(fila);
                });

                agregarEventos();
            })
            .catch(error => console.error("Error al cargar inscripciones:", error));
    }

    // Agregar eventos a los botones de guardar y eliminar
    function agregarEventos() {
        const botonesGuardar = document.querySelectorAll(".btn-save");
        const botonesEliminar = document.querySelectorAll(".btn-delete");

        botonesGuardar.forEach(btn => {
            btn.addEventListener("click", guardarCambios);
        });

        botonesEliminar.forEach(btn => {
            btn.addEventListener("click", eliminarInscripcion);
        });
    }

    // Función para guardar cambios en una inscripción
    function guardarCambios(event) {
        const id = event.target.dataset.id;
        const fila = event.target.closest("tr");
        const datos = {};

        fila.querySelectorAll("[contenteditable]").forEach(celda => {
            datos[celda.dataset.campo] = celda.textContent.trim();
        });

        fetch("php/editar_inscripcion.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id, ...datos })
        })
            .then(response => response.text())
            .then(resultado => {
                alert(resultado);
                cargarInscripciones(); // Recargar la tabla
            })
            .catch(error => console.error("Error al guardar cambios:", error));
    }

    // Función para eliminar una inscripción
    function eliminarInscripcion(event) {
        const id = event.target.dataset.id;

        if (confirm("¿Estás seguro de que deseas eliminar esta inscripción?")) {
            fetch("php/eliminar_inscripcion.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id })
            })
                .then(response => response.text())
                .then(resultado => {
                    alert(resultado);
                    cargarInscripciones(); // Recargar la tabla
                })
                .catch(error => console.error("Error al eliminar inscripción:", error));
        }
    }
});
