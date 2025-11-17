// scripts/Asistencia.js
document.addEventListener('DOMContentLoaded', () => {
  // Helpers cortos
  const $  = (s, ctx = document) => ctx.querySelector(s);
  const $$ = (s, ctx = document) => Array.from(ctx.querySelectorAll(s));

  console.log('[Asistencia.js] cargado');

  // Asegurar contenedor de toasts
  if (!$('#toast-container')) {
    const tc = document.createElement('div');
    tc.id = 'toast-container';
    document.body.appendChild(tc);
  }

  // Fallback de mostrarToast
  if (typeof mostrarToast !== 'function') {
    window.mostrarToast = (m, t='info') => alert(`${t.toUpperCase()}: ${m}`);
    console.warn('[Asistencia.js] mostrarToast no encontrado. Usando alert() como fallback.');
  }

  const form           = $('#formAsistencia');
  const btnGuardarAjax = $('#btnGuardarAjax');
  const btnMarcarTodos = $('#btnMarcarTodos');
  const btnMarcarNadie = $('#btnMarcarNadie');

  if (!form) {
    console.warn('[Asistencia.js] No se encontró #formAsistencia. ¿Hay curso cargado?');
    return;
  }

  function toast(msg, tipo = 'info') {
    try { mostrarToast(msg, tipo); } catch { alert(`${tipo}: ${msg}`); }
  }

  function setLoading(el, state) {
    if (!el) return;
    el.disabled = !!state;
    if (state) {
      el.dataset.originalText ??= el.innerHTML;
      el.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Guardando...';
    } else if (el.dataset.originalText) {
      el.innerHTML = el.dataset.originalText;
      delete el.dataset.originalText;
    }
  }

  // ========= TOGGLE de estado (Presente/Ausente) =========
  $$('.estado-toggle', form).forEach(toggle => {
    const id          = toggle.dataset.id;
    const btnPresente = toggle.querySelector('.btn-presente');
    const btnAusente  = toggle.querySelector('.btn-ausente');
    const hidden      = toggle.querySelector('input[type="hidden"]') || document.getElementById('estado-' + id);

    if (!btnPresente || !btnAusente || !hidden) return;

    btnPresente.addEventListener('click', () => {
      btnPresente.classList.add('active');
      btnAusente.classList.remove('active');
      hidden.value = '1';
    });

    btnAusente.addEventListener('click', () => {
      btnAusente.classList.add('active');
      btnPresente.classList.remove('active');
      hidden.value = '0';
    });
  });

  // Marcar todos presente / ausente usando los toggles
   function marcarTodos(valor) {
    const esPresente = (valor === '1' || valor === 1);

    $$('.estado-toggle', form).forEach(toggle => {
      const id          = toggle.dataset.id;
      const btnPresente = toggle.querySelector('.btn-presente');
      const btnAusente  = toggle.querySelector('.btn-ausente');
      const hidden      = toggle.querySelector('input[type="hidden"]') || document.getElementById('estado-' + id);

      if (!btnPresente || !btnAusente || !hidden) return;

      if (esPresente) {
        btnPresente.classList.add('active');
        btnAusente.classList.remove('active');
        hidden.value = '1';
      } else {
        btnAusente.classList.add('active');
        btnPresente.classList.remove('active');
        hidden.value = '0';
      }
    });

    // 🔹 Cambiamos el tipo de toast según el estado
    toast(
      `Todos marcados como ${esPresente ? 'Presente' : 'Ausente'}`,
      esPresente ? 'success' : 'danger'
    );
  }


  // Construir payload para AJAX leyendo los <input hidden> estado-ID
  function construirPayload() {
    const curso = parseInt($('input[name="curso"]', form)?.value || '0', 10);
    const fecha = $('input[name="fecha"]', form)?.value || '';
    const items = [];

    $$('#formAsistencia tbody tr').forEach(tr => {
      const idInput = $('input[name="estudiante_id[]"]', tr);
      if (!idInput) return;

      const id = parseInt(idInput.value || '0', 10);
      const hidden = $('#estado-' + id) || $('input[type="hidden"][name^="estado"]', tr);
      const pres = hidden ? parseInt(hidden.value || '0', 10) : 0;

      items.push({ Id_Estudiante: id, Presente: pres });
    });

    return { curso, fecha, items };
  }

  async function guardarAjax() {
    console.log('[Asistencia.js] Click Guardar (AJAX)');
    const payload = construirPayload();

    if (!payload.curso) {
      toast('Selecciona un curso antes de guardar.', 'warning');
      return;
    }

    try {
      setLoading(btnGuardarAjax, true);

      const url = 'RegistrarAsistenciaController.php';
      console.log('[Asistencia.js] POST →', url, payload);

      const resp = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      const text = await resp.text();
      console.log('[Asistencia.js] Respuesta cruda:', text);

      let data = {};
      try {
        data = JSON.parse(text);
      } catch (e) {
        console.error('[Asistencia.js] No es JSON válido:', e);
        toast('Respuesta no válida del servidor.', 'danger');
        return;
      }

      if (resp.ok && data.ok) {
        toast(data.mensaje || 'Asistencia guardada', 'success');
      } else {
        toast((data && data.mensaje) || 'No se pudo guardar la asistencia.', 'danger');
      }
    } catch (err) {
      console.error('[Asistencia.js] Error en fetch:', err);
      toast('Error de red/servidor al guardar.', 'danger');
    } finally {
      setLoading(btnGuardarAjax, false);
    }
  }

  // Eventos
  btnMarcarTodos?.addEventListener('click', () => marcarTodos('1'));
  btnMarcarNadie?.addEventListener('click', () => marcarTodos('0'));
  btnGuardarAjax?.addEventListener('click', guardarAjax);

  // Mensaje server-side (modo POST tradicional)
  const flash = $('#server-flash');
  if (flash?.dataset.message) {
    toast(flash.dataset.message, flash.dataset.type || 'info');
  }
});
