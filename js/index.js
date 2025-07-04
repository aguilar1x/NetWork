// Archivo principal JavaScript para NetWork

// Inicializar componentes cuando DOM es cargado
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando componentes...');
    try {
        insertHeader();
        insertFooter();
        console.log('Componentes inicializados correctamente');
    } catch (error) {
        console.error('Error al inicializar componentes:', error);
    }
});