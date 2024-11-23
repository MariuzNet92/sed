document.addEventListener("DOMContentLoaded", () => {
    const tablaInscripciones = document.querySelector("#tabla-inscripciones tbody");
    const filtroCurso = document.getElementById("filter-curso");

    // Cargar las inscripciones al cargar la página
    cargarInscripciones();

    // Cargar opciones de cursos para el filtro
    fetch("php/obtener_cursos.php")
        .then(response => response.json())
        .then(cursos => {
            cursos.forEach(curso => {
                const option = document.createElement("option");
                option.value = curso.nombre;
                option.textContent = curso.nombre;
                filtroCurso.appendChild(option);
            });
        })
        .catch(error => console.error("Error al cargar cursos:", error));

    filtroCurso.addEventListener("change", () => {
        cargarInscripciones(filtroCurso.value);
    });

    // Función para cargar inscripciones
    function cargarInscripciones(cursoFiltro = "") {
        fetch("php/obtener_inscripciones.php")
            .then(response => response.json())
            .then(inscripciones => {
                tablaInscripciones.innerHTML = ""; // Limpiar la tabla

                // Filtrar inscripciones por curso si se selecciona uno en el filtro
                const inscripcionesFiltradas = cursoFiltro
                    ? inscripciones.filter(inscripcion => inscripcion.curso === cursoFiltro)
                    : inscripciones;

                // Ordenar por nombres
                inscripcionesFiltradas.sort((a, b) => {
                    const nombreA = `${a.apellido_pat || ""} ${a.apellido_mat || ""} ${a.nombres}`.trim().toLowerCase();
                    const nombreB = `${b.apellido_pat || ""} ${b.apellido_mat || ""} ${b.nombres}`.trim().toLowerCase();
                    return nombreA.localeCompare(nombreB);
                });

                // Renderizar filas en la tabla
                inscripcionesFiltradas.forEach(inscripcion => {
                    const fila = document.createElement("tr");

                    fila.innerHTML = `
                        <td>${inscripcion.rude}</td>
                        <td>${inscripcion.apellido_pat || ""} ${inscripcion.apellido_mat || ""} ${inscripcion.nombres}</td>
                        <td contenteditable="true" data-campo="curso">${inscripcion.curso}</td>
                        <td contenteditable="true" data-campo="paralelo">${inscripcion.paralelo}</td>
                        <td>${inscripcion.fecha_inscripcion}</td>
                        <td>
                            <div class="actions">
                                <button class="btn-save" data-id="${inscripcion.id}">Guardar</button>
                                <button class="btn-delete" data-id="${inscripcion.id}">Eliminar</button>
                            </div>
                        </td>
                    `;
                    tablaInscripciones.appendChild(fila);
                });

                agregarEventos();
            })
            .catch(error => console.error("Error al cargar inscripciones:", error));
    }

    // Función para agregar eventos a los botones de guardar y eliminar
    function agregarEventos() {
        const botonesGuardar = document.querySelectorAll(".btn-save");
        const botonesEliminar = document.querySelectorAll(".btn-delete");

        botonesGuardar.forEach(btn => btn.addEventListener("click", guardarCambios));
        botonesEliminar.forEach(btn => btn.addEventListener("click", eliminarInscripcion));
    }

    // Función para guardar cambios
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
            body: JSON.stringify({ id, ...datos }),
        })
        .then(response => response.text())
        .then(resultado => {
            alert(resultado);
            cargarInscripciones(filtroCurso.value); // Recargar la tabla con el filtro aplicado
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
                body: JSON.stringify({ id }),
            })
            .then(response => response.text())
            .then(resultado => {
                alert(resultado);
                cargarInscripciones(filtroCurso.value); // Recargar la tabla con el filtro aplicado
            })
            .catch(error => console.error("Error al eliminar inscripción:", error));
        }
    }
});
