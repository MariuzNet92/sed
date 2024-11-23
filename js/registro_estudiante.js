document.getElementById("form-registro-estudiante").addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(event.target);

    fetch("php/registro_estudiante.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((result) => {
            if (result.success) {
                alert("Estudiante registrado exitosamente.");
                event.target.reset(); // Limpiar el formulario
            } else {
                alert(result.message); // Mostrar mensaje de error
            }
        })
        .catch((error) => {
            console.error("Error al registrar estudiante:", error);
            alert("Ocurrió un error al registrar el estudiante.");
        });
});
