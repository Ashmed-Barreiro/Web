// Mapa centrado en Catalunya
const map = L.map('mapa', {
    zoomControl: false,      // Quita los controles de zoom
    dragging: false,         // Deshabilita el panning
    scrollWheelZoom: false,  // Deshabilita zoom con la rueda
    doubleClickZoom: false,  // Deshabilita zoom con doble clic
    boxZoom: false,          // Deshabilita zoom de selección
    keyboard: false,         // Deshabilita zoom con teclado
    touchZoom: false
}).setView([41.8, 1.6], 8);

// Capa base de OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Cargar comarcas desde el archivo local JSON
fetch('/data/divisions-administratives-v2r1-comarques-100000-20250101.json')
    .then(response => response.json())
    .then(data => {
        L.geoJSON(data, {
            style: {
                color: "#2E86AB",
                weight: 1,
                fillColor: "#AED6F1",
                fillOpacity: 0.3
            },
            onEachFeature: (feature, layer) => {
                const nom = feature.properties.NOMCOMAR;
                const cap = feature.properties.CAPCOMAR;
                const codi = feature.properties.CODICOMAR;

                layer.bindPopup(`<strong>${nom}</strong><br>Capital: ${cap}`);

                /* Realizar una llamada a la API pasandole el codigo de la comarca para obtener
                el listado de municipios de la comarca */
                layer.on('click', () => {
                    fetch(`http://localhost:3000/api/municipi.php?comarca=${codi}`)
                        .then(res => res.json())
                        .then(municipis => {
                           /* TODO necesitamos modular todo el codigo en funciones reutilizables */
                            console.log('Municipis de la comarca', codi, municipis);
                            const ul = document.createElement('ul');
                            
                            municipis.forEach(municipi => {
                                const li = document.createElement('li');
                                li.textContent = municipi.municipi + ' - ' + municipi.codi_municipi;
                                ul.appendChild(li);
                            });

                            document.querySelector('#list-municipis').appendChild(ul);
                        })
                        .catch(err => {
                            console.error('Error obtenint municipis:', err);
                        });
                });
            }
        }).addTo(map);
    })
    .catch(error => console.error('Error cargando GeoJSON:', error));


/* Marcar pagina en la que estem */
setTimeout(() => {
    document.querySelector('#link-mapa').classList.add('pagina-activa');
}, 150);