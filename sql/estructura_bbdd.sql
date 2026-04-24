-- 1. Categorías: Necesaria para el filtrado avanzado que habéis propuesto [cite: 78]
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(50) NOT NULL
);

-- 2. Productos: Incluye stock para la gestión en tiempo real y campos para filtros [cite: 39, 66]
CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0, -- Para bloquear artículos sin stock como dice vuestra propuesta 
    imagen_url VARCHAR(255),
    genero VARCHAR(50),  -- Filtro: Hip Hop, R&B, Pop [cite: 11, 60]
    formato VARCHAR(50), -- Filtro: Vinilo, Cassette, Digital [cite: 12, 59]
    talla VARCHAR(10),   -- Filtro para merchandising [cite: 39, 79]
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
);

-- 3. Usuarios: Con control de acceso por roles (RBAC) 
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('cliente', 'admin') DEFAULT 'cliente',
    direccion_envio TEXT
);

-- 4. Pedidos y Detalles: Para el sistema transaccional y el historial de compras [cite: 21, 36, 41]
CREATE TABLE pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    fecha_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'completado') DEFAULT 'pendiente',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE detalles_pedido (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,
    id_producto INT,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);


-- Insertar categorías básicas
INSERT INTO categorias (nombre_categoria) VALUES ('Música'), ('Merchandising');

-- Insertar algunos productos de vuestro catálogo [cite: 60]
INSERT INTO productos (id_categoria, nombre, precio, stock, genero, formato) 
VALUES 
(1, 'Future – I Never Liked You', 25.00, 10, 'Hip-Hop', 'Vinilo'),
(1, 'Billie Eilish – Happier Than Ever', 27.00, 5, 'Pop', 'Cassette'),
(1, 'The Weeknd – Dawn FM', 28.00, 0, 'R&B', 'Vinilo'); -- Este debería salir como "Sin Stock" 

-- Insertar un Administrador y un Cliente para probar los roles 
INSERT INTO usuarios (nombre, email, password, rol) 
VALUES 
('Admin BeatDrop', 'beatdroptfg@gmail.com', '$2y$10$Ekbka38pxBwgPUiHzqC6GuIAO3yvMFiKte/HwyFbor1G80OlLzRKS', 'admin'),
('Usuario Prueba', 'cliente@gmail.com', 'cliente123', 'cliente');
