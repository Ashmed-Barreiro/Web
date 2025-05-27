document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);
  const municipi = params.get("codi_municipi");

  if (municipi) {
    // Crida a la teva API (ajusta l'URL real de la teva API aquí)
    fetch(`../api/poblacio.php?codi_municipi=${encodeURIComponent(municipi)}`)
      .then(response => response.json())
      .then(data => {
        console.log("Dades del municipi:", data);

        // Ex: mostrar dades a l'HTML
        document.getElementById("nom").textContent ="Municipi: " + data.municipi;
        document.getElementById("comarca").textContent = "Comarca: " + data.comarca;
        document.getElementById("poblacio").textContent = "Pobalacio: " + data.poblacio + " hab.";
      })
      .catch(error => {
        console.error("Error carregant les dades:", error);
      });
  } else {
    console.warn("No s'ha trobat el paràmetre 'municipi' a la URL.");
  }
  fetch(`../api/consum.php?codi_municipi=${encodeURIComponent(municipi)}`)
  .then(response => response.json())
  .then(data =>{
    for(let i of data){
    if(i.tipus=="aigua"){
    document.getElementById("aigua").textContent = "Consum d'aigua: "+ i.valor + "  " +i.unitat ;
}}})
});
