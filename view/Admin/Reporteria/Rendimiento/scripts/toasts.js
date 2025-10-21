// scripts/toasts.js
function mostrarToast(mensaje, tipo = 'info') {
    const clases = {
        success: 'bg-success text-white',
        warning: 'bg-warning text-dark',
        danger:  'bg-danger text-white',
        info:    'bg-info text-dark'
    };

    const contenedor = document.getElementById('toast-container');
    if (!contenedor) return;

    const toast = document.createElement('div');
    toast.className = `toast align-items-center border-0 mb-2 ${clases[tipo] || clases.info}`;
    toast.role = 'alert';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${mensaje}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Cerrar"></button>
        </div>`;

    contenedor.appendChild(toast);

    // 🔹 Activa la animación de entrada
    requestAnimationFrame(() => toast.classList.add('show'));

    // 🔹 Inicia el temporizador de salida
    const visibleMs = 3000;
    const fadeMs = 800;
    let fadeTimeout, removeTimeout;

    fadeTimeout = setTimeout(() => {
        toast.classList.add('fade-out');
        removeTimeout = setTimeout(() => toast.remove(), fadeMs);
    }, visibleMs);

    // 🔹 Pausar si el usuario pasa el mouse
    toast.addEventListener('mouseenter', () => {
        clearTimeout(fadeTimeout);
        clearTimeout(removeTimeout);
        toast.classList.remove('fade-out');
    });

    toast.addEventListener('mouseleave', () => {
        fadeTimeout = setTimeout(() => {
            toast.classList.add('fade-out');
            removeTimeout = setTimeout(() => toast.remove(), fadeMs);
        }, 1500);
    });

    // 🔹 Cerrar manualmente con fade-out
    const btnClose = toast.querySelector('.btn-close');
    btnClose.addEventListener('click', () => {
        clearTimeout(fadeTimeout);
        clearTimeout(removeTimeout);
        toast.classList.add('fade-out');
        setTimeout(() => toast.remove(), fadeMs);
    });
}
