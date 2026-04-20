// Esperamos a que todo el contenido del DOM esté cargado
document.addEventListener("DOMContentLoaded", () => {
  // Seleccionamos los botones de Género y Formato
  const botonesGenero = document.querySelectorAll(".filtro-grupo:nth-child(1) .botones-filtro button");
  const botonesFormato = document.querySelectorAll(".filtro:nth-child(2) .botones-filtro button");

  // Seleccionamos todos los artículos de álbumes
  const articulos = document.querySelectorAll("section.cards article");

  // Elemento donde mostramos cuántos productos se están mostrando
  const productosMostrados = document.querySelector(".productos-mostrados");

  // Variables para guardar el filtro activo
  let generoActivo = "Todos";
  let formatoActivo = "Todos";

  // Función que filtra los artículos según el género y formato activos
  function filtrarArticulos() {
    let count = 0; // Contador de productos visibles

    articulos.forEach((articulo) => {
      const genero = articulo.getAttribute("data-genero");
      const formato = articulo.getAttribute("data-formato");

      // Comprobamos coincidencia con género
      const coincideGenero = (generoActivo === "Todos") || (genero === generoActivo);

      // Comprobamos coincidencia con formato
      // Si el atributo contiene el formato activo (por ejemplo "Vinilo / CD")
      const coincideFormato = (formatoActivo === "Todos") || (formato.includes(formatoActivo));

      if (coincideGenero && coincideFormato) {
        articulo.style.display = ""; // Mostramos el artículo
        count++;
      } else {
        articulo.style.display = "none"; // Ocultamos el artículo
      }
    });

    // Actualizamos el texto de productos mostrados
    productosMostrados.textContent = `Mostrando ${count} producto${count !== 1 ? "s" : ""}`;
  }

  // Configuramos los eventos para los botones de Género
  botonesGenero.forEach((boton) => {
    boton.addEventListener("click", () => {
      // Quitamos la clase active de todos los botones y la agregamos al seleccionado
      botonesGenero.forEach(b => b.classList.remove("active"));
      boton.classList.add("active");

      // Actualizamos el filtro activo y filtramos
      generoActivo = boton.textContent;
      filtrarArticulos();
    });
  });

  // Configuramos los eventos para los botones de Formato
  botonesFormato.forEach((boton) => {
    boton.addEventListener("click", () => {
      // Quitamos la clase active de todos los botones y la agregamos al seleccionado
      botonesFormato.forEach(b => b.classList.remove("active"));
      boton.classList.add("active");

      // Actualizamos el filtro activo y filtramos
      formatoActivo = boton.textContent;
      filtrarArticulos();
    });
  });

  // Filtramos al cargar la página para mostrar todos los artículos inicialmente
  filtrarArticulos();
});
