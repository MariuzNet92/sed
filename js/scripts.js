// Verificar enlaces no configurados o inválidos
document.querySelectorAll('nav a, .dropdown-content a').forEach(link => {
    link.addEventListener('click', (event) => {
        // Prevenir navegación si el enlace no está configurado
        if (!link.href || link.href === "#") {
            event.preventDefault();
            console.warn("Enlace no configurado o inválido.");
            alert("Esta funcionalidad está en desarrollo.");
        }
    });
});

// Mostrar un mensaje en la consola al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    console.log('Sistema Educativo Digital cargado correctamente.');
});
