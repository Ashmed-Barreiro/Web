document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);
  const municipi = params.get("codi_municipi");

  if (municipi) {
    //Datos del municipi
    fetch(`../api/poblacio.php?codi_municipi=${encodeURIComponent(municipi)}`)
      .then(response => response.json())
      .then(data => {
        console.log("Dades del municipi:", data);

        const comarca_codi = data.codi_comarca;

        document.getElementById("nom").textContent = "Municipi: " + data.municipi;
        document.getElementById("comarca").textContent = "Comarca: " + data.comarca;
        document.getElementById("poblacio").textContent = "Població: " + data.poblacio + " hab.";

        // Fetch de municipis de la mateixa comarca
        fetch(`../api/municipi.php?comarca=${encodeURIComponent(comarca_codi)}`)
          .then(res => res.json())
          .then(comarcas => {
            const container = document.getElementById('list-comarcas');
            container.innerHTML = "";

            comarcas.forEach(comarca => {
              const a = document.createElement('a');
              a.textContent = toInitCap(comarca.municipi);
              a.href = `../pages/detallMunicipi.html?codi_municipi=${comarca.codi_municipi}`;
              container.appendChild(a);
            });
          })
          .catch(err => {
            console.error('Error obtenint municipis de la comarca:', err);
          });
      })
      .catch(error => {
        console.error("Error carregant les dades del municipi:", error);
      });

    // Consum
    fetch(`../api/consum.php?codi_municipi=${encodeURIComponent(municipi)}`)
      .then(response => response.json())
      .then(data => {
        for (let i of data) {
          if (i.tipus == "aigua") {
            document.getElementById("aigua").textContent = "Consum d'aigua: " + i.valor + " " + i.unitat;
          }
          if (i.tipus == "energia") {
            document.getElementById("energia").textContent = "Consum d'energia: " + i.valor + " " + i.unitat;
          }
        }
      });
  } else {
    console.warn("No s'ha trobat el paràmetre 'municipi' a la URL.");
  }
    // Proximitat
   fetch(`../api/consum.php?codi_municipi=${encodeURIComponent(municipi)}`)
      .then(response => response.json())
      .then(data => {
        for (let i of data) {
          if (i.tipus == "aigua") {
            document.getElementById("aigua").textContent = "Consum d'aigua: " + i.valor + " " + i.unitat;
          }
          if (i.tipus == "energia") {
            document.getElementById("energia").textContent = "Consum d'energia: " + i.valor + " " + i.unitat;
          }
        }
      });
  } else {
    console.warn("No s'ha trobat el paràmetre 'municipi' a la URL.");
  }

  function toInitCap(str) {
    let article = str.split(",")[1] != undefined ? str.split(",")[1] : "";
    let nom =  article+ " " + str.split(",")[0];
    return nom.toLowerCase().replace(/(^|\s|-|\/)([^\s\-\/])/g, (match, sep, char) => sep + char.toLocaleUpperCase('es-ES'));
}
  // Animació d'entrada
  setTimeout(() => {
    document.querySelectorAll('.titol-animat').forEach(el => {
      el.classList.add('visible');
    });
  }, 100);
});
