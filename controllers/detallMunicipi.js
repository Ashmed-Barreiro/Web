document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);
  const municipi = params.get("codi_municipi");

  if (!municipi) {
    console.warn("No s'ha trobat el paràmetre 'municipi' a la URL.");
    return;
  }

  fetch(`../api/poblacio.php?codi_municipi=${encodeURIComponent(municipi)}`)
    .then(res => res.json())
    .then(data => {
      const comarca_codi = data.codi_comarca;
      const nom_municipi = data.municipi.split(",")[0].toUpperCase();

      document.getElementById("nom").textContent = data.municipi;
      document.getElementById("comarca").textContent = data.comarca;
      document.getElementById("poblacio").textContent = data.poblacio + " hab.";

      // Municipis de la mateixa comarca
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
        });

      // Comerços de proximitat (productors)
     fetch(`https://analisi.transparenciacatalunya.cat/resource/xmyy-7xqi.json?municipi=${nom_municipi}&$limit=1000`)
  .then(res => res.json())
  .then(productors => {
    const div = document.getElementById("grid-proximitat");
    div.innerHTML = "";

    if (productors.length === 0) {
      div.innerHTML = "<p>No hi ha productors disponibles per aquest municipi.</p>";
      return;
    }

    productors.forEach(productor => {
      const card = document.createElement("div");
      card.className = "card-productor";
      card.innerHTML = `
        <h3>${productor.nom_productor}</h3>
        <p><i class="fa-solid fa-cart-shopping icon-style-productes"></i> ${productor.productes || "No especificat"}</p>
        <p><i class="fa-solid fa-phone icon-style-productes"></i> ${productor.tel_fon || "No disponible"}</p>
        <p><i class="fa-solid fa-envelope icon-style-productes"></i> ${productor.correu || "No disponible"}</p>
        ${productor.marca_comercial ? `<p><strong>Marca comercial:</strong> ${productor.marca_comercial}</p>` : ""}
      `;
      div.appendChild(card);
    });
  });

      // Consum d'aigua i energia
      fetch(`../api/consum.php?codi_municipi=${encodeURIComponent(municipi)}`)
        .then(res => res.json())
        .then(data => {
          for (let i of data) {
            if (i.tipus == "aigua") {
              document.getElementById("aigua").textContent = i.valor + " " + i.unitat;
            }
            if (i.tipus == "energia") {
              document.getElementById("energia").textContent = i.valor + " " + i.unitat;
            }
            if (i.tipus == "reciclatge") {
              document.getElementById("totales").textContent = i.valor + " " + i.unitat;
            }
            if (i.tipus == "residus") {
              document.getElementById("resid").textContent = i.valor + " " + i.unitat;
            }
          }
        });
    });

  // Helper per posar majúscules als noms
  function toInitCap(str) {
    let article = str.split(",")[1] != undefined ? str.split(",")[1] : "";
    let nom = article + " " + str.split(",")[0];
    return nom.toLowerCase().replace(/(^|\s|-|\/)([^\s\-\/])/g, (match, sep, char) => sep + char.toLocaleUpperCase('es-ES'));
  }

  // Animació de títols
  setTimeout(() => {
    document.querySelectorAll('.titol-animat').forEach(el => {
      el.classList.add('visible');
    });
  }, 100);
});
