const catalogoBot = [
    { nombre: 'Future - I Never Liked You', precio: 25, genero: 'Hip-Hop', formato: 'Vinilo', img: '/img/future-album.jpg' },
    { nombre: 'Young Thug - Business is Business', precio: 28, genero: 'Hip-Hop', formato: 'Vinilo', img: '/img/youngthug-album.jpg' },
    { nombre: 'Drake - Honestly, Nevermind', precio: 26, genero: 'Hip-Hop', formato: 'Vinilo', img: '/img/drake-album.jpg' },
    { nombre: 'Kendrick Lamar - Mr. Morale & The Big Steppers', precio: 30, genero: 'Hip-Hop', formato: 'Vinilo', img: '/img/kendrick-album.jpg' },
    { nombre: 'Tems - For Broken Ears', precio: 20, genero: 'R&B', formato: 'CD', img: '/img/tems-album.jpg' },
    { nombre: 'Billie Eilish - Happier Than Ever', precio: 27, genero: 'Pop', formato: 'Cassette', img: '/img/billie-album.jpg' },
    { nombre: 'Ariana Grande - Positions', precio: 22, genero: 'Pop', formato: 'CD', img: '/img/ariana-album.jpg' },
    { nombre: 'Taylor Swift - Midnights', precio: 24, genero: 'Pop', formato: 'CD', img: '/img/taylor-album.jpg' },
    { nombre: 'Foo Fighters - Medicine at Midnight', precio: 29, genero: 'Rock', formato: 'Vinilo', img: '/img/foofighters-album.jpg' },
    { nombre: 'Miles Davis - Kind of Blue', precio: 20, genero: 'Jazz', formato: 'CD', img: '/img/miles-album.jpg' },
    { nombre: 'Daft Punk - Random Access Memories', precio: 32, genero: 'Electronica', formato: 'Vinilo', img: '/img/daftpunk-album.jpg' },
    { nombre: 'Metallica - The Black Album', precio: 25, genero: 'Metal', formato: 'CD', img: '/img/metallica-album.jpg' },
    { nombre: 'Bob Marley - Legend', precio: 27, genero: 'Reggae', formato: 'Vinilo', img: '/img/bobmarley-album.jpg' },
    { nombre: 'B.B. King - Live at the Regal', precio: 22, genero: 'Blues', formato: 'CD', img: '/img/bbking-album.jpg' },
];

let respuestas = { nombre: null, presupuesto: null, genero: null, formato: null };

