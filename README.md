# 🎵 BeatDrop 

¡Hola! 👋 Bienvenid@s al repositorio de **BeatDrop**. Esta es una tienda online especializada en música (vinilos, cassettes y formato digital) y merchandising de cultura urbana y pop.

Este es nuestro Proyecto de Fin de Grado (PFG) para el ciclo de Desarrollo de Aplicaciones Web (DAW). Lo hemos desarrollado desde cero siguiendo el patrón **MVC** y usando **Docker** para que funcione a la primera en cualquier ordenador.

## ✨ Lo que hace especial a BeatDrop
* **Catálogo y stock real:** Nada de datos estáticos. Los productos se cargan desde MySQL y si el stock llega a cero, el botón de compra se bloquea.
* **Seguridad a tope:** Hemos protegido el login encriptando las contraseñas con **BCRYPT** y blindado las consultas con **PDO** para evitar inyecciones SQL.
* **Carrito fluido:** Usamos JavaScript (Fetch API) para que puedas añadir discos al carrito de forma asíncrona, sin que la página se tenga que recargar.
* **BeatBot:** Nuestro propio asistente virtual creado en JS. Te hace unas preguntas y te recomienda música según tus gustos y presupuesto.

## 🛠️ Tecnologías que hemos usado
* **Frontend:** HTML5, CSS3 (diseño responsivo con Grid y Flexbox) y Vanilla JS.
* **Backend:** PHP 8.2 estructurado en Modelo-Vista-Controlador.
* **Base de Datos:** MySQL 8.0.
* **Infraestructura:** Docker & Docker Compose.

## 📦 Cómo probar el proyecto en tu ordenador
Como hemos metido todo en contenedores, no necesitas instalar XAMPP ni pelearte con versiones de PHP. Solo necesitas tener [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado y abierto.

1. **Clona este repositorio:**
   ```bash
   git clone [https://github.com/margarett-git/BeatDrop_PFC.git](https://github.com/margarett-git/BeatDrop_PFC.git)
Levanta el servidor y la base de datos:

Bash
docker-compose up -d
Carga los productos:
Entra en phpMyAdmin desde el navegador (http://localhost:8080), selecciona la base de datos beatdrop_db y dale a "Importar". Sube el archivo estructura_bbdd.sql que hemos dejado en la carpeta /sql.

¡Y listo! Ya puedes entrar a http://localhost:8000 y probar la tienda.

👩‍💻 Desarrollado por:
- Ludeynis Gamez Cambero
- Margarett Mamotos Mamaradlo