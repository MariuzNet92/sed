document.addEventListener("DOMContentLoaded", () => {
  const tablaCursos = document.querySelector("#tabla-cursos tbody");

  // Cargar los cursos al iniciar la página
  cargarCursos();

  // Función para cargar cursos desde el servidor
  function cargarCursos() {
      fetch("php/obtener_cursos.php")
          .then((response) => response.json())
          .then((cursos) => {
              tablaCursos.innerHTML = ""; // Limpiar la tabla
              cursos.forEach((curso) => {
                  const fila = document.createElement("tr");

                  fila.innerHTML = `
                      <td>${curso.id}</td>
                      <td contenteditable="true" data-campo="nombre">${curso.nombre}</td>
                      <td contenteditable="true" data-campo="nivel">${curso.nivel}</td>
                      <td contenteditable="true" data-campo="grado">${curso.grado}</td>
                      <td contenteditable="true" data-campo="paralelo">${curso.paralelo}</td>
                      <td contenteditable="true" data-campo="descripcion">${curso.descripcion}</td>
                      <td>
                          <button class="btn-guardar" data-id="${curso.id}">Guardar</button>
                          <button class="btn-eliminar" data-id="${curso.id}">Eliminar</button>
                      </td>
                  `;

                  tablaCursos.appendChild(fila);
              });

              agregarEventos();
          })
          .catch((error) => console.error("Error al cargar cursos:", error));
  }

  // Agregar eventos a los botones de guardar y eliminar
  function agregarEventos() {
      const botonesGuardar = document.querySelectorAll(".btn-guardar");
      const botonesEliminar = document.querySelectorAll(".btn-eliminar");

      botonesGuardar.forEach((btn) => {
          btn.addEventListener("click", guardarCambios);
      });

      botonesEliminar.forEach((btn) => {
          btn.addEventListener("click", eliminarCurso);
      });
  }

  // Función para guardar cambios en un curso
  function guardarCambios(event) {
      const id = event.target.dataset.id;
      const fila = event.target.closest("tr");
      const datos = {};

      fila.querySelectorAll("[contenteditable]").forEach((celda) => {
          datos[celda.dataset.campo] = celda.textContent;
      });

      fetch("php/editar_curso.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id, ...datos }),
      })
          .then((response) => response.text())
          .then((resultado) => {
              alert(resultado);
              cargarCursos(); // Recargar la tabla
          })
          .catch((error) => console.error("Error al guardar cambios:", error));
  }

  // Función para eliminar un curso
  function eliminarCurso(event) {
      const id = event.target.dataset.id;

      if (confirm("¿Estás seguro de que deseas eliminar este curso?")) {
          fetch("php/eliminar_curso.php", {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({ id }),
          })
              .then((response) => response.text())
              .then((resultado) => {
                  alert(resultado);
                  cargarCursos(); // Recargar la tabla
              })
              .catch((error) => console.error("Error al eliminar curso:", error));
      }
  }
});