document.addEventListener('DOMContentLoaded', () => {
    const chatVentana = document.getElementById('chat-ventana');
    const chatMensajes = document.getElementById('chat-mensajes');
    const btnAbrir = document.getElementById('chat-abrir');
    const btnCerrar = document.getElementById('chat-cerrar');

    if (!chatVentana || !chatMensajes || !btnAbrir || !btnCerrar) return;

    function setChatOpen(open) {
        chatVentana.classList.toggle('is-open', open);
        chatVentana.setAttribute('aria-hidden', open ? 'false' : 'true');
        btnAbrir.setAttribute('aria-expanded', open ? 'true' : 'false');
        btnAbrir.classList.toggle('is-active', open);

        if (open) {
            chatMensajes.scrollTop = chatMensajes.scrollHeight;
        }
    }

    btnAbrir.addEventListener('click', () => {
        setChatOpen(!chatVentana.classList.contains('is-open'));
    });

    btnCerrar.addEventListener('click', () => {
        setChatOpen(false);
    });

    iniciarConversacion();

    function iniciarConversacion() {
        respuestas = { nombre: null, presupuesto: null, genero: null, formato: null };
        chatMensajes.innerHTML = '';

        mensajeBot('Hola, soy <strong>Lara</strong>, tu asistente BeatDrop. Puedo recomendarte musica y resolver dudas de compra.');
        mostrarOpciones([
            { label: 'Recomiendame musica', valor: 'recomendar' },
            { label: 'Dudas sobre mi pedido', valor: 'pedido' },
            { label: 'Como funciona el envio', valor: 'envio' },
        ], (val) => {
            if (val === 'recomendar') {
                mensajeBot('Perfecto. Empecemos por algo simple: como te llamas?');
                mostrarInput('Tu nombre', (nombre) => {
                    respuestas.nombre = nombre;
                    mensajeBot(`Encantada, <strong>${nombre}</strong>. Cual es tu presupuesto?`);
                    mostrarOpciones([
                        { label: 'Menos de $25', valor: [0, 24.99] },
                        { label: 'Entre $25 y $30', valor: [25, 30] },
                        { label: 'Mas de $30', valor: [30.01, 9999] },
                    ], (valPresupuesto) => {
                        respuestas.presupuesto = valPresupuesto;
                        preguntarGenero();
                    });
                });
            } else if (val === 'pedido') {
                mensajeBot('Para consultas sobre pedidos puedes escribirnos a <strong>info@beatdrop.es</strong> o llamar al <strong>+34 912 345 678</strong>.');
                preguntarSiMasAyuda();
            } else if (val === 'envio') {
                mensajeBot('Los pedidos se procesan en <strong>24-48 horas</strong> y el envio tarda entre <strong>3 y 5 dias habiles</strong>. El envio es gratuito a partir de $50.');
                preguntarSiMasAyuda();
            }
        });
    }

    function preguntarGenero() {
        mensajeBot(`Muy bien, ${respuestas.nombre}. Que genero te apetece escuchar?`);
        mostrarOpciones([
            { label: 'Hip-Hop / Rap', valor: 'Hip-Hop' },
            { label: 'R&B / Soul', valor: 'R&B' },
            { label: 'Pop', valor: 'Pop' },
            { label: 'Cualquiera', valor: null },
        ], (val) => {
            respuestas.genero = val;
            preguntarFormato();
        });
    }

    function preguntarFormato() {
        mensajeBot('Y para rematar: tienes preferencia de formato?');
        mostrarOpciones([
            { label: 'Vinilo', valor: 'Vinilo' },
            { label: 'CD / Cassette', valor: 'CD' },
            { label: 'Me da igual', valor: null },
        ], (val) => {
            respuestas.formato = val;
            mostrarRecomendaciones();
        });
    }

    function mostrarRecomendaciones() {
        mensajeBot('Estoy buscando opciones para ti...');

        setTimeout(() => {
            const [min, max] = respuestas.presupuesto;

            let resultados = catalogoBot.filter((p) => {
                const okPrecio = p.precio >= min && p.precio <= max;
                const okGenero = !respuestas.genero || p.genero === respuestas.genero;
                const okFormato = !respuestas.formato || p.formato.includes(respuestas.formato);
                return okPrecio && okGenero && okFormato;
            });

            if (resultados.length === 0 && respuestas.genero) {
                resultados = catalogoBot.filter((p) => p.precio >= min && p.precio <= max);
                mensajeBot(`No encontre una coincidencia exacta, ${respuestas.nombre}, pero estas opciones encajan con tu presupuesto.`);
            } else if (resultados.length > 0) {
                mensajeBot(`Estas son mis mejores recomendaciones para ti, ${respuestas.nombre}.`);
            }

            if (resultados.length === 0) {
                mensajeBot('Ahora mismo no tengo una recomendacion perfecta para ese perfil.');
            } else {
                resultados.slice(0, 3).forEach((p) => mostrarProducto(p));
            }

            setTimeout(() => preguntarSiMasAyuda(), 450);
        }, 700);
    }

    function preguntarSiMasAyuda() {
        mensajeBot('Quieres que sigamos?');
        mostrarOpciones([
            { label: 'Si, otra consulta', valor: 'reiniciar' },
            { label: 'No, gracias', valor: 'salir' },
        ], (val) => {
            if (val === 'reiniciar') {
                iniciarConversacion();
            } else {
                const despedida = respuestas.nombre
                    ? `Hasta luego, ${respuestas.nombre}. Espero verte pronto por BeatDrop.`
                    : 'Hasta luego. Espero verte pronto por BeatDrop.';
                mensajeBot(despedida);
            }
        });
    }

    function mensajeBot(html) {
        const div = document.createElement('div');
        div.className = 'chat-msg bot';
        div.innerHTML = `
            <span class="chat-avatar chat-avatar-bot">BD</span>
            <div class="chat-burbuja">${html}</div>
        `;
        chatMensajes.appendChild(div);
        chatMensajes.scrollTop = chatMensajes.scrollHeight;
    }

    function mensajeUsuario(texto) {
        const div = document.createElement('div');
        div.className = 'chat-msg user';
        div.innerHTML = `
            <div class="chat-burbuja">${texto}</div>
            <span class="chat-avatar chat-avatar-user">Tu</span>
        `;
        chatMensajes.appendChild(div);
        chatMensajes.scrollTop = chatMensajes.scrollHeight;
    }

    function mostrarInput(placeholder, callback) {
        const contenedor = document.createElement('div');
        contenedor.className = 'chat-input-wrap';

        const input = document.createElement('input');
        input.type = 'text';
        input.placeholder = placeholder;
        input.className = 'chat-input-texto';

        const btn = document.createElement('button');
        btn.textContent = 'Enviar';
        btn.className = 'chat-input-btn';

        const enviar = () => {
            const valor = input.value.trim();
            if (!valor) return;
            contenedor.remove();
            mensajeUsuario(valor);
            callback(valor);
        };

        btn.addEventListener('click', enviar);
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') enviar();
        });

        contenedor.appendChild(input);
        contenedor.appendChild(btn);
        chatMensajes.appendChild(contenedor);
        input.focus();
        chatMensajes.scrollTop = chatMensajes.scrollHeight;
    }

    function mostrarOpciones(opciones, callback) {
        const contenedor = document.createElement('div');
        contenedor.className = 'chat-opciones';

        opciones.forEach((op) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = op.label;
            btn.addEventListener('click', () => {
                contenedor.remove();
                mensajeUsuario(op.label);
                callback(op.valor);
            });
            contenedor.appendChild(btn);
        });

        chatMensajes.appendChild(contenedor);
        chatMensajes.scrollTop = chatMensajes.scrollHeight;
    }

    function mostrarProducto(p) {
        const div = document.createElement('div');
        div.className = 'chat-msg bot';
        div.innerHTML = `
            <span class="chat-avatar chat-avatar-bot">BD</span>
            <div class="chat-burbuja">
                <div class="bot-producto">
                    <img src="${p.img}" alt="${p.nombre}">
                    <div class="bot-producto-info">
                        <strong>${p.nombre}</strong>
                        <span>${p.genero} · ${p.formato}</span>
                        <span class="bot-precio">$${p.precio.toFixed(2)}</span>
                        <button type="button" class="bot-btn-carrito">Anadir al carrito</button>
                    </div>
                </div>
            </div>
        `;

        div.querySelector('.bot-btn-carrito')?.addEventListener('click', (e) => {
            agregarAlCarrito(p);
            e.target.textContent = 'Anadido';
            e.target.disabled = true;
        });

        chatMensajes.appendChild(div);
        chatMensajes.scrollTop = chatMensajes.scrollHeight;
    }

    function agregarAlCarrito(p) {
        const carrito = JSON.parse(localStorage.getItem('carrito') || '[]');
        const titulo = p.nombre;
        const id = titulo.replace(/\s+/g, '-').toLowerCase();
        const existe = carrito.find((item) => item.id === id);
        if (existe) {
            existe.cantidad++;
            existe.titulo = titulo;
            existe.imagen = p.img;
            existe.precio = p.precio;
        } else {
            carrito.push({ id, titulo, precio: p.precio, imagen: p.img, cantidad: 1 });
        }
        localStorage.setItem('carrito', JSON.stringify(carrito));
    }
});
