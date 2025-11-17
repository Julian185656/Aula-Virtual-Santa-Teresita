// scripts/Toasts.js (local al módulo Asistencia)
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

  const visibleMs = 3000;
  const fadeMs = 800;

  requestAnimationFrame(() => toast.classList.add('show'));

  let fadeTimeout = setTimeout(() => {
    toast.classList.add('fade-out');
    setTimeout(() => toast.remove(), fadeMs);
  }, visibleMs);

  toast.addEventListener('mouseenter', () => {
    clearTimeout(fadeTimeout);
    toast.classList.remove('fade-out');
  });

  toast.addEventListener('mouseleave', () => {
    fadeTimeout = setTimeout(() => {
      toast.classList.add('fade-out');
      setTimeout(() => toast.remove(), fadeMs);
    }, 1500);
  });

  toast.querySelector('.btn-close').addEventListener('click', () => {
    clearTimeout(fadeTimeout);
    toast.classList.add('fade-out');
    setTimeout(() => toast.remove(), fadeMs);
  });
}
