// js/login-modal.js
document.addEventListener('DOMContentLoaded', () => {
    // ELEMENTOS
    const modal = document.getElementById('loginModal');
    const btnLogin = document.getElementById('loginBtn');
    const spanClose = document.querySelector('.close');
    const loginForm = document.getElementById('loginForm');
    const switchMode = document.getElementById('switchMode');
    const modalTitle = document.getElementById('modalTitle');
    const loginSubmit = document.getElementById('loginSubmit');
    const switchText = document.getElementById('switchText');
    const nameInput = document.getElementById('registerName');

    // 1. ABRIR/CERRAR MODAL
    if (btnLogin) btnLogin.addEventListener('click', () => modal.style.display = 'flex');
    if (spanClose) spanClose.addEventListener('click', () => modal.style.display = 'none');
    window.addEventListener('click', (e) => { if (e.target == modal) modal.style.display = 'none'; });

    // 2. CAMBIAR MODO (LOGIN <-> REGISTRO)
    if (switchMode) {
        switchMode.addEventListener('click', toggleMode);
    }

    function toggleMode() {
        const isLogin = modalTitle.innerText.includes('Iniciar');
        if (isLogin) {
            // MODO REGISTRO
            modalTitle.innerText = 'Crear cuenta';
            loginSubmit.innerText = 'Registrarse';
            nameInput.style.display = 'block';
            nameInput.setAttribute('required', 'true');
            switchText.innerHTML = '¿Ya tienes cuenta? <span id="switchMode" style="color: #ff7a00; cursor: pointer; text-decoration: underline;">Inicia sesión</span>';
        } else {
            // MODO LOGIN
            modalTitle.innerText = 'Iniciar sesión';
            loginSubmit.innerText = 'Entrar';
            nameInput.style.display = 'none';
            nameInput.removeAttribute('required');
            switchText.innerHTML = '¿No tienes cuenta? <span id="switchMode" style="color: #ff7a00; cursor: pointer; text-decoration: underline;">Regístrate</span>';
        }
        document.getElementById('switchMode').addEventListener('click', toggleMode);
    }

    // 3. PROCESAR LOGIN/REGISTRO CON BASE DE DATOS REAL (PHP)
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            const isRegistro = document.getElementById('modalTitle').innerText.includes('Crear');
            
            // Preparamos los datos a enviar al servidor
            const payload = {
                email: email,
                password: password,
                accion: isRegistro ? 'registro' : 'login'
            };

            if (isRegistro) {
                payload.nombre = document.getElementById('registerName').value;
            }

            try {
                // HACEMOS LA PETICIÓN AL BACKEND (PHP)
                const endpoint = isRegistro ? '/auth/register' : '/auth/login';
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.success) {
                    // ¡ÉXITO! Guardamos en sesión y cerramos modal
                    sessionStorage.setItem('usuarioActivo', result.nombre);
                    alert(result.message); // Muestra "Registro exitoso" o "Login exitoso"
                    
                    modal.style.display = 'none';
                    actualizarInterfazUsuario();
                    
                    // Si estamos en la página del carrito, recargamos para mostrar el checkout
                    if (window.location.pathname.includes('/carrito')) {
                        location.reload();
                    }
                } else {
                    // ERROR (Contraseña mal, correo duplicado, etc.)
                    alert(result.message);
                }

            } catch (error) {
                console.error("Error conectando con el servidor:", error);
                alert("Hubo un problema de conexión. Inténtalo de nuevo.");
            }
        });
    }

    // Inicializar interfaz al cargar la página
    actualizarInterfazUsuario();
});

// ACTUALIZAR HEADER CON USUARIO
function actualizarInterfazUsuario() {
    const usuario = sessionStorage.getItem('usuarioActivo');
    const btnLogin = document.getElementById('loginBtn');
    const usuarioHeader = document.getElementById('usuario-header');
    
    // Buscar si ya existe el botón de cerrar sesión
    let btnLogout = document.getElementById('cerrarSesionBtn');

    if (usuario) {
        if (btnLogin) btnLogin.style.display = 'none';
        if (usuarioHeader) {
        usuarioHeader.innerHTML = `<a href="/perfil" style="color:#ff7a00; text-decoration:none;">Hola, ${usuario}</a>`;            
        usuarioHeader.style.display = 'inline';
        }
        
        // Crear el botón de cerrar sesión si no existe
        if (!btnLogout) {
            const nav = document.querySelector('nav');
            if(nav){
                btnLogout = document.createElement('button');
                btnLogout.id = 'cerrarSesionBtn';
                btnLogout.innerText = 'Cerrar sesión';
                btnLogout.style.cssText = 'margin-left: 10px; background: #ff4c4c; border: none; color: white; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 0.8rem;';
                btnLogout.onclick = cerrarSesion;
                nav.appendChild(btnLogout);
            }
        } else {
            btnLogout.style.display = 'inline-block';
            btnLogout.onclick = cerrarSesion;
        }
    } else {
        if (btnLogin) btnLogin.style.display = 'inline-block';
        if (usuarioHeader) usuarioHeader.innerText = '';
        if (btnLogout) btnLogout.style.display = 'none';
    }
}

// CERRAR SESIÓN
function cerrarSesion() {
    sessionStorage.removeItem('usuarioActivo');
    window.location.href = '/auth/logout';
}