document.addEventListener("DOMContentLoaded", () => {
    const tablaEstudiantes = document.querySelector("#tabla-estudiantes tbody");
    const campoBusqueda = document.getElementById("busqueda");
  
    let estudiantes = []; // Almacenará todos los estudiantes para filtrarlos localmente.
  
    // Función para cargar los estudiantes
    function cargarEstudiantes() {
      fetch("php/obtener_estudiantes.php")
        .then((response) => response.json())
        .then((data) => {
          estudiantes = data; // Guardar los estudiantes localmente
          mostrarEstudiantes(data); // Mostrar los datos en la tabla
        })
        .catch((error) => console.error("Error al cargar los estudiantes:", error));
    }
  
    // Función para mostrar estudiantes en la tabla
    function mostrarEstudiantes(data) {
      tablaEstudiantes.innerHTML = ""; // Limpiar la tabla
      data.forEach((estudiante) => {
        const fila = document.createElement("tr");
        fila.innerHTML = `
          <td>${estudiante.id}</td>
          <td><input type="text" value="${estudiante.nombres}" data-id="${estudiante.id}" data-field="nombres" /></td>
          <td><input type="text" value="${estudiante.apellido_pat}" data-id="${estudiante.id}" data-field="apellido_pat" /></td>
          <td><input type="text" value="${estudiante.apellido_mat}" data-id="${estudiante.id}" data-field="apellido_mat" /></td>
          <td><input type="text" value="${estudiante.carnet_id}" data-id="${estudiante.id}" data-field="carnet_id" /></td>
          <td><input type="date" value="${estudiante.fecha_nac}" data-id="${estudiante.id}" data-field="fecha_nac" /></td>
          <td><input type="email" value="${estudiante.correo}" data-id="${estudiante.id}" data-field="correo" /></td>
          <td><input type="text" value="${estudiante.telefono}" data-id="${estudiante.id}" data-field="telefono" /></td>
          <td><input type="text" value="${estudiante.direccion}" data-id="${estudiante.id}" data-field="direccion" /></td>
          <td>
            <select data-id="${estudiante.id}" data-field="genero">
              <option value="Femenino" ${estudiante.genero === "Femenino" ? "selected" : ""}>Femenino</option>
              <option value="Masculino" ${estudiante.genero === "Masculino" ? "selected" : ""}>Masculino</option>
            </select>
          </td>
          <td>
            <button class="btn-guardar" data-id="${estudiante.id}">Guardar</button>
            <button class="btn-eliminar" data-id="${estudiante.id}">Eliminar</button>
          </td>
        `;
        tablaEstudiantes.appendChild(fila);
      });
  
      // Agregar eventos a los botones
      agregarEventos();
    }
  
    // Función para filtrar estudiantes
    campoBusqueda.addEventListener("input", () => {
      const texto = campoBusqueda.value.toLowerCase();
      const estudiantesFiltrados = estudiantes.filter((estudiante) => {
        return (
          estudiante.nombres.toLowerCase().includes(texto) ||
          estudiante.apellido_pat.toLowerCase().includes(texto) ||
          estudiante.apellido_mat.toLowerCase().includes(texto) ||
          estudiante.carnet_id.toLowerCase().includes(texto)
        );
      });
      mostrarEstudiantes(estudiantesFiltrados); // Mostrar resultados filtrados
    });
  
    // Función para agregar eventos a botones de guardar y eliminar
    function agregarEventos() {
      document.querySelectorAll(".btn-eliminar").forEach((boton) => {
        boton.addEventListener("click", () => {
          const id = boton.getAttribute("data-id");
          eliminarEstudiante(id);
        });
      });
  
      document.querySelectorAll(".btn-guardar").forEach((boton) => {
        boton.addEventListener("click", () => {
          const id = boton.getAttribute("data-id");
          guardarCambios(id);
        });
      });
    }
  
    // Función para eliminar un estudiante (ya implementada)
    function eliminarEstudiante(id) {
      if (confirm("¿Estás seguro de que deseas eliminar este estudiante?")) {
        fetch("php/eliminar_estudiante.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `id=${id}`,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.status === "success") {
              alert(data.message);
              cargarEstudiantes(); // Recargar la tabla
            } else {
              alert(data.message);
            }
          })
          .catch((error) => console.error("Error al eliminar el estudiante:", error));
      }
    }
  
    // Función para guardar cambios (ya implementada)
    function guardarCambios(id) {
      const inputs = document.querySelectorAll(`[data-id="${id}"]`);
      const datos = {};
      inputs.forEach((input) => {
        const field = input.getAttribute("data-field");
        datos[field] = input.value;
      });
  
      datos.id = id;
  
      fetch("php/editar_estudiante.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams(datos).toString(),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            alert(data.message);
            cargarEstudiantes();
          } else {
            alert(data.message);
          }
        })
        .catch((error) => console.error("Error al guardar los cambios:", error));
    }
  
    // Cargar estudiantes al inicio
    cargarEstudiantes();
  });
  