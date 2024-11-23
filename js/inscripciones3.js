document.addEventListener("DOMContentLoaded", () => {
    const carnetInput = document.getElementById("carnet");
    const nombreEstudiante = document.getElementById("nombre-estudiante");
    const cursoSelect = document.getElementById("curso");
    const paraleloSelect = document.getElementById("paralelo");
    const form = document.getElementById("form-inscribir-estudiante");

    // Cargar los cursos disponibles
    fetch("php/obtener_cursos3.php")
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.cursos.forEach(curso => {
                    const option = document.createElement("option");
                    option.value = curso.id;
                    option.textContent = curso.nombre;
                    cursoSelect.appendChild(option);
                });
            } else {
                alert(data.message || "Error al cargar cursos.");
            }
        })
        .catch(error => console.error("Error al cargar cursos:", error));
    // Obtener la lista de paralelos para el curso seleccionado
    cursoSelect.addEventListener("change", () => {
        const cursoId = cursoSelect.value;

        if (cursoId) {
            fetch(`php/obtener_paralelos.php?curso_id=${cursoId}`)
                .then(response => response.json())
                .then(data => {
                    paraleloSelect.innerHTML = '<option value="">Seleccione un paralelo</option>';
                    if (data.success) {
                        data.paralelos.forEach(paralelo => {
                            const option = document.createElement("option");
                            option.value = paralelo;
                            option.textContent = paralelo;
                            paraleloSelect.appendChild(option);
                        });
                    } else {
                        alert(data.message || "No se encontraron paralelos para este curso.");
                    }
                })
                .catch(error => console.error("Error al cargar paralelos:", error));
        } else {
            paraleloSelect.innerHTML = '<option value="">Seleccione un paralelo</option>';
        }
    });
    
    // Obtener el nombre del estudiante al escribir el CI
    carnetInput.addEventListener("input", () => {
        const ci = carnetInput.value.trim();

        if (ci.length > 0) {
            fetch(`php/obtener_estudiante3.php?ci=${ci}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        nombreEstudiante.textContent = `Nombre: ${data.nombre}`;
                    } else {
                        nombreEstudiante.textContent = "Estudiante no encontrado.";
                    }
                })
                .catch(error => {
                    nombreEstudiante.textContent = "Error al buscar estudiante.";
                    console.error("Error:", error);
                });
        } else {
            nombreEstudiante.textContent = "";
        }
    });

    // Manejar la inscripción
    form.addEventListener("submit", event => {
        event.preventDefault();

        const carnet = carnetInput.value.trim();
        const curso = cursoSelect.value;
        const paralelo = paraleloSelect.value;

        if (!carnet || !curso || !paralelo) {
            alert("Todos los campos son obligatorios.");
            return;
        }

        fetch("php/inscribir_estudiante3.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ carnet, curso, paralelo })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || "Inscripción realizada con éxito.");
                    form.reset();
                    nombreEstudiante.textContent = "";
                } else {
                    alert(data.message || "Error al inscribir al estudiante.");
                }
            })
            .catch(error => console.error("Error al inscribir estudiante:", error));
    });
});
