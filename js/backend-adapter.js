// js/backend-adapter.js

document.addEventListener('DOMContentLoaded', () => {
    
    // --- 1. CONEXIÓN REGISTRO (Registro -> BD) ---
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            // No prevenimos el default aquí porque el js/login-modal.js original ya lo hace.
            // Simplemente ejecutamos nuestra lógica en paralelo.

            const modalTitle = document.getElementById('modalTitle').innerText;
            
            // Solo actuamos si es "Crear cuenta" (Lógica copiada de tu modal actual)
            if (modalTitle.includes('Crear')) {
                const nombre = document.getElementById('registerName').value;
                const email = document.getElementById('loginEmail').value;
                const password = document.getElementById('loginPassword').value;

                // Enviamos a PHP mediante FETCH
                fetch('php/registro.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nombre, email, password })
                })
                .then(res => res.json())
                .then(data => {
                    console.log('Respuesta del servidor:', data);
                    // No hacemos alert aquí para no molestar, ya que el JS original
                    // ya muestra mensajes y guarda en localStorage.
                    // Esto ocurre "por detrás" para cumplir con la BD.
                })
                .catch(err => console.error('Error backend:', err));
            }
        });
    }

    // --- 2. CONEXIÓN COMPRA (Carrito -> Email) ---
    const envioForm = document.getElementById('envio-form');
    
    if (envioForm) {
        envioForm.addEventListener('submit', (e) => {
            // Capturamos datos antes de que la página se recargue/redirija
            const nombre = document.getElementById('nombre-envio').value;
            const email = document.getElementById('email-envio').value;
            const direccion = document.getElementById('direccion-envio').value;

            // Enviamos la orden de disparo al PHP
            // IMPORTANTE: Usamos keepalive: true porque tu carrito.js redirige 
            // a index.html casi inmediatamente. Esto asegura que la petición salga.
            fetch('php/procesar_compra.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nombre, email, direccion }),
                keepalive: true 
            });
            
            console.log("Orden enviada a la automatización de correo.");
        });
    }
});