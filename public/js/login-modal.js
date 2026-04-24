document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('loginModal');
    const btnLogin = document.getElementById('loginBtn');
    const spanClose = document.querySelector('.close');
    const loginForm = document.getElementById('loginForm');
    const switchMode = document.getElementById('switchMode');
    const modalTitle = document.getElementById('modalTitle');
    const loginSubmit = document.getElementById('loginSubmit');
    const switchText = document.getElementById('switchText');
    const nameInput = document.getElementById('registerName');

    if (btnLogin && modal) btnLogin.addEventListener('click', () => { modal.style.display = 'flex'; });
    if (spanClose && modal) spanClose.addEventListener('click', () => { modal.style.display = 'none'; });
    window.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
    });

    if (switchMode) {
        switchMode.addEventListener('click', toggleMode);
    }

    function toggleMode() {
        if (!modalTitle || !loginSubmit || !nameInput || !switchText) return;

        const isLogin = modalTitle.innerText.includes('Iniciar');
        if (isLogin) {
            modalTitle.innerText = 'Crear cuenta';
            loginSubmit.innerText = 'Registrarse';
            nameInput.style.display = 'block';
            nameInput.setAttribute('required', 'true');
            switchText.innerHTML = 'Ya tienes cuenta? <span id="switchMode" style="color: #ff7a00; cursor: pointer; text-decoration: underline;">Inicia sesion</span>';
        } else {
            modalTitle.innerText = 'Iniciar sesion';
            loginSubmit.innerText = 'Entrar';
            nameInput.style.display = 'none';
            nameInput.removeAttribute('required');
            switchText.innerHTML = 'No tienes cuenta? <span id="switchMode" style="color: #ff7a00; cursor: pointer; text-decoration: underline;">Registrate</span>';
        }

        document.getElementById('switchMode')?.addEventListener('click', toggleMode);
    }

    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = document.getElementById('loginEmail')?.value || '';
            const password = document.getElementById('loginPassword')?.value || '';
            const isRegistro = document.getElementById('modalTitle')?.innerText.includes('Crear');

            const payload = {
                email,
                password,
                accion: isRegistro ? 'registro' : 'login'
            };

            if (isRegistro) {
                payload.nombre = document.getElementById('registerName')?.value || '';
            }

            try {
                const endpoint = isRegistro ? '/auth/register' : '/auth/login';
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const contentType = response.headers.get('content-type') || '';
                let result = null;

                if (contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    const raw = await response.text();
                    throw new Error(`Respuesta no JSON (${response.status}): ${raw.slice(0, 200)}`);
                }

                if (result.success) {
                    sessionStorage.setItem('usuarioActivo', result.nombre);
                    alert(result.message);

                    if (modal) modal.style.display = 'none';

                    if (result.redirect) {
                        window.location.href = result.redirect;
                        return;
                    }

                    actualizarInterfazUsuario();

                    if (window.location.pathname.includes('/carrito')) {
                        location.reload();
                    }
                } else {
                    alert(result.message);
                }
            } catch (error) {
                console.error('Error conectando con el servidor:', error);
                alert('Hubo un problema de conexion. Intentalo de nuevo.');
            }
        });
    }

    bindLogoutTriggers();
    actualizarInterfazUsuario();
});

function clearClientSession() {
    sessionStorage.removeItem('usuarioActivo');
}

function bindLogoutTriggers() {
    document.querySelectorAll('a[href="/auth/logout"]').forEach((link) => {
        link.addEventListener('click', () => {
            clearClientSession();
        });
    });

    document.querySelectorAll('form[action="/auth/logout"]').forEach((form) => {
        form.addEventListener('submit', () => {
            clearClientSession();
        });
    });
}

function actualizarInterfazUsuario() {
    const usuario = sessionStorage.getItem('usuarioActivo');
    const btnLogin = document.getElementById('loginBtn');
    const usuarioHeader = document.getElementById('usuario-header');
    let btnLogout = document.getElementById('cerrarSesionBtn');

    if (usuario) {
        if (btnLogin) btnLogin.style.display = 'none';
        if (usuarioHeader) {
            usuarioHeader.innerHTML = `<a href="/perfil" style="color:#ff7a00; text-decoration:none;">Hola, ${usuario}</a>`;
            usuarioHeader.style.display = 'inline';
        }

        if (!btnLogout) {
            const nav = document.querySelector('nav');
            if (nav) {
                btnLogout = document.createElement('button');
                btnLogout.id = 'cerrarSesionBtn';
                btnLogout.innerText = 'Cerrar sesion';
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

function cerrarSesion() {
    clearClientSession();
    window.location.href = '/auth/logout';
}
