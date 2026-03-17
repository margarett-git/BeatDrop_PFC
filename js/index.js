/* ========= USUARIO ========= */
const userArea = document.getElementById("userArea");
const user = JSON.parse(localStorage.getItem("loggedUser"));

if (user) {
  userArea.innerHTML = `
    Hola, ${user.name}
    <button onclick="logout()">Salir</button>
  `;
} else {
  userArea.innerHTML = `
    <a href="login.html">Iniciar sesión</a>
  `;
}

function logout() {
  localStorage.removeItem("loggedUser");
  location.reload();
}

/* ========= CARRITO ========= */
function addToCart(nombre, precio) {
  if (!user) {
    alert("Debes iniciar sesión para añadir al carrito");
    window.location.href = "login.html";
    return;
  }

  const key = `carrito_${user.email}`;
  const carrito = JSON.parse(localStorage.getItem(key)) || [];

  const producto = carrito.find(p => p.nombre === nombre);

  if (producto) {
    producto.cantidad++;
  } else {
    carrito.push({ nombre, precio, cantidad: 1 });
  }

  localStorage.setItem(key, JSON.stringify(carrito));
  alert("Producto añadido al carrito 🛒");
}
