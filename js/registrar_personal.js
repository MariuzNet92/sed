document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("form-registrar-personal");

    form.addEventListener("submit", event => {
        event.preventDefault();

        const formData = new FormData(form);

        fetch("php/registrar_personal.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || "Personal registrado con éxito.");
                    form.reset();
                } else {
                    alert(data.message || "Error al registrar al personal.");
                }
            })
            .catch(error => console.error("Error:", error));
    });
});
