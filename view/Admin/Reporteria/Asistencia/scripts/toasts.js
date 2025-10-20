// scripts/toasts.js
function mostrarToast(mensaje, tipo = 'info') {
    const colores = {
        success: 'bg-success text-white',
        warning: 'bg-warning text-dark',
        danger: 'bg-danger text-white',
        info: 'bg-info text-dark'
    };

    const contenedor = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast align-items-center border-0 show mb-2 ${colores[tipo] || colores.info}`;
    toast.role = 'alert';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${mensaje}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>`;
    contenedor.appendChild(toast);

    // Ocultar el toast después de 4 segundos
    setTimeout(() => toast.remove(), 4000);
}
