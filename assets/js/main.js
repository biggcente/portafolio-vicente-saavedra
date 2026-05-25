document.addEventListener('DOMContentLoaded', () => {

  // Animación de barras de tecnología al cargar

  const bars = document.querySelectorAll('.tech-bar');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const bar = entry.target;
        bar.style.width = bar.dataset.width + '%';
        observer.unobserve(bar);
      }
    });
  }, { threshold: 0.3 });

  bars.forEach(bar => observer.observe(bar));

  // Navbar: cambio de fondo al hacer scroll

  const nav = document.getElementById('mainNav');
  window.addEventListener('scroll', () => {
    nav.style.background = window.scrollY > 50
      ? 'rgba(10,10,15,0.98)'
      : 'rgba(10,10,15,0.95)';
  });


  // LOGIN — AJAX

  const btnLogin   = document.getElementById('btnLogin');
  const loginAlert = document.getElementById('loginAlert');

  if (btnLogin) {
    btnLogin.addEventListener('click', async () => {
      const email    = document.getElementById('loginEmail').value.trim();
      const password = document.getElementById('loginPassword').value;

      if (!email || !password) {
        showAlert(loginAlert, 'Completa todos los campos.', 'danger');
        return;
      }

      btnLogin.disabled = true;
      btnLogin.textContent = 'Ingresando...';

      try {
        const res  = await fetch('api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `action=login&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
        });
        const data = await res.json();

        if (data.success) {
          window.location.href = data.redirect;
        } else {
          showAlert(loginAlert, data.message, 'danger');
          btnLogin.disabled = false;
          btnLogin.textContent = 'Ingresar';
        }
      } catch (e) {
        showAlert(loginAlert, 'Error de conexión. Intenta nuevamente.', 'danger');
        btnLogin.disabled = false;
        btnLogin.textContent = 'Ingresar';
      }
    });

    // Enter en campos del login
    ['loginEmail','loginPassword'].forEach(id => {
      document.getElementById(id)?.addEventListener('keydown', e => {
        if (e.key === 'Enter') btnLogin.click();
      });
    });
  }

  
  // FORMULARIO DE CONTACTO — AJAX

  const btnEnviar   = document.getElementById('btnEnviar');
  const contactAlert = document.getElementById('contactAlert');

  if (btnEnviar) {
    btnEnviar.addEventListener('click', async () => {
      const nombre  = document.getElementById('c-nombre').value.trim();
      const email   = document.getElementById('c-email').value.trim();
      const asunto  = document.getElementById('c-asunto').value.trim();
      const mensaje = document.getElementById('c-mensaje').value.trim();

      if (!nombre || !email || !asunto || !mensaje) {
        showAlertDiv(contactAlert, 'Por favor completa todos los campos.', 'danger');
        return;
      }

      btnEnviar.disabled = true;
      btnEnviar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';

      try {
        const res  = await fetch('api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `action=contacto&nombre=${encodeURIComponent(nombre)}&email=${encodeURIComponent(email)}&asunto=${encodeURIComponent(asunto)}&mensaje=${encodeURIComponent(mensaje)}`
        });
        const data = await res.json();

        if (data.success) {
          showAlertDiv(contactAlert, data.message, 'success');
          // Limpiar formulario
          ['c-nombre','c-email','c-asunto','c-mensaje'].forEach(id => {
            document.getElementById(id).value = '';
          });
        } else {
          showAlertDiv(contactAlert, data.message, 'danger');
        }
      } catch (e) {
        showAlertDiv(contactAlert, 'Error al enviar. Intenta nuevamente.', 'danger');
      }

      btnEnviar.disabled = false;
      btnEnviar.innerHTML = '<i class="bi bi-send me-2"></i>Enviar Mensaje';
    });
  }

  
  // Helpers

  function showAlert(el, msg, type) {
    el.className = `alert alert-${type}`;
    el.textContent = msg;
    el.classList.remove('d-none');
  }

  function showAlertDiv(el, msg, type) {
    el.style.display = 'block';
    el.className = `alert alert-${type}`;
    el.textContent = msg;
    setTimeout(() => { el.style.display = 'none'; }, 5000);
  }
});
