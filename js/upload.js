document.getElementById("formSubirArchivos").addEventListener("submit", async (event) => {
    event.preventDefault();

    const formData = new FormData(event.target);

    try {
        const response = await fetch("php/upload.php", {
            method: "POST",
            body: formData,
        });

        const result = await response.json();

        if (result.status === "success") {
            alert("Archivos subidos correctamente.");
        } else {
            alert("Error al subir los archivos: " + result.message);
        }
    } catch (error) {
        console.error("Error:", error);
        alert("Error al subir los archivos.");
    }
});
