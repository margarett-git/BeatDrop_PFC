document.addEventListener('DOMContentLoaded', () => {
    pintarCarrito();

    // Comprobar estado inicial del formulario
    const envioForm = document.getElementById('envio-form');
    if (envioForm) {
        envioForm.addEventListener('submit', (e) => {
            e.preventDefault(); // Evita que la página se recargue de golpe
            
            // 1. Mensaje de éxito
            alert("¡Pedido confirmado con éxito! Gracias por tu compra.");
            
            // 2. CORRECCIÓN: Borramos el carrito DIRECTAMENTE (silencioso)
            // En lugar de llamar a vaciarCarrito(), hacemos esto:
            localStorage.removeItem('carrito'); 
            
            // 3. Redirigimos al inicio
            window.location.href = 'index.html';
        });
    }
});

function normalizarTextoCarrito(texto) {
    const value = String(texto || '');
    return value
        .replace(/Ã¢â‚¬â€œ|â€“/g, '-')
        .replace(/ÃƒÂ³|Ã³/g, 'o')
        .replace(/Ã¡/g, 'a')
        .replace(/Ã©/g, 'e')
        .replace(/Ã­/g, 'i')
        .replace(/Ãº/g, 'u')
        .replace(/Ã±/g, 'n')
        .replace(/\s+/g, ' ')
        .trim();
}

function pintarCarrito() {
    const contenedor = document.getElementById('carrito-items');
    const totalElemento = document.getElementById('carrito-total');
    const btnFinalizar = document.getElementById('btn-finalizar');
    const btnVaciar = document.getElementById('btn-vaciar');
    
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    let carritoActualizado = false;

    carrito = carrito.map((item) => {
        const tituloNormalizado = normalizarTextoCarrito(item.titulo);
        if (tituloNormalizado !== item.titulo) {
            carritoActualizado = true;
            return { ...item, titulo: tituloNormalizado };
        }
        return item;
    });

    if (carritoActualizado) {
        localStorage.setItem('carrito', JSON.stringify(carrito));
    }

    if (!contenedor) return;
    contenedor.innerHTML = '';
    
    // CASO: Carrito Vacío
    if (carrito.length === 0) {
        contenedor.innerHTML = '<p style="text-align:center; padding:30px; color:#777;">Tu carrito está vacío.</p>';
        if (totalElemento) totalElemento.innerText = 'Total: $0.00';
        if (btnFinalizar) btnFinalizar.style.display = 'none';
        if (btnVaciar) btnVaciar.style.display = 'none';
        return;
    }

    // CASO: Hay productos
    if (btnFinalizar) btnFinalizar.style.display = 'inline-block';
    if (btnVaciar) btnVaciar.style.display = 'inline-block';

    let totalCaja = 0;

    carrito.forEach((item, index) => {
        const precio = parseFloat(item.precio) || 0;
        const cantidad = parseInt(item.cantidad) || 1;
        totalCaja += precio * cantidad;

        const div = document.createElement('div');
        div.classList.add('carrito-item'); // Clase definida en CSS
        
        // HTML LIMPIO: Usamos clases en lugar de style=""
        div.innerHTML = `
            <img src="${item.imagen}" alt="${item.titulo}" class="img-producto">
            <div class="carrito-info">
                <h4>${item.titulo}</h4>
                <p>Precio: $${precio.toFixed(2)} | Cantidad: <strong>${cantidad}</strong></p>
            </div>
            <div class="carrito-controls">
                <button onclick="eliminarDelCarrito(${index})" class="btn-eliminar-item">
                    ${cantidad > 1 ? '-1 Unidad' : 'Eliminar'}
                </button>
            </div>
        `;
        contenedor.appendChild(div);
    });

    if (totalElemento) totalElemento.innerText = `Total: $${totalCaja.toFixed(2)}`;
}

// 1. LÓGICA MODIFICADA: Restar uno a uno
window.eliminarDelCarrito = function(index) {
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    
    // Si hay más de 1, restamos cantidad
    if (carrito[index].cantidad > 1) {
        carrito[index].cantidad--;
    } else {
        // Si solo queda 1, lo borramos del array
        carrito.splice(index, 1);
    }
    
    localStorage.setItem('carrito', JSON.stringify(carrito));
    pintarCarrito();
};

// 2. NUEVA FUNCIÓN: Borrar todo de golpe
window.vaciarCarrito = function() {
    if (confirm("¿Estás seguro de que quieres vaciar todo el carrito?")) {
        localStorage.removeItem('carrito');
        pintarCarrito();
    }
};

window.checkUserAndCheckout = function() {
    const usuarioActivo = sessionStorage.getItem('usuarioActivo');
    const formEntrega = document.getElementById('formulario-entrega');
    const btnFinalizar = document.getElementById('btn-finalizar');

    if (!usuarioActivo) {
        if (formEntrega) formEntrega.style.display = 'none';
        alert("Acceso denegado: La cuenta no existe o no has iniciado sesión.");
        const modal = document.getElementById('loginModal');
        if (modal) modal.style.display = 'flex';
    } else {
        if (formEntrega) {
            formEntrega.style.display = 'block';
            if (btnFinalizar) btnFinalizar.style.display = 'none';
            formEntrega.scrollIntoView({ behavior: 'smooth' });
        }
    }
};
